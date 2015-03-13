@section('title', 'Edit Sale Staff')
@section('content')
<?php
$baseUrl = URL::to('/');
?>
<script type="text/javascript">
	$(document).ready(function(){
		$khanCode = '';
		$baseUrl = '{{$baseUrl}}';
		$(".provinceCheck").on("change",function(e){
			$pCode = $(this).val();
			if ($(this).is(':checked')) {
				console.log($pCode);
				loadDistrictByProvinceCode($pCode);
			} else {
				$(".province-"+$pCode).html("");
			}
		});
		$(".provinceClick").click(function(){
			$pCode = $(this).attr("pCode");
			$(".province-"+$pCode).slideToggle();
		});
		
		$('body').on('change', '.districtCheck', function() {
			$dCode = $(this).val();
			console.log($dCode);
			if ($(this).is(':checked')) {
				console.log($dCode);
				loadDealerByDistrictCode($dCode);
			} else {
				$(".district-"+$dCode).html("");
			}
		});
		$('body').on('click', '.districtClick', function() {
			$dCode = $(this).attr("dCode");
			$(".district-"+$dCode).slideToggle();
		});
		$('body').on('change', '.allDealerCheck', function() {
			$dCode = $(this).val();
			if ($(this).is(':checked')) {
				console.log($dCode);
				$(".dealer-khan-"+$dCode).prop('checked', true);
			} else {
				$(".dealer-khan-"+$dCode).prop('checked', false);
			}
			
		});
		function loadDistrictByProvinceCode($code) {						
			var url = $baseUrl+'/locations/khan-by-province-code/'+$code;
			console.log(url);
	    	$.ajax({ 
		        type: 'GET', 
		        url: url, 
		        data: { code: $code }, 
		        success: function (data) {
		        	console.log(data);
		        	$stp = '<ul style="list-style: none;">';
		        	Object.keys(data).forEach(function (key) { 
		        	    var value = data[key];
		        	    console.log("key ="+key);
		        	    console.log("$khanCode = "+$khanCode);
						$stp += '<li>';
						$stp += '<input type="checkbox" value="'+key+'" class="districtCheck"/> ';
						$stp += '<span dCode="'+key+'" class="districtClick">'+'('+key+') '+value+'</span>';
						$stp += '<div class="district-'+key+'"></div>';
						$stp += '</li>';
		        	    // iteration code
		        	});
		        	$stp += '</ul>';
		        	$(".province-"+$code).append($stp);
		        }
		    });
		 }
		 function loadDealerByDistrictCode($code) {						
			var url = $baseUrl+'/dealers/dealer-by-khan-code/'+$code;
			console.log(url);
	    	$.ajax({ 
		        type: 'GET', 
		        url: url, 
		        data: { code: $code }, 
		        success: function (data) {
		        	console.log(data);
		        	$stp = '<ul style="list-style: none;">';
	        		$stp += '<li>';
	        		$stp += '<input type="checkbox" value="'+$code+'" class="allDealerCheck"/> ';
	        		$stp += '<span dCode="'+$code+'" class="allDealerClick">All</span>';
	        		$stp += '<li>';
		        	Object.keys(data).forEach(function (key) { 
		        	    var value = data[key];
		        	    console.log("key ="+key);
						$stp += '<li>';
						$stp += '<input type="checkbox" value="'+key+'" class="dealerCheck dealer-khan-'+$code+'" name="hold_dealers[]"/> ';
						$stp += '<span dCode="'+key+'" class="dealerClick">'+'('+key+') '+value+'</span>';
						$stp += '</li>';
		        	    // iteration code
		        	});
		        	$stp += '</ul>';
		        	$(".district-"+$code).append($stp);
		        }
		    });
		 }
	});
