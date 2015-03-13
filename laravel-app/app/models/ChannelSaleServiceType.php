<?php

class ChannelSaleServiceType extends \Eloquent {
	protected $table = 'rep_channel_sale_service_type';
	protected $fillable = array('csst_id','channel_sale__id','service_type__id','amount');
	public static $rules = array(
	    'channel_owner__id' => 'required',
	    'datetime' => 'required',
	    'currency__id' => 'required',
	    '639' => 'required',
	    '639luk' => 'required',
	    '639_pro' => 'required',
	    'pick5' => 'required',
	    'pick5l' => 'required',
	    'pick5_pro' => 'required' 
	);
	
	protected $primaryKey = 'csst_id';
	public $timestamps = false;
}