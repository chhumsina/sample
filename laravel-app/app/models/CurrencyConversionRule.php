<?php

class CurrencyConversionRule extends \Eloquent {
	protected $fillable = array('c_conversion_rule_id', 'from_currency__id', 'to_currency__id', 'status', 'buy_rate', 'sell_rate', 'mid_rate', 'created_at', 'sequence_number', 'staff__id','updated_by_staff__id','main_multiple_currency__id');

	public static $rules = array(
	    'from_currency__id' => 'required',
	    'to_currency__id' => 'required',
	    'buy_rate' => 'required|numeric',
	    'sell_rate' => 'required|numeric',
	    'mid_rate' => 'required|numeric'
	);
	
	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = 'c_conversion_rule_id';
	
	/**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'c_currency_conversion_rule';
	public function getDates()
	{
	    return array();
	}
}