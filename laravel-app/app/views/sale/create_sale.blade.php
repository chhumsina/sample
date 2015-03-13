@section('title', 'New Sale Staff')
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
<h4>New Sale Staff</h4>
<div class="row">
	<div class="col-md-12">
		{{ Form::open(array('route' => 'sales.store')) }}
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('id', 'Sale Ecard Id:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('id', '', array('class' => 'form-control','placeholder'=>'Sale Ecard Id')) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('name', 'Sale Name:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('name', '', array('class' => 'form-control','placeholder'=>'Sale Name')) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('position', 'Position:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('position', '', array('class' => 'form-control','placeholder'=>'Position')) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('phone', 'Phone:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('phone', '', array('class' => 'form-control','placeholder'=>'Phone')) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('email', 'Email:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('email', '', array('class' => 'form-control','placeholder'=>'Email')) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('parent_id', 'Report To Sale Id:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('parent_id', '', array('class' => 'form-control','placeholder'=>'Report To Sale Id','pattern' => '\d*')) }}
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
			  				foreach ($provinces as $key => $province) {
								 $stp = '<li>';
								 $stp .= '<div><input type="checkbox" value="'.$province->code.'" class="provinceCheck"/> ';
								 $stp .= '<span pCode="'.$province->code.'" class="provinceClick">('.$province->code.') '.$province->name_en.'</span></div>';
								 $stp .= '<div class="province-'.$province->code.'"></div>';
								 $stp .= '</li>';
								 
								 echo $stp;
							}
			  				?>
			  			</ul>
			  			
			  		</div>
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