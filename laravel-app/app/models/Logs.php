<?php

class Logs extends \Eloquent {
	protected $table = 'logs'; //users
	protected $fillable = array('id','staff__id','action','object_type','object__id','old_data','new_data','reason');
	public function getDates()
	{
	    return array();
	}
}