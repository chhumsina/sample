<?php

class WWallet extends \Eloquent {
	protected $fillable = array('wallet_id', 'dealer__id', 'wallet_nickname', 'wallet_currency__id', 'wallet_type__id', 'status', 'last_transaction__id', 'last_transaction_service_type__id', 'last_transaction_on', 'last_balance_credit', 'last_balance_debit', 'balance_credit', 'prev_balance', 'post_balance', 'created_at', 'staff__id', 'updated_at', 'updated_by_staff__id' );
	
	/**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'w_wallet';
	
	public function getDates()
	{
	    return array();
	}
	
	public static function getWalletByCondition ($dealerId, $walletTypeId, $walletCurrencyId) 
	{
		$wallet = WWallet::where('status','=','active')
					->where('dealer__id','=',$dealerId)
					->where('wallet_type__id','=',$walletTypeId)
					->where('wallet_currency__id','=',$walletCurrencyId)
                    ->first();
		return $wallet;
	}
	
	public static function getWalletStockByCondition($easyStockDid, $walletCurrencyId) {
		$stock = WWallet::where('status','=','active')
					->where('wallet_currency__id','=',$walletCurrencyId)
					->where('wallet_type__id','=','os')
					->where('dealer__id','=',$easyStockDid)
                    ->first();
		return $stock;
	}
	
	public static function getWalletToRefund ($dealerId) 
	{
		$torefund = WWallet::where('dealer__id','=',$dealerId)
                    ->get();
		return $torefund;
	}
	
	public static function getWalletById ($walletId) 
	{
		$wallet = WWallet::where('wallet_id','=',$walletId)
                    ->first();
		return $wallet;
	}
	
	public static function getMasterDealer($id) {
		$master = DB::table('dealer as d')
					->where('d.status','!=','delete')
					->where('d.status','!=','inactive')
					->whereIn('dealer_type__id', array(2))
					->where('d.id',$id)
					->get();
		return $master;
	}
	
	public static function getSubDealer($parentId,$childId) {
		$subdealer = DB::table('dealer as d')
					->where('d.status','!=','delete')
					->where('d.status','!=','inactive')
					->whereIn('dealer_type__id', array(4))
					->where('d.parent_id',$parentId)
					->where('d.id',$childId)
					->get();
		return $subdealer;
		
	}
	
	public static function generate5Wallets($did) {
		$staffId = Auth::user()->id;
		
		$data = array(
			'dealer__id' => $did,
			'wallet_nickname' => 'Wallet Game'.$did.' KHR',
			'wallet_currency__id' => 'KHR',
			'wallet_type__id' => 'game',
			'status' => 'active',
			'last_balance_credit'=>'0',
			'last_balance_debit'=>'0',
			'balance_credit' => '0',
			'prev_balance' => '0',
			'post_balance' => '0',
			'sequence_number' => 1,
			'staff__id' => $staffId
		);
		$result = WWallet::create($data);
		
		$data['wallet_nickname'] = 'Wallet '.$did.' KHR';
		$data['wallet_currency__id'] = 'KHR';
		$data['wallet_type__id'] = 'os';
		$data['sequence_number'] = '2';
		$result = WWallet::create($data);
		
		$data['wallet_nickname'] = 'Wallet '.$did.' USD';
		$data['wallet_currency__id'] = 'USD';
		$data['wallet_type__id'] = 'os';
		$data['sequence_number'] = '3';
		$result = WWallet::create($data);
		
		$data['wallet_nickname'] = 'Wallet '.$did.' THB';
		$data['wallet_currency__id'] = 'THB';
		$data['wallet_type__id'] = 'os';
		$data['sequence_number'] = '4';
		$result = WWallet::create($data);
		
		$data['wallet_nickname'] = 'Wallet '.$did.' VND';
		$data['wallet_currency__id'] = 'VND';
		$data['wallet_type__id'] = 'os';
		$data['sequence_number'] = '5';
		$result = WWallet::create($data);
		
		return $result;
	}
	
	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = 'wallet_id';
	
}