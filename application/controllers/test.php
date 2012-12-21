<?php
use VD\Database as DB,
	Everyman\Neo4j\Client,
    Everyman\Neo4j\Transport\Curl,
    Everyman\Neo4j\Node,
    Everyman\Neo4j\Relationship;

class Test_Controller extends Controller
{
	public function action_index()
	{

		DB::connect();

		for($i=0; $i<10; $i++) {
			$me = Auth::retrieve($i);

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