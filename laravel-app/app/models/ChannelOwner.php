<?php

class ChannelOwner extends \Eloquent {
	protected $table = 'sys_channel_owner';
	protected $fillable = array('channel_owner_id','channel_owner_name','status','sequence_number');
	
	public static function getSysServiceType() {
		$sysServiceTypes = DB::table('sys_service_type')
				->where('status','=','active')
				->where('allow_add_actual_sale','=','t')
				->orderBy('sequence_number','asc')
				->get();
		return $sysServiceTypes;
	}
	
	public static function getChannelOwnerByMapKey($mapKey) {
		$channelOwner = DB::table('sys_channel_owner')
				->where('channel_owner_map_key','=',$mapKey)
				->first();
		return $channelOwner;
	}
}