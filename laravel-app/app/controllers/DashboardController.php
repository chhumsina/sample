<?php

class DashboardController extends \BaseController {
	
	protected $layout = 'layouts.master';
	
	/**
	 * Display a listing of the resource.
	 * GET /dashboard
	 *
	 * @return Response
	 */
	 
	public function index()
	{
		$msgs = array();
		
		$arrDealerRegisters = array();
		$arrDealerPOSInstalted = array();
		$arrDealers1 = array();
		$arrDealers2 = array();
		$arrDealers3 = array();
		$arrNewPOSInstallted = array();
		$arrNewDealerTops = array();
		$arrAmountNewDealerTops = array();
		
		$inputs = Input::all();
		
		if(!Input::has('start_date') || !Input::has('end_date')) {
			$startDate = date("Y-m").'-01';
			$endDate = date("Y-m-d");//date("Y-m-d H:i");//.' 23:59';
		} else {
			$startDate = Input::get('start_date');
			$endDate = Input::get('end_date');
		}
		
		if($startDate!='' && $endDate!='') {
	
			//$startDate = Input::get('start_date');
			//$endDate = Input::get('end_date');
			
			//-----------------Dealer < 100000-----
			$dealer1Query = "select vt.created_at as day,vt.num_dealer as n_dealer from vt_charts_reports  as vt where vt.type in ('dealer_wallet_smaller_than_100000')  and vt.created_at >= '".$startDate."' and vt.created_at <='".$endDate." 23:59:59' ORDER BY created_at asc";
			$dealer1 = DB::select(DB::raw($dealer1Query));
			foreach ($dealer1 as $key => $value) {
				$date = date_create($value->day);
				$dateFull = date_format($date, 'Y-m-d');
				$arrDealers1[$dateFull] = $value->n_dealer;
			}
			
			//-----------------Dealer > 800000-----
			$dealer2Query = "select vt.created_at as day,vt.num_dealer as n_dealer from vt_charts_reports  as vt where vt.type in ('dealer_wallet_more_than_800000')  and vt.created_at >= '".$startDate."' and vt.created_at <='".$endDate." 23:59:59' ORDER BY created_at asc";
			$dealer2 = DB::select(DB::raw($dealer2Query));
			foreach ($dealer2 as $key => $value) {
				$date = date_create($value->day);
				$dateFull = date_format($date, 'Y-m-d');
				$arrDealers2[$dateFull] = $value->n_dealer;
			}
			
			//-----------------Dealer not sale Last 10 day-----
			//$startDate
			//$endDate
			

			//--------------------
			$date = DateTime::createFromFormat('Y-m-d', $startDate);
			
			for ($i = 0; $i <= 365; $i++) {
				if ($i > 0) {
					$dateIncrease = $date->modify('+1 day');
				} else {
					$dateIncrease = $date;
				}
				
				/*$day = $dateIncrease->format('d');
				$month = $dateIncrease->format('m');
				$month = $month - 1;
				$year = $dateIncrease->format('Y');*/
				$dateFull = $date->format('Y-m-d');
				
				
				$dateLast5 = DateTime::createFromFormat('Y-m-d', $dateFull);
				$dateLast5 = $dateLast5->modify('-4 day');
				$dateFullLast5 = $dateLast5->format('Y-m-d');
				try {
					///-----------------------Old Code------------
					/*$dealer3QyeryNoSale5Day = "select COUNT(dd.id) as num_dealer from dealer dd inner join province p on p.code = dd.province__code inner join khan k on k.code = dd.khan__code where dd.id not in(select dt.dealer__id from (select t.dealer__id,sum(t.amount) AS sum_amount from transaction AS t 
					where t.datetime >= '".$dateFullLast5."' and t.datetime <= '".$dateFull."' 
					GROUP BY t.dealer__id order by t.dealer__id asc) as dt) and dd.status = 'used'";*/
					
					
					//-------------New Txn Header-------------------
					$queryByServicesType = "service_type__id in ('639','639luk','pick5','pick5l') ";
					
					$dealer3QyeryNoSale5Day = "select COUNT(dd.id) as num_dealer from dealer dd inner join province p on p.code = dd.province__code inner join khan k on k.code = dd.khan__code 
					where dd.id not in(select dt.dealer__id from (select t.dealer__id,sum(t.transfer_value) AS sum_amount from txn_transaction_header AS t 
					where t.status = 'TS' and t.".$queryByServicesType." and t.datetime >= '".$dateFullLast5."' and t.datetime <= '".$dateFull."' 
					GROUP BY t.dealer__id order by t.dealer__id asc) as dt) and dd.status = 'used' and dd.type_account = 'real'";
	
					$dealer3 = DB::select(DB::raw($dealer3QyeryNoSale5Day));
					//echo $dealer3[0]->num_dealer;
					$arrDealers3[$dateFull] = $dealer3[0]->num_dealer;
					
				} catch (Exception $e) {
					$arrDealers3[$dateFull] = '0';
				}
				if ($dateFull >= $endDate) {
					break;
				}
			}
			/*
			//--------------------
			
			$dealer3Query = "select vt.created_at as day,vt.num_dealer as n_dealer from vt_charts_reports  as vt where vt.type in ('dealer_not_sale_last_10day')  and vt.created_at >= '".$startDate."' and vt.created_at <='".$endDate." 23:59:59' ORDER BY created_at asc";
			$dealer3 = DB::select(DB::raw($dealer3Query));
			foreach ($dealer3 as $key => $value) {
				$date = date_create($value->day);
				$dateFull = date_format($date, 'Y-m-d');
				$arrDealers3[$dateFull] = $value->n_dealer;
			}*/
			
			//----------Dealer Register-----------
			$dealerRegisterQuery = "select * from (select date_trunc('day', d.created_at) AS day,count(d.id) as n_dealer from dealer as d where d.status in ('active','used') and d.type_account = 'real' GROUP BY 1 ORDER BY day asc) as dt where dt.day >= '".$startDate."' and dt.day <='".$endDate."'";
			$dealerRegisters = DB::select(DB::raw($dealerRegisterQuery));
			foreach ($dealerRegisters as $key => $value) {
				$arrDealerRegisters[$value->day] = $value->n_dealer;
			}
			
			//----------New POS installted-------
			$newPOSInstallted = "select * from (select date_trunc('day', d.created_at) AS day,count(d.id) as n_dealer from dealer d 
				INNER JOIN terminal tl on d.id = tl.dealer__id
				INNER JOIN sales_visit sv on d.id = sv.dealer__id
				where tl.status = 'active'
				and d.created_at >= '2015-01-10'
				and d.type_account = 'real' 
				GROUP BY 1
				ORDER BY day asc) as dt where dt.day >= '".$startDate."' and dt.day <='".$endDate."'";
			$newPOSInstallteds = DB::select(DB::raw($newPOSInstallted));
			foreach ($newPOSInstallteds as $key => $value) {
				$arrNewPOSInstallted[$value->day] = $value->n_dealer;
			}
			
			//---------New Dealer Top Up-----------
			$newDealerTop = "select * from (select date_trunc('day', wt.datetime) AS day,count(d.id) as n_dealer from dealer d
				inner join txn_transaction_header wt on d.id = wt.dealer__id
				where wt.status = 'TS' and wt.service_type__id in ('deposit_game') GROUP BY 1 ORDER BY day asc) as dt where dt.day >= '".$startDate."' and dt.day <='".$endDate."'";
			$newDealerTops = DB::select(DB::raw($newDealerTop));
			foreach ($newDealerTops as $key => $value) {
				$arrNewDealerTops[$value->day] = $value->n_dealer;
			}
			
			//---------Amount Dealer Top Up-----------
			
			//old code for only new dealer-----------
			/*$amountNewDealerTop = "select * from (select date_trunc('day', wt.create_date) AS day,sum(CAST(coalesce(wt.amount) as float)+CAST(coalesce(wt.credit) as float))/400000 as n_dealer from dealer d
			inner join wallet_transaction wt on d.id = wt.did
			where d.created_at>= '2015-01-10'  and wt.type in ('deposit','payback') and wt.bank_id > 0 GROUP BY 1 ORDER BY day asc) as dt where dt.day >= '".$startDate."' and dt.day <='".$endDate."'";*/
			
