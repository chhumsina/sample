<?php

class Account extends \Eloquent {
	protected $table = 'tbl_user';
	protected $fillable = ['username','email','password','fname','lname','location_id','phone','address','photo','disable'];
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