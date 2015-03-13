<?php

class SysAutomateReportController extends \BaseController {
	
	/**
	 * Check Dealer Wallet By Config And Then Mail
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function getCheckDealerWalletAndThenMailGroup2($username,$password)
	{
		//return $this->checkAndMail($username,$password,'mail_automate_report_group2');
	}
	
	/**
	 * Check Dealer Wallet By Config And Then Mail
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function getCheckDealerWalletAndThenMail($username,$password)
	{
		//return $this->checkAndMail($username,$password,'mail_automate_report');
	}

	/**
	 * Check Dealer Wallet By Config And Then Mail
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function checkAndMail($username,$password,$grupMailType)
	{
		$authenticated = Staff::validationCredentialAutomater($username,$password);
		if ($authenticated) {
			$dealers = DB::table('dealer AS d')->select('d.id','d.name','d.phone','p.name_en AS province','k.name_en AS district','w.wallet_type__id','w.wallet_currency__id','w.balance_credit','w.post_balance','serial')
						->leftJoin('province AS p','p.code','=','d.province__code')
						->leftJoin('khan AS k','k.code','=','d.khan__code')
						->leftJoin('terminal AS tm','tm.dealer__id','=','d.id')
						->join('w_wallet AS w','w.dealer__id','=','d.id')
						->where('d.status','=','used')
						/*->where(function($query){
							$query->where('d.status','=','used')
								  ->orWhere('d.status','=','active');
						})*/
						->where('tm.status','active')
						->where('w.wallet_type__id','game')
						->where('w.post_balance','<=','1000')
						->orderBy('d.id','asc')
						->get();
						
			$dealers2 = DB::table('dealer AS d')->select('d.id','d.name','d.phone','p.name_en AS province','k.name_en AS district','w.wallet_type__id','w.wallet_currency__id','w.balance_credit','w.post_balance','serial')
						->leftJoin('province AS p','p.code','=','d.province__code')
						->leftJoin('khan AS k','k.code','=','d.khan__code')
						->leftJoin('terminal AS tm','tm.dealer__id','=','d.id')
						->join('w_wallet AS w','w.dealer__id','=','d.id')
						->where('d.status','=','used')
						->where('tm.status','active')
						->where('w.wallet_type__id','game')
						->where('w.post_balance','>','1000')
						->where('w.post_balance','<=','100000')
						->orderBy('d.id','asc')
						->get();
			
			$date = date("Y-m").'-01 00:00';
			$dateTime = date("Y-m-d H:i");//.' 23:59';
			$day = date("d");
			echo 'from date = '.$date;
			echo ' to date = '.$dateTime;
			//echo 'day = '.$day;
			//die();
			$fromDate = $date;//'2014-10-13 06:08';
			$toDate = $dateTime;//'2014-10-14 06:08';
			
			
			
			echo '</br>';		
			$yesterdayFrom = date('Y-m-d',strtotime("-1 days")). ' 00:00';
			$yesterdayTo = $toDate;//date('Y-m-d',strtotime("-1 days")). ' 23:59:59.99999';//.' 23:59';
			echo $yesterdayFrom;
			echo $yesterdayTo;
			
