<?php
use Everyman\Neo4j\Client,
    Everyman\Neo4j\Transport\Curl,
    Everyman\Neo4j\Node,
    Everyman\Neo4j\Relationship;

class Test_Controller extends Controller
{
	public function action_index()
	{
		$client = new Client(new Curl());

		$me = $client->getNode(0);

		var_dump($me);

		return "It works! ";
	}

	public function action_welcome($param = '')
	{
		return View::make('test.index')->with('param', $param);
	}
}