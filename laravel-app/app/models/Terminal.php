<?php

class Terminal extends \Eloquent {
	protected $fillable = array('serial', 'imsi', 'ecard_id', 'status', 'reason','updated_by_staff__id','staff__id','stock__location');
	public static $rules = array(
	    'serial' => 'required|min:11|max:20',
	    'imsi' => 'required|min:18|max:20',
	    'ecard_id' => 'required|min:8|max:20'
	);
	
	public static function getDealerSerial($dealerId) {
		$DealerSerial = DB::table("terminal")
					->where('status','=','active')
					->where('dealer__id','=',$dealerId)
					->first();
		return $DealerSerial;
	}
	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = 'serial';

	/**
	 * Indicates if the IDs are auto-incrementing.
	 *
	 * @var bool
	 */
	public $incrementing = false;
	public function getDates()
	{
	    return array();
	}
}