			if ($day != '01') {
				///--note I not yet do with second way with average---------------
				
				//$subqueryTxn = "(select d.id AS dealer_id,d.name AS dealer_name, sum(t.amount) AS sum_amount from dealer AS d inner JOIN transaction AS t on d.id = t.dealer__id where t.datetime >= '".$fromDate."' and t.datetime <= '".$toDate."' GROUP BY d.id,d.name order by d.id asc) as dt";
				$queryByServicesType = "service_type__id in ('639','639luk','pick5','pick5l') ";
				$subqueryTxn = "(select d.id AS dealer_id,d.name AS dealer_name, sum(t.requested_value) AS sum_request_amount,sum(t.transfer_value) sum_request_value from dealer AS d inner JOIN txn_transaction_header AS t on d.id = t.dealer__id where t.status = 'TS' and t.".$queryByServicesType." and t.datetime >= '".$fromDate."' and t.datetime <= '".$toDate."' GROUP BY d.id,d.name order by d.id asc) as dt";
				$dealers3 = DB::table('dealer AS d')->select('d.id','d.name','d.phone','p.name_en AS province','k.name_en AS district','dt.sum_request_amount','w.wallet_type__id','w.wallet_currency__id','w.balance_credit','w.post_balance','serial')
						->leftJoin('province AS p','p.code','=','d.province__code')
						->leftJoin('khan AS k','k.code','=','d.khan__code')
						->leftJoin('terminal AS tm','tm.dealer__id','=','d.id')
						->join('w_wallet AS w','w.dealer__id','=','d.id')
						->join(DB::raw($subqueryTxn), function($join)
					        {
					            $join->on('dt.dealer_id','=','d.id');
					        })
					    ->where('tm.status','active')
					    ->where('w.wallet_type__id','game')
						->where('d.status','=','used')
						->where('dt.sum_request_amount','<=','10000')
						->orderBy('d.id','asc')
						->get();
				
				//$queryDealerNoTxn = "select dd.id,dd.name,dd.phone,dd.wallet,p.name_en province,k.name_en district from dealer dd inner join province p on p.code = dd.province__code inner join khan k on k.code = dd.khan__code where dd.id not in(select dt.dealer__id from (select t.dealer__id,sum(t.amount) AS sum_amount from transaction AS t where t.datetime >= '".$fromDate."' and t.datetime <= '".$toDate."' GROUP BY t.dealer__id order by t.dealer__id asc) as dt) and dd.status = 'used'";
				$queryDealerNoTxn = "SELECT dd.id, dd.name, dd.status, dd.phone, p.name_en provINce, k.name_en district, tm.serial, ww.wallet_currency__id ,ww.post_balance FROM dealer dd 
					INNER JOIN	w_wallet AS ww on ww.dealer__id = dd.id
					LEFT JOIN provINce p on p.code = dd.provINce__code 
					LEFT JOIN khan k on k.code = dd.khan__code
					LEFT JOIN terminal AS tm on tm.dealer__id=dd.id
					WHERE dd.id NOT IN(SELECT dt.dealer__id FROM (SELECT t.dealer__id,SUM(t.requested_value) AS sum_amount FROM txn_transaction_header AS t 
					WHERE t.".$queryByServicesType." AND t.datetime >= '".$fromDate."' AND t.datetime <= '".$toDate."'
					GROUP BY t.dealer__id ORDER BY t.dealer__id ASC) AS dt) 
					AND dd.status = 'used'
					AND ww.wallet_type__id = 'game' ";
				
				$dealers4 = DB::select(DB::raw($queryDealerNoTxn));
			} else {
				$dealers3 = null;
				$dealers4 = null;
				$dealers5 = null;
				$dealers6 = null;
				$dealers7 = null;
				$dealers8 = null;
			}
			
