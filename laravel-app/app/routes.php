<?php

/*
 |--------------------------------------------------------------------------
 | Application Routes
 |--------------------------------------------------------------------------
 |
 | Here is where you can register all of the routes for an application.
 | It's a breeze. Simply tell Laravel the URIs it should respond to
 | and give it the Closure to execute when that URI is requested.
 |
 */

/*Route::get('/', function()
 {
 return View::make('hello');
 });*/

Route::get('backend', array('before' => 'guest', function() {

	return View::make('/');
}));

Route::get('/', 'HomeController@welcome');

// check login
Route::get('login', 'HomeController@login');
Route::post('login', 'HomeController@validate');

// Admin login
Route::post('ohadmin', 'OhadminController@validate');

Route::get('ohadmin', array('before' => 'ohadmin', function() {
	return View::make('login');
}));

// logout route
Route::get('logout', array('before' => 'auth', function() {
	Auth::logout();
	return Redirect::to('/') -> with('flash_notice', 'You are successfully logged out.');
}));

//Route::resource('announcements', 'AnnouncementController');
Route::group(array('before' => 'auth'), function() {
	Route::get('backend', 'BackendController@dashboard');

	Route::get('backend/member', 'MemberController@lists');
	Route::get('backend/member', 'MemberController@lists');

	Route::resource('member', 'MemberController');
	Route::when('member*', 'member');

});
Route::controller('sys-automates', 'SysAutomateReportController');

?>