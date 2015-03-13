@section('title', 'Sales Performance')
@section('content')
<?php
$baseUrl = URL::to('/');

$ddate = $startDate;
$duedt = explode("-", $ddate);
$dateStart  = mktime(0, 0, 0, $duedt[1], $duedt[2], $duedt[0]);
//$weekStart  = (int)date('W', $date);
$weekStart = strftime("%W",$dateStart);
$weekStart +=1;

//echo "Weeknummer: " . $weekStart;

$ddate = $endDate;
$duedt = explode("-", $ddate);
$dateEnd  = mktime(0, 0, 0, $duedt[1], $duedt[2], $duedt[0]);
//mktime(hour, minute, second, month, day, year);
//echo 'end date = '.$date;
//$weekEnd  = (int)date('YW', $date);

$weekEnd = strftime("%W",$dateEnd);
$weekEnd +=1;
//echo 'End Week = '.$weekEnd;

$endDateObject = DateTime::createFromFormat('Y-m-d', $endDate);
//$endDateFull = DateTime::createFromFormat('Y-m-d', $endDate);

function getLastDayOfYear($date) {
	$year = $date->format('Y');
	$last = $date->modify("last day of December $year");
	return $last;
}

function getWeekNumByDateString($dateStr) {
	$ddate = $dateStr;
	$duedt = explode("-", $ddate);
	$date  = mktime(0, 0, 0, $duedt[1], $duedt[2], $duedt[0]);
	//$weekStart  = (int)date('W', $date);
	$week = strftime("%W",$date);
	$week +=1;
	return $week;
}

