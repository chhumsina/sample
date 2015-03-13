<?php

class TransactionController extends \BaseController {
	
	/**
	 * The layout that should be used for responses.
	 */
	protected $layout = 'layouts.master';
	
	/**
	 * Display a listing of the resource.
	 * GET /transaction
	 *
	 * @return Response
	 */
	public function index()
	{	
		$inputs = Input::all();
		
		$db = DB::table('txn_transaction_header as t')
						->select('t.*','d.name','sr.name as createdBy','ss.name as statusName')
						->join('dealer as d','d.id','=','t.dealer__id')
						->join('sys_status as ss','ss.status_id','=','t.status')
						->leftJoin('staff AS sr','sr.id','=','t.staff__id')
						//->leftJoin('txn_transaction_items as ti','ti.transaction__id','=','t.transaction_id')
						//->orderBy('t.transaction_id','desc')
						->orderBy('t.datetime','desc')
						//->orderBy('ss.sequence_num','asc')
						->where('service_type__id','!=','stock_initiate');
		
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
			//$db->where('t.tsn_os',$inputs['tsn_os']);
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
					
		$n = 10;
		if (Input::has('n_record')) {
			$n = Input::get('n_record');
		}			
		$transactions = $db->paginate($n)->appends($inputs);
		$listServiceTypes = DB::table("sys_service_type")->where("service_type_id","!=","stock_initiate")
								->where("service_type_id","!=","stock_approval")
								->orderBy('service_type_name','asc')
								->lists("service_type_id","service_type_id");
								
		$this->layout->content = View::make('transaction.list_transaction',compact('transactions','listServiceTypes'));
	}

	
	
	/**
	 * Show the form for creating a new resource.
	 * GET /transaction/cancel-game
	 *
	 * @return Response
	 */
	public function cancelGame()
	{
		$this->layout->content = View::make('transaction.cancel_game');
	}
	
	/**
	 * Store a newly created resource in storage.
	 * POST /transaction
	 *
	 * @return Response
	 */
	public function storeCancelGame()
	{
		$input = Input::all();
		$rules['file_name'] = 'required';
        $validation = Validator::make($input,$rules);

		$msgs = array();
        if ($validation->passes())
        {
        	$staffId = Auth::user()->id;
			
        	if (Input::hasFile('file_name')) {
		        $file            = Input::file('file_name');
		        $destinationPath = public_path().'/images/upload_cancel_game/';
				$millisecond = round(microtime(true)*1000);
		        $filename        = $millisecond.'_'.str_random(2) . '_' . $file->getClientOriginalName();
				$extension = $file->getClientOriginalExtension();
				
				if ($extension != 'xlsx'  && $extension != 'xls') {
					$msg = array('type'=>'error','msg'=>'File Extension = '.$extension.' Was Not Allowed');
					array_push($msgs,$msg);
					return Redirect::back()
				            ->withInput()
				            ->withErrors($validation)
				            ->with('msgs', $msgs);
				}
				
				
		        $uploadSuccess   = $file->move($destinationPath, $filename);
								
				if ($uploadSuccess) {
					$dateTime = date("Y-m-d H:i:s");
					
					$input['staff__id']=Auth::user()->id;
					$input['file_name']=$filename;
					$input['created_at'] = $dateTime;
					/*
					 * Write Excel File
					 * Excel::fromArray( array(
					    array('Hello', 'World', '!!!'),
					    array('X', 'Y', 'Z')
					) )->save( base_path() . '/sample.xlsx' );
					*/
					$realFilePath = $destinationPath.$filename;
					//$excelList = Excel::excel2Array($realFilePath);
					$excelList = Excel::load($realFilePath, function($reader) {})->toArray();
					DB::beginTransaction();
					try {
						
						$ob = TxnUploadCancelGame::create($input);
						$obLog = array(
							'staff__id'=>$staffId,
							'action'=>'upload',
							'object_type'=>'upload_cancel_game',
							'object__id'=>$ob->upload_cancel_game_id,
							'new_data'=>json_encode($input)
						);
						$obLog = Logs::create($obLog);
						
						
						foreach ($excelList as $key => $excelSheed) {
							//echo $excel['']
							//print_r($excelSheed);
							//die();
							foreach ($excelSheed as $key => $sheed) {
								/*if ($key == 0 ) {
									continue;
								}*/
								
								$sheed = array_values($sheed);								
								//$tsnGame = $sheed[0][0];
								$tsnGame = $sheed[0];
								$typeOfIssue = $sheed[1];
								$input['atr3_name'] =  'type_issue';
								$input['atr3_value'] =  $typeOfIssue;
								
								if ($tsnGame == '' || $tsnGame == null) {
									$msg = array();
										$msg['msg'] = 'Transaction TSN can not be blank, TSN = '.$tsnGame;
										$msg['type'] = 'error';
										array_push($msgs,$msg);
										
										return Redirect::back()
							            ->withInput()
							            ->with('msgs',$msgs);
										break;
								}
								
								$transactionOb = DB::table('txn_transaction_header as t')
												->where('tsn',$tsnGame);
								$transaction = $transactionOb->first();
								if ($transaction != null) {
									if ($transaction->status == 'TC') {
										DB::rollback();
										$msgs = array();
										$msg = array();
										$msg['msg'] = 'Transaction Was Canceled, TSN = '.$transaction->tsn;
										$msg['type'] = 'error';
										array_push($msgs,$msg);
										
										return Redirect::back()
							            ->withInput()
							            ->with('msgs',$msgs);
										break;
									}

									$tsnRef = $transaction->tsn;
									$response = $this->operationRolBackOfTransactionCancel($transactionOb,$transaction,$tsnRef,$input,$dateTime,'game','yes',$ob->upload_cancel_game_id);
									if ($response['status'] == 'F') {
										DB::rollback();
										return Redirect::back()
							            ->withInput()
							            ->with('msgs', $response['msgs']);
										break;
									} else {
										$arrayResponse = $response['msgs'];
										
										foreach ($arrayResponse as $key => $value) {
											$msg['msg'] = $value['msg'];
											$msg['type'] = $value['type'];
											array_push($msgs,$msg);
										}
									}
								} else {
									DB::rollback();
									
									$msg1 = array();
									$msg1['msg'] = 'TSN = '.$tsnGame.' Not Found In System!';
									$msg1['type'] = 'error';
									array_push($msgs,$msg1);
									return Redirect::back()
							            ->withInput()
							            ->withErrors($validation)
							            ->with('msgs', $msgs);
								}
							}
							break;
						}
						
						
						
					    DB::commit();
						
						$msg['msg'] = 'Upload and cancel game successfully!';
						$msg['type'] = 'success';
						array_push($msgs,$msg);
						
					} catch (\Exception $e) {
					    DB::rollback();
						$msg['msg'] = 'Upload and cancel game was failed!'.$e;
						$msg['type'] = 'error';
						$msgs = array($msg);
					}
				} else {
					return $uploadSuccess;
				}
		    } 
        }
		return Redirect::back()
				            ->withInput()
				            ->withErrors($validation)
				            ->with('msgs', $msgs);
	}
	
