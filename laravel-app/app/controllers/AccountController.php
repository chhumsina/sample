<?php

class AccountController extends \BaseController {

	/**
	 * The layout that should be used for responses.
	 */
	protected $layout = 'layouts.master';
	
	public function register()
	{
		$this->layout->content = View::make('account.register');
	}

	public function store()
	{
		$msgs = array();
		$data =  Input::except(array('_token')) ;
		$rule  =  array(
			'username'       => 'required|unique:member',
			'email'      => 'required|email|unique:member',
			'password'   => 'required|min:6|same:cpassword',
			'cpassword'  => 'required|min:6'
		) ;

		$validator = Validator::make($data,$rule);

		if ($validator->passes())
		{
			Account::create(array(
				'username' => Input::get('username'),
				'email' => Input::get('email'),
				'password' => Hash::make(Input::get('password'))
			));

			$msg = array('type'=>'success','msg'=>'The account is create successfully');
			array_push($msgs,$msg);
			return Redirect::back()
				->with('msgs', $msgs);
		}

		$msg = array('type'=>'error','msg'=>'The account is not...');
		array_push($msgs,$msg);
		return Redirect::back()
			->withInput()
			->with('msgs', $msgs);
	}

}