@section('title', 'Dealer Status')
@section('content')
<?php
$baseUrl = URL::to('/');

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
        yAxis: {
            title: {
                text: '(X)'
            },
            min: 0
        },
        tooltip: {
            headerFormat: '<b>{series.name}</b><br>',
            pointFormat: '{point.x:%e. %b}: {point.y:.2f} x'
        },

        series: [
       	<?php
       		//if(Input::has('start_date') && Input::has('end_date')) {
       		if ($startDate != '' && $endDate != '') {
       			echo '{';
				echo "name: 'Balance less than 100,000 R',
            		  data: [";

					  //if(Input::has('start_date') && Input::has('end_date')) {
					if ($startDate != '' && $endDate != '') {
							//$startDate = Input::get('start_date');
							//$endDate = Input::get('end_date');

							$date = DateTime::createFromFormat('Y-m-d', $startDate);

							for ($i = 0; $i <= 365; $i++) {
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
									$nDealer = $arrDealers1[$dateFull];
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
				echo "name: 'Balance bigger than 800,000 R',
            		  data: [";

					  if ($startDate != '' && $endDate != '') {

							$date = DateTime::createFromFormat('Y-m-d', $startDate);

							for ($i = 0; $i <= 365; $i++) {
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
									$nDealer = $arrDealers2[$dateFull];
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
				echo "name: 'Not sales last 5 days',
            		  data: [";

					  if ($startDate != '' && $endDate != '') {

							$date = DateTime::createFromFormat('Y-m-d', $startDate);

							for ($i = 0; $i <= 365; $i++) {
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
									$nDealer = $arrDealers3[$dateFull];
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
				echo "name: 'Registered Dealers',
            		  data: [";

					  if ($startDate != '' && $endDate != '') {

							$date = DateTime::createFromFormat('Y-m-d', $startDate);

							for ($i = 0; $i <= 365; $i++) {
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
									$nDealer = $arrDealerRegisters[$dateFull.' 00:00:00'];
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
				echo "name: 'New POS installed',
            		  data: [";

					  if ($startDate != '' && $endDate != '') {

							$date = DateTime::createFromFormat('Y-m-d', $startDate);

							for ($i = 0; $i <= 365; $i++) {
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
									$nDealer = $arrNewPOSInstallted[$dateFull.' 00:00:00'];
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
				echo "name: 'New Dealer Top Up',
            		  data: [";

					  if ($startDate != '' && $endDate != '') {

							$date = DateTime::createFromFormat('Y-m-d', $startDate);

							for ($i = 0; $i <= 365; $i++) {
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
									$nDealer = $arrNewDealerTops[$dateFull.' 00:00:00'];
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
				echo "name: 'Amount Top up by dealers (x * 100 $)',
            		  data: [";

					  if ($startDate != '' && $endDate != '') {

							$date = DateTime::createFromFormat('Y-m-d', $startDate);

							for ($i = 0; $i <= 365; $i++) {
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
									$nDealer = $arrAmountNewDealerTops[$dateFull.' 00:00:00'];
									echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), '.$nDealer.'  ],';
								} catch (Exception $e) {
									echo '[Date.UTC('.$year.',  '.$month.', '.$day.'), 0  ],';
								}

								if ($dateFull == $endDate) {
									break;
								}
							}

						}

				echo "]}";
       		}
       	?>]

    });
});

</script>

@include('layouts.partial.render-message')
<h4>Dealer Status</h4>
<?php
?>
<div class="row">
	<div class="col-md-12">
		<!-- Default panel contents -->
		<div class="panel panel-default">
		  <div class="panel-heading">
		  	<div class="row">
				{{ Form::open(array('url' => 'dashboard/dashboard')) }}
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