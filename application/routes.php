<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Simply tell Laravel the HTTP verbs and URIs it should respond to. It is a
| breeze to setup your application using Laravel's RESTful routing and it
| is perfectly suited for building large applications and simple APIs.
|
| Let's respond to a simple GET request to http://example.com/hello:
|
|		Route::get('hello', function()
|		{
|			return 'Hello World!';
|		});
|
| You can even respond to more than one URI:
|
|		Route::post(array('hello', 'world'), function()
|		{
|			return 'Hello World!';
|		});
|
| It's easy to allow URI wildcards using (:num) or (:any):
|
|		Route::put('hello/(:any)', function($name)
|		{
|			return "Welcome, $name.";
|		});
|
*/

Route::get('/', function()
{
	return View::make('home.index');
});

Route::get('adminlogin', function()
{
	return View::make('admin.login');
});

Route::post('adminlogin', function()
{
	if (Auth::attempt(Input::all())) {
		return Redirect::to('admin');
	} else {
		return Redirect::to('adminlogin')->with('login_errors', true);
	}
});

Route::get('register', function()
{
	return View::make('form.register');
});

Route::post('register', function()
{
	$rules = array('password' => 'required|alpha_num|max:25');
	$data = Input::all();
    $validation = Validator::make($data, $rules);
    if ($validation->fails()) {
        return Redirect::to('register')->with_errors($validation)->with('register_errors', true);
    }
	
	$login = $data['login'];
	$email = $data['email'];
	$data['password'] = Hash::make($data['password']);
	if (VD\Member::getByIndex('login', $data['login']) != null) {
		//return Redirect::to('register')->with('login duplicate', true);
		echo 'login duplicate';
	}
	if (VD\Member::getByIndex('email', $data['email']) != null) {
		//return Redirect::to('register')->with('email duplicate', true);
		echo 'email duplicate';
	}
	
	$success = VD\Member::newOne($data);
	if ($success) {
		return 'Success!';
	}
	else return Redirect::to('register')->with('register_errors', true);
	//return View::make('admin.login');
});

/*
|--------------------------------------------------------------------------
| Application 404 & 500 Error Handlers
|--------------------------------------------------------------------------
|
| To centralize and simplify 404 handling, Laravel uses an awesome event
| system to retrieve the response. Feel free to modify this function to
| your tastes and the needs of your application.
|
| Similarly, we use an event to handle the display of 500 level errors
| within the application. These errors are fired when there is an
| uncaught exception thrown in the application.
|
*/

Event::listen('404', function()
{
	return Response::error('404');
});

Event::listen('500', function()
{
	return Response::error('500');
});

/*
|--------------------------------------------------------------------------
| Route Filters
|--------------------------------------------------------------------------
|
| Filters provide a convenient method for attaching functionality to your
| routes. The built-in before and after filters are called before and
| after every request to your application, and you may even create
| other filters that can be attached to individual routes.
|
| Let's walk through an example...
|
| First, define a filter:
|
|		Route::filter('filter', function()
|		{
|			return 'Filtered!';
|		});
|
| Next, attach the filter to a route:
|
|		Router::register('GET /', array('before' => 'filter', function()
|		{
|			return 'Hello World!';
|		}));
|
*/

Route::filter('before', function()
{
	// Do stuff before every request to your application...
});

Route::filter('after', function($response)
{
	// Do stuff after every request to your application...
});

Route::filter('csrf', function()
{
	if (Request::forged()) return Response::error('500');
});

Route::filter('auth', function()
{
	if (Auth::guest()) return Redirect::to('login');
});

Route::filter('admin_auth', function()
{
	if (Auth::check()) {
		if (Auth::user()->security_level < 5) {
			return Redirect::to('adminlogin')->with('low_security_level', true);
		}
	} else {
		return Redirect::to('adminlogin');
	}
});

/**
 * Register all of the controllers
 */
Route::controller(Controller::detect());
