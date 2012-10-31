<?php
class Test_Controller extends Controller
{
	public function action_index()
	{
		Asset::add('jquery', 'js/jquery.js');
		return "It works! ". Asset::styles() . Asset::scripts();
	}

	public function action_welcome($param = '')
	{
		return View::make('test.index')->with('param', $param);
	}
}