	/**
	 * Show the form for creating a new resource.
	 * GET /transaction/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /transaction
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /transaction/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		
	}
	
	/**
	 * Display the specified resource.
	 * GET /transaction/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function detail($id) 
	{
		$transaction = DB::table('txn_transaction_header as t')
						->select('t.*','d.name','sr.name as createdBy','su.name as updatedBy','ss.name as statusName')
						->join('dealer as d','d.id','=','t.dealer__id')
						->join('sys_status as ss','ss.status_id','=','t.status')
						->leftJoin('staff AS sr','sr.id','=','t.staff__id')
						->leftJoin('staff AS su','su.id','=','t.updated_by_staff__id')
						->orderBy('t.datetime','desc')
						->where('service_type__id','!=','stock_initiate')
						->where('transaction_id',$id)
						->first();
						
		$transactionItems = DB::table('txn_transaction_items as ti')
						->select('ti.*','d.name as d_name','w.wallet_nickname')
						->join('dealer as d','d.id','=','ti.dealer__id')
						->join('w_wallet as w','w.wallet_id','=','ti.wallet_id')
						->orderBy('ti.user_direction','desc')
						->where('ti.transaction__id',$id)->paginate(15);
		
		$banks = DB::table('bank')
                      ->whereNotIn('bid', array( 2 ))
                      ->lists('bank_name','bid');				
						
		$this->layout->content = View::make('transaction.detail_transaction',compact('transaction','transactionItems','banks'));
	}

	/**
	 * Display the specified resource.
	 * GET /transaction/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id) 
	{
		$transaction = DB::table('txn_transaction_header as t')
						->select('t.*','d.name','sr.name as createdBy','su.name as updatedBy','ss.name as statusName')
						->join('dealer as d','d.id','=','t.dealer__id')
						->join('sys_status as ss','ss.status_id','=','t.status')
						->leftJoin('staff AS sr','sr.id','=','t.staff__id')
						->leftJoin('staff AS su','su.id','=','t.updated_by_staff__id')
						->orderBy('t.datetime','desc')
						->where('service_type__id','!=','stock_initiate')
						->where('transaction_id',$id)
						->first();
						
		$transactionItems = DB::table('txn_transaction_items as ti')
						->select('ti.*','d.name as d_name','w.wallet_nickname')
						->join('dealer as d','d.id','=','ti.dealer__id')
						->join('w_wallet as w','w.wallet_id','=','ti.wallet_id')
						->orderBy('ti.user_direction','desc')
						->where('ti.transaction__id',$id)->paginate(15);
		
		$banks = DB::table('bank')
                      ->whereNotIn('bid', array( 2, 7 ))
                      ->lists('bank_name','bid');				
						
		$this->layout->content = View::make('transaction.edit_transaction',compact('transaction','transactionItems','banks'));
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /transaction
	 *
	 * @return Response
	 */
	public function doActionFromDetail() {
		$input = Input::all();
		
		$rules = array(
			'transaction_id' => 'required',
		    'remark' => 'required|max:255'
		);
		$msgs = array();
        $validation = Validator::make($input, $rules);
		if (!$validation->passes()) 
		{
			return Redirect::back()
            ->withInput()
            ->withErrors($validation)
            ->with('msgs', $msgs);
		}
		$transactionId = Input::get("transaction_id");
		$transactionOb = DB::table('txn_transaction_header as t')
						->where('service_type__id','!=','stock_initiate')
						->where('transaction_id',$transactionId);
		$transaction = $transactionOb->first();
		$serviceTypeId = $transaction->service_type__id;
		
		if(Input::get('cancel')) {
			if ($transaction->service_type__id == 'weiluy') {
				if ($transaction->atr1_value == 'no') {
					DB::beginTransaction();
					$dateTime = date("Y-m-d H:i:s");
					$tsnRef = $transaction->tsn_os;
					$response = $this->operationRolBackOfTransactionCancel($transactionOb,$transaction,$tsnRef,$input,$dateTime,'os');
					
					$msg = array();
					if($response['status'] == "F")
					{
						return Redirect::back()
					            ->withInput()
					            ->with('msgs', $response['msgs']);
									
					} else {
						DB::commit();
						$msg['msg'] = 'Cancel Transaction Weiluy Successfully!';
						$msg['type'] = 'success';
						
						$msgs = $response['msgs'];
						array_push($msgs,$msg);
						return Redirect::back()
					            ->withInput()
					            ->with('msgs', $msgs);
					}
				} else if ($transaction->atr1_value == 'yes') {
					$msg = array('type'=>'error','msg'=>'While you are viewing, someone cashed out!');
					array_push($msgs,$msg);
				} else if ($transaction->atr1_value == 'cancel') {
					$msg = array('type'=>'error','msg'=>'While you are viewing, someone cancel this transaction!');
					array_push($msgs,$msg);
				}
			} else if ($transaction->service_type__id == 'deposit' || $transaction->service_type__id == 'deposit_game') {
				if ($transaction->status == 'TS') {
					DB::beginTransaction();
					$dateTime = date("Y-m-d H:i:s");
					$tsnRef = $transaction->tsn_os;
					$response = $this->operationRolBackOfTransactionCancel($transactionOb,$transaction,$tsnRef,$input,$dateTime,'os');
					
					$msg = array();
					if($response['status'] == "F")
					{
						return Redirect::back()
					            ->withInput()
					            ->with('msgs', $response['msgs']);
									
					} else {
						DB::commit();
						$msg['msg'] = 'Cancel Transaction Successfully!';
						$msg['type'] = 'success';
						
						$msgs = $response['msgs'];
						array_push($msgs,$msg);
						return Redirect::back()
					            ->withInput()
					            ->with('msgs', $msgs);
					}
				} else {
					$msg = array('type'=>'error','msg'=>'While you are viewing, someone cancel this transaction!');
					array_push($msgs,$msg);
				}
			} else {
				$msg = array('type'=>'error','msg'=>'Service type not allow to cancel!');
				array_push($msgs,$msg);
			}
		} else if (Input::get('approve')) {
			if ($serviceTypeId == 'withdraw' || $serviceTypeId == 'withdraw_game' || $serviceTypeId == 'withdraw_game_req'
				||$serviceTypeId == 'deposit' || $serviceTypeId == 'deposit_game') {
				$rules['atr1_value'] = 'required';
				$validation = Validator::make($input, $rules);
				if (!$validation->passes()) 
				{
					$msg = array('type'=>'error','msg'=>'Please select bank!');
					array_push($msgs,$msg);
					return Redirect::back()
		            ->withInput()
		            ->with('msgs', $msgs);
				}
			}
		
			if ($transaction->service_type__id == 'withdraw' || $transaction->service_type__id == 'withdraw_game' || $serviceTypeId == 'withdraw_game_req') {
				if ($transaction->status == 'TI') {
					$response = $this->approveTransactionWithdrawNewProcess($transactionOb,$transaction,$input);
					//$response = $this->approveTransactionWithdraw($transactionOb,$transaction,$input);
					$msg = array();
					if($response['status'] == "F")
					{
						$msg['msg'] = 'Approval '.$transaction->service_type__id.' fail!';
						$msg['type'] = 'error';
						
						$msgs = $response['msgs'];
						array_push($msgs,$msg);
						
						return Redirect::back()
					            ->withInput()
					            ->with('msgs', $msgs);
									
					} else {
						
						$msg['msg'] = 'Approval '.$transaction->service_type__id.' successfully!';
						$msg['type'] = 'success';
						
						$msgs = $response['msgs'];
						array_push($msgs,$msg);
						return Redirect::back()
					            ->withInput()
					            ->with('msgs', $msgs);
					}
					
				} else {
					$msg = array('type'=>'error','msg'=>'While you are viewing, someone doing something!');
					array_push($msgs,$msg);
				}
			} else if ($transaction->service_type__id == 'deposit' || $transaction->service_type__id == 'deposit_game') {
				if ($transaction->status == 'TI') {
					$response = $this->approveTransactionDeposit($transactionOb,$transaction,$input);
					$msg = array();
					if($response['status'] == "F")
					{
						$msg['msg'] = 'Approval '.$transaction->service_type__id.' fail!';
						$msg['type'] = 'error';
						
						$msgs = $response['msgs'];
						array_push($msgs,$msg);
						
						return Redirect::back()
					            ->withInput()
					            ->with('msgs', $msgs);
									
					} else {
						DB::commit();
						$msg['msg'] = 'Approval '.$transaction->service_type__id.' successfully!';
						$msg['type'] = 'success';
						
						$msgs = $response['msgs'];
						array_push($msgs,$msg);
						return Redirect::back()
					            ->withInput()
					            ->with('msgs', $msgs);
					}
					
				} else {
					$msg = array('type'=>'error','msg'=>'While you are viewing, someone doing something!');
					array_push($msgs,$msg);
				}


			}
		} else if (Input::get('reject')) {
			if ($transaction->service_type__id == 'withdraw' || $transaction->service_type__id == 'withdraw_game' || $serviceTypeId == 'withdraw_game_req' ||
				$transaction->service_type__id == 'deposit' || $transaction->service_type__id == 'deposit_game') {
				$response = $this->rejectTransaction($transactionOb,$transaction,$input);
				$msg = array();
				if($response['status'] == "F")
				{
					$msg['msg'] = 'Reject '.$transaction->service_type__id.' fail!';
					$msg['type'] = 'error';
					
					$msgs = $response['msgs'];
					array_push($msgs,$msg);
					
					return Redirect::back()
				            ->withInput()
				            ->with('msgs', $msgs);
								
				} else {
					
					$msg['msg'] = 'Reject '.$transaction->service_type__id.' successfully!';
					$msg['type'] = 'success';
					array_push($msgs,$msg);
					return Redirect::back()
				            ->withInput()
				            ->with('msgs', $msgs);
				}
			}
		}

		
		//return Redirect::back()->with('msgs', $msgs);
		return Redirect::route('transactions.index')->with('msgs', $msgs);
	} 

