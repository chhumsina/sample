@section('title', 'Promotion')
@section('content')
<?php
$baseUrl = URL::to('/');
$dealerId = '';
$bankId = '';
$bankAccName = '';
if (Input::old('dealer__id')) {
	$dealerId = Input::old('dealer__id');
}
if (Input::old('atr1_value')) {
	$bankId = Input::old('atr1_value');
}
if (Input::old('atr2_value')) {
	$bankAccName = Input::old('atr2_value');
}
?>
<script type="text/javascript">
	$(document).ready(function(){
		
		$baseUrl = '{{$baseUrl}}';
		$dealerId = '{{$dealerId}}';
		$bankId = '{{$bankId}}';
		$bankAccName = '{{$bankAccName}}';
		var plsSelect= "Please select";

		$currencyBackup = $("#tcy_currency_id").html();
		$("#wallet_type__id").on("change",function(){
			if($("#wallet_type__id").val() == 'game') {
		    	$obSelect = "<option value=''>Please Select</option>";
		    	$obSelect += "<option value='KHR'>KHR</option>";
		    	$("#tcy_currency_id").html($obSelect);
		    } else {
		    	$("#tcy_currency_id").html($currencyBackup);
		    }
		});
		
		$dealerSelector = "#deposit_dealer_select";
		$banksSelector = "#banks";
		
		$($dealerSelector).on("change",function(ob){
			$dealerId = $(this).val();
			$bankId = $($banksSelector).val();
			//loadBnakAccNames($dealerId,$bankId);
			//console.log("test = "+$dealerId);
			
		});
		
		$($banksSelector).on("change",function(ob){
			$dealerId = $($dealerSelector).val();
			$bankId = $(this).val();
			loadBnakAccNames($dealerId,$bankId);
			//console.log("test = "+$dealerId);
		});
		
		$bankAccNamesSelector = "#bank_acc_names";
		$bankAccNames = $($bankAccNamesSelector);
		$bankAccNames.on("change",function(ob){
			$bankAccNumber = $(this).val();
			$("#account_number").val($bankAccNumber);
			$("#account_number_hidden").val($bankAccNumber);
			$("#bank_acc_name_hidden").val($bankAccNames.find('option:selected').text());
			//console.log("test = "+$dealerId);
		});
		
		function loadBnakAccNames($dealerId,$bankId) {
			$bankAccNames.html('');
			$bankAccNames.append('<option value="">'+plsSelect+'</option>');
			$("#account_number").val("");
			$("#account_number_hidden").val("");
			//alert($baseUrl);
			var url = $baseUrl+'/finances/dealer-bank-account-names/'+$dealerId+'/'+$bankId;
			console.log(url);
	    	$.ajax({ 
		        type: 'GET', 
		        url: url, 
		        //data: { code: $code }, 
		        success: function (data) {
		        	console.log(data);
		        	Object.keys(data).forEach(function (key) { 
		        	    var value = data[key];
		        	    console.log("key ="+key);
		        	    console.log("Value = "+value);
		        	    if ($bankAccName != '' && $bankAccName==key) {
		        	    	$bankAccNames.append('<option value="'+key+'" selected="selected">'+data[key]+'</option>');
		        	    	$("#account_number").val(key);
							$("#account_number_hidden").val(key);
		        	    } else {
		        	    	$bankAccNames.append('<option value="'+key+'">'+data[key]+'</option>');
		        	    }
		        	    $("#bank_acc_name_hidden").val($bankAccNames.find('option:selected').text());
		        	});
		        }
		    });
		}
		
		//-----------load for old value-----------------
		if ($bankAccName != '') {
			loadBnakAccNames($dealerId,$bankId);
		}
		//-----------------------------------------------
	});
	
</script>

<h4>Deposit For Promotion</h4>
@include('layouts.partial.render-message')
<div class="row">
	<div class="col-md-12">
		{{ Form::open(array('url' => 'finances/promotion')) }}
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('dealer__id', 'Dealer ID*:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
		         	{{ Form::text('dealer__id', '', array('class' => 'form-control','id' => 'deposit_dealer_select' , 'list' =>'dealer_list' , 'placeholder'=>'Dealer ID','required'=>'required')) }}
		         	<datalist id="dealer_list">
						@foreach ($dealers as $dealer)
						<option value="{{ $dealer->id }}">
					 	{{ $dealer->id }} - {{$dealer->name}}
						</option>
					 	@endforeach
					</datalist> 
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('wallet_type__id', 'Wallet Type*:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
					{{ Form::select('wallet_type__id', array('' => 'Please Select')+$wallet_types, '', array('class' => 'form-control','id' => 'wallet_type__id','required'=>'required')) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('tcy_currency_id', 'Currency*:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
					{{ Form::select('tcy_currency_id', array('' => 'Please Select')+$currencies, '', array('class' => 'form-control','id' => 'tcy_currency_id','required'=>'required')) }}
			  	</div>
			</div>
			<!--
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('atr1_value', 'Bank Name*:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::select('atr1_value', array('' => 'Please Select')+$banks, '', array('id'=>'banks','class' => 'form-control','required'=>'required')) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('atr2_value', 'Account Name*:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::select('atr2_value', array('' => 'Please Select'), '', array('id'=>'bank_acc_names','class' => 'form-control')) }}
			  		{{ Form::hidden('bank_acc_name_hidden', '', array('id'=>'bank_acc_name_hidden')) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('atr3_value', 'Account Number*:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('atr3_value', '', array('id'=>'account_number','class' => 'form-control','placeholder'=>'Account Number','disabled'=>'disabled')) }}
			  		{{ Form::hidden('atr3_value', '', array('id'=>'account_number_hidden','class' => 'form-control','placeholder'=>'Account Number')) }}
			  	</div>
			</div>
			-->
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('requested_value', 'Deposit Amount*:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('requested_value', '', array('class' => 'form-control','placeholder'=>'Deposit Amount','required'=>'required')) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('remark', 'Remark:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::textarea('remark',Input::old('remark'), array('class' => 'form-control','placeholder'=>'Remark')) }}
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