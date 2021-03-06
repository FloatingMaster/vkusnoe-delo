<?php

class Home_Controller extends Base_Controller {

	/*
	|--------------------------------------------------------------------------
	| The Default Controller
	|--------------------------------------------------------------------------
	|
	| Instead of using RESTful routes and anonymous functions, you might wish
	| to use controllers to organize your application API. You'll love them.
	|
	| This controller responds to URIs beginning with "home", and it also
	| serves as the default controller for the application, meaning it
	| handles requests to the root of the application.
	|
	| You can respond to GET requests to "/home/profile" like so:
	|
	|		public function action_profile()
	|		{
	|			return "This is your profile!";
	|		}
	|
	| Any extra segments are passed to the method as parameters:
	|
	|		public function action_profile($id)
	|		{
	|			return "This is the profile for user {$id}.";
	|		}
	|
	*/

	public function __construct()
	{
		parent::__construct();
		Asset::add('jquery', 'js/jquery.js');
		//Asset::add('banner', 'css/banner.css');
		Asset::add('main-style', 'css/style.css');
	}

	public function action_index()
	{
		Seovel::setTitle('Главная');
		Seovel::setDescription('Вкусное Дело - социальная сеть для поваров и кулинаров. Скоро открытие!');

		return View::make('home.index');
	}

}