@section('title', 'New Dealer')
@section('content')
<?php
$baseUrl = URL::to('/');
$khanCode = '';
$communeCode = '';
$villageCode = '';
if (Input::old('khan__code')) {
	$khanCode = Input::old('khan__code');
}
if (Input::old('commune__code')) {
	$communeCode = Input::old('commune__code');
}
if (Input::old('village__code')) {
	$villageCode = Input::old('village__code');
}

?>
<script type="text/javascript">
	$(document).ready(function(){
		$baseUrl = '{{$baseUrl}}';
		$khanCode = '{{$khanCode}}';
		$communeCode = '{{$communeCode}}';
		$villageCode = '{{$villageCode}}';
		var seleteType = $("#dealer_type__id");
		var seleteProvince = $("#province");
		var seleteKhan = $("#khan__code");
		var seleteCommune = $("#commune__code");
		var seleteVillage = $("#village__code");
		var plsSelect = 'Please Select';
		
		seleteType.on("change",function(){
			setType($(this));
			
			if ($(this).val() == '4') {
				$("#parent_dealer_id").show();
			} else {
				$("#parent_dealer_id").hide();
			}
		});
		seleteProvince.on("change",function(){
			setProvince($(this));
		});
		seleteKhan.on("change",function(){
			setDistrict($(this));
		});
		seleteCommune.on("change",function(){
			setCommune($(this));
		});
		setType(seleteType);
		setProvince(seleteProvince);
		setDistrict(seleteKhan);
		loadCommuneByKhanCode($khanCode);
		loadVillageByCommuneCode($communeCode);
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
		function setDistrict(ob) {
			$val = ob.val();
			if ($val == '') {
				$val = 'xxxx';
			}
			$("#idDistrict").html($val.substring(2));
			loadCommuneByKhanCode($val);
		}
		function setCommune(ob) {
			$val = ob.val();
			loadVillageByCommuneCode($val);
		}
		function loadDistrictByProvinceCode($code) {
			seleteKhan.html('');
			seleteKhan.append('<option value="">'+plsSelect+'</option>');
			seleteCommune.html('');
			seleteCommune.append('<option value="">'+plsSelect+'</option>');
			seleteVillage.html('');
			seleteVillage.append('<option value="">'+plsSelect+'</option>');
						
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
		        	    console.log("$khanCode = "+$khanCode);
		        	    if ($khanCode != '') {
		        	    	
		        	    	if ($khanCode == key) {
		        	    		seleteKhan.append('<option value="'+key+'" selected="selected">('+key+') '+data[key]+'</option>');
							} else {
								seleteKhan.append('<option value="'+key+'">('+key+') '+data[key]+'</option>');
							}
		        	    } else {
		        	    	seleteKhan.append('<option value="'+key+'">('+key+') '+data[key]+'</option>');
		        	    }
		        	    
		        	    // iteration code
		        	});
		        }
		    });
		}
		
		function loadCommuneByKhanCode($code) {
			seleteCommune.html('');
			seleteCommune.append('<option value="">'+plsSelect+'</option>');
			seleteVillage.html('');
			seleteVillage.append('<option value="">'+plsSelect+'</option>');
						
			var url = $baseUrl+'/locations/commune-by-khan-code/'+$code;
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
		        	    console.log("$communeCode = "+$communeCode);
		        	    if ($communeCode != '') {
		        	    	
		        	    	if ($communeCode == key) {
		        	    		seleteCommune.append('<option value="'+key+'" selected="selected">('+key+') '+data[key]+'</option>');
							} else {
								seleteCommune.append('<option value="'+key+'">('+key+') '+data[key]+'</option>');
							}
		        	    } else {
		        	    	seleteCommune.append('<option value="'+key+'">('+key+') '+data[key]+'</option>');
		        	    }
		        	    
		        	    // iteration code
		        	});
		        }
		    });
		}
		
		function loadVillageByCommuneCode($code) {
			seleteVillage.html('');
			seleteVillage.append('<option value="">'+plsSelect+'</option>');
						
			var url = $baseUrl+'/locations/village-by-commune-code/'+$code;
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
		        	    console.log("$villageCode = "+$villageCode);
		        	    if ($villageCode != '') {
		        	    	
		        	    	if ($villageCode == key) {
		        	    		seleteVillage.append('<option value="'+key+'" selected="selected">('+key+') '+data[key]+'</option>');
							} else {
								seleteVillage.append('<option value="'+key+'">('+key+') '+data[key]+'</option>');
							}
		        	    } else {
		        	    	seleteVillage.append('<option value="'+key+'">('+key+') '+data[key]+'</option>');
		        	    }
		        	    
		        	    // iteration code
		        	});
		        }
		    });
		}
		
		if ($("#dealer_type__id").val() == '4') {
			$("#parent_dealer_id").show();
		} else {
			$("#parent_dealer_id").hide();
		}
		if ($khanCode != '') {
			
		}
	});
</script>
<h4>New Dealer</h4>
@include('layouts.partial.render-message')
<div class="row">
	<div class="col-md-12">
		{{ Form::open(array('route' => 'dealers.store')) }}
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('did', 'DID:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		<span id='idProvince'>00</span>
			  		<span id='idDistrict'>00</span>
			  		<span id='idType'>0</span>
			  		<span>xxx</span>
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('name', 'Name*:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('name', '', array('class' => 'form-control','placeholder'=>'Name','required'=>'required')) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('national_card_id', 'National ID:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('national_card_id', '', array('class' => 'form-control','placeholder'=>'National ID')) }}
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
			  		{{ Form::label('dealer_type__id', 'Dealer Type*:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		<?php
			  		foreach ($listDealerTypes as $key => $item) {
						$listDealerTypes[$key] = '('.$key.') ' . $item;
					}
			  		?>
			  		{{ Form::select('dealer_type__id', array('' => 'Please Select')+$listDealerTypes, null, ['id' => 'dealer_type__id', 'class' => 'form-control','required'=>'required']) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('street', 'Address:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('street', '', array('class' => 'form-control','placeholder'=>'Address')) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('province', 'Province/City *:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		<?php
			  		foreach ($listProvinces as $key => $item) {
						$listProvinces[$key] = '('.$key.') ' . $item;
					}
			  		?>
			  		{{ Form::select('province__code', array('' => 'Please Select')+$listProvinces, null, ['id' => 'province', 'class' => 'form-control','required'=>'required']) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('khan__code', 'District/Khan *:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::select('khan__code', array('' => 'Please Select'),null, array('id' => 'khan__code', 'class' => 'form-control','required'=>'required')) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('commune__code', 'Commune :') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('commune__code', '', array('class' => 'form-control','placeholder'=>'Commune')) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('village__code', 'Village :') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('village__code', '', array('class' => 'form-control','placeholder'=>'Village')) }}
			  	</div>
			</div>
			<div class="row" id="parent_dealer_id">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('parent_id', 'Parent Dealer Id:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('parent_id', '', array('class' => 'form-control','placeholder'=>'Parent Dealer Id')) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('reference_dealer_id', 'Reference Dealer ID:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('reference_dealer_id', '', array('class' => 'form-control','placeholder'=>'Reference Dealer ID')) }}
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