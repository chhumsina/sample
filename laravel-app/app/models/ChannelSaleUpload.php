<?php

class ChannelSaleUpload extends \Eloquent {
	protected $fillable = array('channel_sale_upload_id','file_name','created_at','staff__id','updated_at','updated_by_staff__id','status','remark');
	protected $table = 'rep_channel_sale_upload';
	protected $primaryKey = 'channel_sale_upload_id';
}