</script>
<h4>Edit Sale Staff</h4>
@include('layouts.partial.render-message')
<div class="row">
	<div class="col-md-12">
		{{ Form::model($sale, array('method' => 'PATCH', 'route' =>array('sales.update', $sale->sale_staff_id))) }}
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('sale_staff_id', 'Sale Id:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('sale_staff_id', $sale->sale_staff_id, array('class' => 'form-control','placeholder'=>'Sale  Id','disabled'=>'disabled')) }}
			  		{{ Form::hidden('sale_staff_id', $sale->sale_staff_id, array('class' => 'form-control','placeholder'=>'Sale Id')) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('id', 'Sale Ecard Id:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('id', $sale->id, array('class' => 'form-control','placeholder'=>'Sale Ecard Id')) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('name', 'Sale Name:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('name', $sale->name, array('class' => 'form-control','placeholder'=>'Sale Name')) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('position', 'Position:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('position', $sale->position, array('class' => 'form-control','placeholder'=>'Position')) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('phone', 'Phone:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('phone', $sale->phone , array('class' => 'form-control','placeholder'=>'Phone')) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('email', 'Email:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('email', $sale->email, array('class' => 'form-control','placeholder'=>'Email')) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('parent_id', 'Report To Sale Id:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('parent_id', $sale->parent_id, array('class' => 'form-control','placeholder'=>'Report To Sale Id','pattern' => '\d*')) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('dealers', 'Hold Dealers:') }}
			  	</div>
			  	<div class="col-md-6 text-left">
			  		<div style="overflow: scroll;max-height: 400px;border:1px solid gray">
			  			<ul style="list-style: none;">
			  				<?php
			  				$provinceCodes = array();
							$khanCodes = array();
			  				$holdDealers = array();
							if ($sale->hold_dealers != '') {
								$holdDealers = json_decode($sale->hold_dealers); 
								foreach ($holdDealers as $key => $did) {
									$provinceCode =substr($did,0,2);
									$khanCode =substr($did,0,4);
									array_push($provinceCodes,$provinceCode);
									array_push($khanCodes,$khanCode);
								}
							}
		
			  				foreach ($provinces as $key => $province) {
			  					
								 $checked = '';
								 $showKhan = false;
								 if (in_array($province->code,$provinceCodes)) {
								 	$checked = 'checked="checked"';
								 	$showKhan = true;
								 }
								
								 $stp = '<li>';
								 $stp .= '<div><input type="checkbox" value="'.$province->code.'" class="provinceCheck" '.$checked.'/> ';
								 $stp .= '<span pCode="'.$province->code.'" class="provinceClick">('.$province->code.') '.$province->name_en.'</span></div>';
								 $stp .= '<div class="province-'.$province->code.'">';
									 if ($showKhan) {
									 	$khans = DB::table('khan')->where('province__code',$province->code)->get();
										$stp .= '<ul style="list-style: none;">';
										foreach ($khans as $key => $khan) {
											$stp .= '<li>';
											$checked = '';
											$showDealer = false;
											if (in_array($khan->code,$khanCodes)) {
											 	$checked = 'checked="checked"';
											 	$showDealer = true;
											}
											$stp .= '<input type="checkbox" value="'.$khan->code.'" class="districtCheck" '.$checked.'/> ';
											$stp .= '<span dCode="'.$khan->code.'" class="districtClick">'.'('.$khan->code.') '.$khan->name_en.'</span>';
											$stp .= '<div class="district-'.$khan->code.'">';
											
											if ($showDealer) {
												$dealers = DB::table('dealer')->where('khan__code',$khan->code)
																->select('id','name')->get();
												
												$stp .= '<ul style="list-style: none;">';
								        		$stp .= '<li>';
								        		$stp .= '<input type="checkbox" value="'.$khan->code.'" class="allDealerCheck"/> ';
								        		$stp .= '<span dCode="'.$khan->code.'" class="allDealerClick">All</span>';
								        		$stp .= '<li>';
									        	foreach ($dealers as $key => $dealer) {
													$checked = '';
													if (in_array($dealer->id,$holdDealers)) {
													 	$checked = 'checked="checked"';
													}
													$stp .= '<li>';
													$stp .= '<input type="checkbox" value="'.$dealer->id.'" class="dealerCheck dealer-khan-'.$khan->code.'" name="hold_dealers[]" '.$checked.'/> ';
													$stp .= '<span dCode="'.$dealer->id.'" class="dealerClick">('.$dealer->id.') '.$dealer->name.'</span>';
													$stp .= '</li>';
									        	    // iteration code
									        	}
									        	$stp .= '</ul>';
											}
											
											$stp .= '</div>';
											$stp .= '</li>';	
										}
							        	$stp .= '</ul>';
									 }
								 $stp .= '</div>';
								 $stp .= '</li>';
								 
								 echo $stp;
							}
			  				?>
			  			</ul>
			  			
			  		</div>
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('status', 'Status:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		<?php 
			  			$status = $sale->status;// == 1 ?1:0;
			  			echo Form::select('status',array('active' => 'active', 'inactive' => 'inactive'),$status,array('id' => 'status', 'class' => 'form-control','required'=>'required'));
			  		?>
			  	</div>
			</div>
			<div class="row">
				<div class="col-md-4 text-right"></div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::submit('Save',array('class' => 'btn btn-primary')) }}
			  	</div>
			</div>
			@include('layouts.partial.render-message-form')
		{{ Form::close() }}
	</div>
</div>
@stop