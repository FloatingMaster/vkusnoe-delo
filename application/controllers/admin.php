<?php

class Admin_Controller extends Base_Controller {

	public function __construct()
	{
		parent::__construct();
		Asset::add('jquery', 'js/jquery.js');
		Asset::add('bootstrapjs', 'bootstrap/js/bootstrap.min.js', 'jquery');
		Asset::add('bootstrap', 'bootstrap/css/bootstrap.min.css');
		Asset::add('admin-styles', 'css/admin/style.css');
	}

	public function action_index()
	{
		// code here..

		return View::make('admin.index');
	}

}
