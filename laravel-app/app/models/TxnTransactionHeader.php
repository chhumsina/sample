<?php

class TxnTransactionHeader extends \Eloquent {
	/**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'txn_transaction_header'; //users
    
    /**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = 'transaction_id';
    
    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = 'datetime';
	
	protected $fillable = array('transaction_id', 'tsn', 'tsn_os', 'dealer__id', 'terminal__serial', 'service_type__id', 'error_code', 'status', 'request_gateway__id', 'reference_number', 'tcy_currency_id', 'requested_value', 'transfer_value', 'channel_comm', 'customer_comm', 'operator_comm', 'channel_sc', 'customer_sc', 'total_service_charge', 'security_code', 'service_charge_rule_id', 'service_charge_set_version','service_charge_set_rang_id', 'commission', 'remark', 'ref_transaction_id', 'atr1_name', 'atr1_value', 'atr2_name', 'atr2_value', 'atr3_name', 'atr3_value', 'atr4_value', 'atr4_name', 'atr5_value', 'atr5_name', 'atr6_value', 'atr6_name', 'atr7_value', 'atr7_name', 'datetime', 'staff__id', 'updated_at', 'updated_by_staff__id' );
	
	public function getDates()
	{
	    return array();
	}
	
	public static function getTxnTransactionHeader($dealerId, $serviceTypeId) {
		$txnTransaction = DB::table("txn_transaction_header")
					->where('service_type__id','=','deposit_game')
					->where('dealer__id','=',$dealerId)
                    ->count();
		return $txnTransaction;
	}
	
	public static function getDealerBanks($dealerId) {
		$DealerBanks = DB::table("dealer_banks")
					->where('status','=','active')
					->where('dealer__id','=',$dealerId)
                    ->lists();
		return $DealerBanks;
	}

	public static function getDealerBankAccountNames($dealerId,$bankId) {
		$DealerBanks = DB::table("dealer_banks")
					->where('status','=','active')
					->where('dealer__id','=',$dealerId)
					->where('bank__id','=',$bankId)
                    ->lists('account_name','account');
		return $DealerBanks;
	}
	
	public static function getDealerBankAccoutnNumber($dealerId,$bankId,$bankAccountName) {
		$DealerBanks = DB::table("dealer_banks")
					->where('status','=','active')
					->where('dealer__id','=',$dealerId)
					->where('bank__id','=',$bankId)
					->where('account_name','=',$bankAccountName)
                    ->first();
		return $DealerBanks;
	}
	
	public static function operationPaybackGame($dealerId,$currencyId,$paybackAmountRequest,$remark,$dateTime,$txnHeader) {
		$staffId = Auth::user()->id;
		$response = array();
		$msgs = array();
		try {
			$client = new SoapClient(Configuration::$wsdlWebService);
			$resultPaybackAPI = $client->getServiceChargeRange(array('dealerId'=>$dealerId, 'appId'=>'easy','serviceType'=>'payback_game','requestGateWay'=>'web','currency'=>$currencyId,'amount'=>$paybackAmountRequest));
			if ($resultPaybackAPI->return->status == 'F') {
				$msg = array('type'=>'error','msg'=>$resultPaybackAPI->return->message);
				array_push($msgs,$msg);
				$response['status'] = 'F';
				$response['msgs'] = $msgs;
				return $response;
			}
	
			$txnHeader['service_charge_rule_id'] = $resultPaybackAPI->return->scRangeDTO->serviceChargeRuleId;
			$txnHeader['service_charge_set_version'] = $resultPaybackAPI->return->scRangeDTO->version;
			$txnHeader['service_charge_set_rang_id'] = $resultPaybackAPI->return->scRangeDTO->scRange->setRangeId;
			$txnHeader['atr1_name'] = 'payback_by';
	
			$pCt = $resultPaybackAPI->return->scRangeDTO->scRange->channelCommPct / 100;
			$channelCommAmt = ($paybackAmountRequest*$pCt);
			$paybackAmountTransfer = $paybackAmountRequest - $channelCommAmt;
			
			$txnHeader['channel_comm'] = $channelCommAmt;
						
						
			//------------
			/*$txnHeader = new TxnTransactionHeader();
			$txnHeader->dealer__id = $dealerId;
			$txnHeader->service_type__id = 'payback_game';
			$txnHeader->status = 'TS';
			$txnHeader->request_gateway__id = 'web';
			$txnHeader->tcy_currency_id = $currencyId;
			$txnHeader->requested_value = $paybackAmount;
			$txnHeader->transfer_value = $paybackAmount;
			$txnHeader->remark = $remark;
			$txnHeader->staff__id = $staffId;
			
			$txnHeaderOb = $txnHeader->save();
			*/
			//-------------
			$serviceTypeId = 'payback_game';
		
