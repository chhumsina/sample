<?php

class ReportController extends \BaseController {
	/**
	 * The layout that should be used for responses.
	 */
	protected $layout = 'layouts.master';
	
	/**
	 * Show the Sale Visit
	 * GET /report/saleVisit
	 *
	 * @return Response
	 */
	public function saleVisit() {
		$inputs = Input::all();
		
		$db = DB::table('sales')
			            ->join('sales_visit', 'sales.id', '=', 'sales_visit.sales__id')
						->join('dealer As d','sales_visit.dealer__id','=','d.id')
						->join('province As p','p.code','=','d.province__code')
						->join('khan As k','k.code','=','d.khan__code');
			
		if (Input::has('sale__id')) {
				$db->where('sales.id',$inputs['sale__id']);
		}
		if (Input::has('name')) {
			$db->where('sales.name','LIKE','%'.$inputs['name'].'%');
		}
		if (Input::has('dealer__id')) {
				$db->where('sales_visit.dealer__id',$inputs['dealer__id']);
		}
		if (Input::has('dealer_name')) {
			$db->where('d.name','LIKE','%'.$inputs['dealer_name'].'%');
		}
		if (Input::has('province__code')) {
				$db->where('d.province__code',$inputs['province__code']);
		}
		if (Input::has('khan__code')) {
			$db->where('d.khan__code',$inputs['khan__code']);
		}
		
		if (Input::has('start_date') && Input::has('end_date')) {
			$db->whereBetween('sales_visit.datetime', array($inputs['start_date'], $inputs['end_date']));
		}
		$db->orderBy('datetime','desc');
		$saleVisits = $db->select('sales.id','sales.name', 'sales.position', 'sales.phone', 'sales.email', 'd.name As dealerName','sales_visit.dealer__id','d.phone As dealerPhone','sales_visit.terminal__serial','sales_visit.datetime','p.name_en AS provinceName','k.name_en AS khanName')
			            ->paginate(15)->appends($inputs);
						
		$listProvinces = DB::table('province')->lists('name_en','code');
		$this->layout->content = View::make('report.sale_visit',compact('saleVisits','listProvinces'));
	}
	