			//$subqueryTxn5 = "(select d.id AS dealer_id,d.name AS dealer_name, sum(t.amount) AS sum_amount from dealer AS d inner JOIN transaction AS t on d.id = t.dealer__id where t.datetime >= '".$fromDate."' and t.datetime <= '".$toDate."' GROUP BY d.id,d.name order by d.id asc) as dt";
			$subqueryTxn5 = "(SELECT d.id AS dealer_id, d.name AS dealer_name, SUM(t.requested_value) AS sum_amount FROM dealer AS d INNER JOIN txn_transaction_header AS t ON t.dealer__id = d.id WHERE t.".$queryByServicesType." and t.datetime >= '".$fromDate."' and t.datetime <= '".$toDate."' GROUP BY d.id , d.name ORDER BY d.id ASC) as dt";
			$dealers5 = DB::table('dealer AS d')->select('d.id','d.name','w.post_balance','d.phone','p.name_en AS province','k.name_en AS district','dt.sum_amount','tm.serial','w.wallet_currency__id')
					->leftJoin('province AS p','p.code','=','d.province__code')
					->leftJoin('khan AS k','k.code','=','d.khan__code')
					->leftJoin('terminal AS tm','d.id','=','tm.dealer__id')
					->leftJoin('w_wallet AS w','w.dealer__id','=','d.id')
					->join(DB::raw($subqueryTxn5), function($join)
				        {
				            $join->on('dt.dealer_id','=','d.id');
				        })
					->where('d.status','=','used')
					 ->where('w.wallet_type__id','game')
					->orderBy('dt.sum_amount','desc')
					->take(10)
					->get();
			
			
			//--------------Dealer Deposit Daily------------------------------------------------
			// $queryDeposit = "select d.id,d.name,d.wallet,d.phone,p.name_en AS province,k.name_en AS district,wtxn.sum_amt,wtxn.sum_comm,wtxn.sum_amt_deposit_balance
							// from dealer d
							// left join province AS p on p.code = d.province__code
							// left join khan AS k on k.code = d.khan__code
							// inner join (select * from (
								// select did,sum(amount) sum_amt,sum(commission) sum_comm,sum(deposit_balance) sum_amt_deposit_balance
								// from wallet_transaction 
								// where  type in( 'deposit','payback') and bank_id != 0
								// and create_date >= '".$yesterdayFrom."' and create_date <= '".$yesterdayTo."'
								// group by did
								// order by did asc
							// ) as wtxn) as wtxn on wtxn.did = d.id order by d.id asc";
							
			$queryDeposit = "SELECT d.id,d.name,w.post_balance ,d.phone,p.name_en AS province,k.name_en AS district,wtxn.sum_amt,wtxn.sum_comm,wtxn.sum_amt_deposit_balance, w.wallet_currency__id, tm.serial
							FROM dealer AS d
							LEFT JOIN province AS p ON p.code = d.province__code
							LEFT JOIN khan AS k ON k.code = d.khan__code
							LEFT JOIN w_wallet AS w ON w.dealer__id = d.id
							LEFT JOIN terminal AS tm ON d.id = tm.dealer__id
							INNER JOIN (SELECT * FROM (
								SELECT dealer__id,SUM(requested_value) AS sum_amt,SUM(channel_comm) AS sum_comm,SUM(transfer_value) AS sum_amt_deposit_balance
								FROM txn_transaction_header 
								WHERE  service_type__id IN( 'deposit_game') 
								AND datetime >= '".$yesterdayFrom."' AND datetime <= '".$yesterdayTo."'
								GROUP BY dealer__id
								ORDER BY dealer__id ASC
							) AS wtxn) AS wtxn ON wtxn.dealer__id = d.id 
							WHERE w.wallet_type__id = 'game'
							ORDER BY d.id ASC";				
			$dealers6 = DB::select(DB::raw($queryDeposit));
			
			//--------------Dealer Credit Daily------------------------------------------------
			// $queryCredit = "select d.id,d.name, w.post_balance AS wallet, d.phone,p.name_en AS province,k.name_en AS district,txn.sum_amt,txn.sum_comm, w.wallet_currency__id, tm.serial
							// from dealer d
							// left join province AS p on p.code = d.province__code
							// left join khan AS k on k.code = d.khan__code
							// LEFT JOIN w_wallet AS w ON w.dealer__id = d.id
							// LEFT JOIN terminal AS tm ON d.id = tm.dealer__id
							// inner join (select * from (
								// select dealer__id,sum(amount) sum_amt,sum(commission) sum_comm
								// from transaction
								// where type = 'credit'
								// and datetime >= '".$yesterdayFrom."' and datetime <= '".$yesterdayTo."'
								// group by dealer__id
								// order by dealer__id asc
							// ) as txn) as txn on txn.dealer__id = d.id order by d.id asc";
			$queryCredit = "select d.id,d.name, w.post_balance, d.phone,p.name_en AS province,k.name_en AS district,txn.sum_amt,txn.sum_comm, w.wallet_currency__id, tm.serial, w.balance_credit
							from dealer d
							left join province AS p on p.code = d.province__code
							left join khan AS k on k.code = d.khan__code
							LEFT JOIN w_wallet AS w ON w.dealer__id = d.id
							LEFT JOIN terminal AS tm ON d.id = tm.dealer__id
							inner join (select * from (
								select dealer__id,sum(requested_value) sum_amt,sum(channel_comm) sum_comm
								from txn_transaction_header  
								where service_type__id = 'credit_game'
								and datetime >= '".$yesterdayFrom."' and datetime <= '".$yesterdayTo."'
								group by dealer__id
								order by dealer__id asc
							) as txn) as txn on txn.dealer__id = d.id 
							WHERE w.wallet_type__id = 'game'
							and tm.status = 'active'
							order by d.id asc";				
			$dealers7 = DB::select(DB::raw($queryCredit));
			
