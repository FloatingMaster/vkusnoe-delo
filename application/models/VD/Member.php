<?php
namespace VD;

use Everyman\Neo4j\Node,
    Everyman\Neo4j\Index;
	
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
	}
	
	public function save(Member $member)
	{
        $member->node->save();       
	}
	
	public function newOne($email, $password, $data)
	{
		//$member->node = new Node(DataBase::client());
		$this->node = DataBase::client()->makeNode();
		
		$memberIndex = new Index(DataBase::client(), TypeNode, 'members');
		
		if (!$memberIndex->findOne('email', $email)) {
			$this->node->setProperty('email', $email);
			$this->email = $email;	
			$this->node->setProperty('password', $password);
			//$this->password = $password;
			$this->data = $data;
			foreach ($data as $key=>$value) {
				$this->node->setProperty($key, $value);
			}
			
			$this->save($this);
			$memberIndex->add($this->node, 'email', $this->email);
			$memberIndex->save();
			return true;	
		} else return 'email is already registered';
	}
	
	public static function GetByEmail($email)
	{
		$memberIndex = new Index\NodeIndex(DataBase::client(), 'members');
		//$matches = $memberIndex->find('email', 'master2');
        $node = $memberIndex->findOne('email', $email);
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
	
	public function getFriends()
	{
		return $client->getNodeRelationships($this->Node, 'friends');
	}
	
	public function getRecepts()
	{
		return $client->getNodeRelationships($this->Node, 'recipes');
	}
	
	public function addFriend($friend)
	{
		if ($friend instanceof $Member)
			$friend = $friend->node;
		if ($friend instanceof Node) 
			return $this->node->relateTo($driend, 'IN')->save();
	}
	
	public function addRecipe($recipe)
	{
		// code...
	}
	
}
