<?php

class Account extends \Eloquent {
	protected $table = 'member';
	protected $fillable = array('username','email','password','first_name','last_name','location','phone','address','photo','status','use_type');
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