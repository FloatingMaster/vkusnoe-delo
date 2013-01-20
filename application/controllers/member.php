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

	public function action_profile($email = null)
	{
		if (is_null($email)) {
			return View::make('member.notfound'); // or redirect to self page
		}
		$db = DataBase::connect();
		$member = Member::GetByEmail($email);
		if (is_null($member)) {
			//return View::make('error.404');
			return View::make('member.notfound');
		}
		return View::make('member.profile')
				->with('member_data', $member->data());
	}

	public function action_newrecipe()
	{ // code from admin panel
		Asset::add('tinymce', 'js/tiny_mce/tiny_mce.js');

		return View::make('admin.recipe');
	}
	
	public function action_login()
	{
		if (Request::method() == 'GET') {
			return View::make('form.login');
		}
		$rules = array('username' => 'email', 'password' => 'alpha_dash');
		$validation = Validator::make(Input::all(), $rules);
		if ($validation->fails()) {
			return Redirect::to('member/login')
				->with('login_error', true)
				->with_errors($validation);
		}
		$email = Input::get('username');
		$password = Input::get('password');
		if (Auth::attempt(array('username' => $email, 'password' => $password)))
		{
			return Redirect::to('member/profile/' . $email); // or previous page
		}
		return Redirect::to('member/login')->with('login_error', true)->with_input('only', 'username');	
	}
	
	public function action_logout()
	{
		$data = Input::all();
		if (Auth::attempt(array('username' => $email, 'password' => $password)))
		{
			return Redirect::to('member/profile/' . $data['email']); // or previous page
		}
		return Redirect::to('member/login'); // + invalid username or password
	}
}