<?php

class Currency extends \Eloquent {
	protected $fillable = array();
	public static function getListCurrencyActive() {
		$currencies = DB::table('c_currency')
                    ->where('status','=','active')
					->orderBy('sequence_number','asc')
                    ->lists('currency_id','currency_id');	
					
		return $currencies;
	}
}