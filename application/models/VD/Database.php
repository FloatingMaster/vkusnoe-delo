<?php
namespace VD;

use Laravel\Config,
	Everyman\Neo4j\Client,
    Everyman\Neo4j\Transport\Curl,
    Everyman\Neo4j\Node,
    Everyman\Neo4j\Relationship;

class Database
{
	public $client;

	private static $instance;

	/**
	 * Закрытый консртуктор, т. к. база - синглтон
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
				self::$instance = new Database(
					$con_data['host'],
					$con_data['port']
				);
			} catch (\Exception $e) {
				echo $e->getMessage();
			}
		}
		return self::$instance;
	}

	public static function client()
	{
		return self::$instance->client;
	}

}