			//--------------Dealer Paybay Daily------------------------------------------------
			// $queryPayback = "select d.id,d.name,d.phone,p.name_en AS province,k.name_en AS district,wtxn.sum_amt
							// from dealer d
							// left join province AS p on p.code = d.province__code
							// left join khan AS k on k.code = d.khan__code
							// inner join (select * from (
								// select did,sum(credit) sum_amt
								// from wallet_transaction 
								// where type =  'payback'
								// and create_date >= '".$yesterdayFrom."' and create_date <= '".$yesterdayTo."'
								// group by did
								// order by did asc
							// ) as wtxn) as wtxn on wtxn.did = d.id order by d.id asc";
			
			$queryPayback = "SELECT d.id,d.name,d.phone,p.name_en AS province,k.name_en AS district,wtxn.sum_amt, w.wallet_currency__id, tm.serial, w.post_balance
							FROM dealer d
							LEFT JOIN province AS p ON p.code = d.province__code
							LEFT JOIN khan AS k ON k.code = d.khan__code
							LEFT JOIN w_wallet AS w ON w.dealer__id = d.id
							LEFT JOIN terminal AS tm ON d.id = tm.dealer__id
							INNER JOIN (SELECT * FROM (
								SELECT dealer__id,SUM(requested_value) sum_amt,SUM(channel_comm) sum_comm
								FROM txn_transaction_header
							  	WHERE service_type__id = 'payback_game'	
								AND datetime >= '".$yesterdayFrom."' and datetime <= '".$yesterdayTo."'
								GROUP BY dealer__id
								ORDER BY dealer__id ASC
							) AS wtxn) AS wtxn ON wtxn.dealer__id = d.id 
							WHERE w.wallet_type__id = 'game'
							AND tm.status = 'active'
							ORDER BY d.id ASC";		
			$dealers8 = DB::select(DB::raw($queryPayback));
				
			//$dealers = null;
			//$dealers2 = null;
			//$dealers3 = null;	
			//$dealers4 = null;
			//$dealers8 = null;			
			echo $grupMailType;
			var_dump($dealers);
			if ($dealers != null || $dealers2 != null || $dealers3 !=null || $dealers4 !=null || $dealers5 !=null || $dealers6 != null || $dealers7 != null|| $dealers8 != null) {
				$mails = DB::table('sys_enumeration')
					->select('enum_id as toMail')
					->orderBy('sequence_num','asc')
					->where('enum_type__id',$grupMailType)
					->get();
				
				foreach ($mails as $key => $mail) {
					$to = $mail->toMail;
					echo $to;
					$data = array('dealers'=>$dealers,'dealers2'=>$dealers2,'dealers3'=>$dealers3,'dealers4'=>$dealers4,'dealers5'=>$dealers5,'dealers6'=>$dealers6,'dealers7'=>$dealers7,'dealers8'=>$dealers8,'fromDate'=>$fromDate,'toDate'=>$toDate,'day'=>$day,'yesterdayFrom'=>$yesterdayFrom,'yesterdayTo'=>$yesterdayTo);
					Mail::send('email.list_dealer',$data, function($message) use ($to)
					{
					    $message->to($to)->subject('Dealers Report');
					});
				}
			}
			
			return Response::json(array('authenticated'=>'true'));
		} else {
			return Response::json(array('authenticated'=>'false'));
		}
	}
}