	private function operationRolBackOfTransactionCancel($transactionOb,$transaction,$tsnRef,$input,$dateTime,$type,$fromUpload = 'no',$uploadToCancelId = 0) {
		$response = array();
		$msgs = array();
		
		try { 
			if ($transaction->status == 'TC') {
				$msg = array();
				$msg['msg'] = 'Transaction Was Canceled, TSN = '.$transaction->tsn_os;
				$msg['type'] = 'error';
				array_push($msgs,$msg);
				
				$response['status'] = "F";
				$response['msgs'] = $msgs;
				return $response;
			}
			
			$validate = true;
			
			$staffId = Auth::user()->id;
			$transactionId = $transaction->transaction_id;		
			$txnH = null;
			//---------------------Cancel Txn Header-----------------------------------------------
			$txnHeaderArr = array();
			if ($type == 'game') {
				$serviceTypeId = 'cancel_game';
				$txnHeaderArr['atr3_name'] = $input['atr3_name'];
				$txnHeaderArr['atr3_value'] = $input['atr3_value'];
				
				$txnHeaderArr['atr2_name'] = 'type';
				$txnHeaderArr['atr2_value'] = $transaction->service_type__id;
				
			} else {
				$serviceTypeId = 'cancel_'.$transaction->service_type__id;
			}
			
			$currency = $transaction->tcy_currency_id;
			$txnHeaderArr['dealer__id'] = $transaction->dealer__id;
			$txnHeaderArr['service_type__id'] = $serviceTypeId;
			$txnHeaderArr['status'] = 'TS';
			$txnHeaderArr['request_gateway__id'] = 'web';
			$txnHeaderArr['tcy_currency_id'] = $transaction->tcy_currency_id;
			$txnHeaderArr['requested_value'] = $transaction->transfer_value;
			$txnHeaderArr['transfer_value'] = $transaction->transfer_value;
			$txnHeaderArr['remark'] = $input['remark'];
			$txnHeaderArr['staff__id'] = $staffId;
			$txnHeaderArr['ref_transaction_id'] = $transactionId;
			$txnHeaderArr['atr1_name'] = 'ref_tsn';
			$txnHeaderArr['atr1_value'] = $tsnRef;
			if ($fromUpload == 'yes') {
				$txnHeaderArr['atr4_name'] = 'upload-to-cancel-id';
				$txnHeaderArr['atr4_value'] = $uploadToCancelId;
			}
			
			$txnH = TxnTransactionHeader::create($txnHeaderArr);
			$txnObLog = array(
				'staff__id'=>$staffId,
				'action'=>'cancel',
				'object_type'=>$serviceTypeId,
				'object__id'=>$txnH->transaction_id,
				'new_data'=>json_encode($txnHeaderArr)
			);
			$txnHLog = Logs::create($txnObLog);
			
			//---------------------Update Txn Header-----------------------------------------------
			$update = array('status'=>'TC','updated_by_staff__id'=>$staffId,'ref_transaction_id'=>$txnH->transaction_id,'updated_at'=>$dateTime);
			if ($transaction->service_type__id == 'weiluy') {
				$update['atr1_value']  = 'cancel';
			} else {
				
			}
			$successOb = $transactionOb->update($update);
			
			$Log = array(
				'staff__id'=>$staffId,
				'action'=>'cancel',
				'object_type'=>$transaction->service_type__id,
				'object__id'=>$transaction->transaction_id,
				'old_data'=>json_encode($transaction),
				'new_data'=>json_encode($update),
				'reason'=>'Transaction was cancel by Txn ID = '.$txnH->transaction_id
			);
			if ($fromUpload == 'yes') {
				$Log['reason'] = 'Transaction was cancel by Txn ID = '.$txnH->transaction_id.', with upload-to-cancel-id = '.$uploadToCancelId;
			}
			
			$obLog = Logs::create($Log);
			
			//---------------------Txn Item and Wallet---------------------------------------------
			
			$transactionItems = DB::table('txn_transaction_items as ti')
							->select('ti.*')
							->where('ti.transaction__id',$transactionId)->get();
			
			$noteDealerPreAndPostBalance = array();
			$noteDealerPreAndPostBalance['prebalance'] = 0;
			$noteDealerPreAndPostBalance['posbalance'] = 0;
			
			foreach ($transactionItems as $key => $txnItem) {
				$dealerId = $txnItem->dealer__id;
				//------------Calculation----------------------------------
				$transferValue = $txnItem->transfer_value;
				if ($txnItem->user_direction == 'payer') {
					//------------Update wallets-------------------------------
					$wallet = WWallet::getWalletById($txnItem->wallet_id);
					
					$wallet->last_transaction__id = $txnH->transaction_id;
					$wallet->last_transaction_service_type__id = $serviceTypeId;
					$wallet->last_transaction_on = $dateTime;
					$wallet->last_balance_credit = $transferValue;
					$wallet->last_balance_debit = 0;
					$wallet->prev_balance = $wallet->post_balance;
					$wallet->post_balance = $wallet->post_balance + $transferValue;
					$walletOb = $wallet->update();
					
					//------------Txn Item---------------------------------
					$txnTransactionItem = array(
						'transaction__id'=>$txnH->transaction_id,
						'dealer__id'=>$dealerId,
						'wallet_id'=>$wallet->wallet_id,
						'requested_value'=>$transferValue,
						'transfer_value'=>$transferValue,
						'prev_balance'=>$wallet->prev_balance,
						'post_balance'=>$wallet->post_balance,
						'user_direction'=>'payee'
					);
					$itemOb = TxnTransactionItem::create($txnTransactionItem);
					
					if ($dealerId == $transaction->dealer__id) {
						$noteDealerPreAndPostBalance['prebalance'] = $wallet->prev_balance;
						$noteDealerPreAndPostBalance['posbalance'] = $wallet->post_balance;
					}
				} else if ($txnItem->user_direction == 'payee') {
					//------------Update wallets-------------------------------
					$wallet = WWallet::getWalletById($txnItem->wallet_id);
					
					if ($wallet->post_balance < $transferValue) {
						$msg['msg'] = 'DID = '.$dealerId.' , Balance Payer Not Enough To Roleback!';
						$msg['type'] = 'error';
						
						array_push($msgs,$msg);
						$response['status'] = "F";
						$response['msgs'] = $msgs;
						DB::rollback();
						$validate = FALSE;
						break;
					}
					
					
					$wallet->last_transaction__id = $txnH->transaction_id;
					$wallet->last_transaction_service_type__id = $serviceTypeId;
					$wallet->last_transaction_on = $dateTime;
					$wallet->last_balance_credit = $transferValue;
					$wallet->last_balance_debit = 0;
					$wallet->prev_balance = $wallet->post_balance;
					$wallet->post_balance = $wallet->post_balance - $transferValue;
					$walletOb = $wallet->update();
					
					//------------Txn Item---------------------------------
					$txnTransactionItem = array(
						'transaction__id'=>$txnH->transaction_id,
						'dealer__id'=>$dealerId,
						'wallet_id'=>$wallet->wallet_id,
						'requested_value'=>$transferValue,
						'transfer_value'=>$transferValue,
						'prev_balance'=>$wallet->prev_balance,
						'post_balance'=>$wallet->post_balance,
						'user_direction'=>'payer'
					);
					$itemOb = TxnTransactionItem::create($txnTransactionItem);
					
					if ($dealerId == $transaction->dealer__id) {
						$noteDealerPreAndPostBalance['prebalance'] = $wallet->prev_balance;
						$noteDealerPreAndPostBalance['posbalance'] = $wallet->post_balance;
					}
				}
			}

			if ($validate) {
				if ($transaction->service_type__id == 'weiluy') {
					$msg2 = array();
					$msg2['msg'] = 'Cancel to Txn ID = '.$transaction->transaction_id.' or TSN = '.$transaction->tsn_os;
					$msg2['type'] = 'success';
					
					$msg1 = array();
					$msg1['msg'] = 'Cancel Transaction Weiluy Txn ID = '.$txnH->transaction_id.' , Ref Txn ID = '.$transaction->transaction_id;
					$msg1['type'] = 'success';
					array_push($msgs,$msg2);
					array_push($msgs,$msg1);
				} else {
					$msg2 = array();
					$msg2['msg'] = 'Cancel to Txn ID = '.$transaction->transaction_id.' or TSN = '.$transaction->tsn.' , by TXN ID = '.$txnH->transaction_id.', DID = '.$transaction->dealer__id.' , Refunded = '.$txnH->requested_value.' '.$currency.' , Old Balance = '.$noteDealerPreAndPostBalance['prebalance'].' , Update Balance = '.$noteDealerPreAndPostBalance['posbalance'];
					$msg2['type'] = 'success';
					array_push($msgs,$msg2);
				}			
							
				$response['msgs'] = $msgs;			
				$response['status'] = "T";
			}
			
		} catch (\Exception $e) {
		    DB::rollback();
			$msg['msg'] = 'Transaction cancel was failed! Exception'.$e;
			$msg['type'] = 'error';
			$msgs = array($msg);
			$response['status'] = "F";
			$response['msgs'] = $msgs;
		}
		
		//-------------------------------------------------------------------------------------		
		return $response;
	}
	
