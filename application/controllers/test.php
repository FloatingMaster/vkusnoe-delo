<?php
use Everyman\Neo4j\Client,
    Everyman\Neo4j\Transport\Curl,
    Everyman\Neo4j\Node,
    Everyman\Neo4j\Relationship;

class Test_Controller extends Controller
{
	public function action_index()
	{

		$client = new Client();

		for($i=0; $i<10; $i++) {
			$me = $client->getNode($i);

			if($me != NULL) $client->getEntityCache()->deleteCachedEntity($me);

			$me = $client->getNode($i);

			print_r($me);
			echo '<br />';
		}

		return "It works! ";
	}

	public function action_welcome($param = '')
	{
		return View::make('test.index')->with('param', $param);
	}
}