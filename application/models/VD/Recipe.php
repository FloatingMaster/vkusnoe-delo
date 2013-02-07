<?php
namespace VD;

use Everyman\Neo4j\Node,
    Everyman\Neo4j\Index,
    Database as DB;

class Recipe extends Node
{
	// fLf: убрал все свойства. у нас же есть properties
	
	public function __construct(Node $node)
	{
		foreach ($node as $property => $value) {
			$this->$property = $value;
		}
		parent::__construct(DB::client());
	}

	public static function create($name, $data)
	{
		$recipe = new Recipe(DB::client()->makeNode());
		
		$index = new Index(DB::client(), 'Recipes');
		
	}
	
}