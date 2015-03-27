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

	public function forgetPassword()
	{
		$this->layout->content = View::make('account.forget_password');
	}

	public function store()
	{
		$msgs = array();
		$data =  Input::except(array('_token')) ;
		$rule  =  array(
			'username'   => 'required|unique:tbl_user',
			'email'      => 'required|email|unique:tbl_user',
			'password'   => 'required|min:6|same:cpassword',
			'cpassword'  => 'required|min:6'
		) ;

		$validator = Validator::make($data,$rule);

		if ($validator->passes())
		{
			if(Input::has('register')){
				if(!Input::has('honey')){
					$confirmation_code = str_random(30);
					$username = ucfirst(strtolower(Input::get('username')));

					User::create(array('username' => $username, 'email' => Input::get('email'), 'role_id' => 2, 'password' => Hash::make(Input::get('password')), 'confirmation_code' => $confirmation_code, 'location_id' => 1));
					$messages = array('code' => $confirmation_code, 'username' => $username);

					Mail::send('email.verify', $messages, function ($message) {
						$message->to(Input::get('email'), Input::get('username'))->subject('Khmermoo.com : Active Account');
					});

					$msg = array('type' => 'success', 'msg' => 'Thanks '.$username.' for signing up! Please check your email ('.Input::get('email').') to activate the account.');
					array_push($msgs, $msg);

					return Redirect::back()->with('msgs', $msgs);
				}

				$msg = array('type'=>'error','msg'=>'Contact me to get money for your job :)');
				array_push($msgs,$msg);
				return Redirect::back()
					->withInput()
					->with('msgs', $msgs);
			}
		}

		return Redirect::back()
			->withInput()
			->withErrors($validator)
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

		$account = User::whereConfirmationCode($confirmation_code)->first();

		if (!$account)
		{
			$msg = array('type'=>'error','msg'=>'The actiaved link is expired.');
			array_push($msgs,$msg);

			return Redirect::to('login')
				->withInput()
				->with('msgs', $msgs);
		}

		$account->disable = 1;
		$account->confirmation_code = null;
		$account->save();

		$msg = array('type'=>'success','msg'=>'You have successfully activated your account.');
		array_push($msgs,$msg);

		return Redirect::to('login')
			->withInput()
			->with('msgs', $msgs);
	}

	// forget password confirmation
	public function forgetPasswordConfirm($code) {
		$msgs = array();
		$acc = Account::where('confirmation_code','=', $code)->where('password_temp','!=','');

		if($acc->count()) {
			$acc = $acc->first();
			$acc->password = $acc->password_temp;
			$acc->password_temp = '';
			$acc->confirmation_code = '';
			if($acc->save()) {
				$msg = array('type'=>'success','msg'=>'Your account is recoveried now, please login with your New password!.');
				array_push($msgs,$msg);

				return Redirect::to('login')
					->withInput()
					->with('msgs', $msgs);
			}
		}

		$msg = array('type'=>'success','msg'=>'Your account could not recovery!');
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
			if ($user->disable == 1 && $user->role_id == 2) {
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
					$destinationPath = 'assets/images/member/';
					if(File::exists($destinationPath.Auth::user()->photo)){
						File::delete($destinationPath.Auth::user()->photo);
					}
					$file = Input::file('photo');
					$millisecond = round(microtime(true) * 1000);
					$filename = $millisecond . '_' . str_random(2) . '_' . $file->getClientOriginalName();
					//$file->move($destinationPath, $filename);
					Image::make($file->getRealPath())->resize('100','100')->save($destinationPath.$filename);
					$acc->photo = $filename;
				}

				$acc->update();

				$msg = array('type'=>'success','msg'=>'My profile is update successfully');
				array_push($msgs,$msg);
				return Redirect::back()
					->with('msgs', $msgs);

			}elseif(Input::has('savePage'))
			{
				$acc = Account::find(Auth::user()->id);
				$acc->about = Input::get('about');
				$acc->bio = Input::get('bio');
				if (Input::hasFile('photo')){
					$destinationPath = 'assets/images/member/cover/';
					if(File::exists($destinationPath.Auth::user()->cover)){
						File::delete($destinationPath.Auth::user()->cover);
					}
					$file = Input::file('photo');
					$millisecond = round(microtime(true) * 1000);
					$filename = $millisecond . '_' . str_random(2) . '_' . $file->getClientOriginalName();
					//$file->move($destinationPath, $filename);
					Image::make($file->getRealPath())->resize('830','300')->save($destinationPath.$filename);
					$acc->cover = $filename;
				}

				$acc->update();

				$msg = array('type'=>'success','msg'=>'Page is update successfully');
				array_push($msgs,$msg);
				return Redirect::back()
					->with('msgs', $msgs);
			}
		}

		public function validateForgetPassword()
		{
			$msgs = array();
			$inputs = Input::all();
			$email = $inputs['email'];

			if(Input::has('submit'))
			{

				if(empty($email)){
					$msg = array('type'=>'error','msg'=>'Please entry the Email!');
					array_push($msgs,$msg);
					return Redirect::back()
						->withInput()
						->with('msgs', $msgs);
				}

				$acc = Account::where('email','=', $email);
				if($acc->count()) {
					$acc = $acc->first();

					$code = str_random(60);
					$password = str_random(10);

					$acc->confirmation_code = $code;
					$acc->password_temp = Hash::make($password);

					$messages = array( 'code' => $code, 'password'=> $password);
					if($acc->save()) {
						Mail::send('email.forget-password', $messages, function($message) {
							$message->to(Input::get('email'), Input::get('email'))
								->subject('Recovery Password!');
						});

						$msg = array('type'=>'success','msg'=>'Password click the recovery link vai your email.');
						array_push($msgs,$msg);
						return Redirect::back()
							->withInput()
							->with('msgs', $msgs);
					}

				}else{
					$msg = array('type'=>'error','msg'=>'No Email to recovery password!');
					array_push($msgs,$msg);
					return Redirect::back()
						->withInput()
						->with('msgs', $msgs);
				}

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
			$acc = Auth::user();
			$this->layout->content = View::make('account.page', compact('acc'));
		}

		public function myMap()
		{
			$this->layout->content = View::make('account.my-map');
		}

}