			$amountNewDealerTop = "select * from (select date_trunc('day', wt.datetime) AS day,sum(CAST(coalesce(wt.requested_value) as float))/400000 as amount from dealer d
			inner join txn_transaction_header wt on d.id = wt.dealer__id
			where wt.status = 'TS' and d.type_account = 'real' and wt.service_type__id in ('deposit_game') GROUP BY 1 ORDER BY day asc) as dt where dt.day >= '".$startDate."' and dt.day <='".$endDate."'";
			$amountNewDealerTops = DB::select(DB::raw($amountNewDealerTop));
			foreach ($amountNewDealerTops as $key => $value) {
				$arrAmountNewDealerTops[$value->day] = $value->amount;
			}
		} 
		
		$this->layout->content = View::make('dashboard.dashboard',compact('startDate','endDate','arrAmountNewDealerTops','arrNewDealerTops','arrNewPOSInstallted','arrDealers1','arrDealerRegisters','arrDealers2', 'arrDealers3'));

	}

	/**
	 * Show the form for creating a new resource.
	 * GET /dashboard/create
	 *
	 * @return Response
	 */
	 
	public function salePerformances()
	{
		//
		$msgs = array();
		
		$salesPerformanceDaily = array();
		$salesPerformanceWeekly = array();
		$salesPerformance639byDaily = array();
		$salesPerformance639byWeekly = array();
		$salesPerformance639TelcoDailys = array();
		$salesPerformance639TelcoWeeklys = array();
		$salesPerformanceTotalTelcoSalesWeeklys = array();

		$salesPerformancePick5Daily = array();
		$salesPerformancePick5Weekly = array();
		$salesPerformancePick5TelcoDailys = array();
		$salesPerformancePick5TelcoWeeklys = array();

		$salesPerformanceTotalSmartSalesWeekly = array();
		
		
		$salesPerformanceLSDLSaleDailys = array();
		$salesPerformanceLSDLOnlineDailys = array();
		$salesPerformanceLSDLOnlineMovingAvgDailys = array();
		$salesPerformanceSalePerDragonDailys = array();
		$salesPerformanceSalePerDragonMovingAvgDailys = array();
		$salesPerformanceSmartSale639Dailys = array();
		$salesPerformanceSmartSalePick5Dailys = array();
		$salesPerformanceMetfoneSale639Dailys = array();
		$salesPerformanceMetfoneSalePick5Dailys = array();
		$salesPerformanceNumSubscriberOnlineDailys = array();
		$salesPerformanceSalePerSubscriberOnlineDailys = array();
		$salesPerformanceTotalTelcoSalesDailys = array();
		
		$salesPerformanceSalePerDraw639Weeklys = array();
		$salesPerformanceSalePerDrawPick5Weeklys = array();
		
		
		$salesPerformanceLSDLSaleWeeklys = array();
		$salesPerformanceLSDLOnlineWeeklys = array();
		$salesPerformanceSalePerDragonWeeklys = array();
		$salesPerformanceSmartSale639Weeklys = array();
		$salesPerformanceSmartSalePick5Weeklys = array();
		$salesPerformanceMetfoneSale639Weeklys = array();
		$salesPerformanceMetfoneSalePick5Weeklys = array();
		$salesPerformanceNumSubscriberOnlineWeeklys = array();
		$salesPerformanceTotalSmartSalesWeeklys = array();
		$salesPerformanceTotalMetfoneSalesWeeklys = array();

		$salesPerformanceAvgSalePerDraw639Weeklys = array();
		$salesPerformanceAvgSalePerDrawPick5Weeklys = array();
		$salesPerformanceAvgNumSubscriberOnlineWeeklys = array();
		$salesPerformanceLSDLAvgOnlineWeeklys = array();
		$salesPerformanceTotalSmartAvgSalesWeeklys = array();
		
		$salesPerformanceTotalSmartSalesDailys = array();
		$salesPerformanceTotalMetfoneSalesDailys = array();
		
		$salesPerformanceTotalTelcoMovingAvgSalesDailys = array();
		
		$salesPerformanceTotalSaleMovingAvgSalesDailys = array();
		$salesPerformanceTotalSmartSalesMovingAvgSalesDailys = array();
		$salesPerformanceTotalMetfoneSalesMovingAvgSalesDailys = array();
		$salesPerformanceLSDLSaleMovingAvgDailys = array();
		$salesPerformanceTotal639MovingAvgSalesDailys = array();
		$salesPerformanceTotalPick5SalesMovingAvgSalesDailys = array();
		$salesPerformanceTotalNumSubscriberOnlineMovingAvgSalesDailys = array();
		$salesPerformanceTotalSalePerSubscriberMovingAvgSalesDailys = array();
		
		
		$totalSalesGameMovingAvgWeekly = array();
		$totalSalesLotto639MovingAvgWeekly = array();
		
		$inputs = Input::all();
		
		$firstTime = true;
		if(!Input::has('start_date') || !Input::has('end_date')) {
			$startDate = date("Y-m").'-01';
			$endDate = date("Y-m-d");//date("Y-m-d H:i");//.' 23:59';
			
		} else {
			$startDate = Input::get('start_date');
			$endDate = Input::get('end_date');
			$firstTime = false;
		}
		
		//---------------------------
		$ddate = $endDate;
		$duedt = explode("-", $ddate);
		$dateEnd  = mktime(0, 0, 0, $duedt[1], $duedt[2], $duedt[0]);
		//mktime(hour, minute, second, month, day, year);
		//echo 'end date = '.$date;
		//$weekEnd  = (int)date('YW', $date);
		
		$weekEnd = strftime("%W",$dateEnd);
		$weekEnd +=1;
		//---------------------------
		
		if($startDate!='' && $endDate!='') {
			if ((Input::has('sales') && Input::has('groupby') && Input::get('groupby') == 'daily') || $firstTime == 'true') {
				//-----------------Sale Game By Daily-----------
				//$sale1Query = "SELECT sum(cs.bet_639) sum_bet_639,sum(cs.bet_639_luk) sum_bet_639_luk,sum(cs.bet_pick5) sum_bet_pick5,sum(cs.bet_pick5_luk) sum_bet_pick5_luk,(sum(cs.bet_639+cs.bet_639_luk) * 0.5)+(sum(cs.bet_pick5+cs.bet_pick5_luk) * 0.25) as sum_game_amount, cs.datetime from rep_channel_sale cs where cs.status  = 'active' and datetime >= '".$startDate."' and datetime <='".$endDate."' GROUP BY cs.datetime ORDER BY cs.datetime asc";
				
				//-----------------New--------------------------
				$sale1Query = "SELECT (sum(cs.total_sell_amount_639))+(sum(cs.total_sell_amount_pick5)) as sum_game_amount, cs.datetime 
				from rep_channel_sale cs 
				where cs.status  = 'active' and datetime >= '".$startDate."' and datetime <='".$endDate."' 
				GROUP BY cs.datetime ORDER BY cs.datetime asc";
				
				$sale1 = DB::select(DB::raw($sale1Query));
				foreach ($sale1 as $key => $value) {
					$date = date_create($value->datetime);
					$dateFull = date_format($date, 'Y-m-d');
					$salesPerformanceDaily[$dateFull] = $value->sum_game_amount;
				}
				
				//-------------Total Telco Sales by Daily moving avg-----------------
					$date = DateTime::createFromFormat('Y-m-d', $startDate);
					
					for ($i = 0; $i < 365; $i++) {
						if ($i > 0) {
							$dateIncrease = $date->modify('+1 day');
						} else {
							$dateIncrease = $date;
						}
						
						$dateFull = $date->format('Y-m-d');
						
						
						$dateLast5 = DateTime::createFromFormat('Y-m-d', $dateFull);
						$dateLast5 = $dateLast5->modify('-6 day');
						$dateFullLast5 = $dateLast5->format('Y-m-d');
						try {						
							$queryTotalSaleMoving7Day = "SELECT sum(s.sum_game_amount)/7 sum_game_amount
							from
							(
							SELECT (sum(cs.total_sell_amount_639))+(sum(cs.total_sell_amount_pick5)) as sum_game_amount, cs.datetime 
											from rep_channel_sale cs 
											where cs.status  = 'active' and datetime >= '".$dateFullLast5."' and datetime <='".$dateFull." 23:59' 
											GROUP BY cs.datetime ORDER BY cs.datetime asc
							) as s";
			
							$totalSaleMoving7Day = DB::select(DB::raw($queryTotalSaleMoving7Day));
							
							$value = 'null';
							if ($totalSaleMoving7Day[0]->sum_game_amount != null) {
								$value = $totalSaleMoving7Day[0]->sum_game_amount;
							}
							$salesPerformanceTotalSaleMovingAvgSalesDailys[$dateFull] = $value;
							
						} catch (Exception $e) {
							$salesPerformanceTotalSaleMovingAvgSalesDailys[$dateFull] = '0';
						}
						if ($dateFull >= $endDate) {
							break;
						}
					}
			}
			
			
			//-----------------Sale Game By Weekly-----------
//			$sale2Query = "SELECT sum(sr.sum_game_amount) as sum_game_amount, CAST(coalesce(sr.week, '0') AS integer) as week from
//							(select (sum(cs.bet_639+cs.bet_639_luk) * 0.5)+(sum(cs.bet_pick5+cs.bet_pick5_luk) * 0.25) as sum_game_amount,to_char(cs.datetime, 'WW') as week
//						from rep_channel_sale cs where cs.datetime is not null and cs.status = 'active' and datetime >= '".$startDate."' and datetime <='".$endDate."'
//						GROUP BY cs.datetime,week
//						order by week asc) as sr
//						GROUP BY sr.week;";

			if ((Input::has('sales') && Input::has('groupby') && Input::get('groupby') == 'weekly')) {
				//----------------------Sale Game By Weekly (New )-------------------------
				$sale2Query = "SELECT sum(sr.sum_game_amount) as sum_game_amount, CAST(coalesce(sr.week, '0') AS integer) as week from
								(select (sum(cs.total_sell_amount_639))+(sum(cs.total_sell_amount_pick5)) as sum_game_amount,to_char(cs.datetime, 'WW') as week
							from rep_channel_sale cs where cs.datetime is not null and cs.status = 'active' and datetime >= '".$startDate."' and datetime <='".$endDate."'
							GROUP BY cs.datetime,week
							order by week asc) as sr
							GROUP BY sr.week";
				$sale2 = DB::select(DB::raw($sale2Query));
				foreach ($sale2 as $key => $value) {
					$salesPerformanceWeekly[$value->week] = $value->sum_game_amount;
				}	
				
				//----------------------Sale Game By Weekly Moving Everage (New )-------------------------
				$endDateObject = DateTime::createFromFormat('Y-m-d', $endDate);
				$date = DateTime::createFromFormat('Y-m-d', $startDate);
									  
				for ($i = 0; $i <= 53; $i++) {
				  	
						if ($i > 0) {
							
							$dateClone = clone $date;
							$last = $this->getLastDayOfYear($dateClone);
							
							$dateIncrease = $date->modify('+7 day');
							
							$dateFull1 = $date->format('Y-m-d');
							$dateLast1 = $last->format('Y-m-d');
							if ($dateFull1 > $dateLast1) {
								
								$year1 = $date->format('Y');
								$year2 = $endDateObject->format('Y');
								
								if ($year1 != $year2) {
									$date = $last;
								}					
							}
						} else {
							$dateIncrease = $date;
						}
						
						$dateFull = $date->format('Y-m-d');
						$week  = $this->getWeekNumByDateString($dateFull);
						if ($week >= 4) {
							$last4Week = $week -  3;
							try {
								$querySalesPerformanceSaleGameMovingAvgWeekly = "select sum(sum_game_amount)/4 as sum_game_amount
											FROM
											(
											SELECT sum(sr.sum_game_amount) as sum_game_amount, CAST(coalesce(sr.week, '0') AS integer) as week from
																		(select (sum(cs.total_sell_amount_639))+(sum(cs.total_sell_amount_pick5)) as sum_game_amount,to_char(cs.datetime, 'WW') as week
																	from rep_channel_sale cs where cs.datetime is not null and cs.status = 'active' 
																	GROUP BY cs.datetime,week
																	order by week asc) as sr
																	GROUP BY sr.week
											) as tw
											where week >= ".$last4Week." and week <= ".$week;
								
								$salesPerformanceSaleGameMovingAvgWeekly = DB::select(DB::raw($querySalesPerformanceSaleGameMovingAvgWeekly));
								
								$value = 'null';
								if ($salesPerformanceSaleGameMovingAvgWeekly[0]->sum_game_amount != null) {
									$value = $salesPerformanceSaleGameMovingAvgWeekly[0]->sum_game_amount;
								}
								$totalSalesGameMovingAvgWeekly[$week] = $value;
														
							} catch (Exception $e) {
								$totalSalesGameMovingAvgWeekly[$week] = 'null';
							}
						}
						
						
						if ($week == $weekEnd) {
							break;
						}
				}
			}
			//----------------------------------------------------------------------------------------
			
			
			
			//-----------------Sales Lotto 639 By Daily-----------
//			$sale3Query = "SELECT sum(cs.bet_639) sum_bet_639,sum(cs.bet_639_luk) sum_bet_639_luk, (sum(cs.bet_639+cs.bet_639_luk) * 0.5) as sum_game_amount_639, cs.datetime from rep_channel_sale cs where cs.status = 'active' and cs.datetime >= '".$startDate."' and cs.datetime <='".$endDate."' GROUP BY cs.datetime ORDER BY cs.datetime asc";
//			$sale3 = DB::select(DB::raw($sale3Query));
//			foreach ($sale3 as $key => $value) {
//				$date = date_create($value->datetime);
//				$dateFull = date_format($date, 'Y-m-d');
//				$salesPerformance639byDaily[$dateFull] = $value->sum_game_amount_639;
//			}
			
			if ((Input::has('sale_lotto_639') && Input::has('groupby') && Input::get('groupby') == 'daily')) {
				//--------------------- New ----------------------------
				$sale3Query = "SELECT sum(cs.bet_639) sum_bet_639,sum(cs.bet_639_luk) sum_bet_639_luk, (sum(cs.total_sell_amount_639)) as sum_game_amount_639, cs.datetime from rep_channel_sale cs where cs.status = 'active' and cs.datetime >= '".$startDate."' and cs.datetime <='".$endDate."' GROUP BY cs.datetime ORDER BY cs.datetime asc";
				$sale3 = DB::select(DB::raw($sale3Query));
				foreach ($sale3 as $key => $value) {
					$date = date_create($value->datetime);
					$dateFull = date_format($date, 'Y-m-d');
					$salesPerformance639byDaily[$dateFull] = $value->sum_game_amount_639;
				}
				
				//-------------Total Sales Lotto 639 Daily moving avg-----------------
				$date = DateTime::createFromFormat('Y-m-d', $startDate);
				
				for ($i = 0; $i < 365; $i++) {
					if ($i > 0) {
						$dateIncrease = $date->modify('+1 day');
					} else {
						$dateIncrease = $date;
					}
					
					$dateFull = $date->format('Y-m-d');
					
					
					$dateLast5 = DateTime::createFromFormat('Y-m-d', $dateFull);
					$dateLast5 = $dateLast5->modify('-6 day');
					$dateFullLast5 = $dateLast5->format('Y-m-d');
					try {
						$querySalesPerformance639MovingAvgDaily = "SELECT sum(s.sum_game_amount)/7 sum_game_amount
															from
															(
																SELECT (sum(cs.total_sell_amount_639)) as sum_game_amount, cs.datetime 
																from rep_channel_sale cs 
																where cs.status  = 'active' 
																and datetime >= '".$dateFullLast5."' and datetime <='".$dateFull." 23:59'
																GROUP BY cs.datetime ORDER BY cs.datetime asc
															) as s";
						
						$totalSalesPerformance639MovingAvgDaily = DB::select(DB::raw($querySalesPerformance639MovingAvgDaily));
						
						$value = 'null';
						if ($totalSalesPerformance639MovingAvgDaily[0]->sum_game_amount != null) {
							$value = $totalSalesPerformance639MovingAvgDaily[0]->sum_game_amount;
						}
						$salesPerformanceTotal639MovingAvgSalesDailys[$dateFull] = $value;
												
					} catch (Exception $e) {
						$salesPerformanceTotal639MovingAvgSalesDailys[$dateFull] = '0';
					}
					if ($dateFull >= $endDate) {
						break;
					}
				}
			}

			//-----------------Sales Lotto 639 By Weekly-----------
//			$sale4Query = "SELECT sum(sr.sum_game_amount) as sum_game_amount, CAST(coalesce(sr.week, '0') AS integer) as week from
//						(select (sum(cs.bet_639+cs.bet_639_luk) * 0.5) as sum_game_amount,to_char(cs.datetime, 'WW') as week
//						from rep_channel_sale cs where cs.datetime is not null and cs.status = 'active' and datetime >= '".$startDate."' and datetime <='".$endDate."'
//						GROUP BY cs.datetime,week
//						order by week asc) as sr
//						GROUP BY sr.week";

			if ((Input::has('sale_lotto_639') && Input::has('groupby') && Input::get('groupby') == 'weekly')) {
				//-----------------------Sales Lotto 639 By Weekly New --------------------------
				$sale4Query = "SELECT sum(sr.sum_game_amount) as sum_game_amount, CAST(coalesce(sr.week, '0') AS integer) as week from
							(select (sum(cs.total_sell_amount_639)) as sum_game_amount,to_char(cs.datetime, 'WW') as week
							from rep_channel_sale cs where cs.datetime is not null and cs.status = 'active' and datetime >= '".$startDate."' and datetime <='".$endDate."'
							GROUP BY cs.datetime,week
							order by week asc) as sr
							GROUP BY sr.week";
				
				$sale4 = DB::select(DB::raw($sale4Query));
				foreach ($sale4 as $key => $value) {
					$date = date_create($value->week);
					$salesPerformance639byWeekly[$value->week] = $value->sum_game_amount;
				}	
				
				//----------------------Sales Lotto 639 By Weekly Moving Everage (New )-------------------------
				$endDateObject = DateTime::createFromFormat('Y-m-d', $endDate);
				$date = DateTime::createFromFormat('Y-m-d', $startDate);
									  
				for ($i = 0; $i <= 53; $i++) {
				  	
						if ($i > 0) {
							
							$dateClone = clone $date;
							$last = $this->getLastDayOfYear($dateClone);
							
							$dateIncrease = $date->modify('+7 day');
							
							$dateFull1 = $date->format('Y-m-d');
							$dateLast1 = $last->format('Y-m-d');
							if ($dateFull1 > $dateLast1) {
								
								$year1 = $date->format('Y');
								$year2 = $endDateObject->format('Y');
								
								if ($year1 != $year2) {
									$date = $last;
								}					
							}
						} else {
							$dateIncrease = $date;
						}
						
						$dateFull = $date->format('Y-m-d');
						$week  = $this->getWeekNumByDateString($dateFull);
						if ($week >= 4) {
							$last4Week = $week -  3;
							try {
								$querySalesPerformanceSalesLotto639ByMovingAvgWeekly = "select sum(sum_game_amount)/4 as sum_game_amount
											FROM
											(
												SELECT sum(sr.sum_game_amount) as sum_game_amount, CAST(coalesce(sr.week, '0') AS integer) as week from
												(select (sum(cs.total_sell_amount_639)) as sum_game_amount,to_char(cs.datetime, 'WW') as week
												from rep_channel_sale cs where cs.datetime is not null and cs.status = 'active' 
												GROUP BY cs.datetime,week
												order by week asc) as sr
												GROUP BY sr.week
											) as tw
											where week >= ".$last4Week." and week <= ".$week;
								
								$salesPerformanceSalesLotto639MovingAvgWeekly = DB::select(DB::raw($querySalesPerformanceSalesLotto639ByMovingAvgWeekly));
								
								$value = 'null';
								if ($salesPerformanceSalesLotto639MovingAvgWeekly[0]->sum_game_amount != null) {
									$value = $salesPerformanceSalesLotto639MovingAvgWeekly[0]->sum_game_amount;
								}
								$totalSalesLotto639MovingAvgWeekly[$week] = $value;
														
							} catch (Exception $e) {
								$totalSalesLotto639MovingAvgWeekly[$week] = 'null';
							}
						}
						
						
						if ($week == $weekEnd) {
							break;
						}
				}
			}
			//----------------------------------------------------------------------------------------
			
			
			//-----------------Sales Lotto 639 Telco Daily-----------
//			$sale5Query = "SELECT co.channel_owner_name,cs.channel_owner__id,sum(cs.bet_639) sum_bet_639,sum(cs.bet_639_luk) sum_bet_639_luk, (sum(cs.bet_639+cs.bet_639_luk) * 0.5) as sum_game_amount_639,cs.datetime FROM rep_channel_sale cs
//							INNER JOIN sys_channel_owner co ON cs.channel_owner__id = co.channel_owner_id
//							where cs.status = 'active'  and cs.channel_owner__id not in (7,8) and cs.datetime >= '".$startDate."' and cs.datetime <='".$endDate."'
//							GROUP BY cs.channel_owner__id,co.channel_owner_name,cs.datetime,co.sequence_number ORDER BY co.sequence_number asc;";

			//----------------------- New --------------------------
			$sale5Query = "SELECT co.channel_owner_name,cs.channel_owner__id,sum(cs.bet_639) sum_bet_639,sum(cs.bet_639_luk) sum_bet_639_luk, (sum(cs.total_sell_amount_639)) as sum_game_amount_639,cs.datetime FROM rep_channel_sale cs
							INNER JOIN sys_channel_owner co ON cs.channel_owner__id = co.channel_owner_id
							where cs.status = 'active'  and cs.channel_owner__id not in (7,8) and cs.datetime >= '".$startDate."' and cs.datetime <='".$endDate."'
							GROUP BY cs.channel_owner__id,co.channel_owner_name,cs.datetime,co.sequence_number ORDER BY co.sequence_number asc;";
			
			$sale5 = DB::select(DB::raw($sale5Query));
			foreach ($sale5 as $key => $value) {
				$date = date_create($value->datetime);
				$dateFull = date_format($date, 'Y-m-d');
				$salesPerformance639TelcoDailys[$value->channel_owner_name][$dateFull] = $value->sum_game_amount_639;
			}

			if ((Input::has('total_telco_sales') && Input::has('groupby') && Input::get('groupby') == 'daily')) {
				// Total Telco Sales by Daily

				$sale30Query = "SELECT sum(cs.bet_639) sum_bet_639,sum(cs.bet_639_luk) sum_bet_639_luk,sum(cs.bet_pick5) sum_bet_pick5,sum(cs.bet_pick5_luk) sum_bet_pick5_luk,(sum(cs.total_sell_amount_639))+(sum(cs.total_sell_amount_pick5)) as sum_total_telco_sales, cs.datetime from rep_channel_sale cs where channel_owner__id in (1,2,3,4,5,6) and cs.status  = 'active' and datetime >= '".$startDate."' and datetime <='".$endDate."' GROUP BY cs.datetime ORDER BY cs.datetime asc";
				$sale30 = DB::select(DB::raw($sale30Query));
				foreach ($sale30 as $key => $value) {
					$date = date_create($value->datetime);
					$dateFull = date_format($date, 'Y-m-d');
					$salesPerformanceTotalTelcoSalesDailys[$dateFull] = $value->sum_total_telco_sales;
				}

				//-------------Total Telco Sales by Daily moving avg-----------------
					$date = DateTime::createFromFormat('Y-m-d', $startDate);

					for ($i = 0; $i < 365; $i++) {
						if ($i > 0) {
							$dateIncrease = $date->modify('+1 day');
						} else {
							$dateIncrease = $date;
						}

						/*$day = $dateIncrease->format('d');
						$month = $dateIncrease->format('m');
						$month = $month - 1;
						$year = $dateIncrease->format('Y');*/
						$dateFull = $date->format('Y-m-d');


						$dateLast5 = DateTime::createFromFormat('Y-m-d', $dateFull);
						$dateLast5 = $dateLast5->modify('-6 day');
						$dateFullLast5 = $dateLast5->format('Y-m-d');
						try {
							//-------------New Txn Header-------------------

							$queryTotalTelcoMoving7Day = " select sum(ts1.sum_total_telco_sales)/7 as sum_total_telco_sales FROM
							(SELECT
							(sum(cs.total_sell_amount_639))+(sum(cs.total_sell_amount_pick5)) as sum_total_telco_sales, cs.datetime
							from rep_channel_sale cs
							where channel_owner__id in (1,2,3,4,5,6)
							and cs.status  = 'active'
							and datetime >= '".$dateFullLast5."' and datetime <='".$dateFull." 23:59'
							GROUP BY cs.datetime ORDER BY cs.datetime asc
							) as ts1 ";

							$totalTelcoMoving7Day = DB::select(DB::raw($queryTotalTelcoMoving7Day));

							$value = 'null';
							if ($totalTelcoMoving7Day[0]->sum_total_telco_sales != null) {
								$value = $totalTelcoMoving7Day[0]->sum_total_telco_sales;
							}
							$salesPerformanceTotalTelcoMovingAvgSalesDailys[$dateFull] = $value;

						} catch (Exception $e) {
							$salesPerformanceTotalTelcoMovingAvgSalesDailys[$dateFull] = '0';
						}
						if ($dateFull >= $endDate) {
							break;
						}
					}
			}

			// Total Telco Sales by Weekly
			if ((Input::has('total_telco_sales') && Input::has('groupby') && Input::get('groupby') == 'weekly')){

				$sale31Query = "SELECT sum(sr.sum_total_telco_sales) as sum_total_telco_sales, CAST(coalesce(sr.week, '0') AS integer) as week from
								(select (sum(cs.total_sell_amount_639))+(sum(cs.total_sell_amount_pick5)) as sum_total_telco_sales,to_char(cs.datetime, 'WW') as week
							from rep_channel_sale cs where channel_owner__id in (1,2,3,4,5,6) and cs.datetime is not null and cs.status = 'active' and datetime >= '" . $startDate . "' and datetime <='" . $endDate . "'
							GROUP BY cs.datetime,week
							order by week asc) as sr
							GROUP BY sr.week;";
				$sale31 = DB::select(DB::raw($sale31Query));
				foreach($sale31 as $key => $value){
					$salesPerformanceTotalTelcoSalesWeeklys[$value->week] = $value->sum_total_telco_sales;
				}
			}
			//-----------------Sales Lotto 639 Telco Weekly-----------
//			$sale6Query = "SELECT sr.channel_owner_name,sr.channel_owner__id,sr.sequence_number,sum(sr.sum_game_amount) as sum_game_amount, CAST(coalesce(sr.week, '0') AS integer) as week from
//			(select co.channel_owner_name,cs.channel_owner__id,co.sequence_number,(sum(cs.bet_639+cs.bet_639_luk) * 0.5) as sum_game_amount,to_char(cs.datetime, 'WW') as week
//			from rep_channel_sale cs
//			INNER JOIN sys_channel_owner co ON cs.channel_owner__id = co.channel_owner_id
//			where cs.datetime is not null and cs.status = 'active' and cs.channel_owner__id not in (7,8) and cs.datetime >= '".$startDate."' and cs.datetime <='".$endDate."'
//			GROUP BY cs.channel_owner__id,co.channel_owner_name,cs.datetime,co.sequence_number,cs.datetime,week
//			order by co.sequence_number asc,week asc) as sr
//			GROUP BY sr.channel_owner__id,sr.channel_owner_name,sr.sequence_number,sr.week ORDER BY sr.week asc,sr.sequence_number asc";

			//--------------------------------New-------------------------------
			$sale6Query = "SELECT sr.channel_owner_name,sr.channel_owner__id,sr.sequence_number,sum(sr.sum_game_amount) as sum_game_amount, CAST(coalesce(sr.week, '0') AS integer) as week from
			(select co.channel_owner_name,cs.channel_owner__id,co.sequence_number,(sum(cs.total_sell_amount_639)) as sum_game_amount,to_char(cs.datetime, 'WW') as week
			from rep_channel_sale cs
			INNER JOIN sys_channel_owner co ON cs.channel_owner__id = co.channel_owner_id
			where cs.datetime is not null and cs.status = 'active' and cs.channel_owner__id not in (7,8) and cs.datetime >= '".$startDate."' and cs.datetime <='".$endDate."'
			GROUP BY cs.channel_owner__id,co.channel_owner_name,cs.datetime,co.sequence_number,cs.datetime,week
			order by co.sequence_number asc,week asc) as sr
			GROUP BY sr.channel_owner__id,sr.channel_owner_name,sr.sequence_number,sr.week ORDER BY sr.week asc,sr.sequence_number asc";
			
			$sale6 = DB::select(DB::raw($sale6Query));
			foreach ($sale6 as $key => $value) {
				$date = date_create($value->week);
				$salesPerformance639TelcoWeeklys[$value->channel_owner_name][$value->week] = $value->sum_game_amount;
			}
			
			if (Input::has('sale_pick5') && Input::has('groupby') && Input::get('groupby') == 'daily' ) {
				//-----------------Sales Pick5 Daily-----------
				$sale7Query = "SELECT (sum(cs.total_sell_amount_pick5)) as sum_game_amount_pick5, cs.datetime from rep_channel_sale cs where cs.status = 'active' and cs.datetime >= '".$startDate."' and cs.datetime <='".$endDate."' GROUP BY cs.datetime ORDER BY cs.datetime asc";
				$sale7 = DB::select(DB::raw($sale7Query));
				foreach ($sale7 as $key => $value) {
					$date = date_create($value->datetime);
					$dateFull = date_format($date, 'Y-m-d');
					$salesPerformancePick5Daily[$dateFull] = $value->sum_game_amount_pick5;
				}	
				
				//-------------Total Sale Pick5 moving avg-----------------
				$date = DateTime::createFromFormat('Y-m-d', $startDate);
				
				for ($i = 0; $i < 365; $i++) {
					if ($i > 0) {
						$dateIncrease = $date->modify('+1 day');
					} else {
						$dateIncrease = $date;
					}
					
					$dateFull = $date->format('Y-m-d');
					
					
					$dateLast5 = DateTime::createFromFormat('Y-m-d', $dateFull);
					$dateLast5 = $dateLast5->modify('-6 day');
					$dateFullLast5 = $dateLast5->format('Y-m-d');
					try {
						$queryTotalSalePick5MovingAvgDaily = "SELECT sum(s.sum_game_amount)/7 sum_game_amount
															from
															(
																SELECT (sum(cs.total_sell_amount_pick5)) as sum_game_amount, cs.datetime 
																from rep_channel_sale cs 
																where cs.status  = 'active' 
																and datetime >= '".$dateFullLast5."' and datetime <='".$dateFull." 23:59'
																GROUP BY cs.datetime ORDER BY cs.datetime asc
															) as s";
						
						$totalSalePick5MovingAvgDaily = DB::select(DB::raw($queryTotalSalePick5MovingAvgDaily));
						
						$value = 'null';
						if ($totalSalePick5MovingAvgDaily[0]->sum_game_amount != null) {
							$value = $totalSalePick5MovingAvgDaily[0]->sum_game_amount;
						}
						$salesPerformanceTotalPick5SalesMovingAvgSalesDailys[$dateFull] = $value;
						
					} catch (Exception $e) {
						$salesPerformanceTotalPick5SalesMovingAvgSalesDailys[$dateFull] = '0';
					}
					if ($dateFull >= $endDate) {
						break;
					}
				}
			}
			
			//-----------------Sales Pick5 Weekly-----------
			$sale8Query = "SELECT sum(sr.sum_game_amount) as sum_game_amount, CAST(coalesce(sr.week, '0') AS integer) as week from 
			(select (sum(cs.total_sell_amount_pick5)) as sum_game_amount,to_char(cs.datetime, 'WW') as week
			from rep_channel_sale cs where cs.datetime is not null and cs.status = 'active' and cs.datetime >= '".$startDate."' and cs.datetime <='".$endDate."' 
			GROUP BY cs.datetime,week
			order by week asc) as sr 
			GROUP BY sr.week";
			$sale8 = DB::select(DB::raw($sale8Query));
			foreach ($sale8 as $key => $value) {
				$salesPerformancePick5Weekly[$value->week] = $value->sum_game_amount;
			}	
			
			//-----------------Sales Pick5 Telco Daily-----------
			$sale9Query = "SELECT co.channel_owner_name,cs.channel_owner__id,(sum(cs.total_sell_amount_pick5)) as sum_game_amount_pick,cs.datetime FROM rep_channel_sale cs
							INNER JOIN sys_channel_owner co ON cs.channel_owner__id = co.channel_owner_id 
							where cs.status = 'active' and cs.channel_owner__id not in (7,8) and cs.datetime >= '".$startDate."' and cs.datetime <='".$endDate."' 
							GROUP BY cs.channel_owner__id,co.channel_owner_name,cs.datetime,co.sequence_number ORDER BY co.sequence_number asc";
			$sale9 = DB::select(DB::raw($sale9Query));
			foreach ($sale9 as $key => $value) {
				$date = date_create($value->datetime);
				$dateFull = date_format($date, 'Y-m-d');
				$salesPerformancePick5TelcoDailys[$value->channel_owner_name][$dateFull] = $value->sum_game_amount_pick;
			}	
			
			//-----------------Sales Pick5 Telco Weekly-----------
			$sale10Query = "SELECT sr.channel_owner_name,sr.channel_owner__id,sr.sequence_number,sum(sr.sum_game_amount) as sum_game_amount, CAST(coalesce(sr.week, '0') AS integer) as week from 
			(select co.channel_owner_name,cs.channel_owner__id,co.sequence_number,(sum(cs.total_sell_amount_pick5)) as sum_game_amount,to_char(cs.datetime, 'WW') as week
			from rep_channel_sale cs 
			INNER JOIN sys_channel_owner co ON cs.channel_owner__id = co.channel_owner_id 
			where cs.datetime is not null and cs.status = 'active' and cs.channel_owner__id not in (7,8) and cs.datetime >= '".$startDate."' and cs.datetime <='".$endDate."' 
			GROUP BY cs.channel_owner__id,co.channel_owner_name,cs.datetime,co.sequence_number,cs.datetime,week
			order by co.sequence_number asc,week asc) as sr 
			GROUP BY sr.channel_owner__id,sr.channel_owner_name,sr.sequence_number,sr.week ORDER BY sr.week asc,sr.sequence_number asc";
			$sale10 = DB::select(DB::raw($sale10Query));
			foreach ($sale10 as $key => $value) {
				$date = date_create($value->week);
				$salesPerformancePick5TelcoWeeklys[$value->channel_owner_name][$value->week] = $value->sum_game_amount;
			}	
			
			//-----------------7 LSDL Sale Daily-------------------
			if (Input::has('lsdl_sale') && Input::has('groupby') && Input::get('groupby') == 'daily' ) {
				//					$sale11Query = "SELECT (sum(cs.bet_639+cs.bet_639_luk) * 0.5)+(sum(cs.bet_pick5+cs.bet_pick5_luk) * 0.25) as sum_game_amount, cs.datetime, date_trunc('week', cs.datetime) AS week
				//							from rep_channel_sale cs
				//							inner join sys_channel_owner co on cs.channel_owner__id = co.channel_owner_id
				//							where co.channel_owner_id = 7 and cs.status = 'active' and cs.datetime >= '".$startDate."' and cs.datetime <='".$endDate."'
				//							GROUP BY cs.datetime ORDER BY cs.datetime asc";
	
				//-----------------7 LSDL Sale Daily New-------------------
				$sale11Query = "SELECT (sum(cs.total_sell_amount_639))+(sum(cs.total_sell_amount_pick5)) as sum_game_amount, cs.datetime, date_trunc('week', cs.datetime) AS week
								from rep_channel_sale cs
								inner join sys_channel_owner co on cs.channel_owner__id = co.channel_owner_id
								where co.channel_owner_id = 7 and cs.status = 'active' and cs.datetime >= '".$startDate."' and cs.datetime <='".$endDate."'
								GROUP BY cs.datetime ORDER BY cs.datetime asc";
	
				$sale11 = DB::select(DB::raw($sale11Query));
				foreach ($sale11 as $key => $value) {
					$date = date_create($value->datetime);
					$dateFull = date_format($date, 'Y-m-d');
					$salesPerformanceLSDLSaleDailys[$dateFull] = $value->sum_game_amount;
				}
				
				//-------------lsdl sale moving avg-----------------
				$date = DateTime::createFromFormat('Y-m-d', $startDate);
				
				for ($i = 0; $i < 365; $i++) {
					if ($i > 0) {
						$dateIncrease = $date->modify('+1 day');
					} else {
						$dateIncrease = $date;
					}
					
					/*$day = $dateIncrease->format('d');
					$month = $dateIncrease->format('m');
					$month = $month - 1;
					$year = $dateIncrease->format('Y');*/
					$dateFull = $date->format('Y-m-d');
					
					
					$dateLast5 = DateTime::createFromFormat('Y-m-d', $dateFull);
					$dateLast5 = $dateLast5->modify('-6 day');
					$dateFullLast5 = $dateLast5->format('Y-m-d');
					try {						
						//-------------New Txn Header-------------------
						
						$queryLSDLSalesMovingAvg = "SELECT sum(tdo.sum_game_amount)/7 as sum_game_amount from
						(
						SELECT (sum(cs.total_sell_amount_639))+(sum(cs.total_sell_amount_pick5)) as sum_game_amount, cs.datetime
								from rep_channel_sale cs
								inner join sys_channel_owner co on cs.channel_owner__id = co.channel_owner_id
								where co.channel_owner_id = 7 and cs.status = 'active' and cs.datetime >= '".$dateFullLast5."' and cs.datetime <='".$dateFull."'
								GROUP BY cs.datetime ORDER BY cs.datetime asc
						) as tdo";
		
						$LSDLSalesMovingAvg = DB::select(DB::raw($queryLSDLSalesMovingAvg));
						
						$value = 'null';
						if ($LSDLSalesMovingAvg[0]->sum_game_amount != null) {
							$value = $LSDLSalesMovingAvg[0]->sum_game_amount;
						}
						$salesPerformanceLSDLSaleMovingAvgDailys[$dateFull] = $value;
						
					} catch (Exception $e) {
						$salesPerformanceLSDLSaleMovingAvgDailys[$dateFull] = '0';
					}
					if ($dateFull >= $endDate) {
						break;
					}
				}	
			}
			
			//-----------------7.1 LSDL Sale Weekly-----------
//			$sale22Query = "SELECT sum(sr.sum_game_amount) as sum_game_amount, CAST(coalesce(sr.week, '0') AS integer) as week from
//							(
//							SELECT (sum(cs.bet_639+cs.bet_639_luk) * 0.5)+(sum(cs.bet_pick5+cs.bet_pick5_luk) * 0.25) as sum_game_amount,to_char(cs.datetime, 'WW') as week
//							from rep_channel_sale cs
//							inner join sys_channel_owner co on cs.channel_owner__id = co.channel_owner_id
//							where co.channel_owner_id = 7 and cs.status = 'active' and cs.datetime >= '".$startDate."' and cs.datetime <='".$endDate."'
//							GROUP BY cs.datetime ORDER BY cs.datetime asc
//							) as sr
//							GROUP BY sr.week ORDER BY sr.week asc";

			//-----------------New-------------------
			$sale22Query = "SELECT sum(sr.sum_game_amount) as sum_game_amount, CAST(coalesce(sr.week, '0') AS integer) as week from
							(
							SELECT (sum(cs.total_sell_amount_639))+(sum(cs.total_sell_amount_pick5)) as sum_game_amount,to_char(cs.datetime, 'WW') as week
							from rep_channel_sale cs
							inner join sys_channel_owner co on cs.channel_owner__id = co.channel_owner_id
							where co.channel_owner_id = 7 and cs.status = 'active' and cs.datetime >= '".$startDate."' and cs.datetime <='".$endDate."'
							GROUP BY cs.datetime ORDER BY cs.datetime asc
							) as sr
							GROUP BY sr.week ORDER BY sr.week asc";

			$sale22 = DB::select(DB::raw($sale22Query));
			foreach ($sale22 as $key => $value) {
				$date = date_create($value->week);
				$salesPerformanceLSDLSaleWeeklys[$value->week] = $value->sum_game_amount;
			}	
			
			//-----------------8 Num LSDL Online Daily-------------------
			/*$sale12Query = "SELECT sum(cs.active_pos_total)  as sum_dealer_online, cs.datetime, date_trunc('week', cs.datetime) AS week 
							from rep_channel_sale cs
							inner join sys_channel_owner co on cs.channel_owner__id = co.channel_owner_id
							where co.channel_owner_id = 7 and cs.status = 'active' and cs.datetime >= '".$startDate."' and cs.datetime <='".$endDate."' 
							GROUP BY cs.datetime ORDER BY cs.datetime asc";*/
			if (Input::has('lsdl_online')) {
				
				//-------------lsdl online-----------------
				$sale12Query = "select * from (select date_trunc('day', t.datetime) AS day,count(DISTINCT t.dealer__id) as sum_dealer_online 
							from txn_transaction_header t
							inner join dealer d on d.id = t.dealer__id
							where  t.datetime >= '".$startDate."' and t.datetime <='".$endDate." 23:59' and t.service_type__id in ('639','639luk','pick5','pick5l') and d.status in ('active','used') and d.type_account = 'real' GROUP BY 1 ORDER BY day asc) as dt ORDER BY day desc";
							
				$sale12 = DB::select(DB::raw($sale12Query));
				foreach ($sale12 as $key => $value) {
					$date = date_create($value->day);
					$dateFull = date_format($date, 'Y-m-d');
					$salesPerformanceLSDLOnlineDailys[$dateFull] = $value->sum_dealer_online;
				}
				
				//-------------lsdl online moving avg-----------------
				$date = DateTime::createFromFormat('Y-m-d', $startDate);
				
				for ($i = 0; $i < 365; $i++) {
					if ($i > 0) {
						$dateIncrease = $date->modify('+1 day');
					} else {
						$dateIncrease = $date;
					}
					
					/*$day = $dateIncrease->format('d');
					$month = $dateIncrease->format('m');
					$month = $month - 1;
					$year = $dateIncrease->format('Y');*/
					$dateFull = $date->format('Y-m-d');
					
					
					$dateLast5 = DateTime::createFromFormat('Y-m-d', $dateFull);
					$dateLast5 = $dateLast5->modify('-6 day');
					$dateFullLast5 = $dateLast5->format('Y-m-d');
					try {						
						//-------------New Txn Header-------------------
						
						$queryDealerOnlineMoving7Day = "SELECT sum(tdo.sum_dealer_online)/7 as sum_dealer_online from
						(
						select * from (select date_trunc('day', t.datetime) AS day,count(DISTINCT t.dealer__id) as sum_dealer_online 
													from txn_transaction_header t
													inner join dealer d on d.id = t.dealer__id
													where t.status = 'TS' and t.datetime >= '".$dateFullLast5."' and t.datetime <='".$dateFull." 23:59' and t.service_type__id in ('639','639luk','pick5','pick5l') and d.type_account = 'real' GROUP BY 1 ORDER BY day asc) as dt ORDER BY day desc
						) as tdo";
		
						$dealerOnlineMoving7Day = DB::select(DB::raw($queryDealerOnlineMoving7Day));
						$value = 'null';
						if ($dealerOnlineMoving7Day[0]->sum_dealer_online != null) {
							$value = $dealerOnlineMoving7Day[0]->sum_dealer_online;
						}
						$salesPerformanceLSDLOnlineMovingAvgDailys[$dateFull] = $value;
						
					} catch (Exception $e) {
						$salesPerformanceLSDLOnlineMovingAvgDailys[$dateFull] = '0';
					}
					if ($dateFull >= $endDate) {
						break;
					}
				}
			}			
			//print_r($salesPerformanceLSDLOnlineMovingAvgDailys);
			//die();
			//-----------------8.1 Num LSDL Online Weekly-----------
			/*$sale23Query = "SELECT sum(sr.sum_dealer_online) as sum_dealer_online, CAST(coalesce(sr.week, '0') AS integer) as week from 
								(SELECT sum(cs.active_pos_total)  as sum_dealer_online, cs.datetime, to_char(cs.datetime, 'WW') as week 
							from rep_channel_sale cs 
							inner join sys_channel_owner co on cs.channel_owner__id = co.channel_owner_id
							where co.channel_owner_id = 7 and cs.status = 'active' and cs.datetime >= '".$startDate."' and cs.datetime <='".$endDate."'
							GROUP BY cs.datetime ORDER BY cs.datetime asc) as sr
							GROUP BY sr.week
							ORDER BY sr.week asc";*/
			if (Input::has('lsdl_online')) {				
				$sale23Query = "SELECT CAST(coalesce(to_char(tx.day, 'WW'), '0') AS integer) as week,tx.n_dealer as sum_dealer_online
								from
								(select * from (select date_trunc('day', t.datetime) AS day,count(DISTINCT t.dealer__id) as n_dealer 
								from txn_transaction_header t
								inner join dealer d on d.id = t.dealer__id
								where t.datetime >= '".$startDate."' and t.datetime <='".$endDate." 23:59' and d.status in ('active','used') and t.service_type__id in ('639','639luk','pick5','pick5l')
								 and d.type_account = 'real' GROUP BY 1 ORDER BY day asc) as dt ORDER BY day desc) as tx";
								 
				$sale23Query = "select sum(tx.sum_dealer_online) as sum_dealer_online,tx.week

				FROM
				(
				SELECT CAST(coalesce(to_char(tx.day, 'WW'), '0') AS integer) as week,tx.n_dealer as sum_dealer_online
												from
												(select * from (select date_trunc('day', t.datetime) AS day,count(DISTINCT t.dealer__id) as n_dealer 
												from txn_transaction_header t
												inner join dealer d on d.id = t.dealer__id
												where t.datetime >= '".$startDate."' and t.datetime <='".$endDate." 23:59' and d.status in ('active','used') and t.service_type__id in ('639','639luk','pick5','pick5l')
												and d.type_account = 'real' GROUP BY 1 ORDER BY day asc) as dt ORDER BY day desc) as tx
				) tx
				GROUP BY tx.week";
				$sale23 = DB::select(DB::raw($sale23Query));
				foreach ($sale23 as $key => $value) {
					$date = date_create($value->week);
					$salesPerformanceLSDLOnlineWeeklys[$value->week] = $value->sum_dealer_online;
				}	
			}
			//-----------------9 Sales per Dragon Dealer Online Daily-------------------
			if (Input::has('sale_per_dragon_dealer_online') && Input::has('groupby') && Input::get('groupby') == 'daily' ) {
				try {
					$sale13Query = "select (sd.sum_sale_game/n_dealer) as per_dragon,sd.day
									from
									(
									select * from (select date_trunc('day', t.datetime) AS day,SUM(t.requested_value)/4000 sum_sale_game,count(DISTINCT t.dealer__id) as n_dealer 
											from txn_transaction_header t
											inner join dealer d on d.id = t.dealer__id
											where t.datetime >= '".$startDate."' and t.datetime <='".$endDate." 23:59' 
											and d.status in ('active','used') 
											and t.service_type__id in ('639','639luk','pick5','pick5l')
											and d.type_account = 'real' 
											and t.status in ('TS','TC')
											GROUP BY 1 ORDER BY day asc) as dt ORDER BY day desc
									) as sd";
									
					$sale13 = DB::select(DB::raw($sale13Query));
					foreach ($sale13 as $key => $value) {
						$date = date_create($value->day);
						$dateFull = date_format($date, 'Y-m-d');
						$salesPerformanceSalePerDragonDailys[$dateFull] = $value->per_dragon;
					}
				} catch (Exception $e) {
					//echo $e;
				}
				//die();
				//-------------9 Sales per Dragon Dealer Online Daily moving avg-----------------
				$date = DateTime::createFromFormat('Y-m-d', $startDate);
				
				for ($i = 0; $i < 365; $i++) {
					if ($i > 0) {
						$dateIncrease = $date->modify('+1 day');
					} else {
						$dateIncrease = $date;
					}
					
					/*$day = $dateIncrease->format('d');
					$month = $dateIncrease->format('m');
					$month = $month - 1;
					$year = $dateIncrease->format('Y');*/
					$dateFull = $date->format('Y-m-d');
					
					
					$dateLast5 = DateTime::createFromFormat('Y-m-d', $dateFull);
					$dateLast5 = $dateLast5->modify('-6 day');
					$dateFullLast5 = $dateLast5->format('Y-m-d');
					try {
						
						$querySalesPerdragonDealerOnlineMoving = "select SUM(msd.per_dragon)/7 as moving_per_dragon
						from
						(
							select sd.*,(sd.sum_sale_game/n_dealer) as per_dragon
							from
							(
							select * from (select date_trunc('day', t.datetime) AS day,SUM(t.requested_value)/4000 sum_sale_game,count(DISTINCT t.dealer__id) as n_dealer 
															from txn_transaction_header t
															inner join dealer d on d.id = t.dealer__id
															where t.datetime >= '".$dateFullLast5."' and t.datetime <='".$dateFull." 23:59' 
															and d.status in ('active','used') 
															and t.service_type__id in ('639','639luk','pick5','pick5l')
															and d.type_account = 'real' 
															and t.status in ('TS','TC')
															GROUP BY 1 ORDER BY day asc) as dt ORDER BY day desc
							) as sd
						) as msd"; 
						
						$salesPerdragonDealerOnlineMoving = DB::select(DB::raw($querySalesPerdragonDealerOnlineMoving));
						
						$moving = 'null';
						if ($salesPerdragonDealerOnlineMoving[0]->moving_per_dragon !=  null) {
							$moving = $salesPerdragonDealerOnlineMoving[0]->moving_per_dragon;
						}
						$salesPerformanceSalePerDragonMovingAvgDailys[$dateFull] = $moving;
						
					} catch (Exception $e) {
						$salesPerformanceSalePerDragonMovingAvgDailys[$dateFull] = '0';
					}
					if ($dateFull >= $endDate) {
						break;
					}
				}
			}
			
			
			//-----------------9.1 Sales per Dragon Dealer Online Weekly-----------
			if (Input::has('sale_per_dragon_dealer_online') && Input::has('groupby') && Input::get('groupby') == 'weekly' ) {
				try {
	//				$sale24Query = "SELECT sum(sr1.per_dragon) as per_dragon, CAST(coalesce(sr1.week, '0') AS integer) as week from
	//								(SELECT (sr.sum_game_amount/sr.sum_dealer_online) as per_dragon, sr.datetime,sr.week
	//							FROM
	//								(SELECT (sum(cs.bet_639+cs.bet_639_luk) * 0.5)+(sum(cs.bet_pick5+cs.bet_pick5_luk) * 0.25) as sum_game_amount,sum(cs.active_pos_639+cs.active_pos_pick5)  as sum_dealer_online, cs.datetime, to_char(cs.datetime, 'WW') as week
	//							from rep_channel_sale cs
	//							inner join sys_channel_owner co on cs.channel_owner__id = co.channel_owner_id
	//							where co.channel_owner_id = 7 and cs.status = 'active' and cs.datetime >= '".$startDate."' and cs.datetime <='".$endDate."'
	//							GROUP BY cs.datetime ORDER BY cs.datetime asc) as sr) as sr1
	//							GROUP BY sr1.week
	//							ORDER BY sr1.week";
					//--------------------New----------------------------
					$sale24Query = "SELECT sum(sr1.per_dragon) as per_dragon, CAST(coalesce(sr1.week, '0') AS integer) as week from
									(SELECT (sr.sum_game_amount/sr.sum_dealer_online) as per_dragon, sr.datetime,sr.week
								FROM
									(SELECT (sum(cs.bet_639+cs.bet_639_luk) * 0.5)+(sum(cs.bet_pick5+cs.bet_pick5_luk) * 0.25) as sum_game_amount,sum(cs.active_pos_total)  as sum_dealer_online, cs.datetime, to_char(cs.datetime, 'WW') as week
								from rep_channel_sale cs
								inner join sys_channel_owner co on cs.channel_owner__id = co.channel_owner_id
								where co.channel_owner_id = 7 and cs.status = 'active' and cs.datetime >= '".$startDate."' and cs.datetime <='".$endDate."'
								GROUP BY cs.datetime ORDER BY cs.datetime asc) as sr) as sr1
								GROUP BY sr1.week
								ORDER BY sr1.week";
	
					$sale24 = DB::select(DB::raw($sale24Query));
					foreach ($sale24 as $key => $value) {
						$date = date_create($value->week);
						$salesPerformanceSalePerDragonWeeklys[$value->week] = $value->per_dragon;
					}
				} catch (Exception $e) {
					
				}
			}
			
			
			//-----------------10.1 Smart Sale 639 Daily-------------------
//			$sale14Query = "SELECT co.channel_owner_name,co.channel_owner_id,(sum(cs.bet_639+cs.bet_639_luk) * 0.5) as sum_game_amount, cs.datetime, date_trunc('week', cs.datetime) AS week
//							from rep_channel_sale cs
//							inner join sys_channel_owner co on cs.channel_owner__id = co.channel_owner_id
//							where co.channel_owner_id in (4,5,6) and cs.status = 'active' and cs.datetime >= '".$startDate."' and cs.datetime <='".$endDate."'
//							GROUP BY co.channel_owner_name,co.channel_owner_id, cs.datetime ORDER BY co.sequence_number asc,cs.datetime asc";

			//------------------New-----------------------
			$sale14Query = "SELECT co.channel_owner_name,co.channel_owner_id,(sum(cs.total_sell_amount_639)) as sum_game_amount, cs.datetime, date_trunc('week', cs.datetime) AS week
							from rep_channel_sale cs
							inner join sys_channel_owner co on cs.channel_owner__id = co.channel_owner_id
							where co.channel_owner_id in (4,5,6) and cs.status = 'active' and cs.datetime >= '".$startDate."' and cs.datetime <='".$endDate."'
							GROUP BY co.channel_owner_name,co.channel_owner_id, cs.datetime ORDER BY co.sequence_number asc,cs.datetime asc";

			$sale14 = DB::select(DB::raw($sale14Query));
			foreach ($sale14 as $key => $value) {
				$date = date_create($value->datetime);
				$dateFull = date_format($date, 'Y-m-d');
				$salesPerformanceSmartSale639Dailys[$value->channel_owner_name][$dateFull] = $value->sum_game_amount;
			}
			
			//-----------------10.1 Smart Sale 639 Weekly-----------
//			$sale25Query = "SELECT sr.channel_owner_name,sr.channel_owner_id,sum(sr.sum_game_amount) as sum_game_amount, CAST(coalesce(sr.week, '0') AS integer) as week from
//								(SELECT co.channel_owner_name,co.channel_owner_id,(sum(cs.bet_639+cs.bet_639_luk) * 0.5) as sum_game_amount, cs.datetime, to_char(cs.datetime, 'WW') as week
//							from rep_channel_sale cs
//							inner join sys_channel_owner co on cs.channel_owner__id = co.channel_owner_id
//							where co.channel_owner_id in (4,5,6) and cs.status = 'active' and cs.datetime >= '".$startDate."' and cs.datetime <='".$endDate."'
//							GROUP BY co.channel_owner_name,co.channel_owner_id, cs.datetime ORDER BY co.sequence_number asc,cs.datetime asc) as sr
//							GROUP BY sr.channel_owner_name,sr.channel_owner_id,sr.week
//							ORDER BY sr.week asc";

			//--------------New-------------------------
			$sale25Query = "SELECT sr.channel_owner_name,sr.channel_owner_id,sum(sr.sum_game_amount) as sum_game_amount, CAST(coalesce(sr.week, '0') AS integer) as week from
								(SELECT co.channel_owner_name,co.channel_owner_id,(sum(cs.total_sell_amount_639)) as sum_game_amount, cs.datetime, to_char(cs.datetime, 'WW') as week
							from rep_channel_sale cs
							inner join sys_channel_owner co on cs.channel_owner__id = co.channel_owner_id
							where co.channel_owner_id in (4,5,6) and cs.status = 'active' and cs.datetime >= '".$startDate."' and cs.datetime <='".$endDate."'
							GROUP BY co.channel_owner_name,co.channel_owner_id, cs.datetime ORDER BY co.sequence_number asc,cs.datetime asc) as sr
							GROUP BY sr.channel_owner_name,sr.channel_owner_id,sr.week
							ORDER BY sr.week asc";

			$sale25 = DB::select(DB::raw($sale25Query));
			foreach ($sale25 as $key => $value) {
				$date = date_create($value->week);
				$salesPerformanceSmartSale639Weeklys[$value->channel_owner_name][$value->week] = $value->sum_game_amount;
			}
			
			//-----------------10.2 Smart Sale Pick5 Daily-------------------
			$sale15Query = "SELECT co.channel_owner_name,co.channel_owner_id,(sum(cs.total_sell_amount_pick5)) as sum_game_amount, cs.datetime, date_trunc('week', cs.datetime) AS week
							from rep_channel_sale cs
							inner join sys_channel_owner co on cs.channel_owner__id = co.channel_owner_id
							where co.channel_owner_id in (4,5,6) and cs.status = 'active' and cs.datetime >= '".$startDate."' and cs.datetime <='".$endDate."' 
							GROUP BY co.channel_owner_name,co.channel_owner_id, cs.datetime ORDER BY co.sequence_number asc,cs.datetime asc";
			$sale15 = DB::select(DB::raw($sale15Query));
			foreach ($sale15 as $key => $value) {
				$date = date_create($value->datetime);
				$dateFull = date_format($date, 'Y-m-d');
				$salesPerformanceSmartSalePick5Dailys[$value->channel_owner_name][$dateFull] = $value->sum_game_amount;
			}
			
			//-----------------10.2 Smart Sale Pick5 Weekly-----------
			$sale26Query = "SELECT sr.channel_owner_name,sr.channel_owner_id,sum(sr.sum_game_amount) as sum_game_amount, CAST(coalesce(sr.week, '0') AS integer) as week from 
								(SELECT co.channel_owner_name,co.channel_owner_id,(sum(cs.total_sell_amount_pick5)) as sum_game_amount, cs.datetime, cs.datetime, to_char(cs.datetime, 'WW') as week
							from rep_channel_sale cs 
							inner join sys_channel_owner co on cs.channel_owner__id = co.channel_owner_id
							where co.channel_owner_id in (4,5,6) and cs.status = 'active' and cs.datetime >= '".$startDate."' and cs.datetime <='".$endDate."'
							GROUP BY co.channel_owner_name,co.channel_owner_id, cs.datetime ORDER BY co.sequence_number asc,cs.datetime asc) as sr
							GROUP BY sr.channel_owner_name,sr.channel_owner_id,sr.week
							ORDER BY sr.week";
			$sale26 = DB::select(DB::raw($sale26Query));
			foreach ($sale26 as $key => $value) {
				$date = date_create($value->week);
				$salesPerformanceSmartSalePick5Weeklys[$value->channel_owner_name][$value->week] = $value->sum_game_amount;
			}
			
			//-----------------11.1 Metfone Sale 639 Daily-------------------
//			$sale16Query = "SELECT co.channel_owner_name,co.channel_owner_id,(sum(cs.bet_639+cs.bet_639_luk) * 0.5) as sum_game_amount, cs.datetime, date_trunc('week', cs.datetime) AS week
//							from rep_channel_sale cs
//							inner join sys_channel_owner co on cs.channel_owner__id = co.channel_owner_id
//							where co.channel_owner_id in (1,2,3) and cs.status = 'active' and cs.datetime >= '".$startDate."' and cs.datetime <='".$endDate."'
//							GROUP BY co.channel_owner_name,co.channel_owner_id, cs.datetime ORDER BY co.sequence_number asc,cs.datetime asc";

			//----------------------New-------------------
			$sale16Query = "SELECT co.channel_owner_name,co.channel_owner_id,(sum(cs.total_sell_amount_639)) as sum_game_amount, cs.datetime, date_trunc('week', cs.datetime) AS week
							from rep_channel_sale cs
							inner join sys_channel_owner co on cs.channel_owner__id = co.channel_owner_id
							where co.channel_owner_id in (1,2,3) and cs.status = 'active' and cs.datetime >= '".$startDate."' and cs.datetime <='".$endDate."'
							GROUP BY co.channel_owner_name,co.channel_owner_id, cs.datetime ORDER BY co.sequence_number asc,cs.datetime asc";

			$sale16 = DB::select(DB::raw($sale16Query));
			foreach ($sale16 as $key => $value) {
				$date = date_create($value->datetime);
				$dateFull = date_format($date, 'Y-m-d');
				$salesPerformanceMetfoneSale639Dailys[$value->channel_owner_name][$dateFull] = $value->sum_game_amount;
			}
			
			//-----------------11.1 Metfone Sale 639 Weekly-------------------
//			$sale27Query = "SELECT sr.channel_owner_name,sr.channel_owner_id,sum(sr.sum_game_amount) as sum_game_amount, CAST(coalesce(sr.week, '0') AS integer) as week from
//								(SELECT co.channel_owner_name,co.channel_owner_id,(sum(cs.bet_639+cs.bet_639_luk) * 0.5) as sum_game_amount, cs.datetime, to_char(cs.datetime, 'WW') as week
//							from rep_channel_sale cs
//							inner join sys_channel_owner co on cs.channel_owner__id = co.channel_owner_id
//							where co.channel_owner_id in (1,2,3) and cs.status = 'active' and cs.datetime >= '".$startDate."' and cs.datetime <='".$endDate."'
//							GROUP BY co.channel_owner_name,co.channel_owner_id, cs.datetime ORDER BY co.sequence_number asc,cs.datetime asc) as sr
//							GROUP BY sr.channel_owner_name,sr.channel_owner_id,sr.week
//							ORDER BY sr.week";

			//--------------------------New---------------------
			$sale27Query = "SELECT sr.channel_owner_name,sr.channel_owner_id,sum(sr.sum_game_amount) as sum_game_amount, CAST(coalesce(sr.week, '0') AS integer) as week from
								(SELECT co.channel_owner_name,co.channel_owner_id,(sum(cs.total_sell_amount_639)) as sum_game_amount, cs.datetime, to_char(cs.datetime, 'WW') as week
							from rep_channel_sale cs
							inner join sys_channel_owner co on cs.channel_owner__id = co.channel_owner_id
							where co.channel_owner_id in (1,2,3) and cs.status = 'active' and cs.datetime >= '".$startDate."' and cs.datetime <='".$endDate."'
							GROUP BY co.channel_owner_name,co.channel_owner_id, cs.datetime ORDER BY co.sequence_number asc,cs.datetime asc) as sr
							GROUP BY sr.channel_owner_name,sr.channel_owner_id,sr.week
							ORDER BY sr.week";

			$sale27 = DB::select(DB::raw($sale27Query));
			foreach ($sale27 as $key => $value) {
				$date = date_create($value->week);
				$salesPerformanceMetfoneSale639Weeklys[$value->channel_owner_name][$value->week] = $value->sum_game_amount;
			}
			
			//-----------------11.2 Metfone Sale Pick5 Daily-------------------
			$sale17Query = "SELECT co.channel_owner_name,co.channel_owner_id,(sum(cs.total_sell_amount_pick5)) as sum_game_amount, cs.datetime, date_trunc('week', cs.datetime) AS week
							from rep_channel_sale cs
							inner join sys_channel_owner co on cs.channel_owner__id = co.channel_owner_id
							where co.channel_owner_id in (1,2,3)  and cs.status = 'active' and cs.datetime >= '".$startDate."' and cs.datetime <='".$endDate."' 
							GROUP BY co.channel_owner_name,co.channel_owner_id, cs.datetime ORDER BY co.sequence_number asc,cs.datetime asc";
			$sale17 = DB::select(DB::raw($sale17Query));
			foreach ($sale17 as $key => $value) {
				$date = date_create($value->datetime);
				$dateFull = date_format($date, 'Y-m-d');
				$salesPerformanceMetfoneSalePick5Dailys[$value->channel_owner_name][$dateFull] = $value->sum_game_amount;
			}
			
			//-----------------11.2 Metfone Sale Pick5 Weekly-------------------
			$sale28Query = "SELECT sr.channel_owner_name,sr.channel_owner_id,sum(sr.sum_game_amount) as sum_game_amount, CAST(coalesce(sr.week, '0') AS integer) as week from 
								(SELECT co.channel_owner_name,co.channel_owner_id,(sum(cs.total_sell_amount_pick5)) as sum_game_amount, cs.datetime, to_char(cs.datetime, 'WW') as week
							from rep_channel_sale cs
							inner join sys_channel_owner co on cs.channel_owner__id = co.channel_owner_id
							where co.channel_owner_id in (1,2,3) and  cs.status = 'active' and cs.datetime >= '".$startDate."' and cs.datetime <='".$endDate."'
							GROUP BY co.channel_owner_name,co.channel_owner_id, cs.datetime ORDER BY co.sequence_number asc,cs.datetime asc) as sr
							GROUP BY sr.channel_owner_name,sr.channel_owner_id,sr.week
							ORDER BY sr.week";
			$sale28 = DB::select(DB::raw($sale28Query));
			foreach ($sale28 as $key => $value) {
				$date = date_create($value->week);
				$salesPerformanceMetfoneSalePick5Weeklys[$value->channel_owner_name][$value->week] = $value->sum_game_amount;
			}
			
			if ((Input::has('num_subcriber_online') && Input::has('groupby') && Input::get('groupby') == 'daily')) {
				//-----------------12 Num Subscriber Online Daily-------------------
				$sale18Query = "SELECT sum(cs.subscriber_pick_5+cs.subscriber_639_n)  as subscriber_online, cs.datetime, date_trunc('week', cs.datetime) AS week 
								from rep_channel_sale cs
								inner join sys_channel_owner co on cs.channel_owner__id = co.channel_owner_id where cs.status = 'active' and cs.datetime >= '".$startDate."' and cs.datetime <='".$endDate."' 
								GROUP BY cs.datetime ORDER BY cs.datetime asc";
				$sale18 = DB::select(DB::raw($sale18Query));
				foreach ($sale18 as $key => $value) {
					$date = date_create($value->datetime);
					$dateFull = date_format($date, 'Y-m-d');
					$salesPerformanceNumSubscriberOnlineDailys[$dateFull] = $value->subscriber_online;
				}
				
				//-------------Total Num Subscriber Online moving avg-----------------
				$date = DateTime::createFromFormat('Y-m-d', $startDate);
				
				for ($i = 0; $i < 365; $i++) {
					if ($i > 0) {
						$dateIncrease = $date->modify('+1 day');
					} else {
						$dateIncrease = $date;
					}
					
					$dateFull = $date->format('Y-m-d');
					
					
					$dateLast5 = DateTime::createFromFormat('Y-m-d', $dateFull);
					$dateLast5 = $dateLast5->modify('-6 day');
					$dateFullLast5 = $dateLast5->format('Y-m-d');
					try {								
						$queryNumSubscriberOnlineMovingAvgDaily = "SELECT sum(s.subscriber_online)/7 subscriber_online
							from
							(
								SELECT sum(cs.subscriber_pick_5+cs.subscriber_639_n)  as subscriber_online, cs.datetime, date_trunc('week', cs.datetime) AS week 
								from rep_channel_sale cs
								inner join sys_channel_owner co on cs.channel_owner__id = co.channel_owner_id 
								where cs.status = 'active' 
								and cs.datetime >= '".$dateFullLast5."' and cs.datetime <='".$dateFull." 23:59'
								GROUP BY cs.datetime ORDER BY cs.datetime asc
							) as s";
						
						$totalNumSubscriberOnlineMovingAvgDaily = DB::select(DB::raw($queryNumSubscriberOnlineMovingAvgDaily));
						
						$value = 'null';
						if ($totalNumSubscriberOnlineMovingAvgDaily[0]->subscriber_online != null) {
							$value = $totalNumSubscriberOnlineMovingAvgDaily[0]->subscriber_online;
						}
						$salesPerformanceTotalNumSubscriberOnlineMovingAvgSalesDailys[$dateFull] = $value;
						
					} catch (Exception $e) {
						$salesPerformanceTotalNumSubscriberOnlineMovingAvgSalesDailys[$dateFull] = '0';
					}
					if ($dateFull >= $endDate) {
						break;
					}
				}
			}
			
			//-----------------12.1 Num Subscriber Online Weekly-------------------
			$sale29Query = "SELECT sum(sr.subscriber_online) as subscriber_online, CAST(coalesce(sr.week, '0') AS integer) as week from 
								(SELECT sum(cs.subscriber_pick_5+cs.subscriber_639_n)  as subscriber_online, cs.datetime, to_char(cs.datetime, 'WW') as week  
							from rep_channel_sale cs
							inner join sys_channel_owner co on cs.channel_owner__id = co.channel_owner_id
							where cs.status = 'active'  and cs.datetime >= '".$startDate."' and cs.datetime <='".$endDate."'
							GROUP BY cs.datetime ORDER BY cs.datetime asc) as sr
							GROUP BY sr.week
							ORDER BY sr.week";
			$sale29 = DB::select(DB::raw($sale29Query));
			foreach ($sale29 as $key => $value) {
				$date = date_create($value->week);
				$salesPerformanceNumSubscriberOnlineWeeklys[$value->week] = $value->subscriber_online;
			}
			
			if ((Input::has('sale_per_subcriber_online') && Input::has('groupby') && Input::get('groupby') == 'daily')) {
				//-----------------13 Sale Per Subscriber Daily-------------------
	//			$sale19Query = "select (sr.sum_game_amount/sr.subscriber_online) as sale_subscriber,sr.datetime From
	//							(SELECT sum(cs.bet_pick5_luk) sum_bet_pick5_luk,(sum(cs.bet_639+cs.bet_639_luk) * 0.5)+(sum(cs.bet_pick5+cs.bet_pick5_luk) * 0.25) as sum_game_amount,sum(cs.subscriber_pick_5+cs.subscriber_639_n)  as subscriber_online, cs.datetime, date_trunc('week', cs.datetime) AS week
	//							from rep_channel_sale cs
	//							inner join sys_channel_owner co on cs.channel_owner__id = co.channel_owner_id where cs.status = 'active' and cs.datetime >= '".$startDate."' and cs.datetime <='".$endDate."'
	//							GROUP BY cs.datetime ORDER BY cs.datetime asc) as sr";
	
				//-----------------New---------------------
				$sale19Query = "select (sr.sum_game_amount/sr.subscriber_online) as sale_subscriber,sr.datetime From
								(SELECT sum(cs.bet_pick5_luk) sum_bet_pick5_luk,(sum(cs.total_sell_amount_639))+(sum(cs.total_sell_amount_pick5)) as sum_game_amount,sum(cs.subscriber_pick_5+cs.subscriber_639_n)  as subscriber_online, cs.datetime, date_trunc('week', cs.datetime) AS week
								from rep_channel_sale cs
								inner join sys_channel_owner co on cs.channel_owner__id = co.channel_owner_id where cs.status = 'active' and cs.datetime >= '".$startDate."' and cs.datetime <='".$endDate."'
								GROUP BY cs.datetime ORDER BY cs.datetime asc) as sr";
	
				$sale19 = DB::select(DB::raw($sale19Query));
				foreach ($sale19 as $key => $value) {
					$date = date_create($value->datetime);
					$dateFull = date_format($date, 'Y-m-d');
					$salesPerformanceSalePerSubscriberOnlineDailys[$dateFull] = $value->sale_subscriber;
				}
				
				//-------------Total Sale Per Subscriber moving avg-----------------
				$date = DateTime::createFromFormat('Y-m-d', $startDate);
				
				for ($i = 0; $i < 365; $i++) {
					if ($i > 0) {
						$dateIncrease = $date->modify('+1 day');
					} else {
						$dateIncrease = $date;
					}
					
					$dateFull = $date->format('Y-m-d');
					
					
					$dateLast5 = DateTime::createFromFormat('Y-m-d', $dateFull);
					$dateLast5 = $dateLast5->modify('-6 day');
					$dateFullLast5 = $dateLast5->format('Y-m-d');
					try {
						
						$queryTotalSalePerSubscriberMovingAvgDaily = " SELECT sum(s.sale_subscriber)/7 sale_subscriber from
															(select (sr.sum_game_amount/sr.subscriber_online) as sale_subscriber,sr.datetime From
															(SELECT sum(cs.bet_pick5_luk) sum_bet_pick5_luk,(sum(cs.total_sell_amount_639))+(sum(cs.total_sell_amount_pick5)) as sum_game_amount,sum(cs.subscriber_pick_5+cs.subscriber_639_n)  as subscriber_online, cs.datetime, date_trunc('week', cs.datetime) AS week
															from rep_channel_sale cs
															inner join sys_channel_owner co on cs.channel_owner__id = co.channel_owner_id where cs.status = 'active' 
															and cs.datetime >= '".$dateFullLast5."' and datetime <='".$dateFull." 23:59'
															GROUP BY cs.datetime ORDER BY cs.datetime asc) as sr
															) as s ";									
						
						$totalSalePerSubscriberMovingAvgDaily = DB::select(DB::raw($queryTotalSalePerSubscriberMovingAvgDaily));
						
						$value = 'null';
						if ($totalSalePerSubscriberMovingAvgDaily[0]->sale_subscriber != null) {
							$value = $totalSalePerSubscriberMovingAvgDaily[0]->sale_subscriber;
						}
						$salesPerformanceTotalSalePerSubscriberMovingAvgSalesDailys[$dateFull] = $value;
						
					} catch (Exception $e) {
						$salesPerformanceTotalSalePerSubscriberMovingAvgSalesDailys[$dateFull] = '0';
					}
					if ($dateFull >= $endDate) {
						break;
					}
				}
			}
			
			//----------------14 Sale Per Draw 639 By Weekly-----------------------
//			$sale20Query = "SELECT sum(sr.sum_amount) as sum_amount,sr.full_day,sr.num_day,CAST(coalesce(sr.week, '0') AS integer) as week from
//								(select (sum(cs.bet_639+cs.bet_639_luk) * 0.5) as sum_amount,to_char(cs.draw_639_date, 'Day') as full_day,to_char(cs.draw_639_date, 'd') as num_day,draw_639_date,to_char(cs.draw_639_date, 'WW') as Week
//							from rep_channel_sale cs where draw_639_date is not null and cs.status = 'active' and cs.draw_639_date >= '".$startDate."' and cs.draw_639_date <='".$endDate."'
//							group by cs.draw_639_date
//							order by week) as sr
//							GROUP BY sr.full_day,sr.num_day,sr.week ORDER BY sr.week asc,sr.num_day asc";

			//--------------New------------------------
			$sale20Query = "SELECT sum(sr.sum_amount) as sum_amount,sr.full_day,sr.num_day,CAST(coalesce(sr.week, '0') AS integer) as week from
								(select (sum(cs.total_sell_amount_639)) as sum_amount,to_char(cs.draw_639_date, 'Day') as full_day,to_char(cs.draw_639_date, 'd') as num_day,draw_639_date,to_char(cs.draw_639_date, 'WW') as Week
							from rep_channel_sale cs where draw_639_date is not null and cs.status = 'active' and cs.draw_639_date >= '".$startDate."' and cs.draw_639_date <='".$endDate."'
							group by cs.draw_639_date
							order by week) as sr
							GROUP BY sr.full_day,sr.num_day,sr.week ORDER BY sr.week asc,sr.num_day asc";

			$sale20 = DB::select(DB::raw($sale20Query));
			foreach ($sale20 as $key => $value) {
				$salesPerformanceSalePerDraw639Weeklys[$value->full_day][$value->week] = $value->sum_amount;
			}
			
			//----------------14 Sale Per Draw Pick5 By Weekly-----------------------
			$sale21Query = "SELECT sum(sr.sum_amount) as sum_amount,sr.full_day,sr.num_day,CAST(coalesce(sr.week, '0') AS integer) as week from 
								(select (sum(cs.total_sell_amount_pick5)) as sum_amount,to_char(cs.draw_pick5_date, 'Day') as full_day,to_char(cs.draw_pick5_date, 'd') as num_day,draw_pick5_date,to_char(cs.draw_pick5_date, 'WW') as Week
							from rep_channel_sale cs where cs.draw_pick5_date is not null and cs.status = 'active'  and cs.draw_pick5_date >= '".$startDate."' and cs.draw_pick5_date <='".$endDate."'
							group by cs.draw_pick5_date
							order by week) as sr
							GROUP BY sr.full_day,sr.num_day,sr.week ORDER BY sr.week asc,sr.num_day asc";
			$sale21 = DB::select(DB::raw($sale21Query));
			foreach ($sale21 as $key => $value) {
				$salesPerformanceSalePerDrawPick5Weeklys[$value->full_day][$value->week] = $value->sum_amount;
			}
			
			//----------------15 AVG Sale Per Draw 639 By Weekly-----------------------
//			$saleAvg15Query = "SELECT sum(sr.sum_amount) as sum_amount,sr.full_day,sr.num_day,CAST(coalesce(sr.week, '0') AS integer) as week from
//								(select (sum(cs.bet_639+cs.bet_639_luk) * 0.5) as sum_amount,to_char(cs.draw_639_date, 'Day') as full_day,to_char(cs.draw_639_date, 'd') as num_day,draw_639_date,to_char(cs.draw_639_date, 'WW') as Week
//							from rep_channel_sale cs where draw_639_date is not null and cs.status = 'active' and cs.draw_639_date >= '".$startDate."' and cs.draw_639_date <='".$endDate."'
//							group by cs.draw_639_date
//							order by week) as sr
//							GROUP BY sr.full_day,sr.num_day,sr.week ORDER BY sr.week asc,sr.num_day asc";

			//-------------New------------------------
			$saleAvg15Query = "SELECT sum(sr.sum_amount) as sum_amount,sr.full_day,sr.num_day,CAST(coalesce(sr.week, '0') AS integer) as week from
								(select (sum(cs.total_sell_amount_639)) as sum_amount,to_char(cs.draw_639_date, 'Day') as full_day,to_char(cs.draw_639_date, 'd') as num_day,draw_639_date,to_char(cs.draw_639_date, 'WW') as Week
							from rep_channel_sale cs where draw_639_date is not null and cs.status = 'active' and cs.draw_639_date >= '".$startDate."' and cs.draw_639_date <='".$endDate."'
							group by cs.draw_639_date
							order by week) as sr
							GROUP BY sr.full_day,sr.num_day,sr.week ORDER BY sr.week asc,sr.num_day asc";

			$saleAvg15 = DB::select(DB::raw($saleAvg15Query));
			foreach ($saleAvg15 as $key => $value) {
				$salesPerformanceAvgSalePerDraw639Weeklys[$value->full_day][$value->week] = ( $value->sum_amount / 7 );
			}
			
			//----------------16 AVG Sale Per Draw Pick5 By Weekly-----------------------
			$saleAvg16Query = "SELECT sum(sr.sum_amount) as sum_amount,sr.full_day,sr.num_day,CAST(coalesce(sr.week, '0') AS integer) as week from 
								(select (sum(cs.total_sell_amount_pick5)) as sum_amount,to_char(cs.draw_pick5_date, 'Day') as full_day,to_char(cs.draw_pick5_date, 'd') as num_day,draw_pick5_date,to_char(cs.draw_pick5_date, 'WW') as Week
							from rep_channel_sale cs where cs.draw_pick5_date is not null and cs.status = 'active'  and cs.draw_pick5_date >= '".$startDate."' and cs.draw_pick5_date <='".$endDate."'
							group by cs.draw_pick5_date
							order by week) as sr
							GROUP BY sr.full_day,sr.num_day,sr.week ORDER BY sr.week asc,sr.num_day asc";
			$saleAvg16 = DB::select(DB::raw($saleAvg16Query));
			foreach ($saleAvg16 as $key => $value) {
				$salesPerformanceAvgSalePerDrawPick5Weeklys[$value->full_day][$value->week] = ( $value->sum_amount / 7 );
			}
			//-----------------18 AVG Num Subscriber Online Weekly-------------------
			$saleavG18Query = "SELECT sum(sr.subscriber_online) as subscriber_online, CAST(coalesce(sr.week, '0') AS integer) as week from 
								(SELECT sum(cs.subscriber_pick_5+cs.subscriber_639_n)  as subscriber_online, cs.datetime, to_char(cs.datetime, 'WW') as week  
							from rep_channel_sale cs
							inner join sys_channel_owner co on cs.channel_owner__id = co.channel_owner_id
							where cs.status = 'active'  and cs.datetime >= '".$startDate."' and cs.datetime <='".$endDate."'
							GROUP BY cs.datetime ORDER BY cs.datetime asc) as sr
							GROUP BY sr.week
							ORDER BY sr.week";
			$saleavG18 = DB::select(DB::raw($saleavG18Query));
			foreach ($saleavG18 as $key => $value) {
				$date = date_create($value->week);
				$salesPerformanceAvgNumSubscriberOnlineWeeklys[$value->week] = ( $value->subscriber_online / 7 );
			}			
			
			//-----------------19 AVG Num LSDL Online Weekly-----------
			if (Input::has('avg_lsdl_online')) {				
				$sale19AvgQuery = "SELECT CAST(coalesce(to_char(tx.day, 'WW'), '0') AS integer) as week,tx.n_dealer as sum_dealer_online
								from
								(select * from (select date_trunc('day', t.datetime) AS day,count(DISTINCT t.dealer__id) as n_dealer 
								from txn_transaction_header t
								inner join dealer d on d.id = t.dealer__id
								where t.datetime >= '".$startDate."' and t.datetime <='".$endDate." 23:59' and d.status in ('active','used') and t.service_type__id in ('639','639luk','pick5','pick5l')
								 and d.type_account = 'real' GROUP BY 1 ORDER BY day asc) as dt ORDER BY day desc) as tx";
				$sale19Avg = DB::select(DB::raw($sale19AvgQuery));
				foreach ($sale19Avg as $key => $value) {
					$date = date_create($value->week);
					$salesPerformanceLSDLAvgOnlineWeeklys[$value->week] = ( $value->sum_dealer_online / 7 );
				}	
			}
			
			//-------------------Total Sale Smart Daily-------------------------------------------------------------
			if ((Input::has('total_smart_sale') && Input::has('groupby') && Input::get('groupby') == 'daily')) {
				
				//-------------------Total Sale Smart Daily-------------------------------------------------------------
				$saleSmartQuery = "SELECT (sum(cs.total_sell_amount_639))+(sum(cs.total_sell_amount_pick5)) as sum_total_telco_sales, cs.datetime 
									from rep_channel_sale cs 
									where channel_owner__id in (4,5,6) 
									and cs.status  = 'active' 
									and datetime >= '".$startDate."' and datetime <='".$endDate."' 
									GROUP BY cs.datetime ORDER BY cs.datetime asc";
				$saleSmart = DB::select(DB::raw($saleSmartQuery));
				foreach ($saleSmart as $key => $value) {
					$date = date_create($value->datetime);
					$dateFull = date_format($date, 'Y-m-d');
					$salesPerformanceTotalSmartSalesDailys[$dateFull] = $value->sum_total_telco_sales;
				}
				
				//-------------Total Sale Smart Daily moving avg-----------------
				$date = DateTime::createFromFormat('Y-m-d', $startDate);
				
				for ($i = 0; $i < 365; $i++) {
					if ($i > 0) {
						$dateIncrease = $date->modify('+1 day');
					} else {
						$dateIncrease = $date;
					}
					
					$dateFull = $date->format('Y-m-d');
					
					
					$dateLast5 = DateTime::createFromFormat('Y-m-d', $dateFull);
					$dateLast5 = $dateLast5->modify('-6 day');
					$dateFullLast5 = $dateLast5->format('Y-m-d');
					try {
						$queryTotalSaleSmartMovingAvgDaily = "SELECT sum(s.sum_game_amount)/7 sum_game_amount
															from
															(
																SELECT (sum(cs.total_sell_amount_639))+(sum(cs.total_sell_amount_pick5)) as sum_game_amount, cs.datetime 
																from rep_channel_sale cs 
																where channel_owner__id in (4,5,6) 
																and cs.status  = 'active' 
																and datetime >= '".$dateFullLast5."' and datetime <='".$dateFull." 23:59'
																GROUP BY cs.datetime ORDER BY cs.datetime asc
															) as s";
						
						$totalSaleSmartMovingAvgDaily = DB::select(DB::raw($queryTotalSaleSmartMovingAvgDaily));
						
						$value = 'null';
						if ($totalSaleSmartMovingAvgDaily[0]->sum_game_amount != null) {
							$value = $totalSaleSmartMovingAvgDaily[0]->sum_game_amount;
						}
						$salesPerformanceTotalSmartSalesMovingAvgSalesDailys[$dateFull] = $value;
						
					} catch (Exception $e) {
						$salesPerformanceTotalSmartSalesMovingAvgSalesDailys[$dateFull] = '0';
					}
					if ($dateFull >= $endDate) {
						break;
					}
				}
			}

			//-------------------------- Total Smart sales Weekly ------------------------------
			if ((Input::has('total_smart_sale') && Input::has('groupby') && Input::get('groupby') == 'weekly')) {
				//----------------------- New --------------------------
				$saleSmartWeekly = "SELECT sum(sr.sum_total_smart_sales) as sum_total_smart_sales, CAST(coalesce(sr.week, '0') AS integer) as week from
							(select (sum(cs.total_sell_amount_639))+(sum(cs.total_sell_amount_pick5)) as sum_total_smart_sales,to_char(cs.datetime, 'WW') as week
						from rep_channel_sale cs where channel_owner__id in (4,5,6) and cs.datetime is not null and cs.status = 'active' and datetime >= '".$startDate."' and datetime <='".$endDate."'
						GROUP BY cs.datetime,week
						order by week asc) as sr
						GROUP BY sr.week;";
				$saleSmartWeeklys = DB::select(DB::raw($saleSmartWeekly));
				foreach ($saleSmartWeeklys as $key => $value) {
					$salesPerformanceTotalSmartSalesWeeklys[$value->week] = $value->sum_total_smart_sales;
				}
			}

			//---------------------------- Total Smart Sales AVG Weekly -----------------------

			$saleSmartAvgWeekly = "SELECT sum(sr.sum_amount) as sum_amount,sr.full_day,sr.num_day,CAST(coalesce(sr.week, '0') AS integer) as week from
								(SELECT (sum(cs.total_sell_amount_639))+(sum(cs.total_sell_amount_pick5)) as sum_amount,to_char(cs.draw_639_date, 'Day') as full_day,to_char(cs.draw_639_date, 'd') as num_day,draw_639_date,to_char(cs.draw_639_date, 'WW') as Week
							from rep_channel_sale cs where channel_owner__id in (4,5,6) and draw_639_date is not null and cs.status = 'active' and cs.draw_639_date >= '".$startDate."' and cs.draw_639_date <='".$endDate."'
							group by cs.draw_639_date
							order by week) as sr
							GROUP BY sr.full_day,sr.num_day,sr.week ORDER BY sr.week asc,sr.num_day asc";

			$saleSmartAvgWeeklys = DB::select(DB::raw($saleSmartAvgWeekly));
			foreach ($saleSmartAvgWeeklys as $key => $value) {
				$salesPerformanceTotalSmartAvgSalesWeeklys[$value->full_day][$value->week] = ( $value->sum_amount / 7 );
			}
			
			//-------------------Total Sale Metfone Daily-------------------------------------------------------------
			if ((Input::has('total_metfone_sale') && Input::has('groupby') && Input::get('groupby') == 'daily')) {
				//-------------------Total Sale Metfone Daily-------------------------------------------------------------
				$saleMetfoneQuery = "SELECT sum(cs.bet_639) sum_bet_639,sum(cs.bet_639_luk) sum_bet_639_luk,sum(cs.bet_pick5) sum_bet_pick5,sum(cs.bet_pick5_luk) sum_bet_pick5_luk,(sum(cs.total_sell_amount_639))+(sum(cs.total_sell_amount_pick5)) as sum_total_telco_sales, cs.datetime from rep_channel_sale cs where channel_owner__id in (1,2,3) and cs.status  = 'active' and datetime >= '".$startDate."' and datetime <='".$endDate."' GROUP BY cs.datetime ORDER BY cs.datetime asc";
				$saleMetfone = DB::select(DB::raw($saleMetfoneQuery));
				foreach ($saleMetfone as $key => $value) {
					$date = date_create($value->datetime);
					$dateFull = date_format($date, 'Y-m-d');
					$salesPerformanceTotalMetfoneSalesDailys[$dateFull] = $value->sum_total_telco_sales;
				}
				
				//-------------Total Sale Metfone Daily moving avg-----------------
				$date = DateTime::createFromFormat('Y-m-d', $startDate);
				
				for ($i = 0; $i < 365; $i++) {
					if ($i > 0) {
						$dateIncrease = $date->modify('+1 day');
					} else {
						$dateIncrease = $date;
					}
					
					$dateFull = $date->format('Y-m-d');
					
					
					$dateLast5 = DateTime::createFromFormat('Y-m-d', $dateFull);
					$dateLast5 = $dateLast5->modify('-6 day');
					$dateFullLast5 = $dateLast5->format('Y-m-d');
					try {
						$queryTotalSaleMetfoneMovingAvgDaily = "SELECT sum(s.sum_game_amount)/7 sum_game_amount
															from
															(
																SELECT (sum(cs.total_sell_amount_639))+(sum(cs.total_sell_amount_pick5)) as sum_game_amount, cs.datetime 
																from rep_channel_sale cs 
																where channel_owner__id in (1,2,3) 
																and cs.status  = 'active' 
																and datetime >= '".$dateFullLast5."' and datetime <='".$dateFull." 23:59'
																GROUP BY cs.datetime ORDER BY cs.datetime asc
															) as s";
						
						$totalSaleMetfoneMovingAvgDaily = DB::select(DB::raw($queryTotalSaleMetfoneMovingAvgDaily));
						
						$value = 'null';
						if ($totalSaleMetfoneMovingAvgDaily[0]->sum_game_amount != null) {
							$value = $totalSaleMetfoneMovingAvgDaily[0]->sum_game_amount;
						}
						$salesPerformanceTotalMetfoneSalesMovingAvgSalesDailys[$dateFull] = $value;
												
					} catch (Exception $e) {
						$salesPerformanceTotalMetfoneSalesMovingAvgSalesDailys[$dateFull] = '0';
					}
					if ($dateFull >= $endDate) {
						break;
					}
				}
			}

			//-------------------------- Total Metfone sales Weekly ------------------------------
			if ((Input::has('total_metfone_sale') && Input::has('groupby') && Input::get('groupby') == 'weekly')) {
				//----------------------- New --------------------------
				$saleSmartWeekly = "SELECT sum(sr.sum_total_metfone_sales) as sum_total_metfone_sales, CAST(coalesce(sr.week, '0') AS integer) as week from
							(select (sum(cs.total_sell_amount_639))+(sum(cs.total_sell_amount_pick5)) as sum_total_metfone_sales,to_char(cs.datetime, 'WW') as week
						from rep_channel_sale cs where channel_owner__id in (1,2,3) and cs.datetime is not null and cs.status = 'active' and datetime >= '".$startDate."' and datetime <='".$endDate."'
						GROUP BY cs.datetime,week
						order by week asc) as sr
						GROUP BY sr.week;";
				$saleSmartWeeklys = DB::select(DB::raw($saleSmartWeekly));
				foreach ($saleSmartWeeklys as $key => $value) {
					$salesPerformanceTotalMetfoneSalesWeeklys[$value->week] = $value->sum_total_metfone_sales;
				}
			}
		}
		
		$this->layout->content = View::make('sale-performance.sales_performance',compact('firstTime','startDate','endDate','salesPerformanceDaily','salesPerformanceWeekly','salesPerformance639byDaily','salesPerformance639byWeekly','salesPerformancePick5Daily','salesPerformancePick5Weekly','salesPerformancePick5TelcoDailys','salesPerformancePick5TelcoWeeklys','salesPerformance639TelcoDailys','salesPerformance639TelcoWeeklys',
			'salesPerformanceLSDLSaleDailys','salesPerformanceLSDLOnlineDailys','salesPerformanceSalePerDragonDailys','salesPerformanceSmartSale639Dailys',
			'salesPerformanceSmartSalePick5Dailys','salesPerformanceMetfoneSale639Dailys','salesPerformanceMetfoneSalePick5Dailys','salesPerformanceNumSubscriberOnlineDailys',
			'salesPerformanceSalePerSubscriberOnlineDailys','salesPerformanceSalePerDraw639Weeklys','salesPerformanceSalePerDrawPick5Weeklys',
			'salesPerformanceLSDLSaleWeeklys','salesPerformanceLSDLOnlineWeeklys','salesPerformanceSalePerDragonWeeklys',
			'salesPerformanceSmartSale639Weeklys','salesPerformanceSmartSalePick5Weeklys','salesPerformanceMetfoneSale639Weeklys',
			'salesPerformanceLSDLOnlineMovingAvgDailys','salesPerformanceSalePerDragonMovingAvgDailys',
			'salesPerformanceMetfoneSalePick5Weeklys','salesPerformanceNumSubscriberOnlineWeeklys','salesPerformanceAvgSalePerDraw639Weeklys',
			'salesPerformanceAvgSalePerDrawPick5Weeklys','salesPerformanceAvgNumSubscriberOnlineWeeklys','salesPerformanceLSDLAvgOnlineWeeklys',
			'salesPerformanceTotalTelcoSalesDailys','salesPerformanceTotalTelcoSalesWeeklys','salesPerformanceTotalSmartSalesDailys',
			'salesPerformanceTotalMetfoneSalesDailys','salesPerformanceTotalTelcoMovingAvgSalesDailys','salesPerformanceTotalSaleMovingAvgSalesDailys',
			'salesPerformanceTotalSmartSalesMovingAvgSalesDailys','salesPerformanceTotalMetfoneSalesMovingAvgSalesDailys','salesPerformanceLSDLSaleMovingAvgDailys',
			'salesPerformanceTotalSmartSalesWeeklys','salesPerformanceTotalSmartAvgSalesWeeklys','salesPerformanceTotalMetfoneSalesWeeklys','salesPerformanceTotal639MovingAvgSalesDailys','salesPerformanceTotalPick5SalesMovingAvgSalesDailys','salesPerformanceTotalNumSubscriberOnlineMovingAvgSalesDailys','salesPerformanceTotalSalePerSubscriberMovingAvgSalesDailys',
			'totalSalesGameMovingAvgWeekly','totalSalesLotto639MovingAvgWeekly'
		));
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /dashboard/saleTargetGraphic
	 *
	 * @return Response
	 */

	public function saleTargetGraphic()
	{
		//
		$msgs = array();

		$salesGame = array();
		$inputs = Input::all();

		if(!Input::has('start_date') || !Input::has('end_date')) {
			$startDate = date("Y-m").'-01';
			$endDate = date("Y-m-d");//date("Y-m-d H:i");//.' 23:59';

		} else {
			$startDate = Input::get('start_date');
			$endDate = Input::get('end_date');
			$firstTime = false;
		}

		if($startDate!='' && $endDate!=''){
			$quotaQuery = "SELECT * FROM rep_sale_staff_targeting WHERE sale_staff__id = '4' AND status = 'active' AND created_at >= '".$startDate."' AND created_at <='".$endDate."' ORDER  BY target_week asc";

			$quotaQueries = DB::select(DB::raw($quotaQuery));
			//dd($quotaQueries);
			foreach ($quotaQueries as $key => $value) {
				$saleGameWeekly[$value->target_week] = $value->target_sale_game;
				$saleVisitWeekly[$value->target_week] = $value->target_num_sale_visit;
				$saleRecruitWeekly[$value->target_week] = $value->target_num_new_recruit;
				$saleTopupGameWeekly[$value->target_week] = $value->target_topup_game;
			}
		}

		$this->layout->content = View::make('dragon-warrior-quota-graphic.dragon_warrior_quota_graphic',compact('startDate','endDate','saleGameWeekly','saleVisitWeekly','saleRecruitWeekly','saleTopupGameWeekly'));
	}
	 
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /dashboard
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /dashboard/{id}
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
	 * GET /dashboard/{id}/edit
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
	 * PUT /dashboard/{id}
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
	 * DELETE /dashboard/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}
	
	private function getWeekNumByDateString($dateStr) {
		$ddate = $dateStr;
		$duedt = explode("-", $ddate);
		$date  = mktime(0, 0, 0, $duedt[1], $duedt[2], $duedt[0]);
		//$weekStart  = (int)date('W', $date);
		$week = strftime("%W",$date);
		$week +=1;
		return $week;
	}

	private function getLastDayOfYear($date) {
		$year = $date->format('Y');
		$last = $date->modify("last day of December $year");
		return $last;
	}
}