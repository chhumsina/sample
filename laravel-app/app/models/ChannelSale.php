<?php

class ChannelSale extends \Eloquent {
	protected $table = 'rep_channel_sale';
	protected $fillable = array('channel_sale_id','datetime','channel_owner__id','created_at','staff__id','updated_at','updated_by_staff__id','channel_sale_upload__id','status',
	'draw_pick_5_n','draw_639_n','subscriber_pick_5','subscriber_639_n','ticket_pick_5','ticket_639','draw_pick5_date','draw_639_date',
	'bet_639','bet_639_luk','bet_pick5','bet_pick5_luk','active_pos_639','active_pos_pick5','free_count_bet_639','free_count_bet_pick5','net_bet_count_639','net_bet_count_pick5','total_sell_amount_639','total_sell_amount_pick5','active_pos_total','cancel_amount_639','cancel_amount_pick5');
	
	public static $rules = array(
	    'channel_owner__id' => 'required',
	    'datetime' => 'required'
	);
	
	protected $primaryKey = 'channel_sale_id';
}