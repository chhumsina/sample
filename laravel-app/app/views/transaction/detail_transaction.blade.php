@section('title', 'Transaction Detial')
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
		
		$dealerSelector = "#dealer_id";
		$banksSelector = "#banks";
		
		/*$($dealerSelector).on("change",function(ob){
			$dealerId = $(this).val();
			$bankId = $($banksSelector).val();
			loadBnakAccNames($dealerId,$bankId);
			//console.log("test = "+$dealerId);
			
		});*/
		
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
		//loadBnakAccNames($dealerId,$bankId);
		//-----------------------------------------------
		
		//-----------load for old value-----------------
		if ($bankAccName != '') {
			loadBnakAccNames($dealerId,$bankId);
		}
		//-----------------------------------------------
	});
	
</script>

<h4>Transaction Detial</h4>
@include('layouts.partial.render-message')
<div class="row">
	<div class="col-md-12">
		{{ Form::open(array('url' => 'transactions/do-action-from-detail')) }}
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('transaction_id', 'Txn ID :') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{$transaction->transaction_id}}
			  		{{ Form::hidden('transaction_id', $transaction->transaction_id)}}
			  	</div>
			</div>
			<?php if ($transaction->tsn_os != null || $transaction->tsn != null) { ?>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('tsn_os', 'TSN :') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		<?php
			  			if ($transaction->service_type__id == '639' ||
								$transaction->service_type__id == '639luk' ||
								$transaction->service_type__id == '639_pro' ||
								$transaction->service_type__id == 'pick5' ||
								$transaction->service_type__id == 'pick5l' ||
								$transaction->service_type__id == 'pick5_pro') {
			  				echo $transaction->tsn;
			  			} else {
			  				echo $transaction->tsn_os;
			  			}
			  		?>
			  	</div>
			</div>
			<?php } ?>
			
			<?php if ($transaction->request_gateway__id == 'web') { ?>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('requested_value', 'Perform By :') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ $transaction->createdBy }}
			  	</div>
			</div>
			
			<?php } ?>
			
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('dealer__id', 'Dealer :') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		({{$transaction->dealer__id}}) {{$transaction->name}}
			  		{{ Form::hidden('dealer__id', $transaction->dealer__id,array('id'=>'dealer_id'))}}
			  	</div>
			</div>
			
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('service_type__id', 'Service Type :') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		<?php
			  			echo '<span class="info">'.$transaction->service_type__id.'</span>';
			  		?>
			  	</div>
			</div>
			<?php
	  			if ($transaction->service_type__id == '639' ||
						$transaction->service_type__id == '639luk' ||
						$transaction->service_type__id == '639_pro' ||
						$transaction->service_type__id == 'pick5' ||
						$transaction->service_type__id == 'pick5l' ||
						$transaction->service_type__id == 'pick5_pro') {?>
							
						<div class="row">
						  	<div class="col-md-4 text-right">
						  		{{ Form::label('atr2_value', 'Game Type :') }}
						  	</div>
						  	<div class="col-md-4 text-left">
						  		<?php
						  			echo '<span class="info">'.$transaction->atr2_value.'</span>';
						  		?>
						  	</div>
						</div>	
	  			<?php }
	  		?>
			<?php
			if ($transaction->service_type__id == 'payout') {
				if ($transaction->atr3_value != "" || $transaction->atr3_value != null) {?>
					<div class="row">
					  	<div class="col-md-4 text-right">
					  		{{ Form::label('dealer__id', 'Sub Dealer ID (do payout):') }}
					  	</div>
					  	<div class="col-md-4 text-left">
					  		({{$transaction->atr3_value}})
					  	</div>
					</div>
				<?php }
			} ?>
			<?php if ($transaction->service_type__id == 'transfer_wallet') { ?>
				<div class="row">
				  	<div class="col-md-4 text-right">
				  		{{ Form::label('atr1_value', 'From Wallet Type :') }}
				  	</div>
				  	<div class="col-md-4 text-left">
				  		<?php
				  		echo '<span class="blue">'.$transaction->atr1_value.'</span>';
				  		?>
				  	</div>
				</div>
				<div class="row">
				  	<div class="col-md-4 text-right">
				  		{{ Form::label('atr2_value', 'To Wallet Type :') }}
				  	</div>
				  	<div class="col-md-4 text-left">
				  		<?php
				  		echo '<span class="blue">'.$transaction->atr2_value.'</span>';
				  		?>
				  	</div>
				</div>
			<?php }?>
			<?php 
			if ($transaction->service_type__id=='withdraw_game_req' || $transaction->service_type__id=='withdraw_game' || $transaction->service_type__id=='withdraw'
					 || $transaction->service_type__id=='deposit_game' || $transaction->service_type__id=='deposit') {?>
				<div class="row">
				  	<div class="col-md-4 text-right">
				  		{{ Form::label('atr4_value', 'Wallet Type :') }}
				  	</div>
				  	<div class="col-md-4 text-left">
				  		<?php
				  		echo '<span class="info">'.$transaction->atr4_value.'</span>';
				  		?>
				  	</div>
				</div>
			<?php } ?>
			
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('tcy_currency_id', 'Txn Currency :') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ $transaction->tcy_currency_id }}
			  	</div>
			</div>
			
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('requested_value', 'Request Amount :') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		<?php
			  			echo '<span class="info">'.number_format($transaction->requested_value,2).' '.$transaction->tcy_currency_id.'</span>';
			  		?>
			  	</div>
			</div>
			
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('requested_value', 'Request Geteway :') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ $transaction->request_gateway__id }}
			  	</div>
			</div>
			<?php if ($transaction->request_gateway__id == 'terminal') { ?>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('terminal__serial', 'Terminal Serial :') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ $transaction->terminal__serial }}
			  	</div>
			</div>
			<?php } ?>
			
			<?php if ($transaction->service_type__id == 'buy_currency') { ?>
				<div class="row">
				  	<div class="col-md-4 text-right">
				  		{{ Form::label('atr1_value', 'From Wallet Currency :') }}
				  	</div>
				  	<div class="col-md-4 text-left">
				  		<?php
				  		echo '<span class="blue">'.$transaction->atr1_value.'</span>';
				  		?>
				  	</div>
				</div>
				<div class="row">
				  	<div class="col-md-4 text-right">
				  		{{ Form::label('atr2_value', 'To Wallet Currency :') }}
				  	</div>
				  	<div class="col-md-4 text-left">
				  		<?php
				  		echo '<span class="blue">'.$transaction->atr2_value.'</span>';
				  		?>
				  	</div>
				</div>
			<?php } else if ($transaction->service_type__id == 'weiluy') { ?>
				<div class="row">
				  	<div class="col-md-4 text-right">
				  		{{ Form::label('atr2_value', 'Sender Phone :') }}
				  	</div>
				  	<div class="col-md-4 text-left">
				  		{{ $transaction->atr2_value }}
				  	</div>
				</div>
				<div class="row">
				  	<div class="col-md-4 text-right">
				  		{{ Form::label('atr3_value', 'Reciever Phone :') }}
				  	</div>
				  	<div class="col-md-4 text-left">
				  		{{ $transaction->atr3_value }}
				  	</div>
				</div>
				<div class="row">
				  	<div class="col-md-4 text-right">
				  		{{ Form::label('atr1_value', 'Cash Out Yet :') }}
				  	</div>
				  	<div class="col-md-4 text-left">
				  		<?php
				  		if ($transaction->atr1_value == 'yes') {
      						echo '<span class="red">'.$transaction->atr1_value.'</span>';
      					} else if ($transaction->atr1_value == 'no' || $transaction->atr1_value == 'cancel') {
      						echo '<span class="blue">'.$transaction->atr1_value.'</span>';
      					}
				  		?>
				  	</div>
				</div>
				
			<?php } else if ($transaction->service_type__id == 'cash_out') { ?>
				<div class="row">
				  	<div class="col-md-4 text-right">
				  		{{ Form::label('atr2_value', 'Sender Phone :') }}
				  	</div>
				  	<div class="col-md-4 text-left">
				  		{{ $transaction->atr2_value }}
				  	</div>
				</div>
				<div class="row">
				  	<div class="col-md-4 text-right">
				  		{{ Form::label('atr3_value', 'Reciever Phone :') }}
				  	</div>
				  	<div class="col-md-4 text-left">
				  		{{ $transaction->atr3_value }}
				  	</div>
				</div>
				<div class="row">
				  	<div class="col-md-4 text-right">
				  		{{ Form::label('ref_transaction_id', 'Cash Out To Txn ID :') }}
				  	</div>
				  	<div class="col-md-4 text-left">
				  		{{ $transaction->ref_transaction_id }}
				  	</div>
				</div>
			<?php } else if ($transaction->service_type__id == 'ptu') { ?>
				<div class="row">
				  	<div class="col-md-4 text-right">
				  		{{ Form::label('atr3_value', 'Operator :') }}
				  	</div>
				  	<div class="col-md-4 text-left">
				  		<?php
				  		echo '<span class="blue">'.$transaction->atr3_value.'</span>';
				  		?>
				  	</div>
				</div>
			<?php } else if ($transaction->service_type__id == 'cancel_weiluy' ||
							 $transaction->service_type__id == 'cancel_game' ||
							 $transaction->service_type__id == 'cancel_deposit_game' ||
							 $transaction->service_type__id == 'cancel_deposit'
							 ) { ?>
				<div class="row">
				  	<div class="col-md-4 text-right">
				  		{{ Form::label('ref_transaction_id', 'Cancel To Txn ID :') }}
				  	</div>
				  	<div class="col-md-4 text-left">
				  		{{ $transaction->ref_transaction_id }}
				  	</div>
				</div>
				<div class="row">
				  	<div class="col-md-4 text-right">
				  		{{ Form::label('atr1_value', 'Cancel To TSN :') }}
				  	</div>
				  	<div class="col-md-4 text-left">
				  		{{ $transaction->atr1_value }}
				  	</div>
				</div>
				<?php if ($transaction->service_type__id == 'cancel_game'){ ?>
						<div class="row">
						  	<div class="col-md-4 text-right">
						  		{{ Form::label('atr2_value', 'Game Type :') }}
						  	</div>
						  	<div class="col-md-4 text-left">
						  		{{ $transaction->atr2_value }}
						  	</div>
						</div>
						<div class="row">
						  	<div class="col-md-4 text-right">
						  		{{ Form::label('atr3_value', 'Type Of Issue :') }}
						  	</div>
						  	<div class="col-md-4 text-left">
						  		{{ $transaction->atr3_value }}
						  	</div>
						</div>
				<?php }?>
			<?php } else if ($transaction->service_type__id == 'payout') { ?>
				<div class="row">
				  	<div class="col-md-4 text-right">
				  		{{ Form::label('game_type', 'Game Type :') }}
				  	</div>
				  	<div class="col-md-4 text-left">
				  		{{ $transaction->atr2_value }}
				  	</div>
				</div>
				<div class="row">
				  	<div class="col-md-4 text-right">
				  		{{ Form::label('game_txn_id', 'Game Txn Id :') }}
				  	</div>
				  	<div class="col-md-4 text-left">
				  		{{ $transaction->ref_transaction_id }}
				  	</div>
				</div>
				<div class="row">
				  	<div class="col-md-4 text-right">
				  		{{ Form::label('game_tsn', 'Game TSN :') }}
				  	</div>
				  	<div class="col-md-4 text-left">
				  		{{ $transaction->atr1_value }}
				  	</div>
				</div>
			<?php } else if ($transaction->service_type__id == 'deposit' || $transaction->service_type__id == 'deposit_game'
							|| $transaction->service_type__id == 'withdraw' || $transaction->service_type__id == 'withdraw_game' || $transaction->service_type__id =='withdraw_game_req'
							|| $transaction->service_type__id == 'refund' || $transaction->service_type__id == 'refund_game') { 
					if ($transaction->status == 'TS') {			
					?>
				<div class="row">
				  	<div class="col-md-4 text-right">
				  		{{ Form::label('bank', 'Bank :') }}
				  	</div>
				  	<div class="col-md-4 text-left">
				  		<?php
				  			if ($transaction->atr1_value != '') {
				  				try {
				  					$bank = DB::table('bank as b')->where('b.bid','=',$transaction->atr1_value)->first();
									echo $bank->bank_name;
				  				} catch (Exception $e) {
				  					
				  				}
				  			}
				  		?>
				  	</div>
				</div>
				<div class="row">
				  	<div class="col-md-4 text-right">
				  		{{ Form::label('bankAccName', 'Bank Account Name :') }}
				  	</div>
				  	<div class="col-md-4 text-left">
				  		{{ $transaction->atr2_value }}
				  	</div>
				</div>
				<div class="row">
				  	<div class="col-md-4 text-right">
				  		{{ Form::label('atr3_value', 'Bank Account Number :') }}
				  	</div>
				  	<div class="col-md-4 text-left">
				  		{{ $transaction->atr3_value }}
				  	</div>
				</div>
			<?php }} else if ($transaction->service_type__id == 'payback_game') { ?>
				<div class="row">
				  	<div class="col-md-4 text-right">
				  		{{ Form::label('ref_transaction_id', 'Payback while doing Txn ID :') }}
				  	</div>
				  	<div class="col-md-4 text-left">
				  		{{ $transaction->ref_transaction_id }}
				  	</div>
				</div>
			<?php } ?>
			<?php if ($transaction->request_gateway__id == 'web') { ?>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('remark', 'Remark :') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::textarea('', $transaction->remark, array('class' => 'form-control','size' => '30x5','required'=>'required','disabled'=>'disabled')) }}
			  	</div>
			</div>
			
			<!--<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('datetime', 'Created At :') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ $transaction->datetime }}
			  	</div>
			</div>-->
			<?php } ?>
			
			
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('status', 'Transaction Status :') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		<?php
      					if ($transaction->status == 'TI' || $transaction->status == 'A1' || $transaction->status == 'A2' || $transaction->status == 'A3') {
      						echo '<span class="blue">'.$transaction->statusName.'</span>';
      					} else if ($transaction->status == 'TS') {
      						echo '<span class="green">'.$transaction->statusName.'</span>';
      					} else if ($transaction->status == 'TR' || $transaction->status == 'TC') {
      						echo '<span class="red">'.$transaction->statusName.'</span>';
      					} else {
      						echo '<span>'.$transaction->statusName.'</span>';
      					}
      				?>
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('datetime', 'Transaction Date :') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{$transaction->datetime}}
			  	</div>
			</div>
			
			<?php if ($transaction->service_type__id == 'cash_out' || $transaction->service_type__id == 'ptu' || $transaction->service_type__id == 'weiluy'
					|| $transaction->service_type__id == 'deposit_game' || $transaction->service_type__id == 'deposit'
					|| $transaction->service_type__id == 'withdraw_game' || $transaction->service_type__id == 'withdraw'
					|| $transaction->service_type__id == 'payback_game' || $transaction->service_type__id == 'payout'
					|| $transaction->service_type__id == 'buy_currency' || $transaction->service_type__id == 'transfer_wallet') { ?>
				<br/>
				<div class="row">
				  	<div class="col-md-4 text-right">
				  		{{ Form::label('channel_comm', 'Channel Commission :') }}
				  	</div>
				  	<div class="col-md-4 text-left">
				  		<?php
				  			echo '<span class="info">'.number_format($transaction->channel_comm,2).' '.$transaction->tcy_currency_id.'</span>';
				  		?>
				  	</div>
				</div>
				<div class="row">
				  	<div class="col-md-4 text-right">
				  		{{ Form::label('channel_sc', 'Channel Charge :') }}
				  	</div>
				  	<div class="col-md-4 text-left">
				  		{{ number_format($transaction->channel_sc,2) }} {{ $transaction->tcy_currency_id }}
				  	</div>
				</div>
				<?php
				if ($transaction->service_type__id=='weiluy' || $transaction->service_type__id=='ptu'
					|| $transaction->service_type__id=='withdraw_game'
					|| $transaction->service_type__id=='withdraw') {?>
					<div class="row">
					  	<div class="col-md-4 text-right">
					  		{{ Form::label('channel_sc', 'Channel Debit Amount :') }}
					  	</div>
					  	<div class="col-md-4 text-left">
					  		<?php
					  		$channelDebitAmount = $transaction->transfer_value;//$transaction->customer_sc + $transaction->requested_value - $transaction->channel_comm;
					  		?>
					  		<?php
					  			echo '<span class="info">'.number_format($channelDebitAmount,2).' '.$transaction->tcy_currency_id.'</span>';
					  		?>
					  	</div>
					</div>
				<?php } else if ($transaction->service_type__id=='cash_out'
								|| $transaction->service_type__id=='deposit_game'
								|| $transaction->service_type__id=='deposit'){ ?>
					<div class="row">
					  	<div class="col-md-4 text-right">
					  		{{ Form::label('channel_sc', 'Channel Credit Amount :') }}
					  	</div>
					  	<div class="col-md-4 text-left">
					  		<?php
					  		$channelCreditAmount = $transaction->transfer_value;//$transaction->customer_sc + $transaction->requested_value + $transaction->channel_comm;
					  		?>
					  		<?php
					  			echo '<span class="info">'.number_format($channelCreditAmount,2).' '.$transaction->tcy_currency_id.'</span>';
					  		?>
					  	</div>
					</div>
				<?php } ?>
				
				<div class="row">
				  	<div class="col-md-4 text-right">
				  		{{ Form::label('customer_comm', 'Customer Commision :') }}
				  	</div>
				  	<div class="col-md-4 text-left">
				  		{{ number_format($transaction->customer_comm,2) }} {{ $transaction->tcy_currency_id }}
				  	</div>
				</div>
				<div class="row">
				  	<div class="col-md-4 text-right">
				  		{{ Form::label('customer_sc', 'Customer Charge :') }}
				  	</div>
				  	<div class="col-md-4 text-left">
				  		{{ number_format($transaction->customer_sc,2) }} {{ $transaction->tcy_currency_id }}
				  	</div>
				</div>
				<?php
				if ($transaction->service_type__id=='weiluy' || $transaction->service_type__id=='ptu') {?>
					<div class="row">
					  	<div class="col-md-4 text-right">
					  		{{ Form::label('customer_pay', 'Customer Pay :') }}
					  	</div>
					  	<div class="col-md-4 text-left">
					  		<?php
					  		$customerPay = $transaction->customer_sc + $transaction->requested_value;
					  		?>
					  		<?php
					  			echo '<span class="info">'.number_format($customerPay,2).' '.$transaction->tcy_currency_id.'</span>';
					  		?>
					  	</div>
					</div>
				<?php } else if ($transaction->service_type__id=='cash_out'){ ?>
					<div class="row">
					  	<div class="col-md-4 text-right">
					  		{{ Form::label('customer_pay', 'Customer Get :') }}
					  	</div>
					  	<div class="col-md-4 text-left">
					  		<?php
					  		$customerGet = $transaction->requested_value - $transaction->customer_sc;
					  		?>
					  		<?php
					  			echo '<span class="info">'.number_format($customerGet,2).' '.$transaction->tcy_currency_id.'</span>';
					  		?>
					  	</div>
					</div>
				<?php } ?>
				
				<div class="row">
				  	<div class="col-md-4 text-right">
				  		{{ Form::label('operator_comm', 'Operator Commision :') }}
				  	</div>
				  	<div class="col-md-4 text-left">
				  		{{ number_format($transaction->operator_comm,2) }} {{ $transaction->tcy_currency_id }}
				  	</div>
				</div>
				<div class="row">
				  	<div class="col-md-4 text-right">
				  		{{ Form::label('total_service_charge', 'Total Service Charge :') }}
				  	</div>
				  	<div class="col-md-4 text-left">
				  		{{ number_format($transaction->total_service_charge,2) }} {{ $transaction->tcy_currency_id }}
				  	</div>
				</div>
			<?php } else if ($transaction->service_type__id=='withdraw_game' || $transaction->service_type__id=='withdraw') {?>
				<br/>
				<div class="row">
				  	<div class="col-md-4 text-right">
				  		{{ Form::label('channel_comm', 'Channel Commission :') }}
				  	</div>
				  	<div class="col-md-4 text-left">
				  		{{ number_format($transaction->channel_comm,2) }} {{ $transaction->tcy_currency_id }}
				  	</div>
				</div>
			<?php } ?>
			<br/>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('operator_comm', 'Transfer Amount :') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		<?php
			  		echo '<span class="info">'.number_format($transaction->transfer_value,2).' '.$transaction->tcy_currency_id.'</span>';
			  		?>
			  	</div>
			</div>
			
			<?php if ($transaction->service_type__id=='withdraw_game' || $transaction->service_type__id=='withdraw') {?>
				<br/>
				<div class="row">
				  	<div class="col-md-4 text-right">
				  		{{ Form::label('atr6_value', 'Real Money Withdraw :') }}
				  	</div>
				  	<div class="col-md-4 text-left">
				  		<?php
				  		echo '<span class="info">'.number_format($transaction->atr6_value,2).' '.$transaction->tcy_currency_id.'</span>';
				  		?>
				  	</div>
				</div>
			<?php } ?>
			
			<?php if ($transaction->status == "TC") { ?>
				<br/>
				<div class="row">
				  	<div class="col-md-4 text-right">
				  		{{ Form::label('cancel_by', 'Cancel By :') }}
				  	</div>
				  	<div class="col-md-4 text-left">
				  		{{$transaction->updatedBy}}
				  	</div>
				</div>
				<div class="row">
				  	<div class="col-md-4 text-right">
				  		{{ Form::label('cancel_by', 'Cancel By Txn Id:') }}
				  	</div>
				  	<div class="col-md-4 text-left">
				  		{{$transaction->ref_transaction_id}}
				  	</div>
				</div>
				<div class="row">
				  	<div class="col-md-4 text-right">
				  		{{ Form::label('cancel_by', 'Cancel At:') }}
				  	</div>
				  	<div class="col-md-4 text-left">
				  		{{$transaction->updated_at}}
				  	</div>
				</div>
			<?php } ?>
			
			<?php if ($transaction->service_type__id == 'withdraw' || $transaction->service_type__id == 'withdraw_game' || $transaction->service_type__id == 'withdraw_game_req'
				|| $transaction->service_type__id == 'deposit' || $transaction->service_type__id == 'deposit_game') { 
				$approvalLists = DB::table('txn_transaction_approval as ta')
									->select('ta.*','ta.created_at as actionAt','sr.name as actionBy','ss.name as statusName')
									->join('sys_status as ss','ss.status_id','=','ta.approval_status')
									->leftJoin('staff AS sr','sr.id','=','ta.staff__id')
									->where('ta.transaction_id',$transaction->transaction_id)
									->orderBy('ta.created_at','desc')
									->paginate(100);
			?>
			<h5>People Do Action:</h5>
			<table class="table table-striped table-bordered">
		        <thead>
		            <tr>
		                <th>No</th>
				        <th>Name</th>
				        <th>Action</th>
				        <th>Action Gateway</th>
				        <th>Status</th>
				        <th>Action At</th>
				        <th>Remark</th>
		            </tr>
		        </thead>
		        	@if ($approvalLists->count())
					        	<?php 
					        	$i = 0 ?>
					            @foreach ($approvalLists as $approvalList)
					            	<?php $i += 1;?>
									<tr>
					                    <td>{{ $i }}</td>
					          			<td>{{ $approvalList->actionBy }}</td>
					          			<td>{{ $approvalList->action }}</td>
					          			<td>{{ $approvalList->action_gateway }}</td>
					          			<td>
					          				<?php
						      					if ($approvalList->approval_status == 'TI' || $approvalList->approval_status == 'A1' || $approvalList->approval_status == 'A2' || $approvalList->approval_status == 'A3') {
						      						echo '<span class="blue">'.$approvalList->statusName.'</span>';
						      					} else if ($approvalList->approval_status == 'TS') {
						      						echo '<span class="green">'.$approvalList->statusName.'</span>';
						      					} else if ($approvalList->approval_status == 'TR') {
						      						echo '<span class="red">'.$approvalList->statusName.'</span>';
						      					} else {
						      						echo '<span>'.$approvalList->statusName.'</span>';
						      					}
						      				?>	
					          			</td>
					          			<td>{{ $approvalList->actionAt }}</td>
					          			<td>{{ $approvalList->remark }}</td>
					          		</tr>
					          	@endforeach
						<?php echo $approvalLists->links();?>
					@else
					@endif
		        </tbody>
		    </table>
		    <?php } ?>
				
			<h5>Transaction Conversation Payment:</h5>
			<table class="table table-striped table-bordered">
		        <thead>
		            <tr>
		                <th>No</th>
				        <th>User Direction</th>
				        <th>DID</th>
				        <th>Name</th>
				        <th>Wallet ID</th>
				        <th>Wallet Name</th>
				        <th>Transfer Amount</th>
				        <th>Prev balance</th>
				        <th>Post balance</th>
		            </tr>
		        </thead>
		        	@if ($transactionItems->count())
					        	<?php 
					        	$i = 0 ?>
					            @foreach ($transactionItems as $transactionItem)
					            	<?php $i += 1;?>
									<tr>
					                    <td>{{ $i }}</td>
					          			<td>
					          				<?php
					          				if ($transactionItem->user_direction == 'payer') {
					          					echo '<span class="red">'.$transactionItem->user_direction.'</span>';
					          				} else if ($transactionItem->user_direction == 'payee') {
					          					echo '<span class="green">'.$transactionItem->user_direction.'</span>';
					          				}
					          				?>
					          				
					          			</td>
					          			<td>{{ $transactionItem->dealer__id }}</td>
					          			<td>{{ $transactionItem->d_name }}</td>
					          			<td>{{ $transactionItem->wallet_id }}</td>
					          			<td>{{ $transactionItem->wallet_nickname }}</td>
					          			<?php
					          			$currency = $transaction->tcy_currency_id;
										if ($transaction->service_type__id == 'buy_currency') {
											$currency = $transactionItem->atr1_value;
										}
					          			?>
					          			<td align="right">
					          				{{ number_format($transactionItem->transfer_value,2) }} {{ $currency }}	
					          			</td>
					          			<td align="right">
					          				{{ number_format($transactionItem->prev_balance,2) }} {{ $currency }}	
					          			</td>
					          			<td align="right">
					          				{{ number_format($transactionItem->post_balance,2) }} {{ $currency }}	
					          			</td>					          			
					          		</tr>
					          	@endforeach
						<?php echo $transactionItems->links();?>
					@else
					@endif
		        </tbody>
		    </table>
		    <?php if ($transaction->service_type__id == 'weiluy') { 
		    	  	if (Entrust::can('cancel_txn_weiluy')) {
		    	  		if ($transaction->atr1_value == 'no') {
		    	  		?>
			    	  	<br/>
			    	  	<div class="row">
						  	<div class="col-md-4 text-right">
						  		{{ Form::label('remark', 'Remark *:') }}
						  	</div>
						  	<div class="col-md-4 text-left">
						  		{{ Form::textarea('remark', '', array('class' => 'form-control','placeholder'=>'Remark','size' => '30x5','required'=>'required')) }}
						  	</div>
						</div>
			    		<div class="row">
							<div class="col-md-4 text-right">
							</div>
						  	<div class="col-md-4 text-left">
						  		{{ Form::submit('Confirm Cancel Weiluy',array('class' => 'btn btn-primary','name'=>'cancel','value'=>'cancel')) }}
						  	</div>
						</div>
		    <?php		}
					 } 
				  } else if ($transaction->service_type__id == 'withdraw' || $transaction->service_type__id == 'withdraw_game' || $transaction->service_type__id =='withdraw_game_req') {
					 	if (Entrust::can('approve_withdraw')) {
			    	  		if ($transaction->status == 'TI') {
			    	  			?>
			    	  			<br/>
			    	  			<div class="row">
								  	<div class="col-md-4 text-right">
								  		{{ Form::label('atr1_value', 'Bank Name*:') }}
								  	</div>
								  	<div class="col-md-4 text-left">
								  		{{ Form::select('atr1_value', array('' => 'Please Select')+$banks, '', array('id'=>'banks','class' => 'form-control')) }}
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
					    	  	<div class="row">
								  	<div class="col-md-4 text-right">
								  		{{ Form::label('remark', 'Remark *:') }}
								  	</div>
								  	<div class="col-md-4 text-left">
								  		{{ Form::textarea('remark', '', array('class' => 'form-control','placeholder'=>'Remark','size' => '30x5','required'=>'required')) }}
								  	</div>
								</div>
					    		<div class="row">
									<div class="col-md-4 text-right">
									</div>
								  	<div class="col-md-4 text-left">
								  		{{ Form::submit('Approve',array('class' => 'btn btn-primary','name'=>'approve','value'=>'approve')) }}
								  		{{ Form::submit('Reject',array('class' => 'btn btn-primary','name'=>'reject','value'=>'reject')) }}
								  	</div>
								</div>
			    	  			
			    	  			<?php
			    	  		}
						 } else if (Entrust::can('reject_withdraw')) {
			    	  		if ($transaction->status == 'TI') {
			    	  			?>
			    	  			<br/>
			    	  			<div class="row">
								  	<div class="col-md-4 text-right">
								  		{{ Form::label('atr1_value', 'Bank Name*:') }}
								  	</div>
								  	<div class="col-md-4 text-left">
								  		{{ Form::select('atr1_value', array('' => 'Please Select')+$banks, '', array('id'=>'banks','class' => 'form-control')) }}
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
					    	  	<div class="row">
								  	<div class="col-md-4 text-right">
								  		{{ Form::label('remark', 'Remark *:') }}
								  	</div>
								  	<div class="col-md-4 text-left">
								  		{{ Form::textarea('remark', '', array('class' => 'form-control','placeholder'=>'Remark','size' => '30x5','required'=>'required')) }}
								  	</div>
								</div>
					    		<div class="row">
									<div class="col-md-4 text-right">
									</div>
								  	<div class="col-md-4 text-left">
								  		{{ Form::submit('Reject',array('class' => 'btn btn-primary','name'=>'reject','value'=>'reject')) }}
								  	</div>
								</div>
			    	  			
			    	  			<?php
			    	  		}
						 } 
				  } else if ($transaction->service_type__id == 'deposit' || $transaction->service_type__id == 'deposit_game') {
					 	if (Entrust::can('approve_deposit')) {
			    	  		if ($transaction->status == 'TI') {
			    	  			?>
			    	  			<br/>
			    	  			<div class="row">
								  	<div class="col-md-4 text-right">
								  		{{ Form::label('atr1_value', 'Bank Name*:') }}
								  	</div>
								  	<div class="col-md-4 text-left">
								  		{{ Form::select('atr1_value', array('' => 'Please Select')+$banks, '', array('id'=>'banks','class' => 'form-control')) }}
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
					    	  	<div class="row">
								  	<div class="col-md-4 text-right">
								  		{{ Form::label('remark', 'Remark *:') }}
								  	</div>
								  	<div class="col-md-4 text-left">
								  		{{ Form::textarea('remark', '', array('class' => 'form-control','placeholder'=>'Remark','size' => '30x5','required'=>'required')) }}
								  	</div>
								</div>
					    		<div class="row">
									<div class="col-md-4 text-right">
									</div>
								  	<div class="col-md-4 text-left">
								  		{{ Form::submit('Approve',array('class' => 'btn btn-primary','name'=>'approve','value'=>'approve')) }}
								  		{{ Form::submit('Reject',array('class' => 'btn btn-primary','name'=>'reject','value'=>'reject')) }}
								  	</div>
								</div>
			    	  			
			    	  			<?php
			    	  		}
						 }
						 if (Entrust::can('cancel_deposit')) {
			    	  		if ($transaction->status == 'TS') {?>
			    	  			<br/>
					    	  	<div class="row">
								  	<div class="col-md-4 text-right">
								  		{{ Form::label('remark', 'Remark *:') }}
								  	</div>
								  	<div class="col-md-4 text-left">
								  		{{ Form::textarea('remark', '', array('class' => 'form-control','placeholder'=>'Remark','size' => '30x5','required'=>'required')) }}
								  	</div>
								</div>
					    		<div class="row">
									<div class="col-md-4 text-right">
									</div>
								  	<div class="col-md-4 text-left">
								  		{{ Form::submit('Confirm Cancel Deposit',array('class' => 'btn btn-primary','name'=>'cancel','value'=>'cancel')) }}
								  	</div>
								</div>
			    	  		<?php }
						 }
				   }
		    ?>
		@include('layouts.partial.render-message-form')
		{{ Form::close() }}
	</div>
</div>
@stop