	/**
	 * Show the form for editing the specified resource.
	 * GET /transaction/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	private function approveTransactionWithdrawNewProcess($transactionOb,$transaction,$input) {
		$staffId = Auth::user()->id;
		$dateTime = date("Y-m-d H:i:s");
		
		$response = array();
		$msgs = array();
		
		
		DB::beginTransaction();
		$responsePayback2 = null;
		$paybackAmountTransfer = 0;
		try {
			$dealerId = $transaction->dealer__id;
			$transactionId = $transaction->transaction_id;	
			$requestedValue = $transaction->requested_value;	
			$walletCurrencyId = $transaction->tcy_currency_id;
			$serviceTypeId = $transaction->service_type__id;
			$remark = $input['remark'];
			$action = 'approve';
			$dateTime = date("Y-m-d H:i:s");
			$gatetway = 'web';
			$terminalSerial = $transaction->terminal__serial;
			$realMoneyTransferValue = $requestedValue;
			$result = null;
			//---------------------Calculation Approve Withdraw-----------------------------------------------------
			$serviceTypeId = $transaction->service_type__id;
			if ($transaction->service_type__id == 'withdraw') {
				
				$transferValue = $transaction->requested_value;
				$walletTypeId = 'os';
				
				$response = WebServiceCollection::checkWithdrawReq($walletTypeId,$walletCurrencyId,$dealerId,$requestedValue,$gatetway);
				if ($response['status'] == 'F') {
					return $response;
				}
			
				
			}else if($transaction->service_type__id == 'withdraw_game' || $transaction->service_type__id == 'withdraw_game_req') {
				
				//--------------------------------------------------
				$walletTypeId = 'game';
				
				$response = WebServiceCollection::checkWithdrawReq($walletTypeId,$walletCurrencyId,$dealerId,$requestedValue,$gatetway);
				if ($response['status'] == 'F') {
					return $response;
				}		
				//--------------------------------------------------
				
				//=========webservice===============================
				$client = new SoapClient(Configuration::$wsdlWebService);			
				$walletCurrencyId = 'KHR';
				$walletTypeId = 'game';				
				$result = $client->getServiceChargeRange(array('dealerId'=>$dealerId, 'appId'=>'easy','serviceType'=>'withdraw_game','requestGateWay'=>'web','currency'=>$walletCurrencyId,'amount'=>$requestedValue));
				//////end webservice///////////
				if ($result->return->status == 'F') {
					$msg = array('type'=>'error','msg'=>$result->return->message);
					array_push($msgs,$msg);
					$response['status'] = 'F';
					$response['msgs'] = $msgs;
					return $response;
					
				} else if ($result->return->status == 'S'){
					$channelCommPct = $result->return->scRangeDTO->scRange->channelCommPct;
					if($result->return->scRangeDTO->scRange->pctOrAmt == 'pct'){
						$channelCommPct = $result->return->scRangeDTO->scRange->channelCommPct;
						$realMoneyTransferValue = ($requestedValue * 100) / (100 - $channelCommPct);
						
						$transferValue = $requestedValue;
						$commission = $requestedValue - $realMoneyTransferValue;
						$update['channel_comm'] = -$commission;
						$update['service_charge_rule_id'] = $result->return->scRangeDTO->serviceChargeRuleId;
						$update['service_charge_set_version'] = $result->return->scRangeDTO->version;
						$update['service_charge_set_rang_id'] = $result->return->scRangeDTO->scRange->setRangeId;
						
					}else{
						$msg = array('type'=>'error','msg'=>'No calculation with amount yet in my web.');
						array_push($msgs,$msg);
						$response['status'] = 'F';
						$response['msgs'] = $msgs;
						return $response;
					}
					
				}
				//========================================
				
				//-----------------2 Payback-------------------
				if($walletTypeId == 'game')
				{
					$wallet = WWallet::getWalletByCondition ($dealerId, $walletTypeId, $walletCurrencyId);
					
					$balanceCredit = $wallet->balance_credit;
					if ($balanceCredit > 0) {
						
						$paybackAmountRequest = $balanceCredit;
						
						//--------Calculation-----------------
						$resultPaybackAPI = $client->getServiceChargeRange(array('dealerId'=>$dealerId, 'appId'=>'easy','serviceType'=>'payback_game','requestGateWay'=>'web','currency'=>$walletCurrencyId,'amount'=>$paybackAmountRequest));
						if ($resultPaybackAPI->return->status == 'F') {
							$msg = array('type'=>'error','msg'=>$resultPaybackAPI->return->message);
							array_push($msgs,$msg);
							$response['status'] = 'F';
							$response['msgs'] = $msgs;
							return $response;
						}
				
						$txnHeader['atr1_value'] = 'system_auto';
						$txnHeader['ref_transaction_id'] =  $transaction->transaction_id;
						
						$pCt = $resultPaybackAPI->return->scRangeDTO->scRange->channelCommPct / 100;
						$channelCommAmt = ($paybackAmountRequest*$pCt);
						$paybackAmountTransfer = $paybackAmountRequest - $channelCommAmt;
						//--------End Calculation-----------------
						if ($paybackAmountTransfer > $wallet->post_balance) {
							$msg = array();
							$msg['msg'] = 'Could not withdraw to Wallet Type = '.$wallet->wallet_type__id.' , Curremcy = '.$walletCurrencyId.' , Because Post Balance = '.$wallet->post_balance.' > Balance Credit ('.$balanceCredit.') + Comm('.-$channelCommAmt.')';
							$msg['type'] = 'error';
							array_push($msgs,$msg);
							
							$response['status'] = 'F';
							$response['msgs'] = $msgs;
							return $response;
						}

						$responsePayback2 = TxnTransactionHeader::operationPaybackGame($dealerId,$walletCurrencyId,$paybackAmountRequest,$remark,$dateTime,$txnHeader);
						if ($responsePayback2['status'] == 'T') {
							$msg = array('type'=>'success','msg'=>'System Auto Payback! Wallet type = '.$wallet->wallet_type__id.' , Curremcy = '.$walletCurrencyId.' , Amount payback = '.number_format($responsePayback2['amount_payback'],2).' '.$walletCurrencyId.', Amount Debit From Post Balance = '.number_format($responsePayback2['amount_debit_from_balance'],2).' '.$walletCurrencyId.'. Txn ID = '.$responsePayback2['id']);
							array_push($msgs,$msg);
						} else {
							$msgs = $responsePayback2['msgs'];
							$response['status'] = 'F';
							$response['msgs'] = $msgs;
							return $response;
						}
					}
				}
				//------------------------------------------------
					
			} 
		
			//---------------------Update Txn Header-----------------------
			$update['status'] = 'TS';
			$update['updated_by_staff__id'] = $staffId;
			$update['transfer_value'] = $transferValue;
			
			$update['atr1_name'] = 'bank';
			$update['atr1_value'] = $input['atr1_value'];
			
			$update['atr2_name'] = 'bankAccName';	
			$update['atr2_value'] = $input['bank_acc_name_hidden'];	
			
			$update['atr3_name'] = 'bankAccNum';
			$update['atr3_value'] = $input['atr3_value'];
			
			$update['atr5_name'] = 'last_approval_at';
			$update['atr5_value'] = $dateTime;
			
			$update['atr6_name'] = 'realMoneyTransferValue';				
			$update['atr6_value'] = $realMoneyTransferValue;
			
			//------------Update txn_transaction_approval-------------------
			$TxnTransactionApproval = array(
							'transaction_id'=>$transactionId,
							'action'=>$action,
							'action_gateway'=>'web',
							'approval_status'=>'TS',
							'remark'=>$remark,
							'created_at'=>$dateTime,
							'staff__id'=>$staffId
						);
			$actionApproval = TxnTransactionApproval::create($TxnTransactionApproval);
			
			//--------------------------------------------------
			
			
			//---------------------------------------------------
			if ($transaction->service_type__id == 'withdraw_game_req') {
				$serviceTypeId = 'withdraw_game';
				$txnNewHeader = $update;
				$txnNewHeader['staff__id'] = $staffId; 
				$txnNewHeader['dealer__id'] = $transaction->dealer__id;
				$txnNewHeader['terminal__serial'] = $transaction->terminal__serial;
				$txnNewHeader['error_code'] = $transaction->error_code;
				$txnNewHeader['request_gateway__id'] = $transaction->request_gateway__id;
				$txnNewHeader['reference_number'] = $transaction->reference_number;
				$txnNewHeader['tcy_currency_id'] = $transaction->tcy_currency_id;
				$txnNewHeader['requested_value'] = $transaction->requested_value;
				$txnNewHeader['request_gateway__id'] = 'web';
				$txnNewHeader['ref_transaction_id'] = $transaction->transaction_id;
				
				$txnNewHeader['service_type__id'] = $serviceTypeId;
								
				if ($responsePayback2 != null) {
					$requestedValue = $requestedValue - $paybackAmountTransfer;
					$txnNewHeader['requested_value'] = $requestedValue;
					
					//----------------new calcuation---------------------------------------
					$channelCommPct = $result->return->scRangeDTO->scRange->channelCommPct;
					if($result->return->scRangeDTO->scRange->pctOrAmt == 'pct'){
						$channelCommPct = $result->return->scRangeDTO->scRange->channelCommPct;
						$realMoneyTransferValue = ($requestedValue * 100) / (100 - $channelCommPct);
						
						$transferValue = $requestedValue;
						$commission = $requestedValue - $realMoneyTransferValue;
						
						$txnNewHeader['transfer_value'] = $transferValue;
						$txnNewHeader['channel_comm'] = -$commission;
						$txnNewHeader['service_charge_rule_id'] = $result->return->scRangeDTO->serviceChargeRuleId;
						$txnNewHeader['service_charge_set_version'] = $result->return->scRangeDTO->version;
						$txnNewHeader['service_charge_set_rang_id'] = $result->return->scRangeDTO->scRange->setRangeId;
						
						//---ref in table requet---
						$update['atr7_name'] = 'hasPaybackTxn';
						$update['atr7_value'] = $responsePayback2['id'];
					}
				}
				$transaction = TxnTransactionHeader::create($txnNewHeader);
				$transactionId = $transaction->transaction_id;
				
				//-------------update request----------------------------------
				$update['ref_transaction_id'] = $transactionId;
				$txnOb = $transactionOb->update($update);
				
				//------------Update txn_transaction_approval-------------------
				$TxnTransactionApproval = array(
								'transaction_id'=>$transactionId,
								'action'=>$action,
								'action_gateway'=>'web',
								'approval_status'=>'TS',
								'remark'=>$remark,
								'created_at'=>$dateTime,
								'staff__id'=>$staffId
							);
				$actionApproval = TxnTransactionApproval::create($TxnTransactionApproval);
				//----------------------------------------------------------------
				$msg = array('type'=>'success','msg'=>'Withdraw Game Success with new Txn ID = '.$transactionId);
				array_push($msgs,$msg);
			} else {
				$txnOb = $transactionOb->update($update);			
			}
			
			//------------Update wallet Payer-------------------------------
						
			$wallet = WWallet::getWalletByCondition ($dealerId, $walletTypeId, $walletCurrencyId);
			$wallet->last_transaction__id = $transaction->transaction_id;
			$wallet->last_transaction_service_type__id = $serviceTypeId;
			$wallet->last_transaction_on = $dateTime;
			$wallet->last_balance_credit = 0;
			$wallet->last_balance_debit = $requestedValue;
			$wallet->prev_balance = $wallet->post_balance;
			$wallet->post_balance = $wallet->post_balance - $requestedValue;
			$walletOb = $wallet->update();
						
			//------------Txn Item Payer-----------------------------------
			$txnTransactionItem = array(
				'transaction__id'=>$transaction->transaction_id,
				'dealer__id'=>$dealerId,
				'wallet_id'=>$wallet->wallet_id,
				'requested_value'=>$requestedValue,
				'transfer_value'=>$requestedValue,
				'prev_balance'=>$wallet->prev_balance,
				'post_balance'=>$wallet->post_balance,
				'user_direction'=>'payer'
			);
			$itemOb = TxnTransactionItem::create($txnTransactionItem);			
			
			//------------Update wallet Payee-------------------------------
			$easyStockDid = StockConfiguration::$easyStockDid;
			
			$walletPayee = WWallet::getWalletByCondition($easyStockDid, 'os', $walletCurrencyId);
			$walletPayee->last_transaction__id = $transaction->transaction_id;
			$walletPayee->last_transaction_service_type__id = $serviceTypeId;
			$walletPayee->last_transaction_on = $dateTime;
			$walletPayee->last_balance_credit = $realMoneyTransferValue;
			$walletPayee->last_balance_debit = 0;
			$walletPayee->prev_balance = $walletPayee->post_balance;
			$walletPayee->post_balance = $walletPayee->post_balance + $realMoneyTransferValue;
			$walletPayeeOb = $walletPayee->update();
						
			//------------Txn Item Payee------------------------------------
			$txnTransactionItemPayee = array(
				'transaction__id'=>$transaction->transaction_id,
				'dealer__id'=>$easyStockDid,
				'wallet_id'=>$walletPayee->wallet_id,
				'requested_value'=>$transferValue,
				'transfer_value'=>$realMoneyTransferValue,
				'prev_balance'=>$walletPayee->prev_balance,
				'post_balance'=>$walletPayee->post_balance,
				'user_direction'=>'payee'
			);
			$itemObPayee = TxnTransactionItem::create($txnTransactionItemPayee);
			
			//------------Txn Log-------------------------------------------
			$reason = '';
			if ($responsePayback2 != null) {
				$reason = ' Before Withdraw Game, System Auto Payback. Payback Txn Id = '.$responsePayback2['id'];
			}
			$obLog = array(
				'staff__id'=>$staffId,
				'action'=>'approve',
				'object_type'=>$serviceTypeId,
				'object__id'=>$transaction->transaction_id,
				'old_data'=>json_encode($transaction),
				'new_data'=>json_encode($update),
				'reason'=>$reason
			);
			$obLog = Logs::create($obLog);
			
			//-------------------------------------------------------------
			DB::commit();
			$response['status'] = "T";
			$response['msgs'] = $msgs;
			return $response;
		} catch (\Exception $e) {
		    DB::rollback();
			$msg['msg'] = 'Approval was failed! Exception'.$e;
			$msg['type'] = 'error';
			$msgs = array($msg);
			$response['status'] = "F";
			$response['msgs'] = $msgs;
			return $response;
		}
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /transaction/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	/*private function approveTransactionWithdraw($transactionOb,$transaction,$input) {
		$staffId = Auth::user()->id;
		$dateTime = date("Y-m-d H:i:s");
		
		$response = array();
		$msgs = array();
		
		
		DB::beginTransaction();
		$responsePayback2 = null;
		try {
			$dealerId = $transaction->dealer__id;
			$transactionId = $transaction->transaction_id;	
			$requestedValue = $transaction->requested_value;	
			$walletCurrencyId = $transaction->tcy_currency_id;
			$serviceTypeId = $transaction->service_type__id;
			$remark = $input['remark'];
			$action = 'approve';
			$dateTime = date("Y-m-d H:i:s");
			$gatetway = 'web';
			$terminalSerial = $transaction->terminal__serial;
			$realMoneyTransferValue = $requestedValue;
			$result = null;
			//---------------------Calculation Approve Withdraw-----------------------------------------------------
			$serviceTypeId = $transaction->service_type__id;
			if ($transaction->service_type__id == 'withdraw') {
				
				$transferValue = $transaction->requested_value;
				$walletTypeId = 'os';
				
				$response = WebServiceCollection::checkWithdrawReq($walletTypeId,$walletCurrencyId,$dealerId,$requestedValue,$gatetway);
				if ($response['status'] == 'F') {
					return $response;
				}
			
				
			}else if($transaction->service_type__id == 'withdraw_game') {
				
				//--------------------------------------------------
				$walletTypeId = 'game';
				
				$response = WebServiceCollection::checkWithdrawReq($walletTypeId,$walletCurrencyId,$dealerId,$requestedValue,$gatetway);
				if ($response['status'] == 'F') {
					return $response;
				}		
				//--------------------------------------------------
				
				//=========webservice===============================
				$client = new SoapClient(Configuration::$wsdlWebService);			
				$walletCurrencyId = 'KHR';
				$walletTypeId = 'game';				
				$result = $client->getServiceChargeRange(array('dealerId'=>$dealerId, 'appId'=>'easy','serviceType'=>$serviceTypeId,'requestGateWay'=>'web','currency'=>$walletCurrencyId,'amount'=>$requestedValue));
				//////end webservice///////////
				if ($result->return->status == 'F') {
					$msg = array('type'=>'error','msg'=>$result->return->message);
					array_push($msgs,$msg);
					$response['status'] = 'F';
					$response['msgs'] = $msgs;
					return $response;
					
				} else if ($result->return->status == 'S'){
					$channelCommPct = $result->return->scRangeDTO->scRange->channelCommPct;
					if($result->return->scRangeDTO->scRange->pctOrAmt == 'pct'){
						$channelCommPct = $result->return->scRangeDTO->scRange->channelCommPct;
						$realMoneyTransferValue = ($requestedValue * 100) / (100 - $channelCommPct);
						
						$transferValue = $requestedValue;
						$commission = $requestedValue - $realMoneyTransferValue;
						$update['channel_comm'] = -$commission;
						$update['service_charge_rule_id'] = $result->return->scRangeDTO->serviceChargeRuleId;
						$update['service_charge_set_version'] = $result->return->scRangeDTO->version;
						$update['service_charge_set_rang_id'] = $result->return->scRangeDTO->scRange->setRangeId;
						
					}else{
						$msg = array('type'=>'error','msg'=>'No calculation with amount yet in my web.');
						array_push($msgs,$msg);
						$response['status'] = 'F';
						$response['msgs'] = $msgs;
						return $response;
					}
					
				}
				//========================================
				
				//-----------------2 Payback-------------------
				if($walletTypeId == 'game')
				{
					$wallet = WWallet::getWalletByCondition ($dealerId, $walletTypeId, $walletCurrencyId);
					
					$balanceCredit = $wallet->balance_credit;
					if ($balanceCredit > 0) {
						
						$paybackAmountRequest = $balanceCredit;
						
						//--------Calculation-----------------
						$resultPaybackAPI = $client->getServiceChargeRange(array('dealerId'=>$dealerId, 'appId'=>'easy','serviceType'=>'payback_game','requestGateWay'=>'web','currency'=>$walletCurrencyId,'amount'=>$paybackAmountRequest));
						if ($resultPaybackAPI->return->status == 'F') {
							$msg = array('type'=>'error','msg'=>$resultPaybackAPI->return->message);
							array_push($msgs,$msg);
							$response['status'] = 'F';
							$response['msgs'] = $msgs;
							return $response;
							
						}
				
						$txnHeader['atr1_value'] = 'system_auto';
				
						$pCt = $resultPaybackAPI->return->scRangeDTO->scRange->channelCommPct / 100;
						$channelCommAmt = ($paybackAmountRequest*$pCt);
						$paybackAmountTransfer = $paybackAmountRequest - $channelCommAmt;
						//--------End Calculation-----------------
						if ($paybackAmountTransfer > $wallet->post_balance) {
							$msg = array();
							$msg['msg'] = 'Could not withdraw to Wallet Type = '.$wallet->wallet_type__id.' , Curremcy = '.$walletCurrencyId.' , Because Post Balance = '.$wallet->post_balance.' > Balance Credit ('.$balanceCredit.') + Comm('.-$channelCommAmt.')';
							$msg['type'] = 'error';
							array_push($msgs,$msg);
							
							$response['status'] = 'F';
							$response['msgs'] = $msgs;
							return $response;
						}

						$responsePayback2 = TxnTransactionHeader::operationPaybackGame($dealerId,$walletCurrencyId,$paybackAmountRequest,$remark,$dateTime,$txnHeader);
						if ($responsePayback2['status'] == 'T') {
							$msg = array('type'=>'success','msg'=>'System Auto Payback! Wallet type = '.$wallet->wallet_type__id.' , Curremcy = '.$walletCurrencyId.' , Amount payback = '.number_format($responsePayback2['amount_payback'],2).' '.$walletCurrencyId.', Amount Debit From Post Balance = '.number_format($responsePayback2['amount_debit_from_balance'],2).' '.$walletCurrencyId.'. Txn ID = '.$responsePayback2['id']);
							array_push($msgs,$msg);
						} else {
							$msgs = $responsePayback2['msgs'];
							$response['status'] = 'F';
							$response['msgs'] = $msgs;
							return $response;
						}
					}
				}
				//------------------------------------------------
					
			} 
		
			//---------------------Update Txn Header-----------------------------------------------
			$update['status'] = 'TS';
			$update['updated_by_staff__id'] = $staffId;
			$update['transfer_value'] = $transferValue;
			$update['atr5_name'] = 'last_approval_at';
			$update['atr5_value'] = $dateTime;
			$update['atr1_name'] = 'bank';
			$update['atr2_name'] = 'bankAccName';				
			$update['atr3_name'] = 'bankAccNum';
			$update['atr1_value'] = $input['atr1_value'];
			$update['atr2_value'] = $input['bank_acc_name_hidden'];				
			$update['atr3_value'] = $input['atr3_value'];
			
			$update['atr6_name'] = 'realMoneyTransferValue';				
			$update['atr6_value'] = $realMoneyTransferValue;
			
			
			$txnOb = $transactionOb->update($update);
			
			//---------------------Update txn_transaction_approval-----------------------------------------------
			$TxnTransactionApproval = array(
							'transaction_id'=>$transactionId,
							'action'=>$action,
							'action_gateway'=>'web',
							'approval_status'=>'TS',
							'remark'=>$remark,
							'created_at'=>$dateTime,
							'staff__id'=>$staffId
						);
			$actionApproval = TxnTransactionApproval::create($TxnTransactionApproval);
			
			//------------Update wallet Payer-------------------------------
						
			$wallet = WWallet::getWalletByCondition ($dealerId, $walletTypeId, $walletCurrencyId);
			$wallet->last_transaction__id = $transaction->transaction_id;
			$wallet->last_transaction_service_type__id = $serviceTypeId;
			$wallet->last_transaction_on = $dateTime;
			$wallet->last_balance_credit = 0;
			$wallet->last_balance_debit = $requestedValue;
			$wallet->prev_balance = $wallet->post_balance;
			$wallet->post_balance = $wallet->post_balance - $requestedValue;
			$walletOb = $wallet->update();
						
			//------------Txn Item Payer---------------------------------
			$txnTransactionItem = array(
				'transaction__id'=>$transaction->transaction_id,
				'dealer__id'=>$dealerId,
				'wallet_id'=>$wallet->wallet_id,
				'requested_value'=>$requestedValue,
				'transfer_value'=>$requestedValue,
				'prev_balance'=>$wallet->prev_balance,
				'post_balance'=>$wallet->post_balance,
				'user_direction'=>'payer'
			);
			$itemOb = TxnTransactionItem::create($txnTransactionItem);			
			
			//------------Update wallet Payee-------------------------------
			$easyStockDid = StockConfiguration::$easyStockDid;
			
			$walletPayee = WWallet::getWalletByCondition($easyStockDid, 'os', $walletCurrencyId);
			$walletPayee->last_transaction__id = $transaction->transaction_id;
			$walletPayee->last_transaction_service_type__id = $serviceTypeId;
			$walletPayee->last_transaction_on = $dateTime;
			$walletPayee->last_balance_credit = $realMoneyTransferValue;
			$walletPayee->last_balance_debit = 0;
			$walletPayee->prev_balance = $walletPayee->post_balance;
			$walletPayee->post_balance = $walletPayee->post_balance + $realMoneyTransferValue;
			$walletPayeeOb = $walletPayee->update();
						
			//------------Txn Item Payee---------------------------------
			$txnTransactionItemPayee = array(
				'transaction__id'=>$transaction->transaction_id,
				'dealer__id'=>$easyStockDid,
				'wallet_id'=>$walletPayee->wallet_id,
				'requested_value'=>$transferValue,
				'transfer_value'=>$realMoneyTransferValue,
				'prev_balance'=>$walletPayee->prev_balance,
				'post_balance'=>$walletPayee->post_balance,
				'user_direction'=>'payee'
			);
			$itemObPayee = TxnTransactionItem::create($txnTransactionItemPayee);
			
			//------------Txn Log---------------------------------
			$reason = '';
			if ($responsePayback2 != null) {
				$reason = ' Before Withdraw Game, System Auto Payback. Payback Txn Id = '.$responsePayback2['id'];
			}
			$obLog = array(
				'staff__id'=>$staffId,
				'action'=>'approve',
				'object_type'=>$serviceTypeId,
				'object__id'=>$transaction->transaction_id,
				'old_data'=>json_encode($transaction),
				'new_data'=>json_encode($update),
				'reason'=>$reason
			);
			$obLog = Logs::create($obLog);
			
			//-------------------------------------------------------------------------------------		
			DB::commit();
			$response['status'] = "T";
			$response['msgs'] = $msgs;
			return $response;
		} catch (\Exception $e) {
		    DB::rollback();
			$msg['msg'] = 'Approval was failed! Exception'.$e;
			$msg['type'] = 'error';
			$msgs = array($msg);
			$response['status'] = "F";
			$response['msgs'] = $msgs;
			return $response;
		}
	}*/

