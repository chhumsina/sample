<?php

class KhanDealerType extends \Eloquent {
	/**
     * The attributes of the model.
     * 
     * @var array
     */
    protected $guarded = array('khan__code', 'dealer_type__id');
	/**
     * The primary key of the table.
     * 
     * @var string
     */
    protected $primaryKey = array('khan__code', 'dealer_type__id');
	
	/**
     * Disabled the `update_at` field in this table.
     * 
     * @var boolean
     */
    public $timestamps = false;
	
	protected $fillable = array('khan__code','dealer_type_id','num_dealer');
	public function getDates()
	{
	    return array();
	}
}