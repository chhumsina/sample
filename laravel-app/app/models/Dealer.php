<?php

class Dealer extends \Eloquent {
	protected $table = 'dealer';
	protected $fillable = array('id','name','dealer_type__id','wallet','khan__code','street','national_card_id','contract_file',
								'phone','email','contract_date','password','bank_acc','credit','parent_id','status','staff__id',
								'updated_by_staff__id','reference_dealer_id','commune__code','village__code','province__code',
								'status_game','status_os','num_fail_authenticate_pin','num_fail_security_code','type_account');
	
	public static $rules = array(
	    'name' => 'required',
	    'street' => 'max:100',
	    'parent_id' => 'integer',
	    'reference_dealer_id' => 'integer'
	);
	
	public static function getDealerStock() {
		$stocks = DB::table('dealer as d')
						->where('d.status','!=','delete')
						->where('d.status','!=','inactive')
						->where('d.dealer_type__id','9')->lists('name','id');
		return $stocks;
	}
	
	public static function getChildDealerByParentId($parentDid) {
		$dealers = DB::table('dealer as d')
						->where('d.status','!=','delete')
						->where('d.status','!=','inactive')
						->where('d.parent_id',$parentDid)
						->lists('name','id');
		return $dealers;
	}
	public function getDates()
	{
	    return array();
	}
}