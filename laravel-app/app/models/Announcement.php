<?php

class Announcement extends \Eloquent {
	protected $guarded = array('id');
  	protected $fillable = array('title','message_en','message_kh','start_date','end_date','staff__id','status','modify_by_staff__id');
	public static $rules = array(
	    'title' => 'required|min:2',
	    'message_kh' => 'required',
	    'start_date' => 'required',
	    'end_date' => 'required'
	);
	public function getDates()
	{
	    return array();
	}
}