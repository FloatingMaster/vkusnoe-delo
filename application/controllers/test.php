<?php
class Test_Controller extends Controller
{
	public function action_index()
	{
		return "It works!";
	}

	public function action_welcome($param = '')
	{
		return View::make('test.index')->with('param', $param);
	}
}