	private function rejectTransaction($transactionOb,$transaction,$input) {
		$staffId = Auth::user()->id;
		$dateTime = date("Y-m-d H:i:s");
		
		$response = array();
		$msgs = array();
		
		
		DB::beginTransaction();
		try {
			$dealerId = $transaction->dealer__id;
			$transactionId = $transaction->transaction_id;	
			$requestedValue = $transaction->requested_value;
			$walletCurrencyId = $transaction->tcy_currency_id;
			$serviceTypeId = $transaction->service_type__id;
			$remark = $input['remark'];
			$action = 'reject';
			$dateTime = date("Y-m-d H:i:s");
			$gatetway = 'web';
			$terminalSerial = $transaction->terminal__serial;
			
			$update = array();
			//---------------------Calculation Approve Withdraw-----------------------------------------------------
			
		
			//---------------------Update Txn Header-----------------------------------------------
			$update['status'] = 'TR';
			$update['updated_by_staff__id'] = $staffId;
			
			$txnOb = $transactionOb->update($update);
			
			//---------------------txn_transaction_approval-----------------------------------------------
			$TxnTransactionApproval = array(
							'transaction_id'=>$transactionId,
							'action'=>$action,
							'action_gateway'=>'web',
							'approval_status'=>'TR',
							'remark'=>$remark,
							'created_at'=>$dateTime,
							'staff__id'=>$staffId
						);
			$actionApproval = TxnTransactionApproval::create($TxnTransactionApproval);
			//------------Txn Log---------------------------------
			$obLog = array(
				'staff__id'=>$staffId,
				'action'=>$action,
				'object_type'=>$serviceTypeId,
				'object__id'=>$transaction->transaction_id,
				'old_data'=>json_encode($transaction),
				'new_data'=>json_encode($update)
			);
			$obLog = Logs::create($obLog);
			
			//-------------------------------------------------------------------------------------		
			DB::commit();
			$response['status'] = "T";
			return $response;
		} catch (\Exception $e) {
		    DB::rollback();
			$msg['msg'] = 'Reject was failed! Exception'.$e;
			$msg['type'] = 'error';
			$msgs = array($msg);
			$response['status'] = "F";
			$response['msgs'] = $msgs;
			return $response;
		}
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /transaction/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update()
	{
		$staffId = Auth::user()->id;
		
		$input = Input::all();
		
		$rules = array(
			'transaction_id' => 'required',
		    'remark' => 'required|max:255'
		);
		$msgs = array();
        $validation = Validator::make($input, $rules);
		if (!$validation->passes()) 
		{
			return Redirect::back()
            ->withInput()
            ->withErrors($validation)
            ->with('msgs', $msgs);
		}
		
		try {
			$transactionId = Input::get("transaction_id");
			$transactionOb = DB::table('txn_transaction_header as t')
							->where('service_type__id','!=','stock_initiate')
							->where('transaction_id',$transactionId);
			$transaction = $transactionOb->first();
			$serviceTypeId = $transaction->service_type__id;
			
			$input['atr2_value'] = $input['bank_acc_name_hidden'];
			$update = array('remark'=>$input['remark'],'atr1_value'=>$input['atr1_value'],'atr2_value'=>$input['atr2_value'],'atr3_value'=>$input['atr3_value'],);
			
			
			$transactionOb->update($update);
			$obLog = array(
				'staff__id'=>$staffId,
				'action'=>'update',
				'object_type'=>$serviceTypeId,
				'object__id'=>$transactionId,
				'old_data'=>json_encode($transaction),
				'new_data'=>json_encode($update)
				);
			Logs::create($obLog);
			
			
			$msg1 = array('msg'=>'Update Transaction Successfully!','type'=>'success');
			array_push($msgs,$msg1);
		
		} catch (Exception $e) {
			DB::rollback();
			$msg['msg'] = 'Update Transaction Error = '.$e;
			$msg['type'] = 'error';
			array_push($msgs,$msg);
		}
		return Redirect::back()->with('msgs', $msgs);
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /transaction/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

	/**
	 * Get a listing of the resource.
	 * GET /location
	 *
	 * @return Response
	 */
	public function checkProcessTxnCancel($ob)
	{
		if ($ob) {
			return true;
		} else {
			$msgs = array();
			$msg = array();
			$msg['msg'] = 'Transaction Cancel Failed!';
			$msg['type'] = 'error';
			array_push($msgs,$msg);
			return Redirect::back()
	            ->withInput()
	            ->withErrors($validation)
	            ->with('msgs', $msgs);
		}
	}
	
	/**
	 * Get a listing of the resource.
	 * GET /location
	 *
	 * @return Response
	 */
	public function checkProcessTxnAppWithdraw($ob)
	{
		if ($ob) {
			return true;
		} else {
			$msgs = array();
			$msg = array();
			$msg['msg'] = 'Transaction Approve Withdraw Failed!';
			$msg['type'] = 'error';
			array_push($msgs,$msg);
			return Redirect::back()
	            ->withInput()
	            ->withErrors($validation)
	            ->with('msgs', $msgs);
		}
	}
	
	/**
	 * Get a listing of the resource.
	 * GET /location
	 *
	 * @return Response
	 */
	
	private function approveTransactionDeposit($transactionOb,$transaction,$input) {
		$staffId = Auth::user()->id;
		$dateTime = date("Y-m-d H:i:s");
		$response = array();
		$msgs = array();
		
		DB::beginTransaction();
		$responsePayback2 = null;
		try {
			$dealerId = $transaction->dealer__id;
			$transactionId = $transaction->transaction_id;	
			$requestedValue = $transaction->requested_value;	
			$walletCurrencyId = $transaction->tcy_currency_id;
			$serviceTypeId = $transaction->service_type__id;
			$remark = $input['remark'];
			$action = 'approve';
			$dateTime = date("Y-m-d H:i:s");
			$gatetway = 'web';
			$terminalSerial = $transaction->terminal__serial;
			
			
	
			/////Action Deposit////////
				
			///------------calll process depsoit---------------------------------------------------
			$input['dealer__id'] = $dealerId;
			$walletTypeId = 'os';
			if ($serviceTypeId == 'deposit_game') {
				$walletTypeId = 'game';
			}
			$input['wallet_type__id'] = $walletTypeId;
			$input['tcy_currency_id'] = $walletCurrencyId;
			$input['requested_value'] = $requestedValue;
			$input['transaction_id'] = $transaction->transaction_id;
	
			$response = TxnTransactionHeader::operationDeposit($input,'by_approval_form');
			return $response;
					
		} catch (\Exception $e) {
		    DB::rollback();
			$msg['msg'] = 'Approval was failed! Exception'.$e;
			$msg['type'] = 'error';
			$msgs = array($msg);
			$response['status'] = "F";
			$response['msgs'] = $msgs;
			return $response;
		}
	}
	
}