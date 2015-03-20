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

	public function login()
	{
		$this->layout->content = View::make('account.login');
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
			$confirmation_code = str_random(30);

			Account::create(array(
				'username' => Input::get('username'),
				'email' => Input::get('email'),
				'use_type' => 2,
				'password' => Hash::make(Input::get('password')),
				'confirmation_code' => $confirmation_code
			));
			$messages = array( 'code' => $confirmation_code );

			Mail::send('email.verify', $messages, function($message) {
				$message->to(Input::get('email'), Input::get('username'))
					->subject('Verify your email address');
			});

			$msg = array('type'=>'success','msg'=>'Thanks for signing up! Please check your email.');
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

	// Confirm email
	public function confirm($confirmation_code)
	{
		$msgs = array();
		if( ! $confirmation_code)
		{
			return Redirect::to('/');
		}

		$account = Account::whereConfirmationCode($confirmation_code)->first();

		if (!$account)
		{
			return Redirect::to('/');
		}

		$account->status = 1;
		$account->confirmation_code = null;
		$account->save();

		$msg = array('type'=>'success','msg'=>'You have successfully verified your account.');
		array_push($msgs,$msg);

		return Redirect::to('login')
			->withInput()
			->with('msgs', $msgs);
	}

	// validate login
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
			if ($user->status == 1 && $user->use_type == 2) {
				return Redirect::to('member/manage_ads');
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

	// Member Dashboard
		public function manageAds()
		{
			$this->layout->content = View::make('account.manage-ads');
		}

		public function myProfile()
		{
			$acc = Auth::user();
			$locations = Location::orderBy('title','asc')->lists('title','id');
			$this->layout->content = View::make('account.my-profile',compact('locations','acc'));
		}
		
		public function update()
		{
			$msgs = array();
			if(Input::has('save'))
			{
				$acc = Account::find(Auth::user()->id);
				$acc->email      = Auth::user()->email;
				$acc->first_name = Input::get('first_name');
				$acc->last_name = Input::get('last_name');
				$acc->location = Input::get('location');
				$acc->phone = Input::get('phone');
				$acc->address = Input::get('address');
				if (Input::hasFile('photo')){
					$photo = Input::file('photo');
					$destinationPath = public_path() . 'assets/images/member/';
					$millisecond = round(microtime(true) * 1000);
					$filename = $millisecond . '_' . str_random(2) . '_' . $photo->getClientOriginalName();
					$uploadSuccess = $photo->move($destinationPath, $filename);
				}
				$acc->update();

				$msg = array('type'=>'success','msg'=>'My profile is update successfully');
				array_push($msgs,$msg);
				return Redirect::back()
					->with('msgs', $msgs);
			}
		}

		public function changePassword()
		{
			$msgs = array();
			if (Input::has('curPassword')) {
				$user = Auth::user();
				if (Hash::check(Input::get('curPassword'), $user->password)) {
					if (Input::has('newPassword') && Input::has('conPassword')) {
						if (Input::get('newPassword') == Input::get('conPassword')) {
							$newPassword = Hash::make(Input::get('newPassword'));
							$user->password = $newPassword;
							$user->save();

							$msg = array('type'=>'success','msg'=>'Password changed successfully!');
							array_push($msgs,$msg);
							return Redirect::to('member/my_profile')->with('msgs', $msgs);
						} else {
							$msg = array('type'=>'error','msg'=>'Password does not match!');
							array_push($msgs,$msg);
						}
					} else {
						$msg = array('type'=>'error','msg'=>'Password could not be blank!');
						array_push($msgs,$msg);
					}
				}
				else {
					$msg = array('type'=>'error','msg'=>'Current Password is not correct!');
					array_push($msgs,$msg);
				}
			} else {
				$msg = array('type'=>'error','msg'=>'Please input Current Password');
				array_push($msgs,$msg);
			}
			return Redirect::to('member/my_profile')
				->withInput()
				->with('msgs', $msgs);
		}

		public function page()
		{
			$this->layout->content = View::make('account.page');
		}

		public function myMap()
		{
			$this->layout->content = View::make('account.my-map');
		}

}