function gernerateWeekResult($weekStart,$weekEnd,$startDate,$endDate,$data) {
	$endDateObject = DateTime::createFromFormat('Y-m-d', $endDate);
	$date = DateTime::createFromFormat('Y-m-d', $startDate);
						  
	for ($i = 0; $i <= 53; $i++) {
	  	
			if ($i > 0) {
				
				$dateClone = clone $date;
				$last = getLastDayOfYear($dateClone);
				
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
			$week  = getWeekNumByDateString($dateFull);
			
			try {
	  			echo "".$data[$week].",";
	  		} catch (Exception $e) {
	  			echo "null,";
	  		}
			
			if ($week == $weekEnd) {
				break;
			}
	}
}
?>
<script type="text/javascript">

$(function () {
    $('#container').highcharts({
        chart: {
            type: 'spline'
        },
        title: {
            text: 'Graphic Report'
        },
        subtitle: {
            text: ''
        },
        
        <?php
        if (Input::has('groupby') && Input::get('groupby') == 'weekly') {
        	echo "xAxis: {";
		    echo "categories: [";
				$date = DateTime::createFromFormat('Y-m-d', $startDate);
				for ($i = 0; $i <= 53; $i++) {
					if ($i > 0) {
						$dateClone = clone $date;
						$last = getLastDayOfYear($dateClone);
				
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
					$year = $date->format('Y');
					$week  = getWeekNumByDateString($dateFull);
					echo "'Week ".$week." (".$year.")',";
					
					if ($week == $weekEnd) {
						break;
					}
				}
			echo "]";
		    echo "},";
        } else {?>
        	xAxis: {
	            type: 'datetime',
	            dateTimeLabelFormats: { // don't display the dummy year
	                month: '%e. %b',
	                year: '%b'
	            },
	            title: {
	                text: 'Date'
	            }
	        },
        <?php }
        ?>
       
        
       	/*xAxis: {  
            tickInterval:  7 * 24 * 3600 * 1000,
	        type: 'datetime',
            startOnTick: true,
            startOfWeek: 2,
	        labels: {
	            format: '{value:%d/%m/%Y}',
	            rotation: -45,
	            y: 30,
	            align: 'center'
	        } 
	    },*/
        yAxis: {
            title: {
                text: '(X)'
            },
            min: 0
        },
         <?php
        if (Input::has('groupby') && Input::get('groupby') == 'weekly') {?>
        	tooltip: {
	            //valueSuffix: '°C'
	            //headerFormat: '<b>{series.name}</b><br>',
	            //pointFormat: '{point.x:.}: {point.y:.2f} x',
	            valueSuffix: 'x'
	        },
        <?php } else {?>
	       	tooltip: {
	            //headerFormat: '<b>{series.name}</b><br>',
	            //pointFormat: '{point.x:%e. %b}: {point.y:.2f} x',
	            valueSuffix: 'x'
	        },
        <?php }
        ?>
        

        series: [
       	<?php
       		
       		if ($startDate != '' && $endDate != '') {
       			if ((Input::has('sales') && Input::has('groupby') && Input::get('groupby') == 'daily') || $firstTime == 'true') {
					echo '{';
					echo "name: 'Total Sales (x * $)',
	            		  data: [";
						  
						  if ($startDate != '' && $endDate != '') {
								
								$date = DateTime::createFromFormat('Y-m-d', $startDate);
								
								for ($i = 0; $i < 365; $i++) {
									if ($i > 0) {
										$dateIncrease = $date->modify('+1 day');
									} else {
										$dateIncrease = $date;
									}
									
									$day = $dateIncrease->format('d');
									$month = $dateIncrease->format('m');
									$month = $month - 1;
									$year = $dateIncrease->format('Y');
									$dateFull = $date->format('Y-m-d');
									
									
									try {
										$nDealer = $salesPerformanceDaily[$dateFull];
										echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), '.$nDealer.'  ],';
									} catch (Exception $e) {
										echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), 0  ],';
									}
									
									if ($dateFull == $endDate) {
										break;
									}		
								}
								
							}
						  
					echo "]},";
					
					echo '{';
					echo "name: 'Total Sales Moving Average (x * $)',
	            		  data: [";
						  
						  if ($startDate != '' && $endDate != '') {
								
								$date = DateTime::createFromFormat('Y-m-d', $startDate);
								
								for ($i = 0; $i < 365; $i++) {
									if ($i > 0) {
										$dateIncrease = $date->modify('+1 day');
									} else {
										$dateIncrease = $date;
									}
									
									$day = $dateIncrease->format('d');
									$month = $dateIncrease->format('m');
									$month = $month - 1;
									$year = $dateIncrease->format('Y');
									$dateFull = $date->format('Y-m-d');
									
									
									try {
										$nDealer = $salesPerformanceTotalSaleMovingAvgSalesDailys[$dateFull];
										echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), '.$nDealer.'  ],';
									} catch (Exception $e) {
										echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), 0  ],';
									}
									
									if ($dateFull == $endDate) {
										break;
									}		
								}
								
							}
						  
					echo "]},";
				}
				
				if (Input::has('sales') && Input::has('groupby') && Input::get('groupby') == 'weekly' ) {
					echo '{';
					echo "name: 'Total Sale (x * $)',
	            		  data: [";
						  	gernerateWeekResult($weekStart,$weekEnd,$startDate,$endDate,$salesPerformanceWeekly);
					echo "]},";
					
					echo '{';
					echo "name: 'Total Sale Moving Average (x * $)',
	            		  data: [";
						  	gernerateWeekResult($weekStart,$weekEnd,$startDate,$endDate,$totalSalesGameMovingAvgWeekly);
					echo "]},";
				}

				// Total Telco Sales
				if ((Input::has('total_telco_sales') && Input::has('groupby') && Input::get('groupby') == 'daily')) {
					echo '{';
					echo "name: 'Total Telco Sales (x * $)',
	            		  data: [";

						  if ($startDate != '' && $endDate != '') {

								$date = DateTime::createFromFormat('Y-m-d', $startDate);

								for ($i = 0; $i < 365; $i++) {
									if ($i > 0) {
										$dateIncrease = $date->modify('+1 day');
									} else {
										$dateIncrease = $date;
									}

									$day = $dateIncrease->format('d');
									$month = $dateIncrease->format('m');
									$month = $month - 1;
									$year = $dateIncrease->format('Y');
									$dateFull = $date->format('Y-m-d');


									try {
										$nDealer = $salesPerformanceTotalTelcoSalesDailys[$dateFull];
										echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), '.$nDealer.'  ],';
									} catch (Exception $e) {
										echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), 0  ],';
									}

									if ($dateFull == $endDate) {
										break;
									}
								}

							}

					echo "]},";
					
					echo '{';
					echo "name: 'Total Telco Sales Moving Average(x * $)',
	            		  data: [";

						  if ($startDate != '' && $endDate != '') {

								$date = DateTime::createFromFormat('Y-m-d', $startDate);

								for ($i = 0; $i < 365; $i++) {
									if ($i > 0) {
										$dateIncrease = $date->modify('+1 day');
									} else {
										$dateIncrease = $date;
									}

									$day = $dateIncrease->format('d');
									$month = $dateIncrease->format('m');
									$month = $month - 1;
									$year = $dateIncrease->format('Y');
									$dateFull = $date->format('Y-m-d');


									try {
										$nDealer = $salesPerformanceTotalTelcoMovingAvgSalesDailys[$dateFull];
										echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), '.$nDealer.'  ],';
									} catch (Exception $e) {
										echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), 0  ],';
									}

									if ($dateFull == $endDate) {
										break;
									}
								}

							}

					echo "]},";
				}

				if (Input::has('total_telco_sales') && Input::has('groupby') && Input::get('groupby') == 'weekly' ) {
					echo '{';
					echo "name: 'Total Telco Sales (x * $)',
	            		  data: [";
						  gernerateWeekResult($weekStart,$weekEnd,$startDate,$endDate,$salesPerformanceTotalTelcoSalesWeeklys);
					echo "]},";
				}

				if (Input::has('sale_lotto_639') && Input::has('groupby') && Input::get('groupby') == 'daily') {
					echo '{';
					echo "name: 'Sales Lotto 639 (x * $)',
	            		  data: [";
						  
						  if ($startDate != '' && $endDate != '') {
								
								$date = DateTime::createFromFormat('Y-m-d', $startDate);
								
								for ($i = 0; $i < 365; $i++) {
									if ($i > 0) {
										$dateIncrease = $date->modify('+1 day');
									} else {
										$dateIncrease = $date;
									}
									
									$day = $dateIncrease->format('d');
									$month = $dateIncrease->format('m');
									$month = $month - 1;
									$year = $dateIncrease->format('Y');
									$dateFull = $date->format('Y-m-d');
									
									
									try {
										$nDealer = $salesPerformance639byDaily[$dateFull];
										echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), '.$nDealer.'  ],';
									} catch (Exception $e) {
										echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), 0  ],';
									}
									
									if ($dateFull == $endDate) {
										break;
									}		
								}
								
							}
						  
					echo "]},";
					
					echo '{';
					echo "name: 'Total 639 Sales Moving Average (x * $)',
	            		  data: [";

						  if ($startDate != '' && $endDate != '') {

								$date = DateTime::createFromFormat('Y-m-d', $startDate);

								for ($i = 0; $i < 365; $i++) {
									if ($i > 0) {
										$dateIncrease = $date->modify('+1 day');
									} else {
										$dateIncrease = $date;
									}

									$day = $dateIncrease->format('d');
									$month = $dateIncrease->format('m');
									$month = $month - 1;
									$year = $dateIncrease->format('Y');
									$dateFull = $date->format('Y-m-d');


									try {
										$nDealer = $salesPerformanceTotal639MovingAvgSalesDailys[$dateFull];
										echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), '.$nDealer.'  ],';
									} catch (Exception $e) {
										echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), 0  ],';
									}

									if ($dateFull == $endDate) {
										break;
									}
								}

							}

					echo "]},";
					
				}
				if (Input::has('sale_lotto_639') && Input::has('groupby') && Input::get('groupby') == 'weekly' ) {
					echo '{';
					echo "name: 'Sale Lotto 639 (x * $)',
	            		  data: [";
						  gernerateWeekResult($weekStart,$weekEnd,$startDate,$endDate,$salesPerformance639byWeekly);
					echo "]},";
					
					echo '{';
					echo "name: 'Total Sale Lotto 639 Moving Average (x * $)',
	            		  data: [";
						  	gernerateWeekResult($weekStart,$weekEnd,$startDate,$endDate,$totalSalesLotto639MovingAvgWeekly);
					echo "]},";
					
				}
				if (Input::has('sale_lotto_639_telco') && Input::has('groupby') && Input::get('groupby') == 'daily' ) {		
					foreach ($salesPerformance639TelcoDailys as $key => $salesPerformance639TelcoDaily) {
						echo '{';
						echo "name: 'Sale 639 ".$key." (x * $)',
		            		  data: [";
							  
							  if ($startDate != '' && $endDate != '') {
									
									$date = DateTime::createFromFormat('Y-m-d', $startDate);
									
									for ($i = 0; $i < 365; $i++) {
										if ($i > 0) {
											$dateIncrease = $date->modify('+1 day');
										} else {
											$dateIncrease = $date;
										}
										
										$day = $dateIncrease->format('d');
										$month = $dateIncrease->format('m');
										$month = $month - 1;
										$year = $dateIncrease->format('Y');
										$dateFull = $date->format('Y-m-d');
										
										
										try {
											$nDealer = $salesPerformance639TelcoDaily[$dateFull];
											echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), '.$nDealer.'  ],';
										} catch (Exception $e) {
											echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), 0  ],';
										}
										
										if ($dateFull == $endDate) {
											break;
										}		
									}
									
								}
							  
						echo "]},";						
					}
				}

				if (Input::has('sale_lotto_639_telco') && Input::has('groupby') && Input::get('groupby') == 'weekly' ) {
					foreach ($salesPerformance639TelcoWeeklys as $key => $salesPerformance639TelcoWeekly) {
						echo '{';
						echo "name: 'Sale 639 ".$key." (x * $)',
		            		  data: [";
							  gernerateWeekResult($weekStart,$weekEnd,$startDate,$endDate,$salesPerformance639TelcoWeekly);
						echo "]},";						
					}	
					/*foreach ($salesPerformance639TelcoWeeklys as $key => $salesPerformance639TelcoWeekly) {
						echo '{';
						echo "name: 'Weekly Sale 639 ".$key." (x * $)',
		            		  data: [";
							  
							  if ($startDate != '' && $endDate != '') {
									
									$date = DateTime::createFromFormat('Y-m-d', $startDate);
									
									for ($i = 0; $i < 365; $i++) {
										if ($i > 0) {
											$dateIncrease = $date->modify('+1 day');
										} else {
											$dateIncrease = $date;
										}
										
										$day = $dateIncrease->format('d');
										$month = $dateIncrease->format('m');
										$month = $month - 1;
										$year = $dateIncrease->format('Y');
										$dateFull = $date->format('Y-m-d');
										
										
										try {
											$nDealer = $salesPerformance639TelcoWeekly[$dateFull];
											echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), '.$nDealer.'  ],';
										} catch (Exception $e) {
											echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), 0  ],';
										}
										
										if ($dateFull >= $endDate) {
											break;
										}		
									}
									
								}
							  
						echo "]},";						
					}*/
				}
				if (Input::has('sale_pick5') && Input::has('groupby') && Input::get('groupby') == 'daily') {
					echo '{';
					echo "name: 'Sales Pick5 (x * $)',
	            		  data: [";
						  
						  if ($startDate != '' && $endDate != '') {
								
								$date = DateTime::createFromFormat('Y-m-d', $startDate);
								
								for ($i = 0; $i < 365; $i++) {
									if ($i > 0) {
										$dateIncrease = $date->modify('+1 day');
									} else {
										$dateIncrease = $date;
									}
									
									$day = $dateIncrease->format('d');
									$month = $dateIncrease->format('m');
									$month = $month - 1;
									$year = $dateIncrease->format('Y');
									$dateFull = $date->format('Y-m-d');
									
									
									try {
										$nDealer = $salesPerformancePick5Daily[$dateFull];
										echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), '.$nDealer.'  ],';
									} catch (Exception $e) {
										echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), 0  ],';
									}
									
									if ($dateFull == $endDate) {
										break;
									}		
								}
								
							}
						  
					echo "]},";		
					
					
					echo '{';
					echo "name: 'Total Pick5 Sales Moving Average (x * $)',
	            		  data: [";

						  if ($startDate != '' && $endDate != '') {

								$date = DateTime::createFromFormat('Y-m-d', $startDate);

								for ($i = 0; $i < 365; $i++) {
									if ($i > 0) {
										$dateIncrease = $date->modify('+1 day');
									} else {
										$dateIncrease = $date;
									}

									$day = $dateIncrease->format('d');
									$month = $dateIncrease->format('m');
									$month = $month - 1;
									$year = $dateIncrease->format('Y');
									$dateFull = $date->format('Y-m-d');


									try {
										$nDealer = $salesPerformanceTotalPick5SalesMovingAvgSalesDailys[$dateFull];
										echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), '.$nDealer.'  ],';
									} catch (Exception $e) {
										echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), 0  ],';
									}

									if ($dateFull == $endDate) {
										break;
									}
								}

							}

					echo "]},";
					
				}
				if (Input::has('sale_pick5') && Input::has('groupby') && Input::get('groupby') == 'weekly' ) {
					echo '{';
					echo "name: 'Sale Pick5 (x * $)',
	            		  data: [";
						  gernerateWeekResult($weekStart,$weekEnd,$startDate,$endDate,$salesPerformancePick5Weekly);
					echo "]},";			
					/*echo '{';
					echo "name: 'Weekly Sales Pick5 (x * $)',
	            		  data: [";
						  
						  if ($startDate != '' && $endDate != '') {
								
								$date = DateTime::createFromFormat('Y-m-d', $startDate);
								
								for ($i = 0; $i < 365; $i++) {
									if ($i > 0) {
										$dateIncrease = $date->modify('+1 day');
									} else {
										$dateIncrease = $date;
									}
									
									$day = $dateIncrease->format('d');
									$month = $dateIncrease->format('m');
									$month = $month - 1;
									$year = $dateIncrease->format('Y');
									$dateFull = $date->format('Y-m-d');
									
									
									try {
										$nDealer = $salesPerformancePick5Weekly[$dateFull];
										echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), '.$nDealer.'  ],';
									} catch (Exception $e) {
										echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), 0  ],';
									}
									
									if ($dateFull >= $endDate) {
										break;
									}		
								}
								
							}
						  
					echo "]},";*/
				}
				if (Input::has('sale_pick5_telco') && Input::has('groupby') && Input::get('groupby') == 'daily' ) {		
					foreach ($salesPerformancePick5TelcoDailys as $key => $salesPerformancePick5TelcoDaily) {
						echo '{';
						echo "name: 'Sale Pick5 ".$key." Telco (x * $)',
		            		  data: [";
							  
							  if ($startDate != '' && $endDate != '') {
									
									$date = DateTime::createFromFormat('Y-m-d', $startDate);
									
									for ($i = 0; $i < 365; $i++) {
										if ($i > 0) {
											$dateIncrease = $date->modify('+1 day');
										} else {
											$dateIncrease = $date;
										}
										
										$day = $dateIncrease->format('d');
										$month = $dateIncrease->format('m');
										$month = $month - 1;
										$year = $dateIncrease->format('Y');
										$dateFull = $date->format('Y-m-d');
										
										
										try {
											$nDealer = $salesPerformancePick5TelcoDaily[$dateFull];
											echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), '.$nDealer.'  ],';
										} catch (Exception $e) {
											echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), 0  ],';
										}
										
										if ($dateFull == $endDate) {
											break;
										}		
									}
									
								}
							  
						echo "]},";						
					}
				}
				if (Input::has('sale_pick5_telco') && Input::has('groupby') && Input::get('groupby') == 'weekly' ) {
					foreach ($salesPerformancePick5TelcoWeeklys as $key => $salesPerformancePick5TelcoWeekly) {
						echo '{';
						echo "name: 'Sale Pick5 ".$key." (x * $)',
		            		  data: [";
							  gernerateWeekResult($weekStart,$weekEnd,$startDate,$endDate,$salesPerformancePick5TelcoWeekly);
						echo "]},";						
					}		
					/*foreach ($salesPerformancePick5TelcoWeeklys as $key => $salesPerformancePick5TelcoWeekly) {
						echo '{';
						echo "name: 'Weekly Sale Pick5 ".$key." Telco (x * $)',
		            		  data: [";
							  
							  if ($startDate != '' && $endDate != '') {
									
									$date = DateTime::createFromFormat('Y-m-d', $startDate);
									
									for ($i = 0; $i < 365; $i++) {
										if ($i > 0) {
											$dateIncrease = $date->modify('+1 day');
										} else {
											$dateIncrease = $date;
										}
										
										$day = $dateIncrease->format('d');
										$month = $dateIncrease->format('m');
										$month = $month - 1;
										$year = $dateIncrease->format('Y');
										$dateFull = $date->format('Y-m-d');
										
										
										try {
											$nDealer = $salesPerformancePick5TelcoWeekly[$dateFull];
											echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), '.$nDealer.'  ],';
										} catch (Exception $e) {
											echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), 0  ],';
										}
										
										if ($dateFull >= $endDate) {
											break;
										}		
									}
									
								}
							  
						echo "]},";						
					}*/
				}
				if (Input::has('lsdl_sale') && Input::has('groupby') && Input::get('groupby') == 'daily' ) {
						echo '{';
						echo "name: 'Total LSDL Sale (x * $)',
		            		  data: [";
							  
							  if ($startDate != '' && $endDate != '') {
									
									$date = DateTime::createFromFormat('Y-m-d', $startDate);
									
									for ($i = 0; $i < 365; $i++) {
										if ($i > 0) {
											$dateIncrease = $date->modify('+1 day');
										} else {
											$dateIncrease = $date;
										}
										
										$day = $dateIncrease->format('d');
										$month = $dateIncrease->format('m');
										$month = $month - 1;
										$year = $dateIncrease->format('Y');
										$dateFull = $date->format('Y-m-d');
										
										
										try {
											$nDealer = $salesPerformanceLSDLSaleDailys[$dateFull];
											echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), '.$nDealer.'  ],';
										} catch (Exception $e) {
											echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), 0  ],';
										}
										
										if ($dateFull == $endDate) {
											break;
										}		
									}
									
								}
							  
						echo "]},";
						
						echo '{';
						echo "name: 'Total LSDL Sale Moving Average (x * $)',
		            		  data: [";
							  
							  if ($startDate != '' && $endDate != '') {
									
									$date = DateTime::createFromFormat('Y-m-d', $startDate);
									
									for ($i = 0; $i < 365; $i++) {
										if ($i > 0) {
											$dateIncrease = $date->modify('+1 day');
										} else {
											$dateIncrease = $date;
										}
										
										$day = $dateIncrease->format('d');
										$month = $dateIncrease->format('m');
										$month = $month - 1;
										$year = $dateIncrease->format('Y');
										$dateFull = $date->format('Y-m-d');
										
										
										try {
											$nDealer = $salesPerformanceLSDLSaleMovingAvgDailys[$dateFull];
											echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), '.$nDealer.'  ],';
										} catch (Exception $e) {
											echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), 0  ],';
										}
										
										if ($dateFull == $endDate) {
											break;
										}		
									}
									
								}
							  
						echo "]},";
				}
				if (Input::has('lsdl_sale') && Input::has('groupby') && Input::get('groupby') == 'weekly' ) {
						echo '{';
						echo "name: 'Total LSDL sale (x * $)',
		            		  data: [";
							  gernerateWeekResult($weekStart,$weekEnd,$startDate,$endDate,$salesPerformanceLSDLSaleWeeklys);
						echo "]},";
				}
				if (Input::has('lsdl_online') && Input::has('groupby') && Input::get('groupby') == 'daily' ) {
						echo '{';
						echo "name: 'Total LSDL Online',
		            		  data: [";
							  
							  if ($startDate != '' && $endDate != '') {
									
									$date = DateTime::createFromFormat('Y-m-d', $startDate);
									
									for ($i = 0; $i < 365; $i++) {
										if ($i > 0) {
											$dateIncrease = $date->modify('+1 day');
										} else {
											$dateIncrease = $date;
										}
										
										$day = $dateIncrease->format('d');
										$month = $dateIncrease->format('m');
										$month = $month - 1;
										$year = $dateIncrease->format('Y');
										$dateFull = $date->format('Y-m-d');
										
										
										try {
											$nDealer = $salesPerformanceLSDLOnlineDailys[$dateFull];
											echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), '.$nDealer.'  ],';
										} catch (Exception $e) {
											echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), 0  ],';
										}
										
										if ($dateFull == $endDate) {
											break;
										}		
									}
									
								}
							  
						echo "]},";
						
						echo '{';
						echo "name: 'Total LSDL Online Moving Average',
		            		  data: [";
							  
							  if ($startDate != '' && $endDate != '') {
									
									$date = DateTime::createFromFormat('Y-m-d', $startDate);
									
									for ($i = 0; $i < 365; $i++) {
										if ($i > 0) {
											$dateIncrease = $date->modify('+1 day');
										} else {
											$dateIncrease = $date;
										}
										
										$day = $dateIncrease->format('d');
										$month = $dateIncrease->format('m');
										$month = $month - 1;
										$year = $dateIncrease->format('Y');
										$dateFull = $date->format('Y-m-d');
										
										
										try {
											$nDealer = $salesPerformanceLSDLOnlineMovingAvgDailys[$dateFull];
											echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), '.$nDealer.'  ],';
										} catch (Exception $e) {
											echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), 0  ],';
										}
										
										if ($dateFull == $endDate) {
											break;
										}		
									}
									
								}
							  
						echo "]},";
				}
				if (Input::has('lsdl_online') && Input::has('groupby') && Input::get('groupby') == 'weekly' ) {
						echo '{';
						echo "name: 'Total LSDL Online',
		            		  data: [";
							  gernerateWeekResult($weekStart,$weekEnd,$startDate,$endDate,$salesPerformanceLSDLOnlineWeeklys);
						echo "]},";
				}
				if (Input::has('sale_per_dragon_dealer_online') && Input::has('groupby') && Input::get('groupby') == 'daily' ) {
						echo '{';
						echo "name: 'Sale Per Dragon Dealer Online (x * $)',
		            		  data: [";
							  
							  if ($startDate != '' && $endDate != '') {
									
									$date = DateTime::createFromFormat('Y-m-d', $startDate);
									
									for ($i = 0; $i < 365; $i++) {
										if ($i > 0) {
											$dateIncrease = $date->modify('+1 day');
										} else {
											$dateIncrease = $date;
										}
										
										$day = $dateIncrease->format('d');
										$month = $dateIncrease->format('m');
										$month = $month - 1;
										$year = $dateIncrease->format('Y');
										$dateFull = $date->format('Y-m-d');
										
										try {
											$nDealer = $salesPerformanceSalePerDragonDailys[$dateFull];
											echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), '.$nDealer.'  ],';
										} catch (Exception $e) {
											echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), 0  ],';
										}
										
										if ($dateFull == $endDate) {
											break;
										}		
									}
									
								}
							  
						echo "]},";
						echo '{';
						echo "name: 'Sale Per Dragon Dealer Online Moving Average (x * $)',
		            		  data: [";
							  
							  if ($startDate != '' && $endDate != '') {
									
									$date = DateTime::createFromFormat('Y-m-d', $startDate);
									
									for ($i = 0; $i < 365; $i++) {
										if ($i > 0) {
											$dateIncrease = $date->modify('+1 day');
										} else {
											$dateIncrease = $date;
										}
										
										$day = $dateIncrease->format('d');
										$month = $dateIncrease->format('m');
										$month = $month - 1;
										$year = $dateIncrease->format('Y');
										$dateFull = $date->format('Y-m-d');
										
										
										try {
											$nDealer = $salesPerformanceSalePerDragonMovingAvgDailys[$dateFull];
											echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), '.$nDealer.'  ],';
										} catch (Exception $e) {
											echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), 0  ],';
										}
										
										if ($dateFull == $endDate) {
											break;
										}		
									}
									
								}
							  
						echo "]},";
				}
				if (Input::has('sale_per_dragon_dealer_online') && Input::has('groupby') && Input::get('groupby') == 'weekly' ) {
						echo '{';
						echo "name: 'Sale Per Dragon Dealer Online (x * $)',
		            		  data: [";
							  gernerateWeekResult($weekStart,$weekEnd,$startDate,$endDate,$salesPerformanceSalePerDragonWeeklys);
						echo "]},";
				}
				if (Input::has('smart_sale') && Input::has('groupby') && Input::get('groupby') == 'daily' ) {
					foreach ($salesPerformanceSmartSale639Dailys as $key => $salesPerformanceSmartSale639Daily) {
						echo '{';
						echo "name: 'Sale 639 ".$key." Telco (x * $)',
		            		  data: [";
							  
							  if ($startDate != '' && $endDate != '') {
									
									$date = DateTime::createFromFormat('Y-m-d', $startDate);
									
									for ($i = 0; $i < 365; $i++) {
										if ($i > 0) {
											$dateIncrease = $date->modify('+1 day');
										} else {
											$dateIncrease = $date;
										}
										
										$day = $dateIncrease->format('d');
										$month = $dateIncrease->format('m');
										$month = $month - 1;
										$year = $dateIncrease->format('Y');
										$dateFull = $date->format('Y-m-d');
										
										
										try {
											$nDealer = $salesPerformanceSmartSale639Daily[$dateFull];
											echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), '.$nDealer.'  ],';
										} catch (Exception $e) {
											echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), 0  ],';
										}
										
										if ($dateFull >= $endDate) {
											break;
										}		
									}
									
								}
							  
						echo "]},";						
					}
					foreach ($salesPerformanceSmartSalePick5Dailys as $key => $salesPerformanceSmartSalePick5Daily) {
						echo '{';
						echo "name: 'Sale Pick5 ".$key." Telco (x * $)',
		            		  data: [";
							  
							  if ($startDate != '' && $endDate != '') {
									
									$date = DateTime::createFromFormat('Y-m-d', $startDate);
									
									for ($i = 0; $i < 365; $i++) {
										if ($i > 0) {
											$dateIncrease = $date->modify('+1 day');
										} else {
											$dateIncrease = $date;
										}
										
										$day = $dateIncrease->format('d');
										$month = $dateIncrease->format('m');
										$month = $month - 1;
										$year = $dateIncrease->format('Y');
										$dateFull = $date->format('Y-m-d');
										
										
										try {
											$nDealer = $salesPerformanceSmartSalePick5Daily[$dateFull];
											echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), '.$nDealer.'  ],';
										} catch (Exception $e) {
											echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), 0  ],';
										}
										
										if ($dateFull >= $endDate) {
											break;
										}		
									}
									
								}
							  
						echo "]},";						
					}
				}
				if (Input::has('smart_sale') && Input::has('groupby') && Input::get('groupby') == 'weekly' ) {
					foreach ($salesPerformanceSmartSale639Weeklys as $key => $salesPerformanceSmartSale639Weekly) {
						echo '{';
						echo "name: '".$key." Sale 639 (x * $)',
		            		  data: [";
							  gernerateWeekResult($weekStart,$weekEnd,$startDate,$endDate,$salesPerformanceSmartSale639Weekly);
						echo "]},";						
					}
					foreach ($salesPerformanceSmartSalePick5Weeklys as $key => $salesPerformanceSmartSalePick5Weekly) {
						echo '{';
						echo "name: '".$key." Sale Pick5 (x * $)',
		            		  data: [";
							  for ($i = $weekStart; $i <= 53; $i++) {
							  		try {
							  			echo "".$salesPerformanceSmartSalePick5Weekly[$i].",";
							  		} catch (Exception $e) {
							  			echo "null,";
							  		}
								  	
									if ($i >= $weekEnd) {
										break;
									}
								}
						echo "]},";						
					}
				}
				if (Input::has('metfone_sale') && Input::has('groupby') && Input::get('groupby') == 'daily' ) {
					foreach ($salesPerformanceMetfoneSale639Dailys as $key => $salesPerformanceMetfoneSale639Daily) {
						echo '{';
						echo "name: 'Sale 639 ".$key." Telco (x * $)',
		            		  data: [";
							  
							  if ($startDate != '' && $endDate != '') {
									
									$date = DateTime::createFromFormat('Y-m-d', $startDate);
									
									for ($i = 0; $i < 365; $i++) {
										if ($i > 0) {
											$dateIncrease = $date->modify('+1 day');
										} else {
											$dateIncrease = $date;
										}
										
										$day = $dateIncrease->format('d');
										$month = $dateIncrease->format('m');
										$month = $month - 1;
										$year = $dateIncrease->format('Y');
										$dateFull = $date->format('Y-m-d');
										
										
										try {
											$nDealer = $salesPerformanceMetfoneSale639Daily[$dateFull];
											echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), '.$nDealer.'  ],';
										} catch (Exception $e) {
											echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), 0  ],';
										}
										
										if ($dateFull >= $endDate) {
											break;
										}		
									}
									
								}
							  
						echo "]},";						
					}
					foreach ($salesPerformanceMetfoneSalePick5Dailys as $key => $salesPerformanceMetfoneSalePick5Daily) {
						echo '{';
						echo "name: 'Sale Pick5 ".$key." Telco (x * $)',
		            		  data: [";
							  
							  if ($startDate != '' && $endDate != '') {
									
									$date = DateTime::createFromFormat('Y-m-d', $startDate);
									
									for ($i = 0; $i < 365; $i++) {
										if ($i > 0) {
											$dateIncrease = $date->modify('+1 day');
										} else {
											$dateIncrease = $date;
										}
										
										$day = $dateIncrease->format('d');
										$month = $dateIncrease->format('m');
										$month = $month - 1;
										$year = $dateIncrease->format('Y');
										$dateFull = $date->format('Y-m-d');
										
										
										try {
											$nDealer = $salesPerformanceMetfoneSalePick5Daily[$dateFull];
											echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), '.$nDealer.'  ],';
										} catch (Exception $e) {
											echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), 0  ],';
										}
										
										if ($dateFull >= $endDate) {
											break;
										}		
									}
									
								}
							  
						echo "]},";						
					}
				}
				if (Input::has('metfone_sale') && Input::has('groupby') && Input::get('groupby') == 'weekly' ) {
					foreach ($salesPerformanceMetfoneSale639Weeklys as $key => $salesPerformanceMetfoneSale639Weekly) {
						echo '{';
						echo "name: '".$key." Sale 639 (x * $)',
		            		  data: [";
							  gernerateWeekResult($weekStart,$weekEnd,$startDate,$endDate,$salesPerformanceMetfoneSale639Weekly);
						echo "]},";						
					}
					foreach ($salesPerformanceMetfoneSalePick5Weeklys as $key => $salesPerformanceMetfoneSalePick5Weekly) {
						echo '{';
						echo "name: '".$key." Sale Pick5 (x * $)',
		            		  data: [";
							  gernerateWeekResult($weekStart,$weekEnd,$startDate,$endDate,$salesPerformanceMetfoneSalePick5Weekly);
						echo "]},";						
					}
				}
				if (Input::has('num_subcriber_online') && Input::has('groupby') && Input::get('groupby') == 'daily' ) {
						echo '{';
						echo "name: 'Number Subscriber Online',
		            		  data: [";
							  
							  if ($startDate != '' && $endDate != '') {
									
									$date = DateTime::createFromFormat('Y-m-d', $startDate);
									
									for ($i = 0; $i < 365; $i++) {
										if ($i > 0) {
											$dateIncrease = $date->modify('+1 day');
										} else {
											$dateIncrease = $date;
										}
										
										$day = $dateIncrease->format('d');
										$month = $dateIncrease->format('m');
										$month = $month - 1;
										$year = $dateIncrease->format('Y');
										$dateFull = $date->format('Y-m-d');
										
										try {
											$nDealer = $salesPerformanceNumSubscriberOnlineDailys[$dateFull];
											echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), '.$nDealer.'  ],';
										} catch (Exception $e) {
											echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), 0  ],';
										}
										
										if ($dateFull == $endDate) {
											break;
										}		
									}
									
								}
							  
						echo "]},";
						
						echo '{';
						echo "name: 'Total Number Subscriber Online Moving Average (x)',
		            		  data: [";
	
							  if ($startDate != '' && $endDate != '') {
	
									$date = DateTime::createFromFormat('Y-m-d', $startDate);
	
									for ($i = 0; $i < 365; $i++) {
										if ($i > 0) {
											$dateIncrease = $date->modify('+1 day');
										} else {
											$dateIncrease = $date;
										}
	
										$day = $dateIncrease->format('d');
										$month = $dateIncrease->format('m');
										$month = $month - 1;
										$year = $dateIncrease->format('Y');
										$dateFull = $date->format('Y-m-d');
	
	
										try {
											$nDealer = $salesPerformanceTotalNumSubscriberOnlineMovingAvgSalesDailys[$dateFull];
											echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), '.$nDealer.'  ],';
										} catch (Exception $e) {
											echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), 0  ],';
										}
	
										if ($dateFull == $endDate) {
											break;
										}
									}
	
								}
	
						echo "]},";
						
				}
				if (Input::has('num_subcriber_online') && Input::has('groupby') && Input::get('groupby') == 'weekly' ) {
						echo '{';
						echo "name: 'Number Subscriber Online',
		            		  data: [";
							  gernerateWeekResult($weekStart,$weekEnd,$startDate,$endDate,$salesPerformanceNumSubscriberOnlineWeeklys);
						echo "]},";
				}

				if (Input::has('sale_per_subcriber_online') && Input::has('groupby') && Input::get('groupby') == 'daily' ) {
						echo '{';
						echo "name: 'Sale Per Subscriber Online (x * $)',
		            		  data: [";
							  
							  if ($startDate != '' && $endDate != '') {
									
									$date = DateTime::createFromFormat('Y-m-d', $startDate);
									
									for ($i = 0; $i < 365; $i++) {
										if ($i > 0) {
											$dateIncrease = $date->modify('+1 day');
										} else {
											$dateIncrease = $date;
										}
										
										$day = $dateIncrease->format('d');
										$month = $dateIncrease->format('m');
										$month = $month - 1;
										$year = $dateIncrease->format('Y');
										$dateFull = $date->format('Y-m-d');
										
										try {
											$nDealer = $salesPerformanceSalePerSubscriberOnlineDailys[$dateFull];
											echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), '.$nDealer.'  ],';
										} catch (Exception $e) {
											echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), 0  ],';
										}
										
										if ($dateFull == $endDate) {
											break;
										}		
									}
									
								}
							  
						echo "]},";
						
						
						echo '{';
						echo "name: 'Sale Per Subscriber Online Moving Average (x * $)',
		            		  data: [";
	
							  if ($startDate != '' && $endDate != '') {
	
									$date = DateTime::createFromFormat('Y-m-d', $startDate);
	
									for ($i = 0; $i < 365; $i++) {
										if ($i > 0) {
											$dateIncrease = $date->modify('+1 day');
										} else {
											$dateIncrease = $date;
										}
	
										$day = $dateIncrease->format('d');
										$month = $dateIncrease->format('m');
										$month = $month - 1;
										$year = $dateIncrease->format('Y');
										$dateFull = $date->format('Y-m-d');
	
	
										try {
											$nDealer = $salesPerformanceTotalSalePerSubscriberMovingAvgSalesDailys[$dateFull];
											echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), '.$nDealer.'  ],';
										} catch (Exception $e) {
											echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), 0  ],';
										}
	
										if ($dateFull == $endDate) {
											break;
										}
									}
	
								}
	
						echo "]},";
						
				}
				if (Input::has('sale_per_subcriber_online') && Input::has('groupby') && Input::get('groupby') == 'weekly' ) {
						echo '{';
						echo "name: 'Sale Per Subscriber Online (x * $)',
		            		  data: [";
							  gernerateWeekResult($weekStart,$weekEnd,$startDate,$endDate,$salesPerformanceSalePerDragonWeeklys);
						echo "]},";
				}
				if (Input::has('sale_per_draw') && Input::has('groupby') && Input::get('groupby') == 'weekly' ) {
					foreach ($salesPerformanceSalePerDraw639Weeklys as $key => $salesPerformanceSalePerDraw639Weekly) {
						echo '{';
						echo "name: '".$key." Draw 639 (x * $)',
		            		  data: [";
							  gernerateWeekResult($weekStart,$weekEnd,$startDate,$endDate,$salesPerformanceSalePerDraw639Weekly);
						echo "]},";						
					}
					foreach ($salesPerformanceSalePerDrawPick5Weeklys as $key => $salesPerformanceSalePerDrawPick5Weekly) {
						echo '{';
						echo "name: '".$key." Draw Pick5 (x * $)',
		            		  data: [";
							  for ($i = $weekStart; $i <= 53; $i++) {
							  		try {
							  			echo "".$salesPerformanceSalePerDrawPick5Weekly[$i].",";
							  		} catch (Exception $e) {
							  			echo "null,";
							  		}
								  	
									if ($i >= $weekEnd) {
										break;
									}
								}
						echo "]},";						
					}
				}
				if (Input::has('avg_sale_per_draw') && Input::has('groupby') && Input::get('groupby') == 'weekly' ) {
					foreach ($salesPerformanceAvgSalePerDraw639Weeklys as $key => $salesPerformanceAvgSalePerDraw639Weekly) {
						echo '{';
						echo "name: 'AVG ".$key." Draw 639 (x * $)',
		            		  data: [";
								gernerateWeekResult($weekStart,$weekEnd,$startDate,$endDate,$salesPerformanceAvgSalePerDraw639Weekly);
						echo "]},";						
					}
					
					foreach ($salesPerformanceAvgSalePerDrawPick5Weeklys as $key => $salesPerformanceAvgSalePerDrawPick5Weekly) {
						echo '{';
						echo "name: 'AVG ".$key." Draw 639 (x * $)',
		            		  data: [";
							  gernerateWeekResult($weekStart,$weekEnd,$startDate,$endDate,$salesPerformanceAvgSalePerDrawPick5Weekly);
						echo "]},";						
					}
				}
								
				if (Input::has('avg_num_subcriber_online') && Input::has('groupby') && Input::get('groupby') == 'weekly' ) {
					echo '{';
					echo "name: 'AVG Number Subscriber Online',
	            		  data: [";
						  gernerateWeekResult($weekStart,$weekEnd,$startDate,$endDate,$salesPerformanceAvgNumSubscriberOnlineWeeklys);
					echo "]},";
				}
				
				if (Input::has('avg_lsdl_online') && Input::has('groupby') && Input::get('groupby') == 'weekly' ) {
					echo '{';
					echo "name: 'AVG Number LSDL Online',
	            		  data: [";
							gernerateWeekResult($weekStart,$weekEnd,$startDate,$endDate,$salesPerformanceLSDLAvgOnlineWeeklys);
					echo "]},";
				}
				
				if ((Input::has('total_smart_sale') && Input::has('groupby') && Input::get('groupby') == 'daily')) {
					echo '{';
					echo "name: 'Total Smart Sales (x * $)',
	            		  data: [";

						  if ($startDate != '' && $endDate != '') {

								$date = DateTime::createFromFormat('Y-m-d', $startDate);

								for ($i = 0; $i < 365; $i++) {
									if ($i > 0) {
										$dateIncrease = $date->modify('+1 day');
									} else {
										$dateIncrease = $date;
									}

									$day = $dateIncrease->format('d');
									$month = $dateIncrease->format('m');
									$month = $month - 1;
									$year = $dateIncrease->format('Y');
									$dateFull = $date->format('Y-m-d');


									try {
										$nDealer = $salesPerformanceTotalSmartSalesDailys[$dateFull];
										echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), '.$nDealer.'  ],';
									} catch (Exception $e) {
										echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), 0  ],';
									}

									if ($dateFull == $endDate) {
										break;
									}
								}

							}

					echo "]},";
					
					echo '{';
					echo "name: 'Total Smart Sales Moving Average (x * $)',
	            		  data: [";

						  if ($startDate != '' && $endDate != '') {

								$date = DateTime::createFromFormat('Y-m-d', $startDate);

								for ($i = 0; $i < 365; $i++) {
									if ($i > 0) {
										$dateIncrease = $date->modify('+1 day');
									} else {
										$dateIncrease = $date;
									}

									$day = $dateIncrease->format('d');
									$month = $dateIncrease->format('m');
									$month = $month - 1;
									$year = $dateIncrease->format('Y');
									$dateFull = $date->format('Y-m-d');


									try {
										$nDealer = $salesPerformanceTotalSmartSalesMovingAvgSalesDailys[$dateFull];
										echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), '.$nDealer.'  ],';
									} catch (Exception $e) {
										echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), 0  ],';
									}

									if ($dateFull == $endDate) {
										break;
									}
								}

							}

					echo "]},";
				}

				if ((Input::has('total_smart_sale') && Input::has('groupby') && Input::get('groupby') == 'weekly')) {
					echo '{';
					echo "name: 'Total Smart Sales (x * $)',
	            		  data: [";
						  gernerateWeekResult($weekStart,$weekEnd,$startDate,$endDate,$salesPerformanceTotalSmartSalesWeeklys);
					echo "]},";
				}

				if ((Input::has('total_metfone_sale') && Input::has('groupby') && Input::get('groupby') == 'daily')) {
					echo '{';
					echo "name: 'Total Metfone Sales (x * $)',
	            		  data: [";

						  if ($startDate != '' && $endDate != '') {

								$date = DateTime::createFromFormat('Y-m-d', $startDate);

								for ($i = 0; $i < 365; $i++) {
									if ($i > 0) {
										$dateIncrease = $date->modify('+1 day');
									} else {
										$dateIncrease = $date;
									}

									$day = $dateIncrease->format('d');
									$month = $dateIncrease->format('m');
									$month = $month - 1;
									$year = $dateIncrease->format('Y');
									$dateFull = $date->format('Y-m-d');


									try {
										$nDealer = $salesPerformanceTotalMetfoneSalesDailys[$dateFull];
										echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), '.$nDealer.'  ],';
									} catch (Exception $e) {
										echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), 0  ],';
									}

									if ($dateFull == $endDate) {
										break;
									}
								}

							}

					echo "]},";
					
					echo '{';
					echo "name: 'Total Metfone Sales Moving Average (x * $)',
	            		  data: [";

						  if ($startDate != '' && $endDate != '') {

								$date = DateTime::createFromFormat('Y-m-d', $startDate);

								for ($i = 0; $i < 365; $i++) {
									if ($i > 0) {
										$dateIncrease = $date->modify('+1 day');
									} else {
										$dateIncrease = $date;
									}

									$day = $dateIncrease->format('d');
									$month = $dateIncrease->format('m');
									$month = $month - 1;
									$year = $dateIncrease->format('Y');
									$dateFull = $date->format('Y-m-d');


									try {
										$nDealer = $salesPerformanceTotalMetfoneSalesMovingAvgSalesDailys[$dateFull];
										echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), '.$nDealer.'  ],';
									} catch (Exception $e) {
										echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), 0  ],';
									}

									if ($dateFull == $endDate) {
										break;
									}
								}

							}

					echo "]},";
				}

				if ((Input::has('total_metfone_sale') && Input::has('groupby') && Input::get('groupby') == 'weekly')) {
					echo '{';
					echo "name: 'Total Metfone Sales (x * $)',
	            		  data: [";
						  gernerateWeekResult($weekStart,$weekEnd,$startDate,$endDate,$salesPerformanceTotalMetfoneSalesWeeklys);
					echo "]},";
				}
				
       		}
       	?>]

    });
});	


