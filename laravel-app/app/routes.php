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

// Admin login
Route::post('ohadmin', 'OhadminController@validate');

// Member register
Route::get('register', 'AccountController@register');
Route::post('register', 'AccountController@store');
Route::get('register/verify/{confirmationCode}', [
    'as' => 'confirmation_path',
    'uses' => 'AccountController@confirm'
]);

Route::get('login', 'AccountController@login');
Route::post('login', 'AccountController@validate');


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
    // member
    Route::get('member/manage_ads', 'AccountController@manageAds');
    Route::get('member/my_profile', 'AccountController@myProfile');
    Route::get('member/page', 'AccountController@page');
    Route::get('member/my_map', 'AccountController@myMap');

    // backend
	Route::get('backend', 'BackendController@dashboard');

	Route::get('backend/member', 'MemberController@lists');
	Route::post('backend/member', 'MemberController@search');

    Route::get('backend/member/edit/{slug}', 'MemberController@edit');
    Route::post('backend/member/edit', 'MemberController@update');


	Route::resource('member', 'MemberController');
	Route::when('member*', 'member');

});
Route::controller('sys-automates', 'SysAutomateReportController');

?>