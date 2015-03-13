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
	//return Hash::make('automatereport^@$$(ASDF/3#)*AS#');
	//return Hash::make('admin@#$%loto');
	//return Hash::make('@password@');
	//return Hash::make('@khengleng@#$%');

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

	Route::get('lucky/print-ticket', 'LuckyController@printTicket');
	Route::post('lucky/print-ticket', 'LuckyController@printStoreTicket');
	Route::get('lucky/cancel-ticket', 'LuckyController@cancelTicket');
	Route::post('lucky/cancel-ticket', 'LuckyController@cancelStoreTicket');
	Route::get('lucky/payout', 'LuckyController@payout');
	Route::post('lucky/payout', 'LuckyController@showPayout');
	Route::get('lucky/result', 'LuckyController@result');
	Route::post('lucky/result', 'LuckyController@showResult');
	Route::get('lucky/show-report', 'LuckyController@showReport');
	Route::post('lucky/show-report', 'LuckyController@showReport');

	Route::resource('lucky', 'LuckyController');
	Route::when('lucky*', 'lucky');


	Route::get('ticket/print-ticket', 'TicketController@printTicket');
	Route::post('ticket/print-ticket', 'TicketController@printStoreTicket');
	Route::get('ticket/cancel-ticket', 'TicketController@cancelTicket');
	Route::post('ticket/cancel-ticket', 'TicketController@cancelStoreTicket');
	Route::get('ticket/payout', 'TicketController@payout');
	Route::post('ticket/payout', 'TicketController@showPayout');

	Route::resource('ticket', 'TicketController');
	Route::when('ticket*', 'ticket');

});
Route::controller('sys-automates', 'SysAutomateReportController');

?>