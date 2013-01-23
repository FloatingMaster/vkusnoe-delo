<?php
namespace VD;

use Everyman\Neo4j\Node,
	Everyman\Neo4j\Relationship,
    Everyman\Neo4j\Index,
	Everyman\Neo4j\Batch;
	
class Member
{
	public $id = null;
	public $email = null;	
	protected $node = null;
	protected $data = array();
	
	public function __construct($node)
	{
		$this->node = $node;
		$this->id = $node->getId();
		$this->email = $this->get('email');
		$this->login = $this->get('login');
	}
	
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
	public function getPrivate($from = 0, $limit = 10) // need to make some pagination or diolog system
	{
		$messeges = $this->node->getRelationships('PrivateTo', Relationship::DirectionIn);
		$result = array();
		foreach($messeges as $msg) 
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
	
	public function getFriends()
	{
		$relationships = $this->node->getRelationships('Friends');
		foreach ($relationships as $ralationship) 
		{
			$friends[] = new Member($ralationship->getEndNode());
		}
		return $friends;
	}

	
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
	
	public static function getById($id)
	{
		$member = new Member(DataBase::client()->getNode($id));
		return $member;
	}
	
	public function getId()
	{
		return $this->node->getId();
	}
	
	public function get($property)
	{
		return $this->node->getProperty($property);
	}
	
	public function set($property)
	{
		return $this->node->setProperty('property');
	}
	
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
