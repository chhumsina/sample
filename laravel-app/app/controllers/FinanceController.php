<?php

class FinanceController extends \BaseController {
	/**
	 * The layout that should be used for responses.
	 */
	protected $layout = 'layouts.master';
	/**
	 * Display a listing of the resource.
	 * GET /finance
	 *
	 * @return Response
	 */
	 
	public function deposit()
	{
		$dealers = DB::table('dealer')
                    ->where('status','<>','inactive')
					->whereIn('dealer_type__id', array(1, 2, 3))
                    ->get();
		
		$currencies = DB::table('c_currency')
                    ->where('status','=','active')
                    ->lists('currency_id','currency_id');		
					
		$wallet_types = DB::table('sys_enumeration')
                      ->where('enum_type__id','=','wallet_type')
                      ->lists('name','enum_id');	
					
		$banks = DB::table('bank')
                      ->whereNotIn('bid', array( 2 ))
                      ->lists('bank_name','bid');	

		$this->layout->content = View::make('finance.deposit', compact('dealers','wallet_types','currencies','banks'));
	}
	
	public function storeDeposit()
	{
		DB::beginTransaction();		
		$input = Input::all();
		$rules = array(
		    'dealer__id' => 'required',
		    'tcy_currency_id' => 'required',
		    'wallet_type__id' => 'required',
		    'atr1_value' => 'required',
		    // 'atr2_value' => 'required',
		    //'atr3_value' => 'required',
		    'requested_value' => 'required',
		    'remark' => 'max:255'
		);
		
        $validation = Validator::make($input, $rules);
		$msgs = array();
		
		if ($validation->passes()) 
		{
			$response = TxnTransactionHeader::operationDeposit($input,'by_deposit_form');
			$msg = array();
			if($response['status'] == "F")
			{
				$msgs = $response['msgs'];
				
				return Redirect::back()
			            ->withInput()
			            ->with('msgs', $msgs);
							
			} else {
				DB::commit();
				$msgs = $response['msgs'];
				return Redirect::back()->with('msgs', $msgs);
			}
			//var_dump($response);
			//die();		
		}
		
		return Redirect::back()
            ->withInput()
            ->withErrors($validation)
            ->with('msgs', $msgs);
	}

	public function promotion()
	{
		$dealers = DB::table('dealer')
                    ->where('status','<>','inactive')
					->whereIn('dealer_type__id', array(1, 2, 3))
                    ->get();
		
		$currencies = DB::table('c_currency')
                    ->where('status','=','active')
                    ->lists('currency_id','currency_id');		
					
		$wallet_types = DB::table('sys_enumeration')
                      ->where('enum_type__id','=','wallet_type')
                      ->lists('name','enum_id');	
					
		$banks = DB::table('bank')
                      ->whereNotIn('bid', array( 2 ))
                      ->lists('bank_name','bid');	

		$this->layout->content = View::make('finance.promotion', compact('dealers','wallet_types','currencies','banks'));
	}
	
	public function storePromotion()
	{
		DB::beginTransaction();		
		$input = Input::all();
		$rules = array(
		    'dealer__id' => 'required',
		    'tcy_currency_id' => 'required',
		    'wallet_type__id' => 'required',
		    'requested_value' => 'required',
		    'remark' => 'max:255'
		);
		
        $validation = Validator::make($input, $rules);
		$msgs = array();
		
		if ($validation->passes()) 
		{
			$response = TxnTransactionHeader::operationPromotion($input);
			$msg = array();
			if($response['status'] == "F")
			{
				$msgs = $response['msgs'];
				
				return Redirect::back()
			            ->withInput()
			            ->with('msgs', $msgs);
							
			} else {
				DB::commit();
				$msgs = $response['msgs'];
				return Redirect::back()->with('msgs', $msgs);
			}
			//var_dump($response);
			//die();		
		}
		
		return Redirect::back()
            ->withInput()
            ->withErrors($validation)
            ->with('msgs', $msgs);
	}
	
	public function commissionFree()
	{
		$dealers = DB::table('dealer')
                    ->where('status','<>','inactive')
					->whereIn('dealer_type__id', array(1, 2, 3))
                    ->get();
		
		$currencies = DB::table('c_currency')
                    ->where('status','=','active')
                    ->lists('currency_id','currency_id');		
					
		$wallet_types = DB::table('sys_enumeration')
                      ->where('enum_type__id','=','wallet_type')
                      ->lists('name','enum_id');	
					
		$banks = DB::table('bank')
                      ->whereNotIn('bid', array( 2, 7 ))
                      ->lists('bank_name','bid');	

		$this->layout->content = View::make('finance.commission-free', compact('dealers','wallet_types','currencies','banks'));
	}
	
	public function storeCommissionFree()
	{
		DB::beginTransaction();		
		$input = Input::all();
		$rules = array(
		    'dealer__id' => 'required',
		    'tcy_currency_id' => 'required',
		    'wallet_type__id' => 'required',
		    'requested_value' => 'required',
		    'remark' => 'max:255'
		);
		
        $validation = Validator::make($input, $rules);
		$msgs = array();
		
		if ($validation->passes()) 
		{
			$response = TxnTransactionHeader::operationCommionFree($input);
			$msg = array();
			if($response['status'] == "F")
			{
				$msgs = $response['msgs'];
				
				return Redirect::back()
			            ->withInput()
			            ->with('msgs', $msgs);
							
			} else {
				DB::commit();
				$msgs = $response['msgs'];
				return Redirect::back()->with('msgs', $msgs);
			}
			//var_dump($response);
			//die();		
		}
		
		return Redirect::back()
            ->withInput()
            ->withErrors($validation)
            ->with('msgs', $msgs);
	}
	
