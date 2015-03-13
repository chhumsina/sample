<?php

class TxnTransactionItem extends \Eloquent {
	protected $fillable = array('transaction_item_id', 'transaction__id', 'dealer__id', 'wallet_id', 'requested_value', 'transfer_value', 'prev_balance', 'post_balance', 'user_direction','prev_balance_credit','post_balance_credit');
	public $timestamps = false;
	
	/**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'txn_transaction_items'; //users
    
    /**

	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = 'transaction_item_id';
	
	public function getDates()
	{
	    return array();
	}
}