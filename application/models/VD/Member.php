<?php
namespace VD;

use Everyman\Neo4j\Node,
	Everyman\Neo4j\Relationship,
    Everyman\Neo4j\Index,
	Everyman\Neo4j\Cypher,
	Everyman\Neo4j\Batch,
	VD\DataBase as DB;
	
class Member
{
	// Бля. А мне казалось, что наследовать модели от класса Node - 
	// неплохая идея.
	// Она же реально неплохая! Нам не придется писать геттеры для каждого
	// свойства модели, а ведь их будет становиться все больше!
	// Может, все-таки сделаем как я думал?
	public $id = null;
	public $login = null;	
	public $node = null; 	//node as property of an object. Its more useful then Class extends Node 
	protected $data = array();
	
	public function __construct($node)
	{
		$this->node = $node;
		$this->id = $node->getId();
		// убрал ненужные присвоения, магия же пашет
	}
	
	/**
	 * Send private message to user.
	 *
	 * @param  Member $to, string $id
	 * @return true|make excaption
	 */
	public function sendPrivate(Member $to, $msg)
	{
		$client = DB::client();
		$batch = $client->startBatch();
		$node = new Node($client);
		$node->setProperty('msg', $msg);
		$node->setProperty('date', time());
		$node->save();
		$node->relateTo($this->node, 'PrivateFrom')->save();
		$node->relateTo($to->node, 'PrivateTo')->save();
		//$this->node->makeDialog($to);
		$client->endBatch();
		return $batch->commit();
	}
	
	/**
	 * Send private message to user.
	 *
	 * @param  Member $to, string $id
	 * @return array
	 */
	public function getPrivate($from = 0, $limit = 10) // need to make some pagination or diolog system
	{
		$queryString = "START n=node(".$this->id.") ".
			"MATCH (n)<-[:PrivateTo]-(x) ".
			"RETURN x ".
			"ORDER BY x.date ".
			"SKIP ".$from.
			"LIMIT ".$limit;
		$query = new Cypher\Query(DB::client(), $queryString);
		$result = $query->getResultSet();
		$msg = array();
		foreach ($result as $row) 
		{
			$node = $row['x'];
			$rel = $node->getRelationships('PrivateFrom', Relationship::DirectionOut);
			foreach($rel as $relationship)
			{
				$from = new Member($relationship->getEndNode()); // Member pbject, should member's data
			}
			$msg[] = array(
				'msg' => $node->getProperty('msg'),
				'date' => $node->getProperty('date'),
				'from' => $from
			);
		}
		return $msg;
	}
	
	/*
	protected function makeDialog($to)
	{
		$dialogs = $this->node->findPathsTo($to->node, 'DialogWith', Relationship::DirectionBoth)
			->getSinglePath();
		if (count($dialogs) > 0) {
			foreach ($dialogs as $dialog)
			{
				$dialog->setProperty(time(), 'Last');
			}
			return true;
		}
		$dialog = new Node(DB::client());
		$dialog->setProperty(time(), 'Last');
		$dialog->ralateTo();
	}
	*/
	
	/**
	 * Sunscribe on user, not used yet
	 *
	 * @param  Member $friend
	 * @return true|make excaption
	 */
	public function Subscribe(Member $friend)
	{
		return $this->node->relateTo($friend->node, 'Subscrible')->save();
	}
	
	/**
	 * Get "Wants to friends"
	 *
	 * @param  none
	 * @return array
	 */
	public function getFriendRequest()
	{
		$relationships = $this->node->getRelationships('Subscrible', Relationship::DirectionIn); // all incoming
		$friend_request = array();
		foreach($relationships as $relationship) 
		{ 
			$friend_request[] = $relationship->getStartNode();
		}
		return $friend_request;
	}
	/**
	 * Decline request
	 *
	 * @param  none
	 * @return array
	 */
	public function declineFriendRequest(Member $notFriend)
	{
		$requestRelationship = $notFriend->node->findPathsTo($this->node, 'Subscrible', Relationship::DirectionOut)
			->getSinglePath();
		echo '<pre>';
		print_r($requestRelationship);
		echo '</pre>';	
		
	}
	/**
	 * Add user to friends
	 *
	 * @param  Member $friend
	 * @return true|make excaption
	 */
	public function addFriend(Member $friend)
	{
		/*
		$path = $this->node->findPathsTo($this->node, 'Friends')->getSinglePath();
		foreach ($path as $rel) 
		{
			$rel->delete();
			$rel->setId(0); //use same id for new rel
		}
		*/
		return $this->node->relateTo($friend->node, 'Friends')->save(); 
	}
	
	/**
	 * Get list of all current user's friends
	 *
	 * @param  none
	 * @return array
	 */
	public function getFriends()
	{
		//self::addFriend(new Member(DB::client()->getNode(17)));
		$relationships = $this->node->getRelationships('Friends');
		$friends = array();
		foreach ($relationships as $ralationship) 
		{
			$node = $ralationship->getEndNode();
			if ($node->getId() == $this->id)
				$node = $ralationship->getStartNode(); // bad code, need Query
			$friends[] = new Member($node);
		}
		return $friends;
	}

	/**
	 * Create new user with params
	 *
	 * @param array $data
	 * @return true|string
	 */
	public static function newOne($data)
	{
		//$member->node = new Node(DB::client());
		// бля. 
		$member = new Member(DB::client()->makeNode());
		
		
		if (!Member::GetByIndex('login', $data['login'])&&!Member::GetByIndex('email', $data['email'])) {
			foreach ($data as $key=>$value) {
				$member->node->setProperty($key, $value);
			}
			
			$member->save();
			$memberIndex->add($member->node, 'login', $data['login']);
			$memberIndex->add($member->node, 'email', $data['email']);
			$memberIndex->save();
			return true;	
		} // else return 'already registered!'; // твоюж мать
	}
	
	/**
	 * Get user by indexed property
	 *
	 * @param  string $property, $values
	 * @return Member|null
	 */
	public static function GetByIndex($property, $value)
	{
		$memberIndex = new Index\NodeIndex(DB::client(), 'Member');
		//$matches = $memberIndex->find('email', 'master2');
        $node = $memberIndex->findOne($property, $value	);
        if (!$node) {
            return null;
        }
        $member = new Member($node);
        return $member;
	}
	/**
	 * Get user by Id
	 *
	 * @param  integer $id
	 * @return Member|null
	 */
	public static function getById($id)
	{
		$member = new Member(DB::client()->getNode($id));
		return $member;
	}
	/**
	 * Get current user's id
	 *
	 * @param  none
	 * @return integer
	 */
	public function getId()
	{
		return $this->node->getId();
	}

	// Эти методы не нужны. Любое свойство можно получить и установить так:
	// $member->property
	/**
	 * Get current user's property
	 *
	 * @param  string $property
	 * @return mixed
	 */
	// public function get($property)
	// {
	// 	return $this->node->getProperty($property);
	// }
	/**
	 * Set current user's property
	 *
	 * @param  string $property, mixed $value
	 * @return true|make excaption
	 */
	// public function set($property, $value)
	// {
	// 	return $this->node->setProperty($property, $value);
	// }
	
	/**
	 * Get all current user's properties
	 *
	 * @param  string $property
	 * @return true|make excaption
	 */
	public function data()
	{
		return $this->node->getProperties();
	}
	
	
	public function getRecepts()
	{
		return $client->getNodeRelationships($this->Node, 'recipes');
	}
	
	/**
	 * Doesn't working yet
	 */
	public function addRecipe($recipe)
	{
		// code...
	}
	
	public function save()
	{
        $this->node->save();       
	}
	
}
