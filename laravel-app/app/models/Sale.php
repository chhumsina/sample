<?php

class Sale extends \Eloquent {
	protected $fillable = array('sale_staff_id','id','name','position','phone','email','status','staff__id','parent_id','ecard_id','hold_dealers');
	
	public static $rules = array(
	    //'id' => 'required',
	    'name' => 'required',
	    'position' => 'required',
	    'ecard_id' => 'max:20'
	);
	
	/**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = 'datetime';
	 /**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = 'sale_staff_id';
	
	public function getDates()
	{
	    return array();
	}
}