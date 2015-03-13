<?php

class DealerBank extends \Eloquent {
	protected $fillable = array('dealer_bank_id','dealer__id','bank__id','account_name','account','status','staff__id','updated_by_staff__id');
	
	public static $rules = array(
	    'dealer__id' => 'integer|required',
	    'bank__id' => 'required',
	    'account_name' => 'required',
	    'account' => 'required',
	);
	
	/**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dealer_banks'; //users
    
    /**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = 'dealer_bank_id';
	public function getDates()
	{
	    return array();
	}
}