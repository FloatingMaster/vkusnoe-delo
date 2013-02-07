<?php

class Admin_Controller extends Base_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->filter('before', 'admin_auth');
		Seovel::defaultTitleSuffix(' | Администратор Вкусного дела');
		Asset::add('jquery', 'js/jquery.js');
		Asset::add('bootstrapjs', 'bootstrap/js/bootstrap.min.js', 'jquery');
		Asset::add('bootstrap', 'bootstrap/css/bootstrap.min.css');
		Asset::add('admin-styles', 'css/admin/style.css');
	}

	public function action_index()
	{
		Seovel::setTitle('Главная');
		return View::make('admin.index');
	}

	public function action_recipe()
	{
		Seovel::setTitle('Рецепты');
		Asset::add('tinymce', 'js/tiny_mce/tiny_mce.js');

		return View::make('admin.recipe');
	}
}
