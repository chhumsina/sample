<?php

class TxnTransactionApproval extends \Eloquent {
	protected $fillable = array('transaction_approval_id', 'transaction_id', 'action','action_gateway', 'approval_status', 'remark', 'created_at', 'staff__id');
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'txn_transaction_approval';

	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = 'transaction_approval_id';
	
	public $timestamps = false;
	public function getDates()
	{
	    return array();
	}
}