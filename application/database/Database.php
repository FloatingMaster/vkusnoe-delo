<?php
use Everyman\Neo4j\Client,
    Everyman\Neo4j\Transport\Curl,
    Everyman\Neo4j\Node,
    Everyman\Neo4j\Relationship;

class Database
{
	public $client;

	private $instance;

	/**
	 * Закрытый консртуктор, т. к. база - синглтон
	 */
	private function __construct()
	{
		$this->client = new Client();
		return $this;
	}

	/**
	 * gi - алиас getInstance
	 */
	public static function gi()
	{
		return self::getInstance();
	}

	/**
	 * Реализация синглтона
	 */
	public static function getInstance()
	{
		if(self::$instance == NULL) {
			self::$instance = new Database();
		}
		return self::$instance;
	}

}