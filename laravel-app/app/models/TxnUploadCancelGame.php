<?php

class TxnUploadCancelGame extends \Eloquent {
	/**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'txn_upload_cancel_game';
	
	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = 'upload_cancel_game_id';
	
	protected $fillable = array('upload_cancel_game_id','remark','file_name','created_at','staff__id','updated_at','updated_by_staff__id');
	
	public $timestamps = false;
	public function getDates()
	{
	    return array();
	}
}