			$txnHeader['dealer__id'] = $dealerId;
			$txnHeader['service_type__id'] = $serviceTypeId;
			$txnHeader['status'] = 'TS';
			$txnHeader['request_gateway__id'] = 'web';
			$txnHeader['tcy_currency_id'] = $currencyId;
			$txnHeader['requested_value'] = $paybackAmountRequest;
			$txnHeader['transfer_value'] = $paybackAmountTransfer;
			$txnHeader['remark'] = $remark;
			$txnHeader['staff__id'] = $staffId;
			
			$txnHeaderOb = TxnTransactionHeader::create($txnHeader);
			
			$obLog = array(
				'staff__id'=>$staffId,
				'action'=>'payback',
				'object_type'=>'payback_game',
				'object__id'=>$txnHeaderOb->transaction_id,
				'new_data'=>json_encode($txnHeader)
			);
			$obLog = Logs::create($obLog);
					
			//------------wallet payer-------------------------------
			$walletTypeId = 'game';
			$walletCurrencyId = $currencyId;
			$walletPayer = WWallet::getWalletByCondition($dealerId, $walletTypeId, $walletCurrencyId);
			
			$oldBalanceCredit = $walletPayer->balance_credit;
			
			$walletPayer->last_transaction__id = $txnHeaderOb->transaction_id;
			$walletPayer->last_transaction_service_type__id = $serviceTypeId;
			$walletPayer->last_transaction_on = $dateTime;
			$walletPayer->last_balance_credit = 0;
			$walletPayer->last_balance_debit = $paybackAmountTransfer;
			$walletPayer->balance_credit = $walletPayer->balance_credit - $paybackAmountRequest;
			$walletPayer->prev_balance = $walletPayer->post_balance;
			$walletPayer->post_balance = $walletPayer->post_balance - $paybackAmountTransfer;
			$walletPayerOb = $walletPayer->update();
			
			//------------Txn Item payer---------------------------------
			$txnTransactionItemPayer = array(
				'transaction__id'=>$txnHeaderOb->transaction_id,
				'dealer__id'=>$dealerId,
				'wallet_id'=>$walletPayer->wallet_id,
				'requested_value'=>$paybackAmountRequest,
				'transfer_value'=>$paybackAmountTransfer,
				'prev_balance'=>$walletPayer->prev_balance,
				'post_balance'=>$walletPayer->post_balance,
				'prev_balance_credit'=>$oldBalanceCredit,
				'post_balance_credit'=>$walletPayer->balance_credit,
				'user_direction'=>'payer'
			);
			$itemPayerOb = TxnTransactionItem::create($txnTransactionItemPayer);
			
			//------------wallet payee-------------------------------
			$walletTypeId = 'game';
			$walletCurrencyId = $currencyId;
			$easyStockCreditDid = StockConfiguration::$easyStockCreditDid;
			
			$walletPayee = WWallet::getWalletByCondition($easyStockCreditDid, $walletTypeId, $walletCurrencyId);
			
			$walletPayee->last_transaction__id = $txnHeaderOb->transaction_id;
			$walletPayee->last_transaction_service_type__id = $serviceTypeId;
			$walletPayee->last_transaction_on = $dateTime;
			$walletPayee->last_balance_credit = $paybackAmountRequest;
			$walletPayee->last_balance_debit = 0;
			$walletPayee->prev_balance = $walletPayee->post_balance;
			$walletPayee->post_balance = $walletPayee->post_balance + $paybackAmountRequest;
			$walletPayeeOb = $walletPayee->update();
			
			//------------Txn Item payee---------------------------------
			$txnTransactionItemPayee = array(
				'transaction__id'=>$txnHeaderOb->transaction_id,
				'dealer__id'=>$easyStockCreditDid,
				'wallet_id'=>$walletPayee->wallet_id,
				'requested_value'=>$paybackAmountTransfer,
				'transfer_value'=>$paybackAmountRequest,
				'prev_balance'=>$walletPayee->prev_balance,
				'post_balance'=>$walletPayee->post_balance,
				'user_direction'=>'payee'
			);
			$itemPayeeOb = TxnTransactionItem::create($txnTransactionItemPayee);
					
