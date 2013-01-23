<?php
namespace VD;

use Everyman\Neo4j\Node,
    Everyman\Neo4j\Index;

class Recipe
{
	protected $node;
	protected $id = null;
	protected $name = '';
	protected $data = array();
	protected $real = false;
	
	public function __construct($node)
	{
		if ($node instanceof Node) {
			$this->node = $node;
			$this->id = $node->getId();
		} 
	}
	public function create($name, $data)
	{
		$this->node = DataBase::client()->makeNode();
		
		$index = new Index(DataBase::client(), Index::TypeNode, 'login');
		
		if (!$memberIndex->findOne('login', $login)) {
			$this->node->setProperty('login', $login);
			$this->login = $login;	
			$this->node->setProperty('password', $password);
			//$this->password = $password;
			$this->data = $data;
			foreach ($data as $key=>$value) {
				$this->node->setProperty($key, $value);
			}
			
			$this->save($this);
			$memberIndex->add($this->node, 'login', $this->login);
			return true;	
		}
	}
	
}