	/**
	 * Show the Sale Visit
	 * GET /report/saleVisit
	 *
	 * @return Response
	 */
	public function dealerSummaryBalanceReportNewStructure() {
		$inputs = Input::all();
		
		$rules = array(
		    'specific_date' => 'required',
		    'wallet_type__id' => 'required',
		    'wallet_currency_id' => 'required',
		    'balance_type' => 'required'
		);
		
		$msgs = array();
		$dealersSummaryBalance = null;
		$sumbalance = 0;
		if (Input::has('specific_date')) {
			$validation = Validator::make($inputs, $rules);
			if (!$validation->passes()) 
			{
				return Redirect::back()
	            ->withInput()
	            ->withErrors($validation);
			}
		
			$date = Input::get('specific_date');//.':59.999999';
			$walletTypeId = $inputs['wallet_type__id'];
			$walletCurrencyId = $inputs['wallet_currency_id'];
			$balanceType = $inputs['balance_type'];
			$userCategory = $inputs['user_category'];
			$userType = $inputs['dealer_type__id'];
			
			if ($balanceType == 'post_balance_credit') {
				$query = "(select * from (SELECT t1.transaction_id,t1.iterm_dealer__id as dealer__id,t1.wallet_type__id,t1.wallet_currency__id,t1.requested_value,t1.transfer_value,t1.datetime,t1.prev_balance,t1.post_balance,t1.prev_balance_credit,t1.post_balance_credit,t1.user_direction
							FROM 
							(select row_number() OVER (ORDER BY t1.datetime) row_number,t1.*,ti1.prev_balance,ti1.post_balance,ti1.prev_balance_credit,ti1.post_balance_credit,ti1.dealer__id as iterm_dealer__id,ti1.user_direction,w1.wallet_type__id,w1.wallet_currency__id from txn_transaction_header t1  
								INNER JOIN txn_transaction_items ti1 ON t1.transaction_id = ti1.transaction__id
								INNER JOIN w_wallet w1 ON w1.wallet_id = ti1.wallet_id
								WHERE t1.datetime <= '".$date."'
											AND w1.wallet_type__id = '".$walletTypeId."'
											AND w1.wallet_currency__id = '".$walletCurrencyId."'
											AND (t1.service_type__id = 'credit_game' OR t1.service_type__id = 'payback_game')
								ORDER BY ti1.dealer__id asc,t1.datetime desc) t1 
							LEFT JOIN 
							(select row_number() OVER (ORDER BY t2.datetime) row_number,t2.*,ti2.prev_balance,ti2.post_balance,ti2.prev_balance_credit,ti2.post_balance_credit,ti2.dealer__id as iterm_dealer__id,ti2.user_direction,w2.wallet_type__id,w2.wallet_currency__id from txn_transaction_header t2 
								INNER JOIN txn_transaction_items ti2 ON t2.transaction_id = ti2.transaction__id
								INNER JOIN w_wallet w2 ON w2.wallet_id = ti2.wallet_id
								WHERE t2.datetime <= '".$date."'
											AND w2.wallet_type__id = '".$walletTypeId."'
											AND w2.wallet_currency__id = '".$walletCurrencyId."'
											AND (t2.service_type__id = 'credit_game' OR t2.service_type__id = 'payback_game')
								ORDER BY ti2.dealer__id asc,t2.datetime desc) t2
						ON (t1.iterm_dealer__id = t2.iterm_dealer__id AND t1.row_number < t2.row_number)
													WHERE t2.row_number IS NULL order by t1.iterm_dealer__id asc,t1.datetime desc) as txn) as dtwt";
			} else {
				$query = "(select * from (SELECT t1.transaction_id,t1.iterm_dealer__id as dealer__id,t1.wallet_type__id,t1.wallet_currency__id,t1.requested_value,t1.transfer_value,t1.datetime,t1.prev_balance,t1.post_balance,t1.prev_balance_credit,t1.post_balance_credit,t1.user_direction

						FROM 
						
						(select row_number() OVER (ORDER BY t1.datetime) row_number,t1.*,ti1.prev_balance,ti1.post_balance,ti1.prev_balance_credit,ti1.post_balance_credit,ti1.dealer__id as iterm_dealer__id,ti1.user_direction,w1.wallet_type__id,w1.wallet_currency__id from txn_transaction_header t1  
														INNER JOIN txn_transaction_items ti1 ON t1.transaction_id = ti1.transaction__id
														INNER JOIN w_wallet w1 ON w1.wallet_id = ti1.wallet_id
														WHERE t1.datetime <= '".$date."'
															AND w1.wallet_type__id = '".$walletTypeId."'
															AND w1.wallet_currency__id = '".$walletCurrencyId."'
														ORDER BY ti1.dealer__id asc,t1.datetime desc) t1
						
						LEFT JOIN
						
						(select row_number() OVER (ORDER BY t1.datetime) row_number,t1.*,ti1.prev_balance,ti1.post_balance,ti1.prev_balance_credit,ti1.post_balance_credit,ti1.dealer__id as iterm_dealer__id,ti1.user_direction,w1.wallet_type__id,w1.wallet_currency__id from txn_transaction_header t1  
														INNER JOIN txn_transaction_items ti1 ON t1.transaction_id = ti1.transaction__id
														INNER JOIN w_wallet w1 ON w1.wallet_id = ti1.wallet_id
														WHERE t1.datetime <= '".$date."'
															AND w1.wallet_type__id = '".$walletTypeId."'
															AND w1.wallet_currency__id = '".$walletCurrencyId."'
														ORDER BY ti1.dealer__id asc,t1.datetime desc) t2
						
						ON (t1.iterm_dealer__id = t2.iterm_dealer__id AND t1.row_number < t2.row_number)
													WHERE t2.row_number IS NULL order by t1.iterm_dealer__id asc,t1.datetime desc) as txn) as dtwt";
			}
				
				
			$db = DB::table('dealer AS d')->select('d.id','d.name','d.dealer_type__id','d.phone','p.name_en AS province','k.name_en AS district','dtwt.*')
					->leftJoin('province AS p','p.code','=','d.province__code')
					->leftJoin('khan AS k','k.code','=','d.khan__code')
					->join(DB::raw(DB::raw($query)), function($join)
				        {
				            $join->on('dtwt.dealer__id','=','d.id');
				        });
			
			if (Input::has('dealer__id')) {
					$db->where('d.id',$inputs['dealer__id']);
			}
			if (Input::has('province__code')) {
					$db->where('d.province__code',$inputs['province__code']);
			}
			if (Input::has('khan__code')) {
				$db->where('d.khan__code',$inputs['khan__code']);
			}
			if (Input::has('user_category')) {
				if ($userCategory == 'stock') {
					$db->where('d.dealer_type__id','9');
				} else if ($userCategory == 'channel') {
					$db->where(function($query){
						$query->where('d.dealer_type__id','1')
							  ->orwhere('d.dealer_type__id','2')
							  ->orwhere('d.dealer_type__id','3')
							  ->orwhere('d.dealer_type__id','4');
					});
				}
			}
			
			if (Input::has('dealer_type__id')) {
				$db->where('d.dealer_type__id',$userType);
			}
			if (Input::has('condition')) {
				if (Input::get('condition') == '1') {
					$db->where('dtwt.post_balance','<','100000');
				} else if (Input::get('condition') == '2') {
					$db->where('dtwt.post_balance','>','800000');
				}
			}
			
			if (Input::has('type_account')) {
				$db->where('d.type_account',Input::get('type_account'));
			}
			
			$db->orderBy('d.id','asc');
			
			$dealersSummaryBalanceSumAll = $db->get();
			$dealersSummaryBalance = $db->paginate(15)->appends($inputs);
			
			
			if ($balanceType == 'post_balance_credit') {
				foreach ($dealersSummaryBalanceSumAll as $key => $value) {
					$sumbalance += $value->post_balance_credit;
				}
			} else {
				foreach ($dealersSummaryBalanceSumAll as $key => $value) {
					$sumbalance += $value->post_balance;
				}
			}
			
		} else {
			$msg = array('type'=>'error','msg'=>'Please input a specific date!');
			array_push($msgs,$msg);
		}
		
						
		$listProvinces = DB::table('province')->lists('name_en','code');
		$currencies = DB::table('c_currency')
                    ->where('status','=','active')
                    ->lists('currency_id','currency_id');		
					
		$wallet_types = DB::table('sys_enumeration')
                      ->where('enum_type__id','=','wallet_type')
                      ->lists('name','enum_id');
		$dealerTypeIds = DB::table('dealer_type')
                      ->lists('name','id');
		$this->layout->content = View::make('report.dealer_summary_balance_report',compact('dealerTypeIds','wallet_types','currencies','dealersSummaryBalance','dealersSummaryBalanceSumAll','listProvinces','sumbalance'))->with('msgs', $msgs);;
	}

	/**
	 * Show the Sale Visit
	 * GET /report/saleVisit
	 *
	 * @return Response
	 */
	public function dealerSummaryBalanceReport() {
		$inputs = Input::all();
		
		$msgs = array();
		$dealersSummaryBalance = null;
		$sumbalance = 0;
		if (Input::has('specific_date')) {
			$date = Input::get('specific_date');//.':59.999999';
			$query = "(select txn_wallet_txn1.id,txn_wallet_txn1.dealer__id,txn_wallet_txn1.amount,txn_wallet_txn1.datetime,txn_wallet_txn1.pre_balance,txn_wallet_txn1.post_balance,txn_wallet_txn1.tbl from
						(
							select *,'txn' as tbl from (SELECT m1.id,m1.dealer__id,m1.amount,m1.datetime,m1.pre_balance,m1.post_balance
							FROM (select * from transaction  m1 where m1.datetime <= '".$date."' ORDER BY m1.dealer__id asc,m1.datetime desc) m1 LEFT JOIN (select * from transaction  m1 where m1.datetime <= '".$date."' ORDER BY m1.dealer__id asc,m1.datetime desc) m2
							 ON (m1.dealer__id = m2.dealer__id AND m1.id < m2.id)
							WHERE m2.id IS NULL order by dealer__id asc,datetime desc) as txn
					
							UNION ALL
					
							select *,'wallet_txn' as tbl from (
							select wt1.id,wt1.dealer__id,wt1.amount,wt1.datetime,wt1.pre_balance,wt1.post_balance from 
					
							(select wt.id,wt.did as dealer__id,wt.amount,wt.create_date datetime,wt.pre_balance,wt.balance post_balance 
							from wallet_transaction wt 
							where wt.create_date < '".$date."'
							order by wt.did asc,wt.create_date desc) wt1
					
							LEFT JOIN 
					
							(select wt.id,wt.did as dealer__id,wt.amount,wt.create_date datetime,wt.pre_balance,wt.balance post_balance 
							from wallet_transaction wt 
							where wt.create_date < '".$date."'
							order by wt.did asc,wt.create_date desc) wt2
					
							ON (wt1.dealer__id = wt2.dealer__id AND wt1.id < wt2.id)
							WHERE wt2.id IS NULL order by dealer__id asc,datetime desc
							) as wallet_txn order by dealer__id asc,datetime desc
						) as txn_wallet_txn1
					
						LEFT JOIN 
					
						(
							select *,'txn' as tbl from (SELECT m1.id,m1.dealer__id,m1.amount,m1.datetime,m1.pre_balance,m1.post_balance
							FROM (select * from transaction  m1 where m1.datetime <= '".$date."' ORDER BY m1.dealer__id asc,m1.datetime desc) m1 LEFT JOIN (select * from transaction  m1 where m1.datetime <= '".$date."' ORDER BY m1.dealer__id asc,m1.datetime desc) m2
							 ON (m1.dealer__id = m2.dealer__id AND m1.id < m2.id)
							WHERE m2.id IS NULL order by dealer__id asc,datetime desc) as txn
					
							UNION ALL
					
							select *,'wallet_txn' as tbl from (
							select wt1.id,wt1.dealer__id,wt1.amount,wt1.datetime,wt1.pre_balance,wt1.post_balance from 
					
							(select wt.id,wt.did as dealer__id,wt.amount,wt.create_date datetime,wt.pre_balance,wt.balance post_balance 
							from wallet_transaction wt 
							where wt.create_date < '".$date."'
							order by wt.did asc,wt.create_date desc) wt1
					
							LEFT JOIN 
					
							(select wt.id,wt.did as dealer__id,wt.amount,wt.create_date datetime,wt.pre_balance,wt.balance post_balance 
							from wallet_transaction wt 
							where wt.create_date < '".$date."'
							order by wt.did asc,wt.create_date desc) wt2
					
							ON (wt1.dealer__id = wt2.dealer__id AND wt1.id < wt2.id)
							WHERE wt2.id IS NULL order by dealer__id asc,datetime desc
							) as wallet_txn order by dealer__id asc,datetime desc
						) as txn_wallet_txn2
					
						ON (txn_wallet_txn1.dealer__id = txn_wallet_txn2.dealer__id AND txn_wallet_txn1.id < txn_wallet_txn2.id)
						WHERE txn_wallet_txn2.id IS NULL order by txn_wallet_txn2.dealer__id asc,txn_wallet_txn2.datetime desc) as dtwt";
				
				
			$db = DB::table('dealer AS d')->select('d.id','d.name','d.phone','p.name_en AS province','k.name_en AS district','dtwt.id AS txn_id','dtwt.amount','dtwt.datetime','dtwt.pre_balance','dtwt.post_balance','dtwt.tbl')
					->leftJoin('province AS p','p.code','=','d.province__code')
					->leftJoin('khan AS k','k.code','=','d.khan__code')
					->join(DB::raw(DB::raw($query)), function($join)
				        {
				            $join->on('dtwt.dealer__id','=','d.id');
				        });
				        
			
			if (Input::has('dealer__id')) {
					$db->where('d.id',$inputs['dealer__id']);
			}
			if (Input::has('province__code')) {
					$db->where('d.province__code',$inputs['province__code']);
			}
			if (Input::has('khan__code')) {
				$db->where('d.khan__code',$inputs['khan__code']);
			}
			
			if (Input::has('condition')) {
				if (Input::get('condition') == '1') {
					$db->where('dtwt.post_balance','<','100000');
				} else if (Input::get('condition') == '2') {
					$db->where('dtwt.post_balance','>','800000');
				}
			}
			$db->orderBy('d.id','asc');
			
			$dealersSummaryBalanceSumAll = $db->get();
			$dealersSummaryBalance = $db->paginate(15)->appends($inputs);
			
			
			foreach ($dealersSummaryBalanceSumAll as $key => $value) {
				$sumbalance += $value->post_balance;
			}
		} else {
			$msg = array('type'=>'error','msg'=>'Please input a specific date!');
			array_push($msgs,$msg);
		}
		
						
		$listProvinces = DB::table('province')->lists('name_en','code');
		$this->layout->content = View::make('report.dealer_summary_balance_report_back_up_old',compact('dealersSummaryBalance','dealersSummaryBalanceSumAll','listProvinces','sumbalance'))->with('msgs', $msgs);;
	}

	public function transactionReport() {
		DB::connection()->disableQueryLog();
		//-------------------------------------------------
		$inputs = Input::all();
		$banksSumTxns = null;
		$servicetypesSumTxns = null;
		$banks = null;
		if (Input::has('tcy_currency_id')) {
			$currency = Input::get('tcy_currency_id');
			$db = DB::table('txn_transaction_header as t')
						->join('dealer as d','d.id','=','t.dealer__id')
						->join('sys_status as ss','ss.status_id','=','t.status')
						->leftJoin('staff AS sr','sr.id','=','t.staff__id')
						->where('service_type__id','!=','stock_initiate');
			if (Input::has('group_service_type_id')) {
				
				$serviceGroupId = $inputs['group_service_type_id'];
				
				$service_type_map_groups = DB::table('sys_service_type_group AS sg')
        			->join('sys_service_type_map_group AS map','map.service_type_group__id','=','sg.group_service_type_id')
                    ->where('sg.status','=','active')
					->where('sg.group_service_type_id',$serviceGroupId)
					->orderBy('map.service_type_group__id','asc')
                    ->get();            
	
				$arrayListGroupServiceType = array();
				$arrayStoreIds = array();
				foreach ($service_type_map_groups as $service_type_map_group) {
					$serviceTypeId = $service_type_map_group->service_type__id;
					array_push($arrayStoreIds,$serviceTypeId);
				}

				$db->whereIn('t.service_type__id',$arrayStoreIds);
			}
			
			if (Input::has('start_date') && Input::has('end_date')) {
				$db->whereBetween('t.datetime', array($inputs['start_date'], $inputs['end_date']));
			}
			
			if (Input::has('did')) {
				$db->where('d.id',$inputs['did']);
			}
			if (Input::has('name')) {
				$db->where('d.name','LIKE','%'.$inputs['name'].'%');
			}
			if (Input::has('service_type__id')) {
				$db->where('t.service_type__id',$inputs['service_type__id']);
			}
			if (Input::has('tcy_currency_id')) {
				$db->where('t.tcy_currency_id',$inputs['tcy_currency_id']);
			}				
			if (Input::has('status')) {
				$status = $inputs['status'];
				if ($status == 'TA') {
					$db->where(function($query) {
							$query->orwhere('t.status','A1')
							->orwhere('t.status','A2')
							->orwhere('t.status','A3');
	       			 });
				} else {
					$db->where('t.status',$inputs['status']);
				}
			}
			
			if (Input::has('transaction_id')) {
				$db->where('t.transaction_id',$inputs['transaction_id']);
			}
			if (Input::has('tsn_os')) {
				
				$db->where(function($query){
					$query->where('t.tsn_os',Input::get('tsn_os'))
					  ->orwhere('t.tsn',Input::get('tsn_os'))
					  ->orwhere('t.atr1_value',Input::get('tsn_os'));
				});
			}	
							
			if (Input::has('staff_name')) {
				$db->where('sr.name','LIKE','%'.$inputs['staff_name'].'%');
			}	
			
			if (Input::has('request_gateway__id')) {
				$db->where('t.request_gateway__id',$inputs['request_gateway__id']);
			}					
			
			$db1 = clone $db;
			$db2 = clone $db;
			
			$db->select('t.*','d.name','sr.name as createdBy','ss.name as statusName')
			   ->orderBy('t.transaction_id','desc');
			
			$txnListAll = $db->get();
			$txnRequestValue = 0;
			$txnTransferValue = 0;
			$txnChannelCommValue = 0;
			$txnOperatorCommValue = 0;
			$txnRecord = 0;
			foreach ($txnListAll as $key => $value) {
				$txnRequestValue += $value->requested_value;
				$txnTransferValue += $value->transfer_value;
				$txnChannelCommValue += $value->channel_comm;
				$txnOperatorCommValue += $value->operator_comm;
				$txnRecord++;
			}
			$n = 10;
			if (Input::has('n_record')) {
				$n = Input::get('n_record');
			}
			$transactions = $db->paginate($n)->appends($inputs);
			
			if (Input::has('service_type__id')) {
				$serviceType = $inputs['service_type__id'];
				
				//----------sum by bank---------------------
				if ($serviceType == 'deposit' || $serviceType == 'deposit_game' 
					|| $serviceType = 'withdraw' || $serviceType == 'withdraw_game' 
					|| $serviceType == 'refund' || $serviceType == 'refund_game') {
				
					$queryBanks = "(select t.transaction_id, b.bank_name from txn_transaction_header as t inner join bank as b on b.bid = CAST(coalesce(t.atr1_value) as integer) where t.service_type__id = '".$serviceType."') as btxn";
					$banksSumTxns = $db1->select(DB::raw('t.atr1_value,sum(t.requested_value) as sumRequestValue, btxn.bank_name'))
						->join(DB::raw($queryBanks), function($join)
					        {
					            $join->on('btxn.transaction_id','=','t.transaction_id');
					        })
						->groupBy('t.atr1_value')
						->groupBy('btxn.bank_name')
						->get();
				}
			}
			
			//----------sum by service type-------------
			$servicetypesSumTxns = $db2->select(DB::raw('t.service_type__id,sum(t.requested_value) as sumRequestValue, sum(t.transfer_value) as sumTransferValue,sum(t.channel_comm) as sumChannelComm,sum(t.customer_comm) as sumCustomerComm ,sum(t.operator_comm) as sumOperatorComm ,sum(ti.prev_balance) as sumPrevBalance,sum(ti.post_balance) as sumPostBalance,sum(ti.prev_balance_credit) as sumPrevBalanceCredit,sum(ti.post_balance_credit) as sumPostBalanceCredit'))
					->join('txn_transaction_items as ti','ti.transaction__id','=','t.transaction_id')
					//->where('t.dealer__id','=','ti.dealer__id')
					->where(DB::raw('"t"."dealer__id"'),DB::raw('"ti"."dealer__id"'))
					->join('w_wallet as ww','ww.wallet_id','=','ti.wallet_id')
					->where('ww.wallet_currency__id',$currency)
					->groupBy('t.service_type__id')
					->get();
				
		} else {
			$transactions = null;
		}
		
		$listServiceTypes = DB::table("sys_service_type")->where("service_type_id","!=","stock_initiate")
								->where("service_type_id","!=","stock_approval")
								->orderBy('service_type_name','asc')
								->lists("service_type_id","service_type_id");
								
		$currencies = DB::table('c_currency')
                    ->where('status','=','active')
                    ->lists('currency_id','currency_id');
                    
        $service_type_map_groups = DB::table('sys_service_type_group AS sg')
        			->join('sys_service_type_map_group AS map','map.service_type_group__id','=','sg.group_service_type_id')
                    ->where('sg.status','=','active')
					->orderBy('map.service_type_group__id','asc')
                    ->get();    
					
	
		$arrayListGroupServiceType = array();
		$arrayStoreIds = array();
		foreach ($service_type_map_groups as $service_type_map_group) {
			$serviceTypeId = $service_type_map_group->service_type__id;
			$groupServiceTypeId = $service_type_map_group->group_service_type_id;
			$groupServiceTypeName = $service_type_map_group->group_service_type_name;
			if (array_key_exists($groupServiceTypeId,$arrayListGroupServiceType)) {
				$arrayListGroupServiceType[$groupServiceTypeId] .= ','.$serviceTypeId;
			} else {
				$str = $groupServiceTypeName.' => '.$serviceTypeId;
				$arrayListGroupServiceType[$groupServiceTypeId] = $str;
			}
		}
		//--------------------------------------------------------------------------
		DB::connection()->enableQueryLog();
		
		////////////Check to export_excel////////////////////////////////////////////////////////////////
		if(isset($_POST['export_excel'])){
			
			$this->getExcelfile($txnListAll, $servicetypesSumTxns, $banksSumTxns);
			
		}else{
								
			$this->layout->content = View::make('report.transaction-report',compact('servicetypesSumTxns','banksSumTxns','transactions', 'listServiceTypes', 'currencies', 'txnRequestValue', 'txnTransferValue', 'txnChannelCommValue', 'txnOperatorCommValue', 'txnRecord', 'arrayListGroupServiceType'));
	
		}
	
	}

	/////--------------Bank Report---------------------------------
	public function bankReport() {
		DB::connection()->disableQueryLog();
		//-------------------------------------------------
		$inputs = Input::all();
		$banksSumTxns = null;
		$servicetypesSumTxns = null;
		$banks = null;
		if (Input::has('tcy_currency_id')) {
			$currency = $inputs['tcy_currency_id'];
			
			$db = DB::table('txn_transaction_header as t')
						->join('dealer as d','d.id','=','t.dealer__id')
						->join('sys_status as ss','ss.status_id','=','t.status')
						->leftJoin('staff AS sr','sr.id','=','t.staff__id')
						->where('service_type__id','!=','stock_initiate');
			if (Input::has('group_service_type_id')) {
				
				$serviceGroupId = $inputs['group_service_type_id'];
				
				$service_type_map_groups = DB::table('sys_service_type_group AS sg')
        			->join('sys_service_type_map_group AS map','map.service_type_group__id','=','sg.group_service_type_id')
                    ->where('sg.status','=','active')
					->where('sg.group_service_type_id',$serviceGroupId)
					->orderBy('map.service_type_group__id','asc')
                    ->get();            
	
				$arrayListGroupServiceType = array();
				$arrayStoreIds = array();
				foreach ($service_type_map_groups as $service_type_map_group) {
					$serviceTypeId = $service_type_map_group->service_type__id;
					array_push($arrayStoreIds,$serviceTypeId);
				}

				$db->whereIn('t.service_type__id',$arrayStoreIds);
			}
			
			if (Input::has('start_date') && Input::has('end_date')) {
				$db->whereBetween('t.datetime', array($inputs['start_date'], $inputs['end_date']));
			}
			
			if (Input::has('did')) {
				$db->where('d.id',$inputs['did']);
			}
			if (Input::has('name')) {
				$db->where('d.name','LIKE','%'.$inputs['name'].'%');
			}
			if (Input::has('service_type__id')) {
				$db->where('t.service_type__id',$inputs['service_type__id']);
			}
			if (Input::has('tcy_currency_id')) {
				$db->where('t.tcy_currency_id',$inputs['tcy_currency_id']);
			}				
			if (Input::has('status')) {
				$status = $inputs['status'];
				if ($status == 'TA') {
					$db->where(function($query) {
							$query->orwhere('t.status','A1')
							->orwhere('t.status','A2')
							->orwhere('t.status','A3');
	       			 });
				} else {
					$db->where('t.status',$inputs['status']);
				}
			}
			
			if (Input::has('transaction_id')) {
				$db->where('t.transaction_id',$inputs['transaction_id']);
			}
			if (Input::has('tsn_os')) {
				
				$db->where(function($query){
					$query->where('t.tsn_os',Input::get('tsn_os'))
					  ->orwhere('t.tsn',Input::get('tsn_os'))
					  ->orwhere('t.atr1_value',Input::get('tsn_os'));
				});
			}	
							
			if (Input::has('staff_name')) {
				$db->where('sr.name','LIKE','%'.$inputs['staff_name'].'%');
			}	
			
			if (Input::has('request_gateway__id')) {
				$db->where('t.request_gateway__id',$inputs['request_gateway__id']);
			}	
			
			if (Input::has('bank_name')) {
				$db->where('t.atr1_value',$inputs['bank_name']);
			}	
			
			if (Input::has('service_type__id')) {
				$serviceType = $inputs['service_type__id'];
				$queryBanks = "(select t.transaction_id, b.bank_name from txn_transaction_header as t inner join bank as b on b.bid = CAST(coalesce(t.atr1_value) as integer) where t.service_type__id = '".$serviceType."') as btxn";
			}else{
				
				$queryBanks = "(select t.transaction_id, b.bank_name from txn_transaction_header as t inner join bank as b on b.bid = CAST(coalesce(t.atr1_value) as integer) where t.service_type__id IN('deposit','deposit_game','withdraw','withdraw_game','refund','refund_game') ) as btxn";
				
			}
			
			if (Input::has('province__code')) {
				$db->where('d.province__code',$inputs['province__code']);
			}
			if (Input::has('khan__code')) {
				$db->where('d.khan__code',$inputs['khan__code']);
			}

			//-----Join Bank------
			$db->join(DB::raw($queryBanks), function($join)
	        {
	            $join->on('btxn.transaction_id','=','t.transaction_id');
	        });	
			
			//-----------------------
			$db1 = clone $db;
			$db2 = clone $db;
			//-----------------------
			$db->select('t.*','d.name','sr.name as createdBy','ss.name as statusName','btxn.bank_name')
			   ->orderBy('t.transaction_id','desc');
			
			$txnListAll = $db->get();
			$txnRequestValue = 0;
			$txnTransferValue = 0;
			$txnChannelCommValue = 0;
			$txnOperatorCommValue = 0;
			$txnRecord = 0;
			foreach ($txnListAll as $key => $value) {
				$txnRequestValue += $value->requested_value;
				$txnTransferValue += $value->transfer_value;
				$txnChannelCommValue += $value->channel_comm;
				$txnOperatorCommValue += $value->operator_comm;
				$txnRecord++;
			}
			
			$n = 15;
			if (Input::has('n_record')) {
				$n = Input::get('n_record');
			}
			$transactions = $db->paginate($n)->appends($inputs);
			
			//---------sumby bank------------------
			$banksSumTxns = $db1->select(DB::raw('t.atr1_value,sum(t.requested_value) as sumRequestValue, btxn.bank_name'))
				->groupBy('t.atr1_value')
				->groupBy('btxn.bank_name')
				->get();
						
			//----------sum by service type-------------
			$servicetypesSumTxns = $db2->select(DB::raw('t.service_type__id,sum(t.requested_value) as sumRequestValue, sum(t.transfer_value) as sumTransferValue,sum(t.channel_comm) as sumChannelComm,sum(t.customer_comm) as sumCustomerComm ,sum(t.operator_comm) as sumOperatorComm ,sum(ti.prev_balance) as sumPrevBalance,sum(ti.post_balance) as sumPostBalance,sum(ti.prev_balance_credit) as sumPrevBalanceCredit,sum(ti.post_balance_credit) as sumPostBalanceCredit'))
					->join('txn_transaction_items as ti','ti.transaction__id','=','t.transaction_id')
					//->where('t.dealer__id','=','ti.dealer__id')
					->where(DB::raw('"t"."dealer__id"'),DB::raw('"ti"."dealer__id"'))
					->groupBy('t.service_type__id')
					->get();
				
		} else {
			$transactions = null;
		}
		
		$bank_name = DB::table('bank')
                      ->whereNotIn('bid', array( 2 ))
                      ->lists('bank_name','bid');	
		
		$listServiceTypes = DB::table("sys_service_type")->where("service_type_id","!=","stock_initiate")
								->where("service_type_id","!=","stock_approval")
								->orderBy('service_type_name','asc')
								->lists("service_type_id","service_type_id");
								
		$currencies = DB::table('c_currency')
                    ->where('status','=','active')
                    ->lists('currency_id','currency_id');
                    
        $service_type_map_groups = DB::table('sys_service_type_group AS sg')
        			->join('sys_service_type_map_group AS map','map.service_type_group__id','=','sg.group_service_type_id')
                    ->where('sg.status','=','active')
					->orderBy('map.service_type_group__id','asc')
                    ->get();    
					
	
		$arrayListGroupServiceType = array();
		$arrayStoreIds = array();
		foreach ($service_type_map_groups as $service_type_map_group) {
			$serviceTypeId = $service_type_map_group->service_type__id;
			$groupServiceTypeId = $service_type_map_group->group_service_type_id;
			$groupServiceTypeName = $service_type_map_group->group_service_type_name;
			if (array_key_exists($groupServiceTypeId,$arrayListGroupServiceType)) {
				$arrayListGroupServiceType[$groupServiceTypeId] .= ','.$serviceTypeId;
			} else {
				$str = $groupServiceTypeName.' => '.$serviceTypeId;
				$arrayListGroupServiceType[$groupServiceTypeId] = $str;
			}
		}
		//--------------------------------------------------------------------------
		DB::connection()->enableQueryLog();
		
		////////////Check to export_excel////////////////////////////////////////////////////////////////
		if(isset($_POST['export_excel'])){
			
			$this->getExcelfile($txnListAll, $servicetypesSumTxns, $banksSumTxns);
			
		}else{
			
			$listProvinces = DB::table('province')->lists('name_en','code');					
			$this->layout->content = View::make('report.bank-report',compact('listProvinces','servicetypesSumTxns','banksSumTxns','transactions', 'listServiceTypes', 'currencies', 'txnRequestValue', 'txnTransferValue', 'txnChannelCommValue', 'txnOperatorCommValue', 'txnRecord', 'arrayListGroupServiceType','bank_name'));
		}
	}

	// Master and Subdealer report
	public function masterSubdealerReport() {
		DB::connection()->disableQueryLog();
		//-------------------------------------------------
		$inputs = Input::all();
		$banksSumTxns = null;
		$servicetypesSumTxns = null;
		$banks = null;
		if (Input::has('tcy_currency_id')) {
			$db = DB::table('txn_transaction_header as t')
				->join('dealer as d','d.id','=','t.dealer__id')
				->join('sys_status as ss','ss.status_id','=','t.status')
				->leftJoin('staff AS sr','sr.id','=','t.staff__id')
				->where('service_type__id','!=','stock_initiate');

			$db->whereIn('t.service_type__id',array('master_deposit_game','master_withdraw_game','payout'));
			$db->whereIn('d.dealer_type__id',array(1,2,4));
			
			if (Input::has('group_service_type_id')) {

				$serviceGroupId = $inputs['group_service_type_id'];

				$service_type_map_groups = DB::table('sys_service_type_group AS sg')
					->join('sys_service_type_map_group AS map','map.service_type_group__id','=','sg.group_service_type_id')
					->where('sg.status','=','active')
					->where('sg.group_service_type_id',$serviceGroupId)
					->orderBy('map.service_type_group__id','asc')
					->get();

				$arrayListGroupServiceType = array();
				$arrayStoreIds = array();
				foreach ($service_type_map_groups as $service_type_map_group) {
					$serviceTypeId = $service_type_map_group->service_type__id;
					array_push($arrayStoreIds,$serviceTypeId);
				}

				$db->whereIn('t.service_type__id',$arrayStoreIds);
			}

			if (Input::has('start_date') && Input::has('end_date')) {
				$db->whereBetween('t.datetime', array($inputs['start_date'], $inputs['end_date']));
			}

			if (Input::has('did')) {
				$db->where('d.id',$inputs['did']);
			}
			if (Input::has('sub_dealer__id')) {
				$db->where('t.atr3_value',$inputs['sub_dealer__id']);
			}

			if (Input::has('name')) {
				$db->where('d.name','LIKE','%'.$inputs['name'].'%');
			}
			if (Input::has('service_type__id')) {
				$db->where('t.service_type__id',$inputs['service_type__id']);
			}
			if (Input::has('tcy_currency_id')) {
				$db->where('t.tcy_currency_id',$inputs['tcy_currency_id']);
			}
			if (Input::has('status')) {
				$status = $inputs['status'];
				if ($status == 'TA') {
					$db->where(function($query) {
						$query->orwhere('t.status','A1')
							->orwhere('t.status','A2')
							->orwhere('t.status','A3');
					});
				} else {
					$db->where('t.status',$inputs['status']);
				}
			}

			if (Input::has('transaction_id')) {
				$db->where('t.transaction_id',$inputs['transaction_id']);
			}
			if (Input::has('tsn_os')) {

				$db->where(function($query){
					$query->where('t.tsn_os',Input::get('tsn_os'))
					  ->orwhere('t.tsn',Input::get('tsn_os'))
					  ->orwhere('t.atr1_value',Input::get('tsn_os'));
				});
			}

			if (Input::has('staff_name')) {
				$db->where('sr.name','LIKE','%'.$inputs['staff_name'].'%');
			}

			if (Input::has('request_gateway__id')) {
				$db->where('t.request_gateway__id',$inputs['request_gateway__id']);
			}

			$db1 = clone $db;
			$db2 = clone $db;

			$db->select('t.*','d.name','sr.name as createdBy','ss.name as statusName')
				->orderBy('t.transaction_id','desc');

			$txnListAll = $db->get();
			$txnRequestValue = 0;
			$txnTransferValue = 0;
			$txnChannelCommValue = 0;
			$txnOperatorCommValue = 0;
			$txnRecord = 0;
			foreach ($txnListAll as $key => $value) {
				$txnRequestValue += $value->requested_value;
				$txnTransferValue += $value->transfer_value;
				$txnChannelCommValue += $value->channel_comm;
				$txnOperatorCommValue += $value->operator_comm;
				$txnRecord++;
			}

			$transactions = $db->paginate(15)->appends($inputs);

			if (Input::has('service_type__id')) {
				$serviceType = $inputs['service_type__id'];

				//----------sum by bank---------------------
				if ($serviceType == 'deposit' || $serviceType == 'deposit_game'
					|| $serviceType = 'withdraw' || $serviceType == 'withdraw_game'
						|| $serviceType == 'refund' || $serviceType == 'refund_game') {

					$queryBanks = "(select t.transaction_id, b.bank_name from txn_transaction_header as t inner join bank as b on b.bid = CAST(coalesce(t.atr1_value) as integer) where t.service_type__id = '".$serviceType."') as btxn";
					$banksSumTxns = $db1->select(DB::raw('t.atr1_value,sum(t.requested_value) as sumRequestValue, btxn.bank_name'))
						->join(DB::raw($queryBanks), function($join)
						{
							$join->on('btxn.transaction_id','=','t.transaction_id');
						})
						->groupBy('t.atr1_value')
						->groupBy('btxn.bank_name')
						->get();
				}
			}

			//----------sum by service type-------------
			$servicetypesSumTxns = $db2->select(DB::raw('t.service_type__id,sum(t.requested_value) as sumRequestValue, sum(t.transfer_value) as sumTransferValue,sum(t.channel_comm) as sumChannelComm,sum(t.customer_comm) as sumCustomerComm ,sum(t.operator_comm) as sumOperatorComm ,sum(ti.prev_balance) as sumPrevBalance,sum(ti.post_balance) as sumPostBalance,sum(ti.prev_balance_credit) as sumPrevBalanceCredit,sum(ti.post_balance_credit) as sumPostBalanceCredit'))
				->join('txn_transaction_items as ti','ti.transaction__id','=','t.transaction_id')
				//->where('t.dealer__id','=','ti.dealer__id')
				->where(DB::raw('"t"."dealer__id"'),DB::raw('"ti"."dealer__id"'))
				->groupBy('t.service_type__id')
				->get();

		} else {
			$transactions = null;
		}

		$listServiceTypes = DB::table("sys_service_type")->where("service_type_id","!=","stock_initiate")
			->where("service_type_id","!=","stock_approval")
			->orderBy('service_type_name','asc')
			->lists("service_type_id","service_type_id");

		$currencies = DB::table('c_currency')
			->where('status','=','active')
			->lists('currency_id','currency_id');

		$service_type_map_groups = DB::table('sys_service_type_group AS sg')
			->join('sys_service_type_map_group AS map','map.service_type_group__id','=','sg.group_service_type_id')
			->where('sg.status','=','active')
			->orderBy('map.service_type_group__id','asc')
			->get();


		$arrayListGroupServiceType = array();
		$arrayStoreIds = array();
		foreach ($service_type_map_groups as $service_type_map_group) {
			$serviceTypeId = $service_type_map_group->service_type__id;
			$groupServiceTypeId = $service_type_map_group->group_service_type_id;
			$groupServiceTypeName = $service_type_map_group->group_service_type_name;
			if (array_key_exists($groupServiceTypeId,$arrayListGroupServiceType)) {
				$arrayListGroupServiceType[$groupServiceTypeId] .= ','.$serviceTypeId;
			} else {
				$str = $groupServiceTypeName.' => '.$serviceTypeId;
				$arrayListGroupServiceType[$groupServiceTypeId] = $str;
			}
		}
		//--------------------------------------------------------------------------
		DB::connection()->enableQueryLog();

		////////////Check to export_excel////////////////////////////////////////////////////////////////
		if(isset($_POST['export_excel'])){

			$this->getExcelfile($txnListAll, $servicetypesSumTxns, $banksSumTxns);

		}else{

			$this->layout->content = View::make('report.transaction-master-subdealer-report',compact('servicetypesSumTxns','banksSumTxns','transactions', 'listServiceTypes', 'currencies', 'txnRequestValue', 'txnTransferValue', 'txnChannelCommValue', 'txnOperatorCommValue', 'txnRecord', 'arrayListGroupServiceType'));

		}

	}
	// end master and subdealer report

		//export excel//////
	public function getExcelfile($txnListAll, $servicetypesSumTxns, $banksSumTxns){
		$sumTxns = array();
		$banks = array();
		$txns = array();
		$numTxns = 0;
		$numBank = 0;
		$numSumTxns = 0;
		$nameFile = ucwords(str_replace('-', ' ',Request::segment(2))).'-'.date("Y-m-d H-i");

		$sumrequestvalue = 0;
		$sumtransfervalue = 0;
		$sumchannelcomm = 0;
		$sumprevbalance = 0;
		$sumpostbalance = 0;
		$totalSumBanks = 0;
		$currency = null;
		if(Request::segment(2) == 'bank-report') {
			foreach ($txnListAll as $item) {
				$currency = $item->tcy_currency_id;
				$numTxns = $numTxns + 1;
				$txn = array(
					'No' => $numTxns,
					'Txn ID' => $item->transaction_id,
					'DID' => $item->dealer__id,
					'Dealer Name' => $item->name,
					'Service Type' => $item->service_type__id,
					'Currency' => $item->tcy_currency_id,
					'Requested Amount' => number_format($item->requested_value, 2) . ' ' . $item->tcy_currency_id,
					'Channel Commission' => number_format($item->channel_comm, 2) . ' ' . $item->tcy_currency_id,
					'Transfered Amount' => number_format($item->transfer_value, 2) . ' ' . $item->tcy_currency_id,
					'Bank' => $item->bank_name,
					'Requested GateWay' => $item->request_gateway__id,
					'Status' => $item->statusName,
					'Created At' => $item->datetime,
					'Created By' => $item->createdBy
				);
				array_push($txns,$txn);
			}
		}else {
			foreach ($txnListAll as $item) {
				$currency = $item->tcy_currency_id;
				$numTxns = $numTxns + 1;
				$txn = array(
					'No' => $numTxns,
					'Txn ID' => $item->transaction_id,
					'TSN' => $item->tsn,
					'DID' => $item->dealer__id,
					'Dealer Name' => $item->name,
					'Service Type' => $item->service_type__id,
					'Currency' => $item->tcy_currency_id,
					'Requested Amount' => number_format($item->requested_value, 2) . ' ' . $item->tcy_currency_id,
					'Channel Commission' => number_format($item->channel_comm, 2) . ' ' . $item->tcy_currency_id,
					'Transfered Amount' => number_format($item->transfer_value, 2) . ' ' . $item->tcy_currency_id,
					'Requested GateWay' => $item->request_gateway__id,
					'Status' => $item->statusName,
					'Created At' => $item->datetime,
					'Created By' => $item->createdBy
				);
				array_push($txns,$txn);
			}
		}

		if($banksSumTxns != null){
			foreach ($banksSumTxns as $item) {
				$numBank = $numBank + 1;
				$sumBank = array(
					'No' => $numBank,
					'Group By Bank Name' => $item->bank_name,
					'Total Request Value' => number_format($item->sumrequestvalue, 2).' '.$currency,
				);

				$totalSumBanks = $totalSumBanks + $item->sumrequestvalue;
				array_push($banks,$sumBank);
			}
		}

		$totalSumBanks = number_format($totalSumBanks, 2) . ' ' . $currency;

		foreach ($servicetypesSumTxns as $item) {
			$numSumTxns = $numSumTxns + 1;
			$sumTxn = array(
				'No' => $numSumTxns,
				'Group By Service Type' => $item->service_type__id,
				'Total Request Value' => number_format($item->sumrequestvalue, 2).' '.$currency,
				'Total Channel Commission' => number_format($item->sumchannelcomm, 2).' '.$currency,
				'Total Transfer Value' => number_format($item->sumtransfervalue, 2).' '.$currency,
				'Total Prev Balance' =>number_format($item->sumprevbalance, 2).' '.$currency,
				'Total Post Balance' => number_format($item->sumpostbalance, 2).' '.$currency,
			);

			$sumrequestvalue = $sumrequestvalue + $item->sumrequestvalue;
			$sumchannelcomm = $sumchannelcomm + $item->sumchannelcomm;
			$sumtransfervalue = $sumtransfervalue + $item->sumtransfervalue;
			$sumprevbalance = $sumprevbalance + $item->sumprevbalance;
			$sumpostbalance = $sumpostbalance + $item->sumpostbalance;

			array_push($sumTxns,$sumTxn);
		}

		$allSumTxns = array('','Total:',number_format($sumrequestvalue, 2).' '.$currency, number_format($sumchannelcomm, 2).' '.$currency, number_format($sumtransfervalue, 2).' '.$currency, number_format($sumprevbalance, 2).' '.$currency, number_format($sumpostbalance, 2).' '.$currency);

		Excel::create($nameFile, function ($excel) use($txns, $sumTxns, $allSumTxns, $banks, $totalSumBanks) {

			$excel->sheet('Report', function ($sheet) use($txns, $sumTxns, $allSumTxns, $banks, $totalSumBanks){

				$sheet->mergeCells('A1:N1');
				//$sheet->setAllBorders('thin');
				$sheet->row(1, function ($row) {

					// call cell manipulation methods
					$row->setFontFamily('Ariel');
					$row->setFontSize(15);
					$row->setFontWeight('bold');


				});

				$sheet->row(1, array(ucwords(str_replace('-', ' ',Request::segment(2)))));

				// setting column names for data - you can of course set it manually
				if(!isset($txns[0])){
					return Redirect::to('/reports/bank-report');
				}

				$sheet->appendRow(array_keys($txns[0])); // column names

				// getting last row number (the one we already filled and setting it to bold
				$sheet->setBorder('A2:N2', 'thin');
				$sheet->row($sheet->getHighestRow(), function ($row) {
					$row->setFontWeight('bold');
				});

				$num = 0;
				foreach ($txns as $txn) {
					$num = $num +1;
					$border = $num+2;
					$sheet->appendRow($txn);
					$sheet->setBorder('A'.$border.':'.'N'.$border, 'thin');
				}

				$rowNum = $num+4;
				$sheet->row($rowNum, function ($row) {

					// call cell manipulation methods
					$row->setFontFamily('Ariel');
					$row->setFontSize(15);
					$row->setFontWeight('bold');

				});

				$sheet->mergeCells('A'.$rowNum.':N'.$rowNum);
				$sheet->row($rowNum, array('Sum by services'));

				$sheet->appendRow(array_keys($sumTxns[0]));
				$border = $rowNum+1;
				$sheet->setBorder('A'.$border.':'.'G'.$border, 'thin');
				$sheet->row($sheet->getHighestRow(), function ($row) {
					$row->setFontWeight('bold');
				});

				$num = $rowNum;
				foreach ($sumTxns as $sumTxn) {
					$num = $num +1;
					$border = $num + 1;
					$sheet->appendRow($sumTxn);
					$sheet->setBorder('A'.$border.':'.'G'.$border, 'thin');
				}

				$rowNum = $num+2;
				$sheet->setBorder('A'.$rowNum.':'.'G'.$rowNum, 'thin');
				$sheet->row($rowNum, function ($row) {
					// call cell manipulation methods
					$row->setFontFamily('Ariel');
					$row->setFontSize(12);
					$row->setFontWeight('bold');

				});

				$sheet->row($rowNum, $allSumTxns);

				// Sum group banks
				if($banks != null){
					$rowNum = $num+4;

					$sheet->row($rowNum, function ($row) {

						// call cell manipulation methods
						$row->setFontFamily('Ariel');
						$row->setFontSize(15);
						$row->setFontWeight('bold');

					});

					$sheet->mergeCells('A'.$rowNum.':N'.$rowNum);
					$sheet->row($rowNum, array('Sum by banks'));

					$sheet->appendRow(array_keys($banks[0]));
					$border = $rowNum+1;
					$sheet->setBorder('A'.$border.':'.'C'.$border, 'thin');

					$sheet->row($sheet->getHighestRow(), function ($row) {
						$row->setFontWeight('bold');
					});

					$num = $rowNum+1;
					foreach($banks as $bank){
						$num = $num + 1;
						$sheet->appendRow($bank);
						$sheet->setBorder('A'.$num.':'.'C'.$num, 'thin');
					}

					$sumAllBank = array(''=>'','Total:'=>'', $totalSumBanks => '');
					$sheet->appendRow(array_keys($sumAllBank));
					$num = $num+1;
					$sheet->setBorder('A'.$num.':'.'C'.$num, 'thin');
					$sheet->row($sheet->getHighestRow(), function ($row) {
						$row->setFontWeight('bold');
					});
				}
			});

		})->export('xls');


	}

}