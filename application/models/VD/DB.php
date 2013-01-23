<?php
namespace VD;

use Laravel\Config,
	Everyman\Neo4j\Client,
    Everyman\Neo4j\Transport\Curl,
    Everyman\Neo4j\Node,
    Everyman\Neo4j\Relationship;

/*
 * Класс для работы с базой. Практический весь функционал будет описан в нём. Когда логика усложниться, можно разбить на подмодели для разных типов данных, но может и одного класса хватить. 
 * Рабочий комментарий.
*/
class DB
{
	public $client;

	private static $instance;

	/**
	 * Закрытый консртуктор, т. к. база - синглтон
	 * Сам код просто скопирован из оригинала
	 *
	 * @param 	string 	$host
	 * @param 	string|int 	$port
	 * @return 	Database
	 */
	private function __construct($host, $port)
	{
		$this->client = new Client($host, $port);
		return $this;
	}

	/**
	 * Реализация синглтона
	 */
	public static function connect()
	{
		if(self::$instance == NULL) {
			try {
				$con_data = Config::get('database.connections.neo4j');
				self::$instance = new DB(
					$con_data['host'],
					$con_data['port']
				);
			} catch (\Exception $e) {
				echo $e->getMessage();
			}
		}
		return self::$instance;
	}
	
	/**
	 * А где описание?
	 */
	public static function client()
	{
		return self::$instance->client;
	}
	
	public static function newUser()
	{
		$node = new Node;
		
		return $node;
	}
}