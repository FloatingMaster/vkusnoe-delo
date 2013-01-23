<?php

use
	VD\DataBase,
	VD\Member;

class Member_Controller extends Controller {

	public function __construct()
	{
		parent::__construct();
		//$this->filter('before', 'member_auth');
	}

	public function action_profile($login = null)
	{
		
		if (is_null($login)) {
			return View::make('member.notfound'); // or redirect to self page
		}
		$db = DataBase::connect();
		$member = Member::GetByIndex('login', $login);
		if (is_null($member)) {
			//return View::make('error.404');
			return View::make('member.notfound');
		}
		
		$node = DataBase::client()->getNode(17);
		$friend = new Member($node);
		
		//$member->addFriend($friend);
		//$friend->friendRequest($member);		
		
		return View::make('member.profile')
				->with('member_data', $member->data());
	}
	
	public function action_subscribe($username)
	{
		$member = Auth::user();
		if ($member == null) {
			return Redirect::to('member/login')->with('should_login', true);	
		}
		$friend = Member::getByIndex('login', $username);
		if ($friend == null) {
			return Redirect::to('member/profile/' . $member->login);
		}
		$member->Subscribe($friend);
		return Redirect::to('member/profile/' . $member->login);
	}
	
	public function unsubscribe($username)
	{
	}
	
	public function action_send($to = null)
	{
		if (Request::method() == 'GET') {
			return View::make('msg.private')->with('to', $to);	
		}
		//$rules = array('text' => 'alpha_dash', 'to' =>'alpha_dash');
		//$validator = Validator::make(Input::all(), $rules);
		//if ($validator->fails()) {
		//	return Redirect::to('member/send')
		//		->with('send_error', true)
		//		->with_errors($validator);
		//}
		$to = Member::getByIndex('login', Input::get('to'));
		$from = Auth::user();
		$from->sendPrivate($to, Input::get('text'));
		return Redirect::to('member/profile/', $to->login);
	}

	public function action_newrecipe()
	{ // code from admin panel
		Asset::add('tinymce', 'js/tiny_mce/tiny_mce.js');

		return View::make('admin.recipe');
	}
	
	public function action_read_private($id = null)
	{
		$member = Auth::user();
		$msg = $member->getPrivate();
		return View::make('msg.list')->with('Messeges', $msg);
	}
	
	
	public function action_login()
	{
		if (Request::method() == 'GET') {
			return View::make('form.login');
		}
		$rules = array('username' => 'required|alpha_dash', 'password' => 'required|alpha_dash');
		$validation = Validator::make(Input::all(), $rules);
		if ($validation->fails()) {
			return Redirect::to('member/login')
				->with('login_error', true)
				->with_errors($validation);
		}
		if (Auth::attempt(Input::all()))
		{
			return Redirect::to('member/profile/' . Input::get('username')); // or previous page
		}
		return Redirect::to('member/login')->with('login_error', true)->with_input('only', 'username');	
	}
	
	public function action_logout()
	{
		Auth::logout();
		return Redirect::home();
	}
}