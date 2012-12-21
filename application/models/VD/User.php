<?php
namespace VD;

use VD\Database as DB,
	Everyman\Neo4j\Client,
    Everyman\Neo4j\Transport\Curl,
    Everyman\Neo4j\Node,
    Everyman\Neo4j\Relationship,
    Everyman\Neo4j\Index,
    Everyman\Neo4j\Index\NodeIndex;

class User extends Node
{
	protected static $users_index;

	/**
	 * Конструктор пользователя по узлу
	 * 
	 * @param 	Client 	$client
	 * @param 	Node	$node
	 */
	public function __construct(Node $node)
	{
		foreach ($node as $property => $value) {
			$this->$property = $value;
		}
		parent::__construct(DB::client());
	}

	/**
	 * Устанавливает параметр пользователя, обходя ненужные
	 * 
	 * @param 	string 	$property
	 * @param 	string	$value
	 */
	public function setProperty($property, $value)
	{
		if ($property === 'type' || ! property_exists('VD\User\UserProperties', $property) ) {
			return $this;
		}
		parent::setProperty($property, $value);
		return $this;
	}

	/**
	 * Получает пользователя по емейлу через индекс
	 * 
	 * @param 	string 	$property
	 * @param 	string 	$handle
	 * @return 	Node
	 */
	public static function getByIndex($property, $handle = null)
	{	
		return self::userIndex()->findOne($property, $handle);
	}

	/**
	 * Получает индекс пользователей
	 * 
	 * @return 	NodeIndex
	 */
	public static function userIndex()
	{
		if(self::$users_index == NULL) {
			self::$users_index = new NodeIndex(DB::client(), 'Users');
		}
		return self::$users_index;
	}

	public static function add(UserProperties $properties)
	{
		# code...
	}

	/**
	 * Есть ли такая возможность у пользователя?
	 * 
	 * @param 	string 	$capability
	 * @return 	boolean
	 */
	public function hasCap($capability)
	{
		return in_array($capability, json_decode($this->getProperty('capabilities') ) );
	}
}
