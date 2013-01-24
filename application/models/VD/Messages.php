<?php
namespace VD;

use Everyman\Neo4j\Node,
	Everyman\Neo4j\Relationship,
    Everyman\Neo4j\Index,
	Everyman\Neo4j\Batch;
	
class Messages
{
	public $id = null;
	public $login = null;	
	public $node = null; 	//node as property of an object. Its more useful then Class extends Node 
	protected $data = array();
	
	public function __construct()
	{
		$this->node = $node;
		$this->id = $node->getId();
		$this->email = $this->get('email');
		$this->login = $this->get('login');
	}
	
	/**
	 * Send private message to user.
	 *
	 * @param  Member $to, string $id
	 * @return true|make excaption
	 */
	public function sendPrivate(Member $to, $msg)
	{
		$client = DataBase::client();
		$batch = $client->startBatch();
		$node = new Node($client);
		$node->setProperty($msg, 'msg');
		$node->setProperty(time(), 'date');
		$node->save();
		$node->relateTo($this->node, 'PrivateFrom')->save();
		$node->relateTo($to->node, 'PrivateTo')->save();
		$client->endBatch();
		return $batch->commit();
	}
	/**
	 * Send private message to user.
	 *
	 * @param  Member $to, string $id
	 * @return true|make excaption
	 */
	public function getPrivate($from = 0, $limit = 10) // need to make some pagination or diolog system
	{
		$messages = $this->node->getRelationships('PrivateTo', Relationship::DirectionIn);
		$result = array();
		foreach($messages as $msg) 
		{
			$result[] = array
			(
				'from' => $msg->getStartNode(), 
				'date' => $msg->getProperty('date'),
				'msg' => $msg->getProperty('msg')
			);
		}
		return $result;
	}
	
	public function Subscribe(Member $friend)
	{
		return $this->node->relateTo($friend->node, 'Subscrible')->save();
	}
	
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
		$path = $this->node->findPathsTo($this->node, 'Subscrible', Relationship::DirectionBoth)->getSinglePath();
		foreach ($path as $rel) 
		{
			$rel->delete();
			$rel->setId(0);
		}
		return $this->node->relateTo($friend->node, 'Friends')->save(); //->setProperty('price', 1); -- "best friends on the top", as VK
	}
	/**
	 * Get list of all current user's friends
	 *
	 * @param  none
	 * @return array
	 */
	public function getFriends()
	{
		$relationships = $this->node->getRelationships('Friends');
		$friends = array();
		foreach ($relationships as $ralationship) 
		{
			$friends[] = new Member($ralationship->getEndNode());
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
		//$member->node = new Node(DataBase::client());
		$member = new Member(DataBase::client()->makeNode());
		
		$memberIndex = new Index(DataBase::client(), Index::TypeNode, 'Member');
		
		//if (!$memberIndex->findOne('login', $data['login'])&&!$memberIndex->findOne('email', $data['email'])) {
			foreach ($data as $key=>$value) {
				$member->node->setProperty($key, $value);
			}
			
			$member->save();
			$memberIndex->add($member->node, 'login', $data['login']);
			$memberIndex->add($member->node, 'email', $data['email']);
			$memberIndex->save();
			return true;	
		//} else return 'already registered!';
	}
	/**
	 * Get user by indexed property
	 *
	 * @param  string $property, $values
	 * @return Member|null
	 */
	public static function GetByIndex($property, $value)
	{
		$memberIndex = new Index\NodeIndex(DataBase::client(), 'Member');
		//$matches = $memberIndex->find('email', 'master2');
        $node = $memberIndex->findOne($property, $value	);
        if (!$node) {
            return null;
        }
        $member = new member($node);
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
		$member = new Member(DataBase::client()->getNode($id));
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
	/**
	 * Get current user's property
	 *
	 * @param  string $property
	 * @return mixed
	 */
	public function get($property)
	{
		return $this->node->getProperty($property);
	}
	/**
	 * Set current user's property
	 *
	 * @param  string $property
	 * @return true|make excaption
	 */
	public function set($property)
	{
		return $this->node->setProperty('property');
	}
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
	
	
	public function addRecipe($recipe)
	{
		// code...
	}
	
	public function save()
	{
        $this->node->save();       
	}
	
}