	/*public function storeDeposit()
	{
		DB::beginTransaction();		
		$input = Input::all();
		$rules = array(
		    'dealer__id' => 'required',
		    'tcy_currency_id' => 'required',
		    'wallet_type__id' => 'required',
		    'atr1_value' => 'required',
		    // 'atr2_value' => 'required',
		    //'atr3_value' => 'required',
		    'requested_value' => 'required',
		    'remark' => 'max:255'
		);
		
        $validation = Validator::make($input, $rules);
		$msgs = array();
		$result = null;
		$channelCommPct = 0;
		$channelCommAmt = 0;
		$transferValue = 0;
		$walletCurrencyId = '';
		if ($validation->passes()) 
		{
			$dealerId = $input['dealer__id'];
			$walletTypeId = $input['wallet_type__id'];
			$walletCurrencyId = $input['tcy_currency_id'];
			$dateTime = date("Y-m-d H:i:s");
			$requestedValue = $input['requested_value'];
			$transferValue = $requestedValue;
			$serviceTypeId = 'deposit_game';
			$subsequent = 'subsequent';
			$remark = $input['remark'];
			
			
			$channelCommAmt = 0;
			$responsePayback = null;
			$responsePayback2 = null;
			$result = null;
			if($walletTypeId == 'game')
			{
				///////////if Game///////////////
		        $client = new SoapClient(Configuration::$wsdlWebService);
				
				$haveTxnTransactionHeader = TxnTransactionHeader::getTxnTransactionHeader($dealerId, $serviceTypeId);
				
				if($haveTxnTransactionHeader <= 0) {
					$subsequent = 'first';
				}

				$result = $client->getServiceChargeRange(array('dealerId'=>$dealerId, 'appId'=>'easy','serviceType'=>$serviceTypeId,'requestGateWay'=>'web','currency'=>$walletCurrencyId,'amount'=>$requestedValue,'additionalConfig'=>array('firstOrSubsequent'=>$subsequent)));
		    	
		    	if ($result->return->status == 'F') {
					$msg = array('type'=>'error','msg'=>$result->return->message);
					array_push($msgs,$msg);
					return Redirect::back()
				            ->withInput()
				            ->withErrors($validation)
				            ->with('msgs', $msgs);
				} else if ($result->return->status == 'S'){
					
					$input['service_charge_rule_id'] = $result->return->scRangeDTO->serviceChargeRuleId;
					$input['service_charge_set_version'] = $result->return->scRangeDTO->version;
					$input['service_charge_set_rang_id'] = $result->return->scRangeDTO->scRange->setRangeId;
					$channelCommPct = $result->return->scRangeDTO->scRange->channelCommPct;
					
					if($result->return->scRangeDTO->scRange->pctOrAmt == 'pct'){
						$pCt = $result->return->scRangeDTO->scRange->channelCommPct / 100;
						$channelCommAmt = $requestedValue*$pCt;
						$transferValue = $channelCommAmt + $requestedValue;
						
						$input['channel_comm'] = $channelCommAmt;
					}else{
						$msg = array('type'=>'error','msg'=>'No calculation with amount yet in my web.');
						array_push($msgs,$msg);
						return Redirect::back()
					            ->withInput()
					            ->withErrors($validation)
					            ->with('msgs', $msgs);
					}
				}
				
				////////////end Game//////////////
			}else{
				$serviceTypeId = 'deposit';
			} 
					
			$currency = DB::table('c_currency')
                    ->where('status','=','active')
					->where('currency_id','=',$walletCurrencyId)
                    ->first();
				
			if($input['requested_value'] % $currency->multiple_of == 0)
			{
				$responsePayback2 = null;
				
	        	$staffId = Auth::user()->id;
				$input['staff__id'] = $staffId;
				$input['atr1_name'] = 'bank';
				$input['atr2_name'] = 'bankAccName';				
				$input['atr3_name'] = 'bankAccNum';
				$input['atr4_name'] = 'walletTypeId';
				$input['atr4_value'] = $walletTypeId;
				
				$input['service_type__id'] = $serviceTypeId;
				$input['status'] = 'TS';
				$input['request_gateway__id'] = 'web';
				$input['transfer_value'] = $transferValue;
				
				$easyStockDid = StockConfiguration::$easyStockDid;
				$stock = WWallet::getWalletStockByCondition($easyStockDid,$walletCurrencyId);
				if($stock == null) {
					$msg = array('type'=>'error','msg'=>'Stock Wallet Not Found!');
					array_push($msgs,$msg);
					return Redirect::back()
				            ->withInput()
				            ->withErrors($validation)
				            ->with('msgs', $msgs);
				}
				
				if($requestedValue <= $stock->post_balance)
				{
					/////Action Deposit////////
					$input['atr2_value'] = $input['bank_acc_name_hidden'];
					
					$ob = TxnTransactionHeader::create($input);
						
					$walletPayee = WWallet::getWalletByCondition($dealerId, $walletTypeId, $walletCurrencyId);
					if($walletPayee == null) {
						$msg = array('type'=>'error','msg'=>'Dealer Wallet Not Found!');
						array_push($msgs,$msg);
						return Redirect::back()
					            ->withInput()
					            ->withErrors($validation)
					            ->with('msgs', $msgs);
					}
					$walletPayee->last_transaction__id = $ob->transaction_id;
					$walletPayee->last_transaction_service_type__id = $serviceTypeId;
					$walletPayee->last_transaction_on = $dateTime;
					$walletPayee->last_balance_credit = $transferValue;
					$walletPayee->last_balance_debit = 0;
					$walletPayee->prev_balance = $walletPayee->post_balance;
					$walletPayee->post_balance = $walletPayee->post_balance + $transferValue;
					$walletOb = $walletPayee->update();
					
					$txnTransactionItem = array(
						'transaction__id'=>$ob->transaction_id,
						'dealer__id'=>$dealerId,
						'wallet_id'=>$walletPayee->wallet_id,
						'requested_value'=>$requestedValue,
						'transfer_value'=>$transferValue,
						'prev_balance'=>$walletPayee->prev_balance,
						'post_balance'=>$walletPayee->post_balance,
						'user_direction'=>'payee'
					);
					$itemOb = TxnTransactionItem::create($txnTransactionItem);
					
					///Update stock/////
					$walletStock = ($stock->post_balance - $requestedValue);
					$stock->last_transaction__id = $ob->transaction_id;
					$stock->last_transaction_service_type__id = $serviceTypeId;
					$stock->last_transaction_on = $dateTime;
					$stock->last_balance_credit = 0;
					$stock->last_balance_debit = $requestedValue;
					$stock->prev_balance = $stock->post_balance;
					$stock->post_balance = $walletStock;
					$stockOb = $stock->update();
					
					$txnTransactionItemStock = array(
						'transaction__id'=>$ob->transaction_id,
						'dealer__id'=>$easyStockDid,
						'wallet_id'=>$stock->wallet_id,
						'requested_value'=>$requestedValue,
						'transfer_value'=>$requestedValue,
						'prev_balance'=>$stock->prev_balance,
						'post_balance'=>$stock->post_balance,
						'user_direction'=>'payer'
					);
					$itemStockOb = TxnTransactionItem::create($txnTransactionItemStock);
					///End Update stock/////
					
					
					
					//-----------------2 Payback-------------------
					if($walletTypeId == 'game')
					{
						$balanceCredit = $walletPayee->balance_credit;
						if ($balanceCredit > 0) {
							
							$paybackAmountRequest = $balanceCredit;
							$txnHeader['atr1_value'] = 'system_auto';
							 
							$responsePayback2 = TxnTransactionHeader::operationPaybackGame($dealerId,$walletCurrencyId,$paybackAmountRequest,$remark,$dateTime,$txnHeader);
							if ($responsePayback2['status'] == 'T') {
								
							} else {
								$msgs = $responsePayback2['msgs'];
							
								return Redirect::back()
						            ->withInput()
						            ->with('msgs', $msgs);
							}
						}
					}

					$reason = '';
					if($walletTypeId == 'game') {
						if ($responsePayback2 != null) {
							$reason = ' After Deposit Game, System Auto Payback. Payback Txn Id = '.$responsePayback2['id'];
						}
					}
					
					$obLog = array(
						'staff__id'=>$staffId,
						'action'=>'deposit',
						'object_type'=>'deposit',
						'object__id'=>$ob->transaction_id,
						'new_data'=>json_encode($input),
						'reason'=>$reason
					);
					$obLog = Logs::create($obLog);
					//------------------------------------------------
					$getDealerSerial = Terminal::getDealerSerial($dealerId);
					$notificationsOb = null;
					if($getDealerSerial != null) {
						$data_array = array('amount' => (int) $requestedValue , 'commission' => (int) $channelCommAmt , 'pre_balance' => (int) $walletPayee->prev_balance , 'post_balance' => (int) $walletPayee->post_balance , 'timestamp' => $dateTime , 'dealer_id' => $dealerId , 'dealer_name'=> $getDealerSerial->name);
						$json = json_encode($data_array);
						$notificationsOb = DB::table('notifications')->insert(
						    array('dealer__id' => $dealerId,
						    'terminal__serial' => $getDealerSerial->serial,
						    'type' => $serviceTypeId,
						    'data' => $json,
						    'status' => 'f',
						    'created_at' => $dateTime)
						);
						if (!$notificationsOb) {
							DB::rollback();
							$msg['msg'] = 'Deposit Failed!';
							$msg['type'] = 'error';
							array_push($msgs,$msg);
							return Redirect::back()
					            ->withInput()
					            ->withErrors($validation)
					            ->with('msgs', $msgs);
						}
					}
					
					if( !$ob || !$obLog || !$walletOb || !$itemOb || !$stockOb || !$itemStockOb)
					{
					    DB::rollback();
						$msg = array();
						$msg['msg'] = 'Deposit Failed!';
						$msg['type'] = 'error';
						array_push($msgs,$msg);
						$input['atr2_value'] = Input::old('atr2_value');
					} else {
					    DB::commit();
						
						$msg = array();
						$msg['msg'] = 'Deposit Successfully To Dealer ID = '.$input['dealer__id'].', Wallet Type = '.$walletTypeId.', Deposit Step = '.$subsequent.', Request Amount = '.number_format($requestedValue,2).' '.$walletCurrencyId.' , Commission Percentage = '.$channelCommPct.'%'.', Commission Amount = '.number_format($channelCommAmt,2).' '.$walletCurrencyId.', Transfer Amount = '.number_format($transferValue,2).' '.$walletCurrencyId;
						$msg['type'] = 'success';
						array_push($msgs,$msg);
						
						if ($responsePayback2 != null) {
							$msg8 = array('type'=>'success','msg'=>'System Auto Payback! amount payback = '.number_format($responsePayback2['amount_payback'],2).' '.$walletCurrencyId.', Amount Debit From Post Balance = '.number_format($responsePayback2['amount_debit_from_balance'],2).' '.$walletCurrencyId.', Txn ID = '.$responsePayback2['id']);
							array_push($msgs,$msg8);
						}

						if($notificationsOb != null){
							$msg9 = array('type'=>'success','msg'=>'System Add Auto Notification to Terminal Serial = '.$getDealerSerial->serial);
							array_push($msgs,$msg9);	
						}
						return Redirect::back()
					            ->with('msgs', $msgs);
					}
					//$msgs = array($msg);
					////End Action Deposit/////
				
				}else{
					$msg = array('type'=>'error','msg'=>'Stock Amount not enough = '.$stock->post_balance);
					array_push($msgs,$msg);	
				}
								
				
			}else{
				
				$msg = array('type'=>'error','msg'=>'Deposit Amount is not multiple of currency!');
				array_push($msgs,$msg);
			}		
		}
		
		return Redirect::back()
            ->withInput()
            ->withErrors($validation)
            ->with('msgs', $msgs);
	}*/
	
