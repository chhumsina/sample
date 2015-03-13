@section('title', 'Dragon Warrior Quota Graphic')
@section('content')
<?php
$baseUrl = URL::to('/');

$ddate = $startDate;
$duedt = explode("-", $ddate);
$date  = mktime(0, 0, 0, $duedt[1], $duedt[2], $duedt[0]);
$weekStart  = (int)date('W', $date);
//echo "Weeknummer: " . $weekStart;

$ddate = $endDate;
$duedt = explode("-", $ddate);
$date  = mktime(0, 0, 0, $duedt[1], $duedt[2], $duedt[0]);
$weekEnd  = (int)date('W', $date);
//echo "Weeknummer: " . $weekEnd;
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
            echo "xAxis: {";
            echo "categories: [";
                for ($i = $weekStart; $i < 52; $i++) {
                    echo "'Week ".$i."',";
                    if ($i >= $weekEnd) {
                        break;
                    }
                }
            echo "]";
            echo "},";
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
		tooltip: {
			//valueSuffix: '°C'
			//headerFormat: '<b>{series.name}</b><br>',
			//pointFormat: '{point.x:.}: {point.y:.2f} x',
			valueSuffix: 'x'
		},

        series: [
			<?php
						echo '{';
						echo "name: 'Sales Game',
		            		  data: [";
							  for ($i = $weekStart; $i < 52; $i++) {
							  		try {
							  			echo "".$saleGameWeekly[$i].",";
							  		} catch (Exception $e) {
							  			echo "null,";
							  		}

									if ($i >= $weekEnd) {
										break;
									}
								}
						echo "]},";

						echo '{';
						echo "name: 'Sales Visit',
		            		  data: [";
							  for ($i = $weekStart; $i < 52; $i++) {
							  		try {
							  			echo "".$saleVisitWeekly[$i].",";
							  		} catch (Exception $e) {
							  			echo "null,";
							  		}

									if ($i >= $weekEnd) {
										break;
									}
								}
						echo "]},";

						echo '{';
						echo "name: 'Top Up',
		            		  data: [";
							  for ($i = $weekStart; $i < 52; $i++) {
							  		try {
							  			echo "".$saleTopupGameWeekly[$i].",";
							  		} catch (Exception $e) {
							  			echo "null,";
							  		}

									if ($i >= $weekEnd) {
										break;
									}
								}
						echo "]},";

						echo '{';
						echo "name: 'Recruit',
		            		  data: [";
							  for ($i = $weekStart; $i < 52; $i++) {
							  		try {
							  			echo "".$saleRecruitWeekly[$i].",";
							  		} catch (Exception $e) {
							  			echo "null,";
							  		}

									if ($i >= $weekEnd) {
										break;
									}
								}
						echo "]},";
			?>
		]

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
<h4>Dragon Warrior Quota Graphic</h4>
<?php

?>

<div class="row">
	<div class="col-md-12">
		<!-- Default panel contents -->
		<div class="panel panel-default">
		  <div class="panel-heading">
		  	<div class="row">
				{{ Form::open(array('url' => 'dragon-warrior-quota-graphic/dragon_warrior_quota_graphic')) }}
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