function weekFunction() {
   	
   	var groupby = document.getElementById("groupby").value;
   	   	
    if(groupby == 'daily'){
    	document.getElementById('hide_weekly').style.display = "none";
    	//document.getElementById('hide_daily').style.display = "block";
    }else if(groupby == 'weekly'){
    	document.getElementById('hide_weekly').style.display = "block";
    	// document.getElementById('hide_daily').style.display = "none";
    }
}

</script>


@include('layouts.partial.render-message')
<h4>Sales Performance</h4>
<?php

?>

<div class="row">
	<div class="col-md-12">
		<!-- Default panel contents -->
		<div class="panel panel-default">
		  <div class="panel-heading">
		  	<div class="row">
				{{ Form::open(array('url' => 'sale-performance/sales_performance')) }}
		  	 		<div class="row">
		  	 			<div class="col-md-1 text-right">
	  	 					<label>Date From:</label>
	  	 				</div>	  	 				
	  	 				<div class="col-md-2">
	  	 					<input class="form-control" required="required" type="text" id="datepicker_start" name="start_date" value="<?php echo $startDate;//if(Input::has('start_date')) {echo  Input::get('start_date');}//else{echo date("Y-m-j").' 00:00';} ?>" />
	  	 				</div>
	  	 				<div class="col-md-1 text-right">
	  	 					<label>To:</label>
	  	 				</div>
	  	 				<div class="col-md-2">
	  	 					<input class="form-control" required="required" type="text" id="datepicker_end" name="end_date" value="<?php echo $endDate;//if(Input::has('end_date')) {echo  Input::get('end_date');}//else{echo date("Y-m-j").' 23:59';} ?>" />
	  	 				</div>
	  	 				<div class="col-md-2">
	  	 					<input class="btn btn-primary" type="submit" name="search_terminal" value="Filter" />
	  	 				</div>
	  	 			</div>
	  	 			<div class="row">
	  	 				<div class="col-md-1 text-right">
		  	 				<label>Date By:</label>
		  	 			</div>
		  	 			<div class="col-md-2">
		  	 				<!-- {{ Form::select('groupby', array('daily'=>'Daily','weekly'=>'Weekly'), Input::get('groupby'), array('class' => 'form-control', 'id' => 'groupby')) }} -->
		  	 				<?php $groupby = ''; if (Input::has('groupby')){$groupby = Input::get('groupby');}
			  	 	    	  $listgroupby = array('daily'=>'Daily','weekly'=>'Weekly');
			  	 	    	  //$listgroupby = array('daily'=>'Daily');
				  	 	   	?>
				  	 	    {{ Form::select('groupby',$listgroupby, $groupby, ['id' => 'groupby','class' => 'form-control', 'onchange' => 'weekFunction()']) }}
		  	 			</div>
		  	 		</div>	
	  	 			<div class="row">
	  	 				<div class="col-md-6">
	  	 					<div id="hide_daily">
	  	 						<?php //if (Input::has('groupby') && Input::get('groupby') == 'daily') {?>
		  	 					<div class="row">	  	 					
		  	 						<div class="col-md-1 text-right">
		  	 							<?php $sales = ''; 
		  	 							if (Input::has('sales') || $firstTime){
		  	 								echo '<input type="checkbox" name="sales" value="sales" checked="checked"/>';
		  	 							} else {
		  	 								echo '<input type="checkbox" name="sales" value="sales"/>';
		  	 							}?>
				  	 				</div>
				  	 				<div class="col-md-6 text-left">
				  	 					<label>Total Sales</label>
				  	 				</div>
		  	 					</div>
		  	 					<div class="row">	  	 					
		  	 						<div class="col-md-1 text-right">
		  	 							<!-- <?php $sale_lotto_639 = ''; if (Input::has('sale_lotto_639')){$sale_lotto_639 = Input::get('sale_lotto_639');}?>
				  	 					{{ Form::checkbox('sale_lotto_639',$sale_lotto_639, array('id'=>'sale_lotto_639')) }} -->
				  	 					<?php $sales = ''; 
		  	 							if (Input::has('sale_lotto_639')){
		  	 								echo '<input type="checkbox" name="sale_lotto_639" value="sale_lotto_639" checked="checked"/>';
		  	 							} else {
		  	 								echo '<input type="checkbox" name="sale_lotto_639" value="sale_lotto_639"/>';
		  	 							}?>
				  	 				</div>
				  	 				<div class="col-md-6 text-left">
				  	 					<label>Lotto 639 Sales</label>
				  	 				</div>
		  	 					</div>
		  	 					
		  	 					<div class="row">	  	 					
		  	 						<div class="col-md-1 text-right">
		  	 							<!-- <?php $sale_pick5 = ''; if (Input::has('sale_pick5')){$sale_pick5 = Input::get('sale_pick5');}?>
				  	 					{{ Form::checkbox('sale_pick5',$sale_pick5, array('id'=>'sale_pick5')) }} -->
				  	 					<?php $sales = ''; 
		  	 							if (Input::has('sale_pick5')){
		  	 								echo '<input type="checkbox" name="sale_pick5" value="sale_pick5" checked="checked"/>';
		  	 							} else {
		  	 								echo '<input type="checkbox" name="sale_pick5" value="sale_pick5"/>';
		  	 							}?>
				  	 				</div>
				  	 				<div class="col-md-6 text-left">
				  	 					<label>Pick5 Sales</label>
				  	 				</div>
		  	 					</div>
		  	 					
		  	 					<div class="row">	  	 					
		  	 						<div class="col-md-1 text-right">
				  	 					<?php $lsdl_sale = ''; 
		  	 							if (Input::has('sale_per_dragon_dealer_online')){
		  	 								echo '<input type="checkbox" name="sale_per_dragon_dealer_online" value="sale_per_dragon_dealer_online" checked="checked"/>';
		  	 							} else {
		  	 								echo '<input type="checkbox" name="sale_per_dragon_dealer_online" value="sale_per_dragon_dealer_online"/>';
		  	 							}?>
				  	 				</div>
				  	 				<div class="col-md-6 text-left">
				  	 					<label>Sales Per Dragon Dealer Online</label>
				  	 				</div>
		  	 					</div>
		  	 					
		  	 					<div class="row">	  	 					
		  	 						<div class="col-md-1 text-right">
				  	 					<?php $lsdl_sale = ''; 
		  	 							if (Input::has('num_subcriber_online')){
		  	 								echo '<input type="checkbox" name="num_subcriber_online" value="num_subcriber_online" checked="checked"/>';
		  	 							} else {
		  	 								echo '<input type="checkbox" name="num_subcriber_online" value="num_subcriber_online"/>';
		  	 							}?>
				  	 				</div>
				  	 				<div class="col-md-6 text-left">
				  	 					<label>Num Subscriber Online</label>
				  	 				</div>
		  	 					</div>
		  	 					<div class="row">	  	 					
		  	 						<div class="col-md-1 text-right">
				  	 					<?php $lsdl_sale = ''; 
		  	 							if (Input::has('sale_per_subcriber_online')){
		  	 								echo '<input type="checkbox" name="sale_per_subcriber_online" value="sale_per_subcriber_online" checked="checked"/>';
		  	 							} else {
		  	 								echo '<input type="checkbox" name="sale_per_subcriber_online" value="sale_per_subcriber_online"/>';
		  	 							}?>
				  	 				</div>
				  	 				<div class="col-md-6 text-left">
				  	 					<label>Sales Per Subscriber Online</label>
				  	 				</div>
		  	 					</div>
		  	 				<?php //}?>		
	  	 					</div>
	  	 					
	  	 					<div id="hide_weekly" style="display: <?php if (Input::has('groupby') && Input::get('groupby') == 'daily'){echo 'none';}elseif(Input::has('groupby') && Input::get('groupby') == 'weekly'){echo 'block';}else{echo 'none';};?>">
	  	 					<?php //if (Input::has('groupby') && Input::get('groupby') == 'weekly') {?>
		  	 					<div class="row">	  	 					
		  	 						<div class="col-md-1 text-right">
				  	 					<?php 
		  	 							if (Input::has('sale_per_draw')){
		  	 								echo '<input type="checkbox" name="sale_per_draw" value="sale_per_draw" checked="checked"/>';
		  	 							} else {
		  	 								echo '<input type="checkbox" name="sale_per_draw" value="sale_per_draw"/>';
		  	 							}?>
				  	 				</div>
				  	 				<div class="col-md-6 text-left">
				  	 					<label>Sales Per Draw</label>
				  	 				</div>
		  	 					</div>
		  	 					
		  	 					<!-- <div class="row">	  	 					
		  	 						<div class="col-md-1 text-right">
				  	 					<?php 
		  	 							if (Input::has('avg_sale_per_draw')){
		  	 								echo '<input type="checkbox" name="avg_sale_per_draw" value="avg_sale_per_draw" checked="checked"/>';
		  	 							} else {
		  	 								echo '<input type="checkbox" name="avg_sale_per_draw" value="avg_sale_per_draw"/>';
		  	 							}?>
				  	 				</div>
				  	 				<div class="col-md-6 text-left">
				  	 					<label>AVG Sales Per Draw</label>
				  	 				</div>
		  	 					</div> -->
		  	 					<!-- <div class="row">	  	 					
		  	 						<div class="col-md-1 text-right">
				  	 					<?php 
		  	 							if (Input::has('avg_num_subcriber_online')){
		  	 								echo '<input type="checkbox" name="avg_num_subcriber_online" value="avg_num_subcriber_online" checked="checked"/>';
		  	 							} else {
		  	 								echo '<input type="checkbox" name="avg_num_subcriber_online" value="avg_num_subcriber_online"/>';
		  	 							}?>
				  	 				</div>
				  	 				<div class="col-md-6 text-left">
				  	 					<label>AVG Num Subscriber Online</label>
				  	 				</div>
		  	 					</div> -->
		  	 					<!-- <div class="row">	  	 					
		  	 						<div class="col-md-1 text-right">
				  	 					<?php 
		  	 							if (Input::has('avg_lsdl_online')){
		  	 								echo '<input type="checkbox" name="avg_lsdl_online" value="avg_lsdl_online" checked="checked"/>';
		  	 							} else {
		  	 								echo '<input type="checkbox" name="avg_lsdl_online" value="avg_lsdl_online"/>';
		  	 							}?>
				  	 				</div>
				  	 				<div class="col-md-6 text-left">
				  	 					<label>AVG LSDL Online</label>
				  	 				</div>
		  	 					</div> -->
		  	 				<?php //} ?>	
	  	 					</div>	
	  	 					
	  	 				</div>
	  	 				<div class="col-md-6">
							<div class="row">
								<div class="col-md-1 text-right">
									<!-- <?php $total_telco_sales = ''; if (Input::has('total_telco_sales')){$total_telco_sales = Input::get('total_telco_sales');}?>
			  	 					{{ Form::checkbox('total_telco_sales',$total_telco_sales, array('id'=>'total_telco_sales')) }} -->
									<?php $sales = '';
									if (Input::has('total_telco_sales')){
									echo '<input type="checkbox" name="total_telco_sales" value="total_telco_sales" checked="checked"/>';
									} else {
									echo '<input type="checkbox" name="total_telco_sales" value="total_telco_sales"/>';
									}?>
								</div>
								<div class="col-md-6 text-left">
									<label>Total Telco Sales</label>
								</div>
							</div>
	  	 					<div class="row">	  	 					
	  	 						<div class="col-md-1 text-right">
	  	 							<!-- <?php $sale_lotto_639_telco = ''; if (Input::has('sale_lotto_639_telco')){$sale_lotto_639_telco = Input::get('sale_lotto_639_telco');}?>
			  	 					{{ Form::checkbox('sale_lotto_639_telco',$sale_lotto_639_telco, array('id'=>'sale_lotto_639_telco')) }} -->
			  	 					<?php $sales = ''; 
	  	 							if (Input::has('sale_lotto_639_telco')){
	  	 								echo '<input type="checkbox" name="sale_lotto_639_telco" value="sale_lotto_639_telco" checked="checked"/>';
	  	 							} else {
	  	 								echo '<input type="checkbox" name="sale_lotto_639_telco" value="sale_lotto_639_telco"/>';
	  	 							}?>
			  	 				</div>
			  	 				<div class="col-md-6 text-left">
			  	 					<label>Lotto 639 Telco Sales</label>
			  	 				</div>
	  	 					</div>
	  	 						
	  	 					<div class="row">	  	 					
	  	 						<div class="col-md-1 text-right">
	  	 							<!-- <?php $sale_pick5_telco = ''; if (Input::has('sale_pick5_telco')){$sale_pick5_telco = Input::get('sale_pick5_telco');}?>
			  	 					{{ Form::checkbox('sale_pick5_telco',$sale_pick5_telco, array('id'=>'sale_pick5_telco')) }} -->
			  	 					<?php $sales = ''; 
	  	 							if (Input::has('sale_pick5_telco')){
	  	 								echo '<input type="checkbox" name="sale_pick5_telco" value="sale_pick5_telco" checked="checked"/>';
	  	 							} else {
	  	 								echo '<input type="checkbox" name="sale_pick5_telco" value="sale_pick5_telco"/>';
	  	 							}?>
			  	 				</div>
			  	 				<div class="col-md-6 text-left">
			  	 					<label>Pick5 Telco Sales</label>
			  	 				</div>
	  	 					</div>
	  	 					<div class="row">	  	 					
	  	 						<div class="col-md-1 text-right">
			  	 					<?php $lsdl_sale = ''; 
	  	 							if (Input::has('lsdl_sale')){
	  	 								echo '<input type="checkbox" name="lsdl_sale" value="lsdl_sale" checked="checked"/>';
	  	 							} else {
	  	 								echo '<input type="checkbox" name="lsdl_sale" value="lsdl_sale"/>';
	  	 							}?>
			  	 				</div>
			  	 				<div class="col-md-6 text-left">
			  	 					<label>Total LSDL Sales (POS Sales)</label>
			  	 				</div>
	  	 					</div>
	  	 					<div class="row">	  	 					
	  	 						<div class="col-md-1 text-right">
			  	 					<?php $lsdl_sale = ''; 
	  	 							if (Input::has('lsdl_online')){
	  	 								echo '<input type="checkbox" name="lsdl_online" value="lsdl_online" checked="checked"/>';
	  	 							} else {
	  	 								echo '<input type="checkbox" name="lsdl_online" value="lsdl_online"/>';
	  	 							}?>
			  	 				</div>
			  	 				<div class="col-md-6 text-left">
			  	 					<label>Total LSDL Online (Dealer Online)</label>
			  	 				</div>
	  	 					</div>
	  	 					<div class="row">	  	 					
	  	 						<div class="col-md-1 text-right">
			  	 					<?php $lsdl_sale = ''; 
	  	 							if (Input::has('total_smart_sale')){
	  	 								echo '<input type="checkbox" name="total_smart_sale" value="total_smart_sale" checked="checked"/>';
	  	 							} else {
	  	 								echo '<input type="checkbox" name="total_smart_sale" value="total_smart_sale"/>';
	  	 							}?>
			  	 				</div>
			  	 				<div class="col-md-6 text-left">
			  	 					<label>Total Smart Sales</label>
			  	 				</div>
	  	 					</div>
	  	 					<div class="row">	  	 					
	  	 						<div class="col-md-1 text-right">
			  	 					<?php $lsdl_sale = ''; 
	  	 							if (Input::has('smart_sale')){
	  	 								echo '<input type="checkbox" name="smart_sale" value="smart_sale" checked="checked"/>';
	  	 							} else {
	  	 								echo '<input type="checkbox" name="smart_sale" value="smart_sale"/>';
	  	 							}?>
			  	 				</div>
			  	 				<div class="col-md-6 text-left">
			  	 					<label>Smart Sales ( Sub )</label>
			  	 				</div>
	  	 					</div>
	  	 					<div class="row">	  	 					
	  	 						<div class="col-md-1 text-right">
			  	 					<?php $lsdl_sale = ''; 
	  	 							if (Input::has('total_metfone_sale')){
	  	 								echo '<input type="checkbox" name="total_metfone_sale" value="total_metfone_sale" checked="checked"/>';
	  	 							} else {
	  	 								echo '<input type="checkbox" name="total_metfone_sale" value="total_metfone_sale"/>';
	  	 							}?>
			  	 				</div>
			  	 				<div class="col-md-6 text-left">
			  	 					<label>Total Metfone Sales</label>
			  	 				</div>
	  	 					</div>
	  	 					<div class="row">	  	 					
	  	 						<div class="col-md-1 text-right">
			  	 					<?php $lsdl_sale = ''; 
	  	 							if (Input::has('metfone_sale')){
	  	 								echo '<input type="checkbox" name="metfone_sale" value="metfone_sale" checked="checked"/>';
	  	 							} else {
	  	 								echo '<input type="checkbox" name="metfone_sale" value="metfone_sale"/>';
	  	 							}?>
			  	 				</div>
			  	 				<div class="col-md-6 text-left">
			  	 					<label>Metfone Sales ( Sub )</label>
			  	 				</div>
	  	 					</div>
	  	 					
	  	 				</div>
	  	 			</div>
	  	 		{{ Form::close() }}
			</div>	
			
			<div class="row">
		  	 	<div class="col-md-12">
		  	 		<div id="container" style="min-width: 310px; height: 600px; margin: 0 auto"></div>
		  	 	</div>
		  	</div> 		
			
		  </div>	
		  <!-- Table -->
		</div>
		@stop
	</div>
</div>