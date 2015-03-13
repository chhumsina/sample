<?php

class SaleStaffTarget extends \Eloquent {
	/**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'rep_sale_staff_targeting'; //users
    
    public static $rules = array(
	    'sale_staff__id' => 'required|integer',
	    'target_year' => 'required|integer',
	    'target_month' => 'required',
	    'target_week' => 'required',
	    'target_sale_game' => 'required|integer',
	    'target_topup_game' => 'required|integer',
	    'target_num_new_recruit' => 'required|integer',
	    'target_num_sale_visit' => 'required|integer'
	);
	
    /**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = 'sale_staff_target_id';
	
	
	protected $fillable = array('sale_staff_target_id','sale_staff__id','target_year','target_month','target_week','target_sale_game','target_topup_game',
	'target_num_new_recruit','target_num_sale_visit','created_at','staff__id','updated_at','updated_by_staff__id','upload_sale_staff_targeting__id','status');
}