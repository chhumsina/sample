<?php

class HomeController extends \BaseController {
	/*
	 * The layout that should be used for responses.
	 */
	protected $layout = 'layouts.master';
	
	/**
	 * Display home page
	 */
	public function welcome()
	{
		$this->layout->content = View::make('home.show');
	}

	/*
	 * Validation
	 *
	 * */
	public function validate()
	{
		// attempt to do the login
		$auth = Auth::attempt(
			array(
				'username'  => strtolower(Input::get('username')),
				'password'  => Input::get('password')
			)
		);
		$user = Auth::user();
		$sms = 'Your username/password combination was incorrect.';
		if ($auth) {
			if ($user->status == 1) {
				return Redirect::to('home');
			} else {
				$sms = 'This account not yet active!';
				Auth::logout();
			}

		}
		// validation not successful, send back to form

		return Redirect::to('/')
			->withInput(Input::except('password'))
			->with('flash_notice_error', $sms);

	}

}
