@section('title', 'Wallet List')
@section('content')

<?php
$baseUrl = URL::to('/');
?>
<script type="text/javascript">
	$(document).ready(function(){
		
	});
</script>
@include('layouts.partial.render-message')
<h4>Wallet List</h4>
<div class="row">
	<div class="col-md-12">
		<!-- Default panel contents -->
		<div class="panel panel-default">
		  <div class="panel-heading">
		  		{{ Form::open(array('url' => 'wallets/index')) }}
		  		<!--<div class="row">
	  	 			<div class="col-md-2">
  	 					<label>Last Txn From:</label>
  	 				</div>	  	 				
  	 				<div class="col-md-2">
  	 					<input type="text" id="datepicker_start_datetime" name="start_date" value="<?php if(Input::has('start_date')) {echo  Input::get('start_date');}//else{echo date("Y-m-j").' 00:00';} ?>" />
  	 				</div>
  	 				<div class="col-md-2">
  	 					<label>Last Txn To:</label>
  	 				</div>
  	 				<div class="col-md-2">
  	 					<input type="text" id="datepicker_end_datetime" name="end_date" value="<?php if(Input::has('end_date')) {echo  Input::get('end_date');}//else{echo date("Y-m-j").' 23:59';} ?>" />
  	 				</div>
  	 			</div>-->
  	 			<div class="row">
	  	 			<div class="col-md-2">
  	 					<label>DID:</label>
  	 				</div>	  	 				
  	 				<div class="col-md-2">
  	 					<?php $did = ''; if (Input::has('did')){$did = Input::get('did');} ?>
  	 					{{Form::text('did',$did,array('pattern' => '\d*'))}}
  	 				</div>
  	 				<div class="col-md-2">
  	 					<label>Dealer Name:</label>
  	 				</div>
  	 				<div class="col-md-2">
			  	 	    <?php $name = ''; if (Input::has('name')){$name = Input::get('name');}?>
			  	 	    {{Form::text('name',$name)}}
  	 				</div>
  	 			</div>
  	 			<div class="row">
	  	 			<div class="col-md-2">
  	 					<label>Last Txn ID:</label>
  	 				</div>	  	 				
  	 				<div class="col-md-2">
  	 					<?php $id = ''; if (Input::has('transaction_id')){$id = Input::get('transaction_id');} ?>
  	 					{{Form::text('transaction_id',$id,array('pattern' => '\d*'))}}
  	 				</div>
  	 				<div class="col-md-2">
  	 					<input type="submit" name="search_terminal" value="Search" />
  	 				</div>
  	 			</div>
  	 		{{ Form::close() }}
		  </div>	
		  <!-- Table -->
		@if ($dealerWallets->count())
		    <table class="table table-striped table-bordered">
		        <thead>
		            <tr>
		                <th>No</th>
				        <th>Dealer ID</th>
				        <th>Dealer Name</th>
				        <th>Wallet ID</th>
				        <th>Wallet NickName</th>
				        <th>Wallet Currency</th>
				        <th>Wallet Type</th>
				        <th>Last Txn Id</th>
				        <th>Last Txn Service</th>
				        <th>Last Txn On</th>
				        <th>Last Txn Credit</th>
				        <th>Last Txn Debit</th>
				        <th>Credit Balance (owe)</th>
				        <th>Previous Balance</th>
				        <th>Current Balance</th>
				        <th>Wallet Status</th>
		            </tr>
		        </thead>
		        <tbody>
		        	<?php 
		        	$arrayIds = array();
		        	$i = ($dealerWallets->getCurrentPage() - 1)* $dealerWallets->getPerPage(); ?>
		            @foreach ($dealerWallets as $dealerWallet)
		            	<?php 
						if (array_key_exists($dealerWallet->id,$arrayIds)) {	//die();
							?>
							<tr>
								<td>{{ $dealerWallet->wallet_id }}</td>
			          			<td>{{ $dealerWallet->wallet_nickname }}</td>
			          			<td>{{ $dealerWallet->wallet_currency__id }}</td>
			          			<td>{{ $dealerWallet->wallet_type__id }}</td>
			          			<td>{{ $dealerWallet->last_transaction__id }}</td>
			          			<td>{{ $dealerWallet->last_transaction_service_type__id }}</td>
			          			<td>{{ $dealerWallet->last_transaction_on }}</td>
			          			<td align="right">{{ number_format($dealerWallet->last_balance_credit,2) }} {{ $dealerWallet->wallet_currency__id }}</td>
			          			<td align="right">{{ number_format($dealerWallet->last_balance_debit,2) }} {{ $dealerWallet->wallet_currency__id }}</td>
			          			<td align="right">
			          				<?php
			          				if ($dealerWallet->balance_credit > 0) {
			          					echo '<span class="red">'.number_format($dealerWallet->balance_credit,2).' '.$dealerWallet->wallet_currency__id.'</span>';
			          				} else {
			          					echo '<span>'.number_format($dealerWallet->balance_credit,2).' '.$dealerWallet->wallet_currency__id.'</span>';
			          				}
			          				?>
			          			</td>
			          			<td align="right">{{ number_format($dealerWallet->prev_balance,2) }} {{ $dealerWallet->wallet_currency__id }}</td>
			          			<td align="right">
			          				<?php
			          				echo '<span class="blue">'.number_format($dealerWallet->post_balance,2).' '.$dealerWallet->wallet_currency__id.'</span>';
			          				?>
			          			</td>
			          			<td>{{ $dealerWallet->status }}</td>
			          		</tr>
							<?php
						} else {
							$arrayIds[$dealerWallet->id] = $dealerWallet->id;
							//array_push($arrayIds,$dealerWallet->id);
							$i += 1;
							?>
							<tr>
			                    <td rowspan="{{$dealerWallet->num}}">{{ $i }}</td>
			          			<td rowspan="{{$dealerWallet->num}}">{{ $dealerWallet->id }}</td>
			          			<td rowspan="{{$dealerWallet->num}}">{{ $dealerWallet->name }}</td>
			          			<td>{{ $dealerWallet->wallet_id }}</td>
			          			<td>{{ $dealerWallet->wallet_nickname }}</td>
			          			<td>{{ $dealerWallet->wallet_currency__id }}</td>
			          			<td>{{ $dealerWallet->wallet_type__id }}</td>
			          			<td>{{ $dealerWallet->last_transaction__id }}</td>
			          			<td>{{ $dealerWallet->last_transaction_service_type__id }}</td>
			          			<td>{{ $dealerWallet->last_transaction_on }}</td>
			          			<td align="right">{{ number_format($dealerWallet->last_balance_credit,2) }} {{ $dealerWallet->wallet_currency__id }}</td>
			          			<td align="right">{{ number_format($dealerWallet->last_balance_debit,2) }} {{ $dealerWallet->wallet_currency__id }}</td>
			          			<td align="right">
			          				<?php
			          				if ($dealerWallet->balance_credit > 0) {
			          					echo '<span class="red">'.number_format($dealerWallet->balance_credit,2).' '.$dealerWallet->wallet_currency__id.'</span>';
			          				} else {
			          					echo '<span>'.number_format($dealerWallet->balance_credit,2).' '.$dealerWallet->wallet_currency__id.'</span>';
			          				}
			          				?>
			          			</td>
			          			<td align="right">{{ number_format($dealerWallet->prev_balance,2) }} {{ $dealerWallet->wallet_currency__id }}</td>
			          			<td align="right">
			          				<?php
			          				echo '<span class="blue">'.number_format($dealerWallet->post_balance,2).' '.$dealerWallet->wallet_currency__id.'</span>';
			          				?>
			          			</td>
			          			<td>{{ $dealerWallet->status }}</td>
			          		</tr>
							<?php
						}
		            	?>
		          	@endforeach
		        </tbody>
		    </table>
			<?php echo $dealerWallets->links();?>
		@else
		    There are no record!
		@endif
		</div>
		@stop
	</div>
</div>