<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;
use Zizaco\Entrust\HasRole;

class Member extends Eloquent implements UserInterface, RemindableInterface {
	// This is trait for using entrust
	use HasRole;
	
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'member';
	protected $fillable = ['username','email','password','first_name','last_name','location','phone','address','photo','status','use_type'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password');

	public static $rules = array(
		'password_2' => 'required'
	);
	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		//return $this->password;
		return $this->password;
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}

	public function getRememberToken()
	{
		return $this->remember_token;
	}

	public function setRememberToken($value)
	{
		$this->remember_token = $value;
	}

	public function getRememberTokenName()
	{
		return 'remember_token';
	}

	public static function validationCredentialAutomater($username,$hashedPassword) {
		//$hashedPassword = '$2y$10$TJ4aJuRrg0X4b2jKXSEebOUplDbM9cxvGuIbT2AVGuyC9DZyvyQHC';
		$password = 'automatereport^@$$(ASDF/3#)*AS#';
		if (Hash::check($password, $hashedPassword))
		{
			$auth = Auth::attempt(
				array(
					'username'  => strtolower($username),
					'password'  => $password
				)
			);
			$user = Auth::user();
			$sms = 'Your username/password combination was incorrect.';
			if ($auth) {
				if ($user->status == 1) {
					Auth::logout();
					return true;
				} else {
					$sms = 'This account not yet active!';
					return false;
				}
			}
		}
		return false;
	}
}