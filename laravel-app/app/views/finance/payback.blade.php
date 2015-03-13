@section('title', 'Dealer Pay Back')
@section('content')
<?php
$baseUrl = URL::to('/');
$dealerId = '';
$bankId = '';
$bankAccName = '';
$creditBalance = '0';
if (Input::old('dealer__id')) {
	$dealerId = Input::old('dealer__id');
}
if (Input::old('credit_balance')) {
	$creditBalance = Input::old('credit_balance');
}

?>
<script type="text/javascript">
	$(document).ready(function(){
		
		$baseUrl = '{{$baseUrl}}';
		$creditBalance = '{{$creditBalance}}';
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
		
		$dealerSelector = "#dealer__id";
		$walletType = "#wallet_type__id";
		$tcyCurrencyId = "#tcy_currency_id";
		
		$($dealerSelector).on("change",function(ob){
			$($tcyCurrencyId).append('<option value="">'+plsSelect+'</option>');
		});
		
		$($tcyCurrencyId).on("change",function(ob){
			$dealerId = $($dealerSelector).val();
			$walletTypeId = $($walletType).val();
			$currencyId = $($tcyCurrencyId).val();
			loadWalletByCondition($dealerId,$walletTypeId,$currencyId);
		});
		
		function loadWalletByCondition($dealerId,$walletTypeId,$currencyId) {
			$("#credit_balance").html("0");
		    $("#credit_balance_hidden").val("0");
			
			var url = $baseUrl+'/finances/dealer-wallet-by-condition/'+$dealerId+'/'+$walletTypeId+'/'+$currencyId;
			console.log(url);
	    	$.ajax({ 
		        type: 'GET', 
		        url: url, 
		        //data: { code: $code }, 
		        success: function (data) {
		        	if (data != null) {
		        		var value = data['balance_credit'];
		        		$("#credit_balance").html(value);
		        	    $("#credit_balance_hidden").val(value);
		        	}
		        }
		    });
		}
		//-----------------------------------------------
		
		$("#credit_balance").html($creditBalance);
		$("#credit_balance_hidden").val($creditBalance);
		
		//--------------------------------------------------
		
	});
	
</script>

<h4>Dealer Pay Back</h4>
@include('layouts.partial.render-message')
<div class="row">
	<div class="col-md-12">
		{{ Form::open(array('url' => 'finances/dealer-payback')) }}
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('dealer__id', 'Dealer ID*:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
		         	{{ Form::text('dealer__id', '', array('class' => 'form-control','id' => 'dealer__id' , 'list' =>'dealer_list' , 'placeholder'=>'Dealer ID','required'=>'required')) }}
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
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('credit_balance_', 'Credit Balance*:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::label('0','0',array('class' => 'form-control','id'=>'credit_balance')) }}
			  		{{ Form::hidden('credit_balance', '', array('class' => 'form-control','placeholder'=>'0','id'=>'credit_balance_hidden')) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('requested_value', 'Pay Back Request Amount*:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('requested_value', '', array('class' => 'form-control','placeholder'=>'Pay Back Amount','required'=>'required')) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('remark', 'Remark:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::textarea('remark',Input::old('remark'), array('class' => 'form-control','placeholder'=>'Remark','required'=>'required')) }}
			  	</div>
			</div>
			
			<div class="row">
				<div class="col-md-4 text-right"></div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::submit('Submit Pay Back',array('class' => 'btn btn-primary')) }}
			  	</div>
			</div>
			@include('layouts.partial.render-message-form')
		{{ Form::close() }}
	</div>
</div>
@stop