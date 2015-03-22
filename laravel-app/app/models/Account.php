<?php

class Account extends \Eloquent {
	protected $table = 'member';
	protected $fillable = array('username','email','password','password_temp','first_name','last_name','location','phone','address','photo','cover','bio','status','about','use_type','confirmation_code');
	public static $rules = array(
		'username' => 'required',
		'email' => 'required',
		'password' => 'required',
	);

	public function getAuthPassword()
	{
		return $this->password;
	}
}