	public function dealerRefund()
	{
		$dealers = DB::table('dealer')
                    ->where('status','<>','inactive')
					->whereIn('dealer_type__id', array(1, 2, 3, 4))
                    ->get();
					
		$banks = DB::table('bank')
                      ->whereNotIn('bid', array( 2, 7 ))
                      ->lists('bank_name','bid');	

		$this->layout->content = View::make('finance.dealer-refund', compact('dealers','banks'));
	}

	public function storeDealerRefund()
	{	
		DB::beginTransaction();
		
		$input = Input::all();
		$rules = array(
		    'dealer__id' => 'required',		    
		    'atr1_value' => 'required',
		    'remark' => 'max:255|required'
		);
		
        $validation = Validator::make($input, $rules);
		$result = null;
		$msgs = array();
		if ($validation->passes())
        {
        	try {
	        	$dealerId = $input['dealer__id'];
				$dateTime = date("Y-m-d H:i:s");
				$haveWalletToRefund = WWallet::getWalletToRefund($dealerId);
				
				$staffId = Auth::user()->id;
				$input['staff__id'] = $staffId;
				$input['atr1_name'] = 'bank';
				$input['atr2_name'] = 'bankAccName';				
				$input['atr3_name'] = 'bankAccNum';
				
				$input['status'] = 'TS';
				$input['request_gateway__id'] = 'web';
				$remark = $input['remark'];
				
				$channelCommPct = 0;
				$commission = 0;
				$k = 0;
				foreach ($haveWalletToRefund as $wallet) {
					$responsePayback2 = null;
					
					$postBalance = $wallet->post_balance;
					$currency = $wallet->wallet_currency__id;
					if ($postBalance <= 0) {
						$msg = array();
						$msg['msg'] = 'No refund to Wallet Type = '.$wallet->wallet_type__id.' , Curremcy = '.$currency.' , Amount = '.$postBalance.' '.$currency;
						$msg['type'] = 'error';
						array_push($msgs,$msg);
						continue;
					}
					$transferValue = $postBalance;
					$walletTypeId = $wallet->wallet_type__id;
					if($walletTypeId == 'game'){
						
						$input['transfer_value'] = $postBalance;
						//////webservice///////////
						$walletCurrencyId = 'KHR';
						$serviceTypeId = 'refund_game';
						$input['service_type__id'] = $serviceTypeId;					
						
						$client = new SoapClient(Configuration::$wsdlWebService);	
						$result = $client->getServiceChargeRange(array('dealerId'=>$dealerId, 'appId'=>'easy','serviceType'=>$serviceTypeId,'requestGateWay'=>'web','currency'=>$walletCurrencyId,'amount'=>$postBalance));
						
						//////end webservice///////////
				
						if ($result->return->status == 'F') {    
							$msg = array('type'=>'error','msg'=>$result->return->message);
							array_push($msgs,$msg);
							return Redirect::back()
						            ->withInput()
						            ->withErrors($validation)
						            ->with('msgs', $msgs);
						} else if ($result->return->status == 'S') {
							$channelCommPct = $result->return->scRangeDTO->scRange->channelCommPct;
							if($result->return->scRangeDTO->scRange->pctOrAmt == 'pct'){
								$channelCommPct = $result->return->scRangeDTO->scRange->channelCommPct;
								$transferValue = ($postBalance * 100) / (100 - $channelCommPct); 
								$commission = $postBalance - $transferValue;
								$input['channel_comm'] = -$commission;
								$input['service_charge_rule_id'] = $result->return->scRangeDTO->serviceChargeRuleId;
								$input['service_charge_set_version'] = $result->return->scRangeDTO->version;
								$input['service_charge_set_rang_id'] = $result->return->scRangeDTO->scRange->setRangeId;
								
								
								//-----------------2 Payback-------------------
								if($walletTypeId == 'game')
								{
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
											$msg['msg'] = 'No refund to Wallet Type = '.$wallet->wallet_type__id.' , Curremcy = '.$currency.' , Because Post Balance = '.$postBalance.' > Balance Credit ('.$balanceCredit.') + Comm('.-$channelCommAmt.')';
											$msg['type'] = 'error';
											array_push($msgs,$msg);
											continue;
										}
										 
										$responsePayback2 = TxnTransactionHeader::operationPaybackGame($dealerId,$currency,$paybackAmountRequest,$remark,$dateTime,$txnHeader);
										if ($responsePayback2['status'] == 'T') {
											$k++;
											
											$input['atr7_name'] = 'hasPaybackTxn';
											$input['atr7_value'] = $responsePayback2['id'];
											
											$msg = array('type'=>'success','msg'=>'System Auto Payback! Wallet type = '.$wallet->wallet_type__id.' , Curremcy = '.$currency.' , Amount payback = '.number_format($responsePayback2['amount_payback'],2).' '.$currency.', Amount Debit From Post Balance = '.number_format($responsePayback2['amount_debit_from_balance'],2).' '.$currency.'. Txn ID = '.$responsePayback2['id']);
											array_push($msgs,$msg);
										} else {
											$msgs = $responsePayback2['msgs'];
										
											return Redirect::back()
									            ->withInput()
									            ->with('msgs', $msgs);
										}
									}
								}
								//------------------------------------------------
						
							}else{
								$msg = array('type'=>'error','msg'=>'No calculation with amount yet in my web.');
								array_push($msgs,$msg);
								return Redirect::back()
							            ->withInput()
							            ->withErrors($validation)
							            ->with('msgs', $msgs);
							}
						}
					}else{				
						//------------Dealer Wallet--------------------
						$serviceTypeId =  'refund';
						//------------end Update wallets----------------
					}
					
					//----------------Check is after payback?-----------
					if($walletTypeId == 'game'){
						$balanceCredit = $wallet->balance_credit;
						if ($balanceCredit > 0) {
							$wallet = WWallet::getWalletById($wallet->wallet_id);
							
							$postBalance = $wallet->post_balance;
							$currency = $wallet->wallet_currency__id;
							if ($postBalance <= 0) {
								$msg = array();
								$msg['msg'] = 'No refund to Wallet Type = '.$wallet->wallet_type__id.' , Curremcy = '.$currency.' , Amount = '.$postBalance.' '.$currency;
								$msg['type'] = 'error';
								array_push($msgs,$msg);
								continue;
							}
												
							$channelCommPct = $result->return->scRangeDTO->scRange->channelCommPct;
							$transferValue = ($postBalance * 100) / (100 - $channelCommPct); 
							$commission = $postBalance - $transferValue;
							$input['channel_comm'] = -$commission;
								
								
						}
					}
					$k++;
					//------------Txn Header------------------------
					$input['tcy_currency_id'] = $wallet->wallet_currency__id;
					$input['atr2_value'] = $input['bank_acc_name_hidden'];
					$input['service_type__id'] = $serviceTypeId;
					$input['requested_value'] = $postBalance;
					$input['transfer_value'] = $transferValue;
					
					$ob = TxnTransactionHeader::create($input);
					$this->checkProcessRefound($ob);
					//------------Update wallets-------------------------------
					$wallet->last_transaction__id = $ob->transaction_id;
					$wallet->last_transaction_service_type__id = $serviceTypeId;
					$wallet->last_transaction_on = $dateTime;
					$wallet->last_balance_credit = 0;
					$wallet->last_balance_debit = $postBalance;
					$wallet->prev_balance = $wallet->post_balance;
					$wallet->post_balance = $wallet->post_balance - $postBalance;
					$walletOb = $wallet->update();
					$this->checkProcessRefound($walletOb);
					//------------Txn Item---------------------------------
					$txnTransactionItem = array(
						'transaction__id'=>$ob->transaction_id,
						'dealer__id'=>$dealerId,
						'wallet_id'=>$wallet->wallet_id,
						'requested_value'=>$postBalance,
						'transfer_value'=>$postBalance,
						'prev_balance'=>$wallet->prev_balance,
						'post_balance'=>$wallet->post_balance,
						'user_direction'=>'payer'
					);
					$itemOb = TxnTransactionItem::create($txnTransactionItem);
					$this->checkProcessRefound($itemOb);
					
					///Update stock/////
					$easyStockDid = StockConfiguration::$easyStockDid;
					$walletCurrencyId = $wallet->wallet_currency__id;
					$stock = WWallet::getWalletStockByCondition($easyStockDid,$walletCurrencyId);
					if($stock == null) {
						$msg = array('type'=>'error','msg'=>'Stock Dealer Wallet Not Found!');
						array_push($msgs,$msg);
						return Redirect::back()
					            ->withInput()
					            ->withErrors($validation)
					            ->with('msgs', $msgs);
					}
					
					$stock->last_transaction__id = $ob->transaction_id;
					$stock->last_transaction_service_type__id = $serviceTypeId;
					$stock->last_transaction_on = $dateTime;
					$stock->last_balance_credit = $postBalance;
					$stock->last_balance_debit = 0;
					$stock->prev_balance = $stock->post_balance;
					$stock->post_balance = $stock->post_balance+$postBalance;
					$stockOb = $stock->update();
					$this->checkProcessRefound($stockOb);
					
					$txnTransactionItemStock = array(
						'transaction__id'=>$ob->transaction_id,
						'dealer__id'=>$easyStockDid,
						'wallet_id'=>$stock->wallet_id,
						'requested_value'=>$postBalance,
						'transfer_value'=>$postBalance,
						'prev_balance'=>$stock->prev_balance,
						'post_balance'=>$stock->post_balance,
						'user_direction'=>'payee'
					);
					$itemStockOb = TxnTransactionItem::create($txnTransactionItemStock);
					$this->checkProcessRefound($itemStockOb);
					///End Update stock/////
					
					$reason = '';
					if($walletTypeId == 'game') {
						if ($responsePayback2 != null) {
							$reason = ' Before Refund Game, System Auto Payback. Payback Txn Id = '.$responsePayback2['id'];
							unset($input['atr7_name']);
							unset($input['atr7_value']);
						}
					}
					$obLog = array(
						'staff__id'=>$staffId,
						'action'=>'refund',
						'object_type'=>'refund',
						'object__id'=>$ob->transaction_id,
						'new_data'=>json_encode($input),
						'reason'=>$reason
					);
					$obLog = Logs::create($obLog);
					$this->checkProcessRefound($obLog);
					if ($wallet->wallet_type__id == 'os') {
						$msg = array('type'=>'success','msg'=>'Refun! Wallet type = '.$wallet->wallet_type__id.' , Curremcy = '.$currency.' , Refound Amount = '.number_format($postBalance,2).' '.$currency.' , '. 'Transfer Amount = '.number_format($transferValue,2).' '.$currency);
					} else if ($wallet->wallet_type__id == 'game') {
						$msg = array('type'=>'success','msg'=>'Refun! Wallet type = '.$wallet->wallet_type__id.' , Curremcy = '.$currency.' , Refound Amount = '.number_format($postBalance,2).' '.$currency.' , Channel Commmission Pct = '.$channelCommPct.' % '. ', Channel Commmission = -'.number_format($commission,2).' '.$currency.' ,  Transfer Amount = '.number_format($transferValue,2).' '.$currency);
					}
					
					array_push($msgs,$msg);
					
				}
				////Action Refund/////
				
				$msg = array();
				if($k>0){
					DB::commit();
					$msg['msg'] = 'Refund Successfully To Dealer ID = '.$input['dealer__id'];
					$msg['type'] = 'success';
					array_unshift($msgs,$msg);
					return Redirect::back()->with('msgs', $msgs);
				}else{
					$msg['msg'] = 'Nothing to Refund To Dealer ID = '.$input['dealer__id'];
					$msg['type'] = 'error';
					array_unshift($msgs,$msg);
				}
				
				
			
			} catch (Exception $e) {
        		DB::rollback();
				$msg['msg'] = 'Refund was failed!'.$e;
				$msg['type'] = 'error';
				array_push($msgs,$msg);
        	}
        }
	
		return Redirect::back()
            ->withInput()
            ->withErrors($validation)
            ->with('msgs', $msgs);
			
	}

	/**
	 * Display a listing of the resource.
	 * GET /finance
	 *
	 * @return Response
	 */
	public function dealerPayback() {
		$dealers = DB::table('dealer')
                    ->where('status','<>','inactive')
					->whereIn('dealer_type__id', array(1, 2, 3))
                    ->get();
		
		$currencies = DB::table('c_currency')
                    ->where('status','=','active')
                    ->lists('currency_id','currency_id');		
					
		$wallet_types = DB::table('sys_enumeration')
                      ->where('enum_type__id','=','wallet_type')
                      ->lists('name','enum_id');	
					
		$this->layout->content = View::make('finance.payback', compact('dealers','wallet_types','currencies'));
		
	}
	
	/**
	 * Display a listing of the resource.
	 * POST /finance
	 *
	 * @return Response
	 */
	public function storeDealerPayback() {
		DB::beginTransaction();
		
		$input = Input::all();
		$rules = array(
		    'dealer__id' => 'required',
		    'wallet_type__id' => 'required',
		    'tcy_currency_id' => 'required',
		    'requested_value'=> 'required',
		    'remark' => 'max:255|required'
		);
		
        $validation = Validator::make($input, $rules);
		$result = null;
		$msgs = array();
		if ($validation->passes())
        {
        	try {
	        	$dealerId = $input['dealer__id'];
				$dateTime = date("Y-m-d H:i:s");
				$walletCurrencyId = $input['tcy_currency_id'];
				$paybackAmountRequest = $input['requested_value'];
				$walletTypeId = $input['wallet_type__id'];
				$remark = $input['remark'];
				$txnHeader['atr1_value'] = 'staff';
				
				$wallet = WWallet::getWalletByCondition ($dealerId, $walletTypeId, $walletCurrencyId);
				if ($wallet != null) {
					$balanceCredit = $wallet->balance_credit;
					if ($balanceCredit < $paybackAmountRequest) {
						$msg = array('type'=>'error','msg'=>'Dealer Id = '.$dealerId.', had balance credit = '.$balanceCredit.' '.$walletCurrencyId. ', but your request amount ='.$paybackAmountRequest.' '.$walletCurrencyId);
						array_push($msgs,$msg);
					} else {
						$responsePayback2 = TxnTransactionHeader::operationPaybackGame($dealerId,$walletCurrencyId,$paybackAmountRequest,$remark,$dateTime,$txnHeader);
						if ($responsePayback2['status'] == 'T') {
							$msg = array('type'=>'success','msg'=>'Payback Successfully! Wallet type = '.$wallet->wallet_type__id.' , Curremcy = '.$walletCurrencyId.' , Amount payback = '.number_format($responsePayback2['amount_payback'],2).' '.$walletCurrencyId.', Amount Debit From Post Balance = '.number_format($responsePayback2['amount_debit_from_balance'],2).' '.$walletCurrencyId.'. Txn ID = '.$responsePayback2['id']);
							array_push($msgs,$msg);
							DB::commit();
							return Redirect::back()->with('msgs', $msgs);
						} else {
							$msgs = $responsePayback2['msgs'];
						}
					}
				} else {
					$msg = array('type'=>'error','msg'=>'Wallet Not Found!');
					array_push($msgs,$msg);
				}
			
			} catch (Exception $e) {
        		DB::rollback();
				$msg['msg'] = 'Refund was failed!'.$e;
				$msg['type'] = 'error';
				array_push($msgs,$msg);
        	}
        }
	
		return Redirect::back()
            ->withInput()
            ->withErrors($validation)
            ->with('msgs', $msgs);
	}
	
	/**
	 * Display a listing of the resource.
	 * GET /finance
	 *
	 * @return Response
	 */
	public function index()
	{
		
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /finance/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /finance
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /finance/{id}
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
	 * GET /finance/{id}/edit
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
	 * PUT /finance/{id}
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
	 * DELETE /finance/{id}
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
	public function getDealerBanks($dealerId)
	{
		return Response::json(TxnTransactionHeader::getDealerBanks($dealerId));
	}
	
	/**
	 * Get a listing of the resource.
	 * GET /finances
	 *
	 * @return Response
	 */
	public function getDealerBankAccountNames($dealerId,$bankId)
	{
		return Response::json(TxnTransactionHeader::getDealerBankAccountNames($dealerId,$bankId));
	}
	
	public function getSubDealer($dealerId)
	{
		return Response::json(Dealer::getChildDealerByParentId($dealerId));
	}
	/**
	 * Get a listing of the resource.
	 * GET /location
	 *
	 * @return Response
	 */
	public function getDealerBankAccountNumber($dealerId,$bankId,$bankAccountName)
	{
		return Response::json(TxnTransactionHeader::getDealerBankAccountNumber($dealerId,$bankId,$bankAccountName));
	}
	
	/**
	 * Get a listing of the resource.
	 * GET /location
	 *
	 * @return Response
	 */
	public function getDealerWalletByCondition($dealerId,$walletType,$currencyId)
	{
		return Response::json(WWallet::getWalletByCondition($dealerId,$walletType,$currencyId));
	}
	
	/**
	 * Get a listing of the resource.
	 * GET /location
	 * 
	 *
	 * @return Response
	 */
	private function checkProcessRefound($ob)
	{
		if ($ob) {
			return true;
		} else {
			$msgs = array();
			$msg = array();
			$msg['msg'] = 'Deposit Failed!';
			$msg['type'] = 'error';
			array_push($msgs,$msg);
			return Redirect::back()
            ->withInput()
            ->withErrors($validation)
            ->with('msgs', $msgs);
		}
	}
	
	
	public function masterDepositToSubDealer()
	{
		$dealers = DB::table('dealer')
                    ->where('status','<>','inactive')
					->whereIn('dealer_type__id', array(2))
                    ->get();
					
		$sub_dealers = DB::table('dealer')
                    ->where('status','<>','inactive')
					->whereIn('dealer_type__id', array(4))
                    ->get();	

		$this->layout->content = View::make('finance.master-deposit-tosub-dealer', compact('dealers','sub_dealers'));
	}

	
	public function storeMasterDepositToSubDealer()
	{
			
		DB::beginTransaction();
		
		$input = Input::all();
		$rules = array(
		    'master_dealer__id' => 'required',
		    'sub_dealer__id' => 'required',
		    'wallet_type__id' => 'required',
		    'requested_value' => 'required',
		    'remark' => 'max:255 | required'
		);
		
        $validation = Validator::make($input, $rules);
		$msgs = array();
		$result = null;
		if ($validation->passes()) 
		{
			$dealerId = $input['sub_dealer__id'];
			$masterDealerId = $input['master_dealer__id'];
			$walletTypeId = $input['wallet_type__id'];
			$dateTime = date("Y-m-d H:i:s");
			$requestedValue = $input['requested_value'];
			$transferValue = $requestedValue;
			$walletCurrencyId = 'KHR';
			$serviceTypeId = 'master_deposit_game';
					
			$currency = DB::table('c_currency')
                    ->where('status','=','active')
					->where('currency_id','=',$walletCurrencyId)
                    ->first();
				
			if($input['requested_value'] % $currency->multiple_of == 0)
			{
	        	$staffId = Auth::user()->id;
				$input['staff__id'] = $staffId;
				$input['dealer__id'] = $dealerId;
				$input['atr4_name'] = 'walletTypeId';
				$input['atr4_value'] = $walletTypeId;
				$input['atr5_name'] = 'masterDealerId';
				$input['atr5_value'] = $masterDealerId;
				$input['tcy_currency_id'] = $walletCurrencyId;
				$input['service_type__id'] = $serviceTypeId;
				$input['status'] = 'TS';
				$input['request_gateway__id'] = 'web';
				$input['transfer_value'] = $transferValue;
				
				$checkmaster = WWallet::getMasterDealer($masterDealerId);
				if($checkmaster == null) {
					$msg00 = array('type'=>'error','msg'=>'Master Dealer Not Found!');
					array_push($msgs,$msg00);
					return Redirect::back()
				            ->withInput()
				            ->withErrors($validation)
				            ->with('msgs', $msgs);
				}

				$checksubdealer = WWallet::getSubDealer($masterDealerId,$dealerId);
				
				if($checksubdealer == null) {
					$msg01 = array('type'=>'error','msg'=>'Sub Dealer Not Found!');
					array_push($msgs,$msg01);
					return Redirect::back()
				            ->withInput()
				            ->withErrors($validation)
				            ->with('msgs', $msgs);
				}
				
				$stock = WWallet::getWalletByCondition ($masterDealerId, $walletTypeId, $walletCurrencyId);
				if($stock == null) {
					$msg = array('type'=>'error','msg'=>'Master Dealer Wallet Not Found!');
					array_push($msgs,$msg);
					return Redirect::back()
				            ->withInput()
				            ->withErrors($validation)
				            ->with('msgs', $msgs);
				}
				
				if(!empty($stock))
				{
					if($requestedValue <= $stock->post_balance)
					{
						
						
						
						/////Action Deposit////////
						/*$ob = TxnTransactionHeader::create($input);
							
						$walletPayee = WWallet::getWalletByCondition($dealerId, $walletTypeId, $walletCurrencyId);
						if($walletPayee == null) {
							$msg = array('type'=>'error','msg'=>'Dealer Wallet Not Found!');
							array_push($msgs,$msg);
							return Redirect::back()
						            ->withInput()
						            ->withErrors($validation)
						            ->with('msgs', $msgs);
						}
						$walletPayee->last_transaction__id = $ob->transaction_id;
						$walletPayee->last_transaction_service_type__id = $serviceTypeId;
						$walletPayee->last_transaction_on = $dateTime;
						$walletPayee->last_balance_credit = $transferValue;
						$walletPayee->last_balance_debit = 0;
						$walletPayee->prev_balance = $walletPayee->post_balance;
						$walletPayee->post_balance = $walletPayee->post_balance + $transferValue;
						$walletOb = $walletPayee->update();
						 
						$txnTransactionItem = array(
							'transaction__id'=>$ob->transaction_id,
							'dealer__id'=>$dealerId,
							'wallet_id'=>$walletPayee->wallet_id,
							'requested_value'=>$requestedValue,
							'transfer_value'=>$transferValue,
							'prev_balance'=>$walletPayee->prev_balance,
							'post_balance'=>$walletPayee->post_balance,
							'user_direction'=>'payee'
						);
						$itemOb = TxnTransactionItem::create($txnTransactionItem);
						
						///Update stock/////
						$walletStock = ($stock->post_balance - $requestedValue);
						$stock->last_transaction__id = $ob->transaction_id;
						$stock->last_transaction_service_type__id = $serviceTypeId;
						$stock->last_transaction_on = $dateTime;
						$stock->last_balance_credit = 0;
						$stock->last_balance_debit = $requestedValue;
						$stock->prev_balance = $stock->post_balance;
						$stock->post_balance = $walletStock;
						$stockOb = $stock->update();
						
						$txnTransactionItemStock = array(
							'transaction__id'=>$ob->transaction_id,
							'dealer__id'=>$masterDealerId,
							'wallet_id'=>$stock->wallet_id,
							'requested_value'=>$requestedValue,
							'transfer_value'=>$requestedValue,
							'prev_balance'=>$stock->prev_balance,
							'post_balance'=>$stock->post_balance,
							'user_direction'=>'payer'
						);
						$itemStockOb = TxnTransactionItem::create($txnTransactionItemStock);
						 **/
						///End Update stock/////
						
						$data = array(
							'did'=>$masterDealerId,
							'sid'=>$dealerId,
							'wallet_type'=>$walletTypeId,
							'currency'=>$walletCurrencyId,
							'staff__id'=>$staffId,
							'gateway'=>'web',
							'amount'=>$requestedValue,
							'action' => 'deposit'
						);
						
						
						$response = WebServiceCollection::masterProcessSub($data);
						if($response['status'] == "F")
						{
							$msgs = $response['msgs'];
							
							return Redirect::back()
						            ->withInput()
						            ->with('msgs', $msgs);
										
						}
				
						$txnId = $response['data']['trans_id'];
						$txn = DB::table('txn_transaction_header')->where('transaction_id',$txnId)->first();
						
						$obLog = array(
							'staff__id'=>$staffId,
							'action'=>'deposit',
							'object_type'=>'master_deposit',
							'object__id'=>$txnId,
							'new_data'=>json_encode($txn)
						);
						$obLog = Logs::create($obLog);
						
						
						if(!$obLog)
						{
						    DB::rollback();
							$msg = array();
							$msg['msg'] = 'Deposit Failed!';
							$msg['type'] = 'error';
							array_push($msgs,$msg);
							$input['atr2_value'] = Input::old('atr2_value');
						} else {
						    DB::commit();
							$msg = array();
							$msg['msg'] = 'Deposit Successfully To Sub Dealer ID = '.$dealerId;
							$msg['type'] = 'success';
							$msg2 = array('type'=>'success','msg'=>'Wallet Type = '.$walletTypeId);
							$msg3 = array('type'=>'success','msg'=>'Transfer Amount = '.$transferValue.' '.$walletCurrencyId);
							array_push($msgs,$msg);
							array_push($msgs,$msg2);
							array_push($msgs,$msg3);
						}
					
					}else{
						$msg = array('type'=>'error','msg'=>'Master Dealer Wallet Amount not enough = '.$stock->post_balance);
						array_push($msgs,$msg);	
					}
				}else{
					$msg = array('type'=>'error','msg'=>'Master Dealer Wallet Not Found!');
					array_push($msgs,$msg);
					return Redirect::back()
				            ->withInput()
				            ->withErrors($validation)
				            ->with('msgs', $msgs);
				}	
								
				
			}else{
				
				$msg = array('type'=>'error','msg'=>'Deposit Amount is not multiple of currency!');
				array_push($msgs,$msg);
			}		
		}
		
		return Redirect::back()
            ->withInput()
            ->withErrors($validation)
            ->with('msgs', $msgs);
		
	}

	/*
	 * 
	 * 
	 */ 
	 /*public function storeMasterDepositToSubDealer()
	{
			
		DB::beginTransaction();
		
		$input = Input::all();
		$rules = array(
		    'master_dealer__id' => 'required',
		    'sub_dealer__id' => 'required',
		    'wallet_type__id' => 'required',
		    'requested_value' => 'required',
		    'remark' => 'max:255 | required'
		);
		
        $validation = Validator::make($input, $rules);
		$msgs = array();
		$result = null;
		if ($validation->passes()) 
		{
			$dealerId = $input['sub_dealer__id'];
			$masterDealerId = $input['master_dealer__id'];
			$walletTypeId = $input['wallet_type__id'];
			$dateTime = date("Y-m-d H:i:s");
			$requestedValue = $input['requested_value'];
			$transferValue = $requestedValue;
			$walletCurrencyId = 'KHR';
			$serviceTypeId = 'master_deposit_game';
					
			$currency = DB::table('c_currency')
                    ->where('status','=','active')
					->where('currency_id','=',$walletCurrencyId)
                    ->first();
				
			if($input['requested_value'] % $currency->multiple_of == 0)
			{
	        	$staffId = Auth::user()->id;
				$input['staff__id'] = $staffId;
				$input['dealer__id'] = $dealerId;
				$input['atr4_name'] = 'walletTypeId';
				$input['atr4_value'] = $walletTypeId;
				$input['atr5_name'] = 'masterDealerId';
				$input['atr5_value'] = $masterDealerId;
				$input['tcy_currency_id'] = $walletCurrencyId;
				$input['service_type__id'] = $serviceTypeId;
				$input['status'] = 'TS';
				$input['request_gateway__id'] = 'web';
				$input['transfer_value'] = $transferValue;
				
				$checkmaster = WWallet::getMasterDealer($masterDealerId);
				if($checkmaster == null) {
					$msg00 = array('type'=>'error','msg'=>'Master Dealer Not Found!');
					array_push($msgs,$msg00);
					return Redirect::back()
				            ->withInput()
				            ->withErrors($validation)
				            ->with('msgs', $msgs);
				}

				$checksubdealer = WWallet::getSubDealer($masterDealerId,$dealerId);
				
				if($checksubdealer == null) {
					$msg01 = array('type'=>'error','msg'=>'Sub Dealer Not Found!');
					array_push($msgs,$msg01);
					return Redirect::back()
				            ->withInput()
				            ->withErrors($validation)
				            ->with('msgs', $msgs);
				}
				
				$stock = WWallet::getWalletByCondition ($masterDealerId, $walletTypeId, $walletCurrencyId);
				if($stock == null) {
					$msg = array('type'=>'error','msg'=>'Master Dealer Wallet Not Found!');
					array_push($msgs,$msg);
					return Redirect::back()
				            ->withInput()
				            ->withErrors($validation)
				            ->with('msgs', $msgs);
				}
				
				if(!empty($stock))
				{
					if($requestedValue <= $stock->post_balance)
					{
						/////Action Deposit////////
						$ob = TxnTransactionHeader::create($input);
							
						$walletPayee = WWallet::getWalletByCondition($dealerId, $walletTypeId, $walletCurrencyId);
						if($walletPayee == null) {
							$msg = array('type'=>'error','msg'=>'Dealer Wallet Not Found!');
							array_push($msgs,$msg);
							return Redirect::back()
						            ->withInput()
						            ->withErrors($validation)
						            ->with('msgs', $msgs);
						}
						$walletPayee->last_transaction__id = $ob->transaction_id;
						$walletPayee->last_transaction_service_type__id = $serviceTypeId;
						$walletPayee->last_transaction_on = $dateTime;
						$walletPayee->last_balance_credit = $transferValue;
						$walletPayee->last_balance_debit = 0;
						$walletPayee->prev_balance = $walletPayee->post_balance;
						$walletPayee->post_balance = $walletPayee->post_balance + $transferValue;
						$walletOb = $walletPayee->update();
						 
						$txnTransactionItem = array(
							'transaction__id'=>$ob->transaction_id,
							'dealer__id'=>$dealerId,
							'wallet_id'=>$walletPayee->wallet_id,
							'requested_value'=>$requestedValue,
							'transfer_value'=>$transferValue,
							'prev_balance'=>$walletPayee->prev_balance,
							'post_balance'=>$walletPayee->post_balance,
							'user_direction'=>'payee'
						);
						$itemOb = TxnTransactionItem::create($txnTransactionItem);
						
						///Update stock/////
						$walletStock = ($stock->post_balance - $requestedValue);
						$stock->last_transaction__id = $ob->transaction_id;
						$stock->last_transaction_service_type__id = $serviceTypeId;
						$stock->last_transaction_on = $dateTime;
						$stock->last_balance_credit = 0;
						$stock->last_balance_debit = $requestedValue;
						$stock->prev_balance = $stock->post_balance;
						$stock->post_balance = $walletStock;
						$stockOb = $stock->update();
						
						$txnTransactionItemStock = array(
							'transaction__id'=>$ob->transaction_id,
							'dealer__id'=>$masterDealerId,
							'wallet_id'=>$stock->wallet_id,
							'requested_value'=>$requestedValue,
							'transfer_value'=>$requestedValue,
							'prev_balance'=>$stock->prev_balance,
							'post_balance'=>$stock->post_balance,
							'user_direction'=>'payer'
						);
						$itemStockOb = TxnTransactionItem::create($txnTransactionItemStock);
						///End Update stock/////
						
						$obLog = array(
							'staff__id'=>$staffId,
							'action'=>'deposit',
							'object_type'=>'master_deposit',
							'object__id'=>$ob->transaction_id,
							'new_data'=>json_encode($input)
						);
						$obLog = Logs::create($obLog);
						
						
						if( !$ob || !$obLog || !$walletOb || !$itemOb || !$stockOb || !$itemStockOb)
						{
						    DB::rollback();
							$msg = array();
							$msg['msg'] = 'Deposit Failed!';
							$msg['type'] = 'error';
							array_push($msgs,$msg);
							$input['atr2_value'] = Input::old('atr2_value');
						} else {
						    DB::commit();
							$msg = array();
							$msg['msg'] = 'Deposit Successfully To Sub Dealer ID = '.$dealerId;
							$msg['type'] = 'success';
							$msg2 = array('type'=>'success','msg'=>'Wallet Type = '.$walletTypeId);
							$msg3 = array('type'=>'success','msg'=>'Transfer Amount = '.$transferValue.' '.$walletCurrencyId);
							array_push($msgs,$msg);
							array_push($msgs,$msg2);
							array_push($msgs,$msg3);
						}
						//$msgs = array($msg);
						////End Action Deposit/////
					
					}else{
						$msg = array('type'=>'error','msg'=>'Master Dealer Wallet Amount not enough = '.$stock->post_balance);
						array_push($msgs,$msg);	
					}
				}else{
					$msg = array('type'=>'error','msg'=>'Master Dealer Wallet Not Found!');
					array_push($msgs,$msg);
					return Redirect::back()
				            ->withInput()
				            ->withErrors($validation)
				            ->with('msgs', $msgs);
				}	
								
				
			}else{
				
				$msg = array('type'=>'error','msg'=>'Deposit Amount is not multiple of currency!');
				array_push($msgs,$msg);
			}		
		}
		
		return Redirect::back()
            ->withInput()
            ->withErrors($validation)
            ->with('msgs', $msgs);
		
	}
	*/
	
	///MasterWithdrawFromSubDealer///
	public function masterWithdrawFromSubDealer()
	{
		$dealers = DB::table('dealer')
                    ->where('status','<>','inactive')
					->whereIn('dealer_type__id', array(2))
                    ->get();
					
		$sub_dealers = DB::table('dealer')
                    ->where('status','<>','inactive')
					->whereIn('dealer_type__id', array(4))
                    ->get();	

		$this->layout->content = View::make('finance.master-withdraw-fromsub-dealer', compact('dealers','sub_dealers'));
	}
	
	public function storeMasterWithdrawFromSubDealer()
	{
			
		DB::beginTransaction();
		
		$input = Input::all();
		$rules = array(
		    'master_dealer__id' => 'required',
		    'sub_dealer__id' => 'required',
		    'wallet_type__id' => 'required',
		    'requested_value' => 'required',
		    'remark' => 'max:255 | required'
		);
		
        $validation = Validator::make($input, $rules);
		$msgs = array();
		$result = null;
		if ($validation->passes()) 
		{
			$dealerId = $input['sub_dealer__id'];
			$masterDealerId = $input['master_dealer__id'];
			$walletTypeId = $input['wallet_type__id'];
			$dateTime = date("Y-m-d H:i:s");
			$requestedValue = $input['requested_value'];
			$transferValue = $requestedValue;
			$walletCurrencyId = 'KHR';
			$serviceTypeId = 'master_withdraw_game';
					
			$currency = DB::table('c_currency')
                    ->where('status','=','active')
					->where('currency_id','=',$walletCurrencyId)
                    ->first();
				
			if($input['requested_value'] % $currency->multiple_of == 0)
			{
	        	$staffId = Auth::user()->id;
				
				$checkmaster = WWallet::getMasterDealer($masterDealerId);
				if($checkmaster == null) {
					$msg00 = array('type'=>'error','msg'=>'Master Dealer Not Found!');
					array_push($msgs,$msg00);
					return Redirect::back()
				            ->withInput()
				            ->withErrors($validation)
				            ->with('msgs', $msgs);
				}

				$checksubdealer = WWallet::getSubDealer($masterDealerId,$dealerId);
				
				if($checksubdealer == null) {
					$msg01 = array('type'=>'error','msg'=>'Sub Dealer Not Found!');
					array_push($msgs,$msg01);
					return Redirect::back()
				            ->withInput()
				            ->withErrors($validation)
				            ->with('msgs', $msgs);
				}
				
				$stock = WWallet::getWalletByCondition ($masterDealerId, $walletTypeId, $walletCurrencyId);
				if($stock == null) {
					$msg02 = array('type'=>'error','msg'=>'Master Dealer Wallet Not Found!');
					array_push($msgs,$msg02);
					return Redirect::back()
				            ->withInput()
				            ->withErrors($validation)
				            ->with('msgs', $msgs);
				}
				
				$walletPayee = WWallet::getWalletByCondition($dealerId, $walletTypeId, $walletCurrencyId);
				if($walletPayee == null) {
					$msg03 = array('type'=>'error','msg'=>'Sub Dealer Wallet Not Found!');
					array_push($msgs,$msg03);
					return Redirect::back()
				            ->withInput()
				            ->withErrors($validation)
				            ->with('msgs', $msgs);
				}
						
				if(!empty($walletPayee))
				{
					if($requestedValue <= $walletPayee->post_balance)
					{
						/////Action Deposit////////
						/*$ob = TxnTransactionHeader::create($input);
						$walletPayee->last_transaction__id = $ob->transaction_id;
						$walletPayee->last_transaction_service_type__id = $serviceTypeId;
						$walletPayee->last_transaction_on = $dateTime;
						$walletPayee->last_balance_credit = 0;
						$walletPayee->last_balance_debit = $transferValue;
						$walletPayee->prev_balance = $walletPayee->post_balance;
						$walletPayee->post_balance = $walletPayee->post_balance - $transferValue;
						$walletOb = $walletPayee->update();
						 
						$txnTransactionItem = array(
							'transaction__id'=>$ob->transaction_id,
							'dealer__id'=>$dealerId,
							'wallet_id'=>$walletPayee->wallet_id,
							'requested_value'=>$requestedValue,
							'transfer_value'=>$transferValue,
							'prev_balance'=>$walletPayee->prev_balance,
							'post_balance'=>$walletPayee->post_balance,
							'user_direction'=>'payer'
						);
						$itemOb = TxnTransactionItem::create($txnTransactionItem);
						
						///Update stock/////
						$walletStock = ($stock->post_balance + $requestedValue);
						$stock->last_transaction__id = $ob->transaction_id;
						$stock->last_transaction_service_type__id = $serviceTypeId;
						$stock->last_transaction_on = $dateTime;
						$stock->last_balance_credit = $requestedValue;
						$stock->last_balance_debit = 0;
						$stock->prev_balance = $stock->post_balance;
						$stock->post_balance = $walletStock;
						$stockOb = $stock->update();
						
						$txnTransactionItemStock = array(
							'transaction__id'=>$ob->transaction_id,
							'dealer__id'=>$masterDealerId,
							'wallet_id'=>$stock->wallet_id,
							'requested_value'=>$requestedValue,
							'transfer_value'=>$requestedValue,
							'prev_balance'=>$stock->prev_balance,
							'post_balance'=>$stock->post_balance,
							'user_direction'=>'payee'
						);
						$itemStockOb = TxnTransactionItem::create($txnTransactionItemStock);
						///End Update stock/////
						*/
						
						$data = array(
							'did'=>$masterDealerId,
							'sid'=>$dealerId,
							'wallet_type'=>$walletTypeId,
							'currency'=>$walletCurrencyId,
							'staff__id'=>$staffId,
							'gateway'=>'web',
							'amount'=>$requestedValue,
							'action' => 'withdraw'
						);
						
						
						$response = WebServiceCollection::masterProcessSub($data);
						if($response['status'] == "F")
						{
							$msgs = $response['msgs'];
							
							return Redirect::back()
						            ->withInput()
						            ->with('msgs', $msgs);
										
						}
						
						$txnId = $response['data']['trans_id'];
						$txn = DB::table('txn_transaction_header')->where('transaction_id',$txnId)->first();
						$obLog = array(
							'staff__id'=>$staffId,
							'action'=>'withdraw',
							'object_type'=>'master_withdraw',
							'object__id'=>$txnId,
							'new_data'=>json_encode($txn)
						);
						$obLog = Logs::create($obLog);
						
						
						if( !$obLog)
						{
						    DB::rollback();
							$msg = array();
							$msg['msg'] = 'Withdraw Failed!';
							$msg['type'] = 'error';
							array_push($msgs,$msg);
							$input['atr2_value'] = Input::old('atr2_value');
						} else {
						    DB::commit();
							$msg = array();
							$msg['msg'] = 'Withdraw Successfully To Master Dealer ID = '.$masterDealerId;
							$msg['type'] = 'success';
							$msg2 = array('type'=>'success','msg'=>'Wallet Type = '.$walletTypeId);
							$msg3 = array('type'=>'success','msg'=>'Transfer Amount = '.$transferValue.' '.$walletCurrencyId);
							array_push($msgs,$msg);
							array_push($msgs,$msg2);
							array_push($msgs,$msg3);
						}
						
					
					}else{
						$msg = array('type'=>'error','msg'=>'Sub Dealer Wallet Amount not enough = '.$walletPayee->post_balance);
						array_push($msgs,$msg);	
					}
				}else{
					$msg = array('type'=>'error','msg'=>'Sub Dealer Wallet Not Found!');
					array_push($msgs,$msg);
					return Redirect::back()
				            ->withInput()
				            ->withErrors($validation)
				            ->with('msgs', $msgs);
				}	
								
				
			}else{
				
				$msg = array('type'=>'error','msg'=>'Deposit Amount is not multiple of currency!');
				array_push($msgs,$msg);
			}		
		}
		
		return Redirect::back()
            ->withInput()
            ->withErrors($validation)
            ->with('msgs', $msgs);
		
	}

	/*public function storeMasterWithdrawFromSubDealer()
	{
			
		DB::beginTransaction();
		
		$input = Input::all();
		$rules = array(
		    'master_dealer__id' => 'required',
		    'sub_dealer__id' => 'required',
		    'wallet_type__id' => 'required',
		    'requested_value' => 'required',
		    'remark' => 'max:255 | required'
		);
		
        $validation = Validator::make($input, $rules);
		$msgs = array();
		$result = null;
		if ($validation->passes()) 
		{
			$dealerId = $input['sub_dealer__id'];
			$masterDealerId = $input['master_dealer__id'];
			$walletTypeId = $input['wallet_type__id'];
			$dateTime = date("Y-m-d H:i:s");
			$requestedValue = $input['requested_value'];
			$transferValue = $requestedValue;
			$walletCurrencyId = 'KHR';
			$serviceTypeId = 'master_withdraw_game';
					
			$currency = DB::table('c_currency')
                    ->where('status','=','active')
					->where('currency_id','=',$walletCurrencyId)
                    ->first();
				
			if($input['requested_value'] % $currency->multiple_of == 0)
			{
	        	$staffId = Auth::user()->id;
				$input['staff__id'] = $staffId;
				$input['dealer__id'] = $dealerId;
				$input['atr4_name'] = 'walletTypeId';
				$input['atr4_value'] = $walletTypeId;
				$input['atr5_name'] = 'masterDealerId';
				$input['atr5_value'] = $masterDealerId;
				$input['tcy_currency_id'] = $walletCurrencyId;
				$input['service_type__id'] = $serviceTypeId;
				$input['status'] = 'TS';
				$input['request_gateway__id'] = 'web';
				$input['transfer_value'] = $transferValue;
				
				$checkmaster = WWallet::getMasterDealer($masterDealerId);
				if($checkmaster == null) {
					$msg00 = array('type'=>'error','msg'=>'Master Dealer Not Found!');
					array_push($msgs,$msg00);
					return Redirect::back()
				            ->withInput()
				            ->withErrors($validation)
				            ->with('msgs', $msgs);
				}

				$checksubdealer = WWallet::getSubDealer($masterDealerId,$dealerId);
				
				if($checksubdealer == null) {
					$msg01 = array('type'=>'error','msg'=>'Sub Dealer Not Found!');
					array_push($msgs,$msg01);
					return Redirect::back()
				            ->withInput()
				            ->withErrors($validation)
				            ->with('msgs', $msgs);
				}
				
				$stock = WWallet::getWalletByCondition ($masterDealerId, $walletTypeId, $walletCurrencyId);
				if($stock == null) {
					$msg02 = array('type'=>'error','msg'=>'Master Dealer Wallet Not Found!');
					array_push($msgs,$msg02);
					return Redirect::back()
				            ->withInput()
				            ->withErrors($validation)
				            ->with('msgs', $msgs);
				}
				
				$walletPayee = WWallet::getWalletByCondition($dealerId, $walletTypeId, $walletCurrencyId);
				if($walletPayee == null) {
					$msg03 = array('type'=>'error','msg'=>'Sub Dealer Wallet Not Found!');
					array_push($msgs,$msg03);
					return Redirect::back()
				            ->withInput()
				            ->withErrors($validation)
				            ->with('msgs', $msgs);
				}
						
				if(!empty($walletPayee))
				{
					if($requestedValue <= $walletPayee->post_balance)
					{
						/////Action Deposit////////
						$ob = TxnTransactionHeader::create($input);
						$walletPayee->last_transaction__id = $ob->transaction_id;
						$walletPayee->last_transaction_service_type__id = $serviceTypeId;
						$walletPayee->last_transaction_on = $dateTime;
						$walletPayee->last_balance_credit = 0;
						$walletPayee->last_balance_debit = $transferValue;
						$walletPayee->prev_balance = $walletPayee->post_balance;
						$walletPayee->post_balance = $walletPayee->post_balance - $transferValue;
						$walletOb = $walletPayee->update();
						 
						$txnTransactionItem = array(
							'transaction__id'=>$ob->transaction_id,
							'dealer__id'=>$dealerId,
							'wallet_id'=>$walletPayee->wallet_id,
							'requested_value'=>$requestedValue,
							'transfer_value'=>$transferValue,
							'prev_balance'=>$walletPayee->prev_balance,
							'post_balance'=>$walletPayee->post_balance,
							'user_direction'=>'payer'
						);
						$itemOb = TxnTransactionItem::create($txnTransactionItem);
						
						///Update stock/////
						$walletStock = ($stock->post_balance + $requestedValue);
						$stock->last_transaction__id = $ob->transaction_id;
						$stock->last_transaction_service_type__id = $serviceTypeId;
						$stock->last_transaction_on = $dateTime;
						$stock->last_balance_credit = $requestedValue;
						$stock->last_balance_debit = 0;
						$stock->prev_balance = $stock->post_balance;
						$stock->post_balance = $walletStock;
						$stockOb = $stock->update();
						
						$txnTransactionItemStock = array(
							'transaction__id'=>$ob->transaction_id,
							'dealer__id'=>$masterDealerId,
							'wallet_id'=>$stock->wallet_id,
							'requested_value'=>$requestedValue,
							'transfer_value'=>$requestedValue,
							'prev_balance'=>$stock->prev_balance,
							'post_balance'=>$stock->post_balance,
							'user_direction'=>'payee'
						);
						$itemStockOb = TxnTransactionItem::create($txnTransactionItemStock);
						///End Update stock/////
						
						$obLog = array(
							'staff__id'=>$staffId,
							'action'=>'withdraw',
							'object_type'=>'master_withdraw',
							'object__id'=>$ob->transaction_id,
							'new_data'=>json_encode($input)
						);
						$obLog = Logs::create($obLog);
						
						
						if( !$ob || !$obLog || !$walletOb || !$itemOb || !$stockOb || !$itemStockOb)
						{
						    DB::rollback();
							$msg = array();
							$msg['msg'] = 'Withdraw Failed!';
							$msg['type'] = 'error';
							array_push($msgs,$msg);
							$input['atr2_value'] = Input::old('atr2_value');
						} else {
						    DB::commit();
							$msg = array();
							$msg['msg'] = 'Withdraw Successfully To Master Dealer ID = '.$masterDealerId;
							$msg['type'] = 'success';
							$msg2 = array('type'=>'success','msg'=>'Wallet Type = '.$walletTypeId);
							$msg3 = array('type'=>'success','msg'=>'Transfer Amount = '.$transferValue.' '.$walletCurrencyId);
							array_push($msgs,$msg);
							array_push($msgs,$msg2);
							array_push($msgs,$msg3);
						}
						//$msgs = array($msg);
						////End Action Deposit/////
					
					}else{
						$msg = array('type'=>'error','msg'=>'Sub Dealer Wallet Amount not enough = '.$walletPayee->post_balance);
						array_push($msgs,$msg);	
					}
				}else{
					$msg = array('type'=>'error','msg'=>'Sub Dealer Wallet Not Found!');
					array_push($msgs,$msg);
					return Redirect::back()
				            ->withInput()
				            ->withErrors($validation)
				            ->with('msgs', $msgs);
				}	
								
				
			}else{
				
				$msg = array('type'=>'error','msg'=>'Deposit Amount is not multiple of currency!');
				array_push($msgs,$msg);
			}		
		}
		
		return Redirect::back()
            ->withInput()
            ->withErrors($validation)
            ->with('msgs', $msgs);
		
	}
	*/
	/**
	 * Get a listing of the resource.
	 * GET /location
	 * 
	 *
	 * @return Response
	 */
	private function checkProcessPayback($ob)
	{
		if ($ob) {
			return true;
		} else {
			$msgs = array();
			$msg = array();
			$msg['msg'] = 'Payback Failed!';
			$msg['type'] = 'error';
			array_push($msgs,$msg);
			return Redirect::back()
	            ->withInput()
	            ->withErrors($validation)
	            ->with('msgs', $msgs);
		}
	}
}