			$msg = array('type'=>'success','msg'=>'Payback Successsfully');
			array_push($msgs,$msg);
			$response['status'] = 'T';
			$response['msgs'] = $msgs;
			$response['id'] = $txnHeaderOb->transaction_id;
			$response['amount_payback'] = $paybackAmountRequest;
			$response['amount_debit_from_balance'] = $paybackAmountTransfer;
			return $response;
					
		} catch (Exception $e) {
			DB::rollback();
			$msg = array('type'=>'error','msg'=>'Operation Payback Fail Exception. '.$e);
			array_push($msgs,$msg);
			$response['status'] = 'F';
			$response['msgs'] = $msgs;
			return $response;
		}
		return $response;
	}

	public static function operationDeposit($input,$fromAction) {
		$staffId = Auth::user()->id;
		$response = array();
		$msgs = array();
		try {
			$result = null;
			$channelCommPct = 0;
			$channelCommAmt = 0;
			$transferValue = 0;
			$walletCurrencyId = '';
		
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
					$response['status'] = 'F';
					$response['msgs'] = $msgs;
					return $response;
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
						$response['status'] = 'F';
						$response['msgs'] = $msgs;
						return $response;
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
					$response['status'] = 'F';
					$response['msgs'] = $msgs;
					return $response;
				}
				
				if($requestedValue <= $stock->post_balance)
				{
					/////Action Deposit////////
					$input['atr2_value'] = $input['bank_acc_name_hidden'];
					if ($fromAction == 'by_deposit_form') {
						//---------------------Insert Txn Header-----------------------------------------------
						$ob = TxnTransactionHeader::create($input);
					} else if ($fromAction == 'by_approval_form'){
						$update = array();			
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
						$update['channel_comm'] = $input['channel_comm'];
						$update['service_charge_rule_id'] = $result->return->scRangeDTO->serviceChargeRuleId;
						$update['service_charge_set_version'] = $result->return->scRangeDTO->version;
						$update['service_charge_set_rang_id'] = $result->return->scRangeDTO->scRange->setRangeId;
				
						$txnId = $input['transaction_id'];
						$transactionOb = DB::table('txn_transaction_header')->where('transaction_id',$txnId);
						$ob = $transactionOb->first();
						$transactionOb->update($update);
						
						//---------------------Insert Txn_transaction_approval--------------------------------
						$TxnTransactionApproval = array(
										'transaction_id'=>$txnId,
										'action'=>'approve',
										'action_gateway'=>'web',
										'approval_status'=>'TS',
										'remark'=>$remark,
										'created_at'=>$dateTime,
										'staff__id'=>$staffId
									);
						$actionApproval = TxnTransactionApproval::create($TxnTransactionApproval);
					}
						
					///------------calll process depsoit------------------
					$walletPayee = WWallet::getWalletByCondition($dealerId, $walletTypeId, $walletCurrencyId);
					if($walletPayee == null) {
						$msg = array('type'=>'error','msg'=>'Dealer Wallet Not Found!');
						array_push($msgs,$msg);
						$response['status'] = 'F';
						$response['msgs'] = $msgs;
						return $response;
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
							$txnHeader['ref_transaction_id'] =  $ob->transaction_id;
							 
							$responsePayback2 = TxnTransactionHeader::operationPaybackGame($dealerId,$walletCurrencyId,$paybackAmountRequest,$remark,$dateTime,$txnHeader);
							if ($responsePayback2['status'] == 'T') {
								$updatePaybackRef['atr7_name'] = 'hasPaybackTxn';
								$updatePaybackRef['atr7_value'] = $responsePayback2['id'];
								$ob->update($updatePaybackRef);
							} else {
								$msgs = $responsePayback2['msgs'];
								$response['status'] = 'F';
								$response['msgs'] = $msgs;
								return $response;
							}
						}
					}
			
					$reason = '';
					if($walletTypeId == 'game') {
						if ($responsePayback2 != null) {
							$reason = ' After Deposit Game, System Auto Payback. Payback Txn Id = '.$responsePayback2['id'];
						}
					}
					
					
					if ($fromAction == 'by_deposit_form') {
						$action = 'deposit';
					} else if ($fromAction == 'by_approval_form'){
						$action = 'approve';		
					}
					
					$obLog = array(
						'staff__id'=>$staffId,
						'action'=>$action,
						'object_type'=>$serviceTypeId,
						'object__id'=>$ob->transaction_id,
						'new_data'=>json_encode($input),
						'reason'=>$reason
					);
					$obLog = Logs::create($obLog);
					//------------------------------------------------
					$getDealerSerial = Terminal::getDealerSerial($dealerId);
					$notificationsOb = null;
					if($getDealerSerial != null) {
						$data_array = array('amount' => (int) $requestedValue , 'commission' => (int) $channelCommAmt , 'pre_balance' => (int) $walletPayee->prev_balance , 'post_balance' => (int) $walletPayee->post_balance , 'timestamp' => $dateTime , 'dealer_id' => $dealerId , 'dealer_name'=> $getDealerSerial->name , 'service' => $walletTypeId , 'currency' => $walletCurrencyId);
						$json = json_encode($data_array);
						$notificationsOb = DB::table('notifications')->insert(
						    array('dealer__id' => $dealerId,
						    'terminal__serial' => $getDealerSerial->serial,
						    'type' => $serviceTypeId,
						    'data' => $json,
						    'status' => 'f',
						    'created_at' => $dateTime)
						);
					}
					
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
			
					$response['status'] = 'T';
					$response['msgs'] = $msgs;
					return $response;
				}else{
					$msg = array('type'=>'error','msg'=>'Stock Amount not enough = '.$stock->post_balance);
					array_push($msgs,$msg);	
				}
								
				
			}else{
				
				$msg = array('type'=>'error','msg'=>'Deposit Amount is not multiple of currency!');
				array_push($msgs,$msg);
			}
			$response['status'] = 'F';
			$response['msgs'] = $msgs;
			return $response;
			
		} catch (Exception $e) {
			DB::rollback();
			$msg = array('type'=>'error','msg'=>'Operation Deposit Fail Exception. '.$e);
			array_push($msgs,$msg);
			$response['status'] = 'F';
			$response['msgs'] = $msgs;
			return $response;
		}
		return $response;
	}

	public static function operationPromotion($input) {
		$staffId = Auth::user()->id;
		$response = array();
		$msgs = array();
		try {
			$result = null;
			$channelCommPct = 0;
			$channelCommAmt = 0;
			$transferValue = 0;
			$walletCurrencyId = '';
		
			$dealerId = $input['dealer__id'];
			$walletTypeId = $input['wallet_type__id'];
			$walletCurrencyId = $input['tcy_currency_id'];
			$dateTime = date("Y-m-d H:i:s");
			$requestedValue = $input['requested_value'];
			$transferValue = $requestedValue;
			
			
			$serviceTypeId = 'promotion';
			$subsequent = 'subsequent';
			$remark = $input['remark'];
			
			
			$channelCommAmt = 0;
			$responsePayback = null;
			$responsePayback2 = null;
			$result = null;
			
			$stockDebitAmount = $requestedValue;
			if($walletTypeId == 'game')
			{
				$client = new SoapClient(Configuration::$wsdlWebService);
				
				
				$serviceTypeId = 'promotion_game';
				$result = $client->getServiceChargeRange(array('dealerId'=>$dealerId, 'appId'=>'easy','serviceType'=>$serviceTypeId,'requestGateWay'=>'web','currency'=>$walletCurrencyId,'amount'=>$requestedValue));
		    	
		    	if ($result->return->status == 'F') {
					$msg = array('type'=>'error','msg'=>$result->return->message);
					array_push($msgs,$msg);
					$response['status'] = 'F';
					$response['msgs'] = $msgs;
					return $response;
				} else if ($result->return->status == 'S'){
					
					$input['service_charge_rule_id'] = $result->return->scRangeDTO->serviceChargeRuleId;
					$input['service_charge_set_version'] = $result->return->scRangeDTO->version;
					$input['service_charge_set_rang_id'] = $result->return->scRangeDTO->scRange->setRangeId;
					$channelCommPct = $result->return->scRangeDTO->scRange->channelCommPct;
					
					if($result->return->scRangeDTO->scRange->pctOrAmt == 'pct'){
						$pCt = $result->return->scRangeDTO->scRange->channelCommPct / 100;
						$channelCommAmt = $requestedValue*$pCt;
						
						$stockDebitAmount =  $requestedValue - $channelCommAmt;
						//--$input['channel_comm'] = $channelCommAmt;
					}else{
							
						$msg = array('type'=>'error','msg'=>'No calculation with amount yet in my web.');
						array_push($msgs,$msg);
						$response['status'] = 'F';
						$response['msgs'] = $msgs;
						return $response;
					}
				}
				
				////////////end Game//////////////
			}else{
				
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
					$response['status'] = 'F';
					$response['msgs'] = $msgs;
					return $response;
				}
				
				if($requestedValue <= $stock->post_balance)
				{
					$ob = TxnTransactionHeader::create($input);
						
					///------------calll process promotion------------------
					$walletPayee = WWallet::getWalletByCondition($dealerId, $walletTypeId, $walletCurrencyId);
					if($walletPayee == null) {
						$msg = array('type'=>'error','msg'=>'Dealer Wallet Not Found!');
						array_push($msgs,$msg);
						$response['status'] = 'F';
						$response['msgs'] = $msgs;
						return $response;
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
					$stock->last_transaction__id = $ob->transaction_id;
					$stock->last_transaction_service_type__id = $serviceTypeId;
					$stock->last_transaction_on = $dateTime;
					$stock->last_balance_credit = 0;
					$stock->last_balance_debit = $stockDebitAmount;
					$stock->prev_balance = $stock->post_balance;
					$stock->post_balance = $stock->post_balance - $stockDebitAmount;
					$stockOb = $stock->update();
					
					$txnTransactionItemStock = array(
						'transaction__id'=>$ob->transaction_id,
						'dealer__id'=>$easyStockDid,
						'wallet_id'=>$stock->wallet_id,
						'requested_value'=>$requestedValue,
						'transfer_value'=>$stockDebitAmount,
						'prev_balance'=>$stock->prev_balance,
						'post_balance'=>$stock->post_balance,
						'user_direction'=>'payer'
					);
					$itemStockOb = TxnTransactionItem::create($txnTransactionItemStock);
					///End Update stock/////
										
					$obLog = array(
						'staff__id'=>$staffId,
						'action'=>'promotion',
						'object_type'=>$serviceTypeId,
						'object__id'=>$ob->transaction_id,
						'new_data'=>json_encode($input)
					);
					$obLog = Logs::create($obLog);
					
					//----------------------------------------------
					$msg = array();
					$msg['msg'] = 'Promotion Successfully To Dealer ID = '.$input['dealer__id'].', Wallet Type = '.$walletTypeId.', Request Amount = '.number_format($requestedValue,2).' '.$walletCurrencyId.' Transfer Amount = '.number_format($transferValue,2).' '.$walletCurrencyId;
					$msg['type'] = 'success';
					array_push($msgs,$msg);
			
					$response['status'] = 'T';
					$response['msgs'] = $msgs;
					return $response;
				}else{
					$msg = array('type'=>'error','msg'=>'Stock Amount not enough = '.$stock->post_balance);
					array_push($msgs,$msg);	
				}
								
				
			}else{
				
				$msg = array('type'=>'error','msg'=>'Deposit Amount is not multiple of currency!');
				array_push($msgs,$msg);
			}
			$response['status'] = 'F';
			$response['msgs'] = $msgs;
			return $response;
			
		} catch (Exception $e) {
			DB::rollback();
			$msg = array('type'=>'error','msg'=>'Operation Deposit Fail Exception. '.$e);
			array_push($msgs,$msg);
			$response['status'] = 'F';
			$response['msgs'] = $msgs;
			return $response;
		}
		return $response;
	}

	public static function operationCommionFree($input) {
		$staffId = Auth::user()->id;
		$response = array();
		$msgs = array();
		try {
			$result = null;
			$channelCommPct = 0;
			$channelCommAmt = 0;
			$transferValue = 0;
			$walletCurrencyId = '';
		
			$dealerId = $input['dealer__id'];
			$walletTypeId = $input['wallet_type__id'];
			$walletCurrencyId = $input['tcy_currency_id'];
			$dateTime = date("Y-m-d H:i:s");
			$requestedValue = $input['requested_value'];
			$transferValue = $requestedValue;
			
			
			$serviceTypeId = 'commission_free';
			$subsequent = 'subsequent';
			$remark = $input['remark'];
			
			
			$channelCommAmt = 0;
			$responsePayback = null;
			$responsePayback2 = null;
			$result = null;
			
			$stockDebitAmount = $requestedValue;
			if($walletTypeId == 'game')
			{
				$client = new SoapClient(Configuration::$wsdlWebService);
				
				
				$serviceTypeId = 'commission_free_game';
				$result = $client->getServiceChargeRange(array('dealerId'=>$dealerId, 'appId'=>'easy','serviceType'=>'promotion_game','requestGateWay'=>'web','currency'=>$walletCurrencyId,'amount'=>$requestedValue));
		    	
		    	if ($result->return->status == 'F') {
					$msg = array('type'=>'error','msg'=>$result->return->message);
					array_push($msgs,$msg);
					$response['status'] = 'F';
					$response['msgs'] = $msgs;
					return $response;
				} else if ($result->return->status == 'S'){
					
					$input['service_charge_rule_id'] = $result->return->scRangeDTO->serviceChargeRuleId;
					$input['service_charge_set_version'] = $result->return->scRangeDTO->version;
					$input['service_charge_set_rang_id'] = $result->return->scRangeDTO->scRange->setRangeId;
					$channelCommPct = $result->return->scRangeDTO->scRange->channelCommPct;
					
					if($result->return->scRangeDTO->scRange->pctOrAmt == 'pct'){
						$pCt = $result->return->scRangeDTO->scRange->channelCommPct / 100;
						$channelCommAmt = $requestedValue*$pCt;
						
						$stockDebitAmount =  $requestedValue - $channelCommAmt;
						//--$input['channel_comm'] = $channelCommAmt;
					}else{
							
						$msg = array('type'=>'error','msg'=>'No calculation with amount yet in my web.');
						array_push($msgs,$msg);
						$response['status'] = 'F';
						$response['msgs'] = $msgs;
						return $response;
					}
				}
				
				////////////end Game//////////////
			}else{
				
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
					$response['status'] = 'F';
					$response['msgs'] = $msgs;
					return $response;
				}
				
				if($requestedValue <= $stock->post_balance)
				{
					$ob = TxnTransactionHeader::create($input);
						
					///------------calll process promotion------------------
					$walletPayee = WWallet::getWalletByCondition($dealerId, $walletTypeId, $walletCurrencyId);
					if($walletPayee == null) {
						$msg = array('type'=>'error','msg'=>'Dealer Wallet Not Found!');
						array_push($msgs,$msg);
						$response['status'] = 'F';
						$response['msgs'] = $msgs;
						return $response;
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
					$stock->last_transaction__id = $ob->transaction_id;
					$stock->last_transaction_service_type__id = $serviceTypeId;
					$stock->last_transaction_on = $dateTime;
					$stock->last_balance_credit = 0;
					$stock->last_balance_debit = $stockDebitAmount;
					$stock->prev_balance = $stock->post_balance;
					$stock->post_balance = $stock->post_balance - $stockDebitAmount;
					$stockOb = $stock->update();
					
					$txnTransactionItemStock = array(
						'transaction__id'=>$ob->transaction_id,
						'dealer__id'=>$easyStockDid,
						'wallet_id'=>$stock->wallet_id,
						'requested_value'=>$requestedValue,
						'transfer_value'=>$stockDebitAmount,
						'prev_balance'=>$stock->prev_balance,
						'post_balance'=>$stock->post_balance,
						'user_direction'=>'payer'
					);
					$itemStockOb = TxnTransactionItem::create($txnTransactionItemStock);
					///End Update stock/////
										
					$obLog = array(
						'staff__id'=>$staffId,
						'action'=>'promotion',
						'object_type'=>$serviceTypeId,
						'object__id'=>$ob->transaction_id,
						'new_data'=>json_encode($input)
					);
					$obLog = Logs::create($obLog);
					
					//----------------------------------------------
					$msg = array();
					$msg['msg'] = 'Promotion Successfully To Dealer ID = '.$input['dealer__id'].', Wallet Type = '.$walletTypeId.', Request Amount = '.number_format($requestedValue,2).' '.$walletCurrencyId.' Transfer Amount = '.number_format($transferValue,2).' '.$walletCurrencyId;
					$msg['type'] = 'success';
					array_push($msgs,$msg);
			
					$response['status'] = 'T';
					$response['msgs'] = $msgs;
					return $response;
				}else{
					$msg = array('type'=>'error','msg'=>'Stock Amount not enough = '.$stock->post_balance);
					array_push($msgs,$msg);	
				}
								
				
			}else{
				
				$msg = array('type'=>'error','msg'=>'Deposit Amount is not multiple of currency!');
				array_push($msgs,$msg);
			}
			$response['status'] = 'F';
			$response['msgs'] = $msgs;
			return $response;
			
		} catch (Exception $e) {
			DB::rollback();
			$msg = array('type'=>'error','msg'=>'Operation Deposit Fail Exception. '.$e);
			array_push($msgs,$msg);
			$response['status'] = 'F';
			$response['msgs'] = $msgs;
			return $response;
		}
		return $response;
	}
}