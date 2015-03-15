<?php

class OhadminController extends \BaseController {
	/*
	 * The layout that should be used for responses.
	 */
	protected $layout = 'layouts.login';
	
	/**
	 * Login Backend page
	 */
	public function login()
	{
		$this->layout->content = View::make('ohadmin.login');
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

		$sms = 'Your username/password combination was incorrect.';

		$user = Auth::user();

		if ($auth) {
			if ($user->status == 1 && $user->use_type == 1) {
				return Redirect::to('backend');
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
