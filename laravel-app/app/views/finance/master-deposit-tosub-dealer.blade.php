@section('title', 'Master Deposit to Sub Dealer')
@section('content')
<?php
$baseUrl = URL::to('/');
$dealerId = '';

$dealerSubId = '';

if (Input::old('dealer__id')) {
	$dealerId = Input::old('dealer__id');
}
if (Input::old('deposit_sub_dealer_select')) {
	$dealerSubId = Input::old('deposit_sub_dealer_select');
}

?>
<script type="text/javascript">
	$(document).ready(function(){
		
		$baseUrl = '{{$baseUrl}}';
		$dealerId = '{{$dealerId}}';
		$dealerSubId = '{{$dealerSubId}}';
		
		$dealerSelector = "#master_withdraw";
		$dealerSubSelector = "#deposit_sub_dealer_select";
		$subDealerList = $("#sub_dealer_list");
		var plsSelect= "Please select";
		
		$($dealerSelector).on("change",function(ob){
			$dealerId = $(this).val();
			loadSubDealers($dealerId);
			//console.log("test = "+$dealerId);
		});
		
		function loadSubDealers($dealerId) {
			
			$subDealerList.html('');
			$subDealerList.append('<option value="">'+plsSelect+'</option>');
			
			var url = $baseUrl+'/finances/sub-dealer/'+$dealerId;
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
		        	    if ($dealerSubId != '' && $dealerSubId==key) {
		        	    	$subDealerList.append('<option value="'+key+'" selected="selected">'+key+' - '+data[key]+'</option>');
		        	    } else {
		        	    	$subDealerList.append('<option value="'+key+'">'+key+' - '+data[key]+'</option>');
		        	    }
		        	});
		        }
		    });
		}
		
		//-----------load for old value-----------------
		
		//-----------------------------------------------
	});
	
</script>

<h4>Master Deposit to Sub Dealer</h4>
@include('layouts.partial.render-message')
<div class="row">
	<div class="col-md-12">
		{{ Form::open(array('url' => 'finances/master-deposit-tosub-dealer')) }}
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('master_dealer__id', 'Master Dealer ID*:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
		         	{{ Form::text('master_dealer__id', '', array('class' => 'form-control','id' => 'deposit_dealer_select' , 'list' =>'dealer_list' , 'placeholder'=>'Master Dealer ID','required'=>'required')) }}
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
			  		{{ Form::label('sub_dealer__id', 'Sub Dealer ID*:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		<!--{{ Form::select('sub_dealer__id', array('' => 'Please Select'), '', array('class' => 'form-control','id' => 'deposit_sub_dealer_select','required'=>'required')) }}-->
			  		
			  		
			  		{{ Form::text('sub_dealer__id', '', array('class' => 'form-control','id' => 'deposit_sub_dealer_select' , 'list' =>'sub_dealer_list' , 'placeholder'=>'Sub Dealer ID','required'=>'required')) }}
		         	<datalist id="sub_dealer_list">
					</datalist>
					
		         	<!--{{ Form::text('sub_dealer__id', '', array('class' => 'form-control','id' => 'deposit_sub_dealer_select' , 'list' =>'sub_dealer_list' , 'placeholder'=>'Sub Dealer ID','required'=>'required')) }}
		         	<datalist id="sub_dealer_list">
						@foreach ($sub_dealers as $sub_dealer)
						<option value="{{ $sub_dealer->id }}">
					 	{{ $sub_dealer->id }} - {{ $sub_dealer->name }}
						</option>
					 	@endforeach
					</datalist> -->
					
			  	</div>
			</div>
			
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('wallet_type__id', 'Wallet Type*:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
					{{ Form::text('wallet_type__id', 'game', array('class' => 'form-control','required'=>'required','readonly' => 'readonly')) }}
			  	</div>
			</div>
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
			  		{{ Form::textarea('remark',Input::old('remark'), array('class' => 'form-control','placeholder'=>'Remark','required'=>'required')) }}
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