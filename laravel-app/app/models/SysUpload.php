<?php

class SysUpload extends \Eloquent {
	/**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sys_upload';
	
	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = 'upload_id';
	
	protected $fillable = array('upload_id','file_name','created_at','staff__id','updated_at', 'type_upload','updated_by_staff__id','status','remark');
	
	public $timestamps = false;
	public function getDates()
	{
	    return array();
	}
}