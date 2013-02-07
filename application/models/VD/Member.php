<?php
namespace VD;

use Everyman\Neo4j\Node,
	Everyman\Neo4j\Relationship,
    Everyman\Neo4j\Index,
	Everyman\Neo4j\Cypher,
	Everyman\Neo4j\Batch,
	VD\Database as DB;
/**
 * Класс пользователя. Сделал его ребенком узла.
 */
class Member extends Node
{
	
	public function __construct(Node $node)
	{
		foreach ($node as $property => $value) {
			$this->$property = $value;
		}
		parent::__construct(DB::client());
	}
	
	/**
	 * Возвращает пользователя или NULL, если узел не найден
	 * @param  int $id - ID узла
	 * @return Member|null
	 */
	public static function getById($id)
	{
		$node = DB::client()->getNode($id);
		if($node == NULL) {
			return NULL;
		}
		return new Member($node);
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
		$node->relateTo($this, 'PrivateFrom')->save();
		$node->relateTo($to, 'PrivateTo')->save();
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
	public function subscribe(Member $friend)
	{
		return $this->relateTo($friend, 'Subscrible')->save();
	}
	
	/**
	 * Get "Wants to friends"
	 *
	 * @param  none
	 * @return array
	 */
	public function getFriendRequest()
	{
		$relationships = $this->getRelationships('Subscrible', Relationship::DirectionIn); // all incoming
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
		$requestRelationship = $notFriend->findPathsTo($this, 'Subscrible', Relationship::DirectionOut)
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
		return $this->relateTo($friend, 'Friends')->save(); 
	}
	
	/**
	 * Get list of all current user's friends
	 *
	 * @param  none
	 * @return array
	 */
	public function getFriends()
	{
		$friends = array();
		// bad code, need Query
		// ты прав.
		$queryString = "START n=node(".$this->id.") ".
			"MATCH (n)<-[:Friends]-(x)-[:Friends]->(n) ".
			"RETURN x ";
		$query = new Cypher\Query(DB::client(), $queryString);
		$res = $query->getResultSet();
		foreach ($res as $row) {
			$node = $row['x'];
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
	public static function getByIndex($property, $value)
	{
		$memberIndex = new Index\NodeIndex(DB::client(), 'Members');
		//$matches = $memberIndex->find('email', 'master2');
        $node = $memberIndex->findOne($property, $value);
        if (!$node) {
            return null;
        }
        $member = new Member($node);
        return $member;
	}
	
	/**
	 * Get all current user's properties
	 * Теперь это просто обертка
	 *
	 * @param  string $property
	 * @return true|make excaption
	 */
	public function data()
	{
		return $this->getProperties();
	}
	
	public function getRecepts()
	{
		// изменил название отношения к выложенному рецепту, кажется, так гораздо понятнее
		// надо будет, наверное, возвращать не массив отношений а массив конкретно рецептов.
		return $this->getRelationships('POSTED_RECEIPT');
	}
	
	/**
	 * Doesn't work yet
	 */
	public function addRecipe($recipe)
	{
		// code...
	}
}
