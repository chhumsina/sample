@section('title', 'New Terminal')
@section('content')
<?php
$baseUrl = URL::to('/');
?>
<script type="text/javascript">
	$(document).ready(function(){
		$baseUrl = '{{$baseUrl}}';
		var seleteType = $("#dealer_type__id");
		var seleteProvince = $("#province");
		var seleteKhan = $("#khan");
		var plsSelect = 'Please Select';
		
		seleteType.on("change",function(){
			setType($(this));
		});
		seleteProvince.on("change",function(){
			seleteKhan.html('');
			seleteKhan.append('<option value="">'+plsSelect+'</option>');
			setProvince($(this));
		});
		seleteKhan.on("change",function(){
			setDistrict($(this));
			
		});
		setType(seleteType);
		setProvince(seleteProvince);
		setDistrict(seleteKhan);
		function setType(ob) {
			$val = ob.val();
			if ($val == '') {
				$val = 'x';
			}
			$("#idType").html($val);
		}
		function setProvince(ob) {
			$val = ob.val();
			if ($val == '') {
				$val = 'xx';
			} else {
				loadDistrictByProvinceCode($val);
			}
			$("#idProvince").html($val);
		}
		function loadDistrictByProvinceCode($code) {
			//seleteKhan.append(plsSelect);
						
			var url = $baseUrl+'/locations/khan-by-province-code/'+$code;
			console.log(url);
	    	$.ajax({ 
		        type: 'GET', 
		        url: url, 
		        data: { code: $code }, 
		        success: function (data) {
		        	console.log(data);
		        	Object.keys(data).forEach(function (key) { 
		        	    var value = data[key];
		        	    console.log("key ="+key);
		        	    seleteKhan.append('<option value="'+key+'">('+key+') '+data[key]+'</option>');
		        	    // iteration code
		        	});
		        }
		    });
		}
		function setDistrict(ob) {
			$val = ob.val();
			if ($val == '') {
				$val = 'xxxx';
			}
			$("#idDistrict").html($val.substring(2));
		}
	});
</script>
<h4>New Terminal</h4>
<div class="row">
	<div class="col-md-12">
		{{ Form::open(array('route' => 'terminals.store')) }}
			
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('serial', 'Terminal Serial*:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('serial', '', array('class' => 'form-control','placeholder'=>'Serial','required'=>'required')) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('imsi', 'IMSI*:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('imsi', '', array('class' => 'form-control','placeholder'=>'IMSI','required'=>'required')) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('ecard_id', 'ECard*:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('ecard_id', '', array('class' => 'form-control','placeholder'=>'ECard','required'=>'required')) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('status', 'Status:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
		  		<?php 
		  			echo Form::select('status',array('active' => 'active', 'inactive' => 'inactive'),'active',array('class' => 'form-control','required'=>'required'));
		  		?>
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('stock__location', 'Stock Locations:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
		  		{{ Form::select('stock__location', array('' => 'Please Select')+$stockLocations, null, ['id' => 'stockLocations', 'class' => 'form-control']) }}
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