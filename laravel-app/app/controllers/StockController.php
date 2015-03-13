<?php

class StockController extends \BaseController {
	/**
	 * The layout that should be used for responses.
	 */
	protected $layout = 'layouts.master';
	
	/**
	 * Display a listing of the resource.
	 * GET /stock
	 *
	 * @return Response
	 */
	public function index()
	{
		$inputs = Input::all();
		
		$db = DB::table('dealer as d')
						->join('dealer_type as dt','d.dealer_type__id','=','dt.id')
						->join('w_wallet as w','w.dealer__id','=','d.id')
						->orderBy('w.dealer__id','asc')
						->orderBy('w.sequence_number','asc')
						->where('w.status','active')
						->where('d.dealer_type__id','9')
						->select('d.*','w.*','dt.id as dt_id',DB::raw('(select count(w1.dealer__id) as count_id from dealer d1 inner join w_wallet w1 on d1.id = w1.dealer__id where d1.id = d.id) as num'));
						
						
		if (Input::has('start_date') && Input::has('end_date')) {
			$db->whereBetween('w.last_transaction_on', array($inputs['start_date'], $inputs['end_date']));
		}
		
		if (Input::has('did')) {
			$db->where('d.id',$inputs['did']);
		}
		if (Input::has('name')) {
			$db->where('d.name','LIKE','%'.$inputs['name'].'%');
		}
		if (Input::has('transaction_id')) {
			$db->where('w.last_transaction__id',$inputs['transaction_id']);
		}
		
		$stocks = $db->paginate(75)->appends($inputs);
		
		$this->layout->content = View::make('stock.list_stock',compact('stocks'));
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /stock/create
	 *
	 * @return Response
	 */
	public function stockInitiate()
	{
		$stocks = Dealer::getDealerStock();
		$currencies  = Currency::getListCurrencyActive();
		$this->layout->content = View::make('stock.stock_initiate',compact('currencies','stocks'));
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /stock
	 *
	 * @return Response
	 */
	public function storeStockInitiate()
	{
		$input = Input::all();
		$rules = array(
		    'dealer__id' => 'required',
		    'service_type__id' => 'required',
		    'tcy_currency_id' => 'required',
		    'requested_value' => 'required|integer',
		    'remark' => 'required|max:255'
		);
		$input['service_type__id'] = 'stock_initiate';
		
        $validation = Validator::make($input, $rules);

		$msgs = array();
        if ($validation->passes())
        {
        	$currencyId = $input['tcy_currency_id'];
        	$currency = DB::table('c_currency')
                    ->where('status','=','active')
					->where('currency_id','=',$currencyId)
                    ->first();
				
			if($input['requested_value'] % $currency->multiple_of != 0)
			{
				$msg['msg'] = 'Amount is not multiple of currency!';
				$msg['type'] = 'error';
				$msgs = array($msg);
				
				return Redirect::back()
			            ->withInput()
			            ->withErrors($validation)
			            ->with('msgs', $msgs);
			}
			
        	$staffId = Auth::user()->id;
			$input['staff__id'] = $staffId;
			$input['status'] = 'TI';
			$input['error_code'] = 'S00007';
			$input['request_gateway__id'] = 'web';
			$input['transfer_value'] = '0';
			$input['atr1_name'] = 'num_approve';
			$input['atr1_value'] = '0';
        	// Start transaction
			DB::beginTransaction();
			
			
			
			$ob = TxnTransactionHeader::create($input);
			$Log = array(
				'staff__id'=>$staffId,
				'action'=>'initiate',
				'object_type'=>'stock',
				'object__id'=>$ob->transaction_id,
				'new_data'=>json_encode($input)
			);
			$obLog = Logs::create($Log);
			$msg = array();
			if( !$ob || !$obLog)
			{
			    DB::rollback();
				$msg['msg'] = 'Stock initiate failed!';
				$msg['type'] = 'error';
				$msgs = array($msg);
			} else {
			    DB::commit();
				$msg['msg'] = 'Stock initiate successfully!';
				$msg['type'] = 'success';
				$msgs = array($msg);
				return Redirect::back()->with('msgs', $msgs);
			}
			
			
        }
		
		return Redirect::back()
            ->withInput()
            ->withErrors($validation)
            ->with('msgs', $msgs);
	}
	
	
	/**
	 * Display a listing of the resource.
	 * GET /stock
	 *
	 * @return Response
	 */
	public function stockInitiateView()
	{
		$inputs = Input::all();
		
		$db = DB::table('txn_transaction_header as t')
						->select('t.*','d.name','sr.name as requestBy','ss.name as statusName')
						->join('dealer as d','d.id','=','t.dealer__id')
						->join('sys_status as ss','ss.status_id','=','t.status')
						->leftJoin('staff AS sr','sr.id','=','t.staff__id')
						->orderBy('t.datetime','desc')
						//->orderBy('ss.sequence_num','asc')
						->where('service_type__id','stock_initiate');
						
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
		
		if (Input::has('start_date') && Input::has('end_date')) {
			$db->whereBetween('t.datetime', array($inputs['start_date'], $inputs['end_date']));
		}				
						
						
						
		$stockInitiates = $db->paginate(15);
		
		$staffId = Auth::user()->id;
		
		$collectionApprovalRange = RangeApprovalStaffPrivilege::getCollectionApprovalRange($staffId);
		$this->layout->content = View::make('stock.list_stock_initiate',compact('stockInitiates','collectionApprovalRange'));
	}
	
	/**
	 * Display the specified resource.
	 * GET /stock/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function stockInitiateDetail($id)
	{
		$staffId = Auth::user()->id;
		
		$stockInitiate = DB::table('txn_transaction_header as t')
						->select('t.*','d.name','sr.name as requestBy','ss.name as statusName')
						->join('dealer as d','d.id','=','t.dealer__id')
						->join('sys_status as ss','ss.status_id','=','t.status')
						->leftJoin('staff AS sr','sr.id','=','t.staff__id')
						->where('t.transaction_id',$id)
						->first();
		
		if ($stockInitiate == null) {
			$msgs = array();
			$msg['msg'] = 'Stock Initiated Not Found!';
			$msg['type'] = 'error';
			$msgs = array($msg);
			return Redirect::to('stocks/stock-initiate-view')->with('msgs', $msgs);
		}
		
		$approvalLists = DB::table('txn_transaction_approval as ta')
			->select('ta.*','ta.created_at as actionAt','sr.name as actionBy','ss.name as statusName')
			->join('sys_status as ss','ss.status_id','=','ta.approval_status')
			->leftJoin('staff AS sr','sr.id','=','ta.staff__id')
			->where('ta.transaction_id',$id)
			->orderBy('ta.created_at','desc')
			->paginate(100);
						
		$this->layout->content = View::make('stock.stock_initiate_detail',compact('stockInitiate','approvalLists'));
	}
	
	/**
	 * Display the specified resource.
	 * GET /stock/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function stockApproval($id)
	{
		$staffId = Auth::user()->id;
		
		$stockInitiate = DB::table('txn_transaction_header as t')
						->select('t.*','d.name','sr.name as requestBy','ss.name as statusName')
						->join('dealer as d','d.id','=','t.dealer__id')
						->join('sys_status as ss','ss.status_id','=','t.status')
						->leftJoin('staff AS sr','sr.id','=','t.staff__id')
						->where(function($query) {
                                $query->where('t.status','TI')
								->orwhere('t.status','A1')
								->orwhere('t.status','A2')
								->orwhere('t.status','A3');
               			 })
						->where('t.staff__id','!=',$staffId)
						->where('t.transaction_id',$id)
						->first();
		
		if ($stockInitiate == null) {
			$msgs = array();
			$msg['msg'] = 'Stock Initiated Not Found!';
			$msg['type'] = 'error';
			$msgs = array($msg);
			return Redirect::to('stocks/stock-initiate-view')->with('msgs', $msgs);
		} else {
			if ($stockInitiate->atr2_value  != $staffId && $stockInitiate->atr3_value  != $staffId && $stockInitiate->atr4_value  != $staffId) {
				
			} else {
				$msgs = array();
				$msg['msg'] = 'You can not approval again!';
				$msg['type'] = 'error';
				$msgs = array($msg);
				
				return Redirect::to('stocks/stock-initiate-view')->with('msgs', $msgs);
			}
		}
						
						
		$approvalRangeStaffPrivileges = RangeApprovalStaffPrivilege::getApprovalRangeByCurrencyAndStaff($stockInitiate->tcy_currency_id,$staffId);
		$privilaged= false;
		
		if ($stockInitiate->staff__id != $staffId) {
			foreach($approvalRangeStaffPrivileges as $approvalRange) {
				if ($approvalRange->start_range<= $stockInitiate->requested_value && $stockInitiate->requested_value<=$approvalRange->end_range) {
					
					if ($stockInitiate->status == 'TI' || $stockInitiate->status == 'A1' || $stockInitiate->status == 'A2' || $stockInitiate->status == 'A3') {
						$privilaged = true;
						$this->layout->content = View::make('stock.stock_approval',compact('stockInitiate'));
						break;
					} else {
						$msgs = array();
						$msg['msg'] = 'At the time you are viewing, another one are also doing!';
						$msg['type'] = 'error';
						$msgs = array($msg);
						
						return Redirect::to('stocks/stock-initiate-view')->with('msgs', $msgs);
					}				
				}
			}
		}
		
		
		if (!$privilaged) {
			$msgs = array();
			$msg['msg'] = 'Do don try to do anything wrong in systerm, you have been noted!';
			$msg['type'] = 'error';
			$msgs = array($msg);
			
			return Redirect::to('stocks/stock-initiate-view')->with('msgs', $msgs);
		}
	}
	
	/**
	 * Display the specified resource.
	 * POST /stock
	 *
	 * @return Response
	 */
	public function storeStockApproval()
	{
		$input = Input::all();
		$rules = array(
			'transaction_id' => 'required',
		    'remark' => 'required|max:255'
		);
		
        $validation = Validator::make($input, $rules);

		$msgs = array();
        if ($validation->passes())
        {
        	$staffId = Auth::user()->id;
			$txnId = $input['transaction_id'];
			$dateTime = date("Y-m-d H:i:s");
			
			$stockInitiate = DB::table('txn_transaction_header as t')
						->where(function($query) {
                                $query->where('t.status','TI')
								  ->orwhere('t.status','A1')
								  ->orwhere('t.status','A2')
								  ->orwhere('t.status','A3');
                		})
						->where('t.staff__id','!=',$staffId)
						->where('t.transaction_id',$txnId);
						
			$stockInitiateOb = $stockInitiate->first();
			if ($stockInitiateOb == null) {
				$msgs = array();
				$msg['msg'] = 'Stock Initiated Not Found!';
				$msg['type'] = 'error';
				$msgs = array($msg);
				
				return Redirect::to('stocks/stock-initiate-view')->with('msgs', $msgs);
			}
			
			
			$input['updated_by_staff__id'] = $staffId;
			
			DB::beginTransaction();
			
			if(Input::get('approve')) {
				$transferOrJustApproveSetp = 'justApproveStep';
				$numReadyApproval = (int)$stockInitiateOb->atr1_value;
				$privilaged = false;
				
				$approvalRangeStaffPrivileges = RangeApprovalStaffPrivilege::getApprovalRangeByCurrencyAndStaff($stockInitiateOb->tcy_currency_id,$staffId);
				
				if ($stockInitiateOb->staff__id != $staffId) {
					foreach($approvalRangeStaffPrivileges as $approvalRange) {
						if ($approvalRange->start_range<= $stockInitiateOb->requested_value && $stockInitiateOb->requested_value<=$approvalRange->end_range) {
							
							if ($stockInitiateOb->status == 'TI' || $stockInitiateOb->status == 'A1' || $stockInitiateOb->status == 'A2' || $stockInitiateOb->status == 'A3') {
								$privilaged = true;
								$numReadyApproval++;
								if ($approvalRange->num_approval == $numReadyApproval) {
									$transferOrJustApproveSetp = 'transfer';
								}
								break;
							} else {
								$msgs = array();
								$msg['msg'] = 'At the time you are viewing, another one are also doing!';
								$msg['type'] = 'error';
								$msgs = array($msg);
								return Redirect::to('stocks/stock-initiate-view')->with('msgs', $msgs);
								break;
							}				
						}
					}
				}
				
				if ($privilaged) {
					$statusName = "";
					$walletOb = true;
					$itemOb = true;
					if ($transferOrJustApproveSetp == 'transfer') {
						
						//---------------------Wallet Payee------------------
						$dealerId = $stockInitiateOb->dealer__id;
						$walletTypeId = 'os';
						$walletCurrencyId = $stockInitiateOb->tcy_currency_id;
						
						$walletPayee = WWallet::getWalletByCondition($dealerId, $walletTypeId, $walletCurrencyId);
						if($walletPayee == null) {
							$msg = array('type'=>'error','msg'=>'Stock Wallet Not Found!');
							array_push($msgs,$msg);
							return Redirect::back()
						            ->withInput()
						            ->withErrors($validation)
						            ->with('msgs', $msgs);
						}
						
						$transferValue = $stockInitiateOb->requested_value;
						
						$walletPayee->last_transaction__id = $stockInitiateOb->transaction_id;
						$walletPayee->last_transaction_service_type__id = $stockInitiateOb->service_type__id;
						$walletPayee->last_transaction_on = $dateTime;
						$walletPayee->last_balance_credit = $transferValue;
						$walletPayee->last_balance_debit = 0;
						$walletPayee->prev_balance = $walletPayee->post_balance;
						$walletPayee->post_balance = $walletPayee->post_balance + $transferValue;
						$walletOb = $walletPayee->update();
						
						//-----------------Txn Headler-------------------------
						$status = 'TS';
						$update['transfer_value'] = $transferValue;
						
						//-----------------Txn Item payee----------------------
						$txnTransactionItem = array(
							'transaction__id'=>$stockInitiateOb->transaction_id,
							'dealer__id'=>$dealerId,
							'wallet_id'=>$walletPayee->wallet_id,
							'requested_value'=>$transferValue,
							'transfer_value'=>$transferValue,
							'prev_balance'=>$walletPayee->prev_balance,
							'post_balance'=>$walletPayee->post_balance,
							'user_direction'=>'payee'
						);
						$itemOb = TxnTransactionItem::create($txnTransactionItem);
					
					} else {
						if ($numReadyApproval == 1) {
							$status = 'A1';
							$statusName = "Approval 1";  
							$update['atr2_name'] = 'approved_by_1';
							$update['atr2_value'] = $staffId;
						} else if ($numReadyApproval == 2) {
							$status = 'A2';
							$statusName = "Approval 2"; 
							$update['atr3_name'] = 'approved_by_2';
							$update['atr3_value'] = $staffId;
						} else if ($numReadyApproval == 3) {
							$status = 'A3';
							$statusName = "Approval 3"; 
							$update['atr4name'] = 'aapproved_by_3';
							$update['atr4_value'] = $staffId;
						}
					}
					
					$stockInitiate = DB::table('txn_transaction_header as t')
						->where('t.transaction_id',$txnId);
						
						
					$update['status'] = $status;
					$update['atr1_value'] = $numReadyApproval;
					$Log = array(
						'staff__id'=>$staffId,
						'action'=>'approve',
						'object_type'=>'stock',
						'object__id'=>$stockInitiateOb->transaction_id,
						'old_data'=>json_encode($stockInitiateOb),
						'new_data'=>json_encode($update)
					);
					$obLog = Logs::create($Log);
					$ob = $stockInitiate->update($update);
					
					$approval = array(
						'transaction_id'=>$stockInitiateOb->transaction_id,
						'action'=>'approve',
						'approval_status'=>$status,
						'action_gateway'=>'web',
						'remark'=>$input['remark'],
						'created_at'=>$dateTime,
						'staff__id'=>$staffId
					);
					
					$approvalOb = TxnTransactionApproval::create($approval);
					$approvalLog = array(
						'staff__id'=>$staffId,
						'action'=>'approve',
						'object_type'=>'stock_approval',
						'object__id'=>$approvalOb->transaction_approval_id,
						'new_data'=>json_encode($approval)
					);
					$approvalObLog = Logs::create($approvalLog);
					
					$msg = array();
					if( !$ob || !$obLog || !$approvalOb || !$approvalObLog || !$walletOb || !$itemOb)
					{
					    DB::rollback();
						$msg['msg'] = 'Stock approve failed!';
						$msg['type'] = 'error';
						$msgs = array($msg);
					} else {
					    DB::commit();
						
						if ($transferOrJustApproveSetp == 'transfer') {
							$msg['msg'] = 'Stock initiate was updated to be approved successfully';
							$msg['type'] = 'success';
							$msgs = array($msg);
						} else {
							$msg['msg'] = 'Stock approve successfully with status = '.$statusName;
							$msg['type'] = 'success';
							$msgs = array($msg);
						}
					}
				}
				
	        } else if(Input::get('reject')) {
	        	$stockInitiate = DB::table('txn_transaction_header as t')
						->where('t.transaction_id',$txnId);
						
	        	$update['status'] = 'TR';
	        	
				$Log = array(
					'staff__id'=>$staffId,
					'action'=>'reject',
					'object_type'=>'stock',
					'object__id'=>$stockInitiateOb->transaction_id,
					'old_data'=>json_encode($stockInitiateOb),
					'new_data'=>json_encode($update)
				);
				$obLog = Logs::create($Log);
				$ob = $stockInitiate->update($update);
				
				$approval = array(
					'transaction_id'=>$stockInitiateOb->transaction_id,
					'action'=>'reject',
					'approval_status'=>'TR',
					'remark'=>$input['remark'],
					'created_at'=>$dateTime,
					'staff__id'=>$staffId
				);
				$approvalOb = TxnTransactionApproval::create($approval);
				
				$msg = array();
				if( !$ob || !$obLog || !$approvalOb)
				{
				    DB::rollback();
					$msg['msg'] = 'Stock reject failed!';
					$msg['type'] = 'error';
					$msgs = array($msg);
				} else {
				    DB::commit();
					$msg['msg'] = 'Stock reject successfully!';
					$msg['type'] = 'success';
					$msgs = array($msg);
				}
	        }
			return Redirect::to('stocks/stock-initiate-view')->with('msgs', $msgs);
        }
		
		return Redirect::to('stocks/stock-initiate-view')->with('msgs', $msgs);
	}
	
	
	/**
	 * Show the form for creating a new resource.
	 * GET /stock/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /stock
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /stock/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /stock/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /stock/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /stock/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}