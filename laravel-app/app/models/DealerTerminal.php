<?php

class DealerTerminal extends \Eloquent {
	protected $table = 'terminal';
	
	protected $fillable = array('serial','dealer__id','name','imsi','ecard_id','status','reason','staff_id','updated_by_staff__id');
	
	public static $rules = array(
	    'serial' => 'required',
	    'dealer__id' => 'integer|required'
	);
	
	/**
     * The attributes of the model.
     * 
     * @var array
     */
    protected $guarded = array('serial', 'dealer__id');
	/**
     * The primary key of the table.
     * 
     * @var string
	 * */
	protected $primaryKey = array('serial', 'dealer__id');
	
	/**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = 'datetime';
	public function getDates()
	{
	    return array();
	}
}