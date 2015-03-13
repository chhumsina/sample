@section('title', 'Stock List')
@section('content')

<?php
$baseUrl = URL::to('/');
?>
<script type="text/javascript">
	$(document).ready(function(){
		
	});
</script>
@include('layouts.partial.render-message')
<h4>Stock List</h4>
<div class="row">
	<div class="col-md-12">
		<!-- Default panel contents -->
		<div class="panel panel-default">
		  <div class="panel-heading">
		  		{{ Form::open(array('url' => 'stocks/index')) }}
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
  	 					<label>Stock ID:</label>
  	 				</div>	  	 				
  	 				<div class="col-md-2">
  	 					<?php $did = ''; if (Input::has('did')){$did = Input::get('did');} ?>
  	 					{{Form::text('did',$did,array('pattern' => '\d*'))}}
  	 				</div>
  	 				<div class="col-md-2">
  	 					<label>Stock Name:</label>
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
		@if ($stocks->count())
		    <table class="table table-striped table-bordered">
		        <thead>
		            <tr>
		                <th>No</th>
				        <th>Stock ID</th>
				        <th>Stock Name</th>
				        <th>Wallet ID</th>
				        <th>Wallet NickName</th>
				        <th>Wallet Currency</th>
				        <th>Wallet Type</th>
				        <th>Last Txn Id</th>
				        <th>Last Txn Service</th>
				        <th>Last Txn On</th>
				        <th>Last Txn Credit</th>
				        <th>Last Txn Debit</th>
				        <th>Previous Balance</th>
				        <th>Current Balance</th>
				        <th>Wallet Status</th>
		            </tr>
		        </thead>
		        <tbody>
		        	<?php 
		        	$arrayIds = array();
		        	$i = ($stocks->getCurrentPage() - 1)* $stocks->getPerPage(); ?>
		            @foreach ($stocks as $stock)
		            	<?php
						if (array_key_exists($stock->id,$arrayIds)) {	//die();
							?>
							<tr>
								<td>{{ $stock->wallet_id }}</td>
			          			<td>{{ $stock->wallet_nickname }}</td>
			          			<td>{{ $stock->wallet_currency__id }}</td>
			          			<td>{{ $stock->wallet_type__id }}</td>
			          			<td>{{ $stock->last_transaction__id }}</td>
			          			<td>{{ $stock->last_transaction_service_type__id }}</td>
			          			<td>{{ $stock->last_transaction_on }}</td>
			          			<td align="right">{{ number_format($stock->last_balance_credit,2) }} {{ $stock->wallet_currency__id }}</td>
			          			<td align="right">{{ number_format($stock->last_balance_debit,2) }} {{ $stock->wallet_currency__id }}</td>
			          			<td align="right">{{ number_format($stock->prev_balance,2) }} {{ $stock->wallet_currency__id }}</td>
			          			<td align="right">{{ number_format($stock->post_balance,2) }} {{ $stock->wallet_currency__id }}</td>
			          			<td>{{ $stock->status }}</td>
			          		</tr>
							<?php
						} else {
							$i += 1;
							$arrayIds[$stock->id] = $stock->id;
							?>
							<tr>
			                    <td rowspan="{{ $stock->num }}">{{ $i }}</td>
			          			<td rowspan="{{ $stock->num }}">{{ $stock->id }}</td>
			          			<td rowspan="{{ $stock->num }}">{{ $stock->name }}</td>
			          			<td>{{ $stock->wallet_id }}</td>
			          			<td>{{ $stock->wallet_nickname }}</td>
			          			<td>{{ $stock->wallet_currency__id }}</td>
			          			<td>{{ $stock->wallet_type__id }}</td>
			          			<td>{{ $stock->last_transaction__id }}</td>
			          			<td>{{ $stock->last_transaction_service_type__id }}</td>
			          			<td>{{ $stock->last_transaction_on }}</td>
			          			<td align="right">{{ number_format($stock->last_balance_credit,2) }} {{ $stock->wallet_currency__id }}</td>
			          			<td align="right">{{ number_format($stock->last_balance_debit,2) }} {{ $stock->wallet_currency__id }}</td>
			          			<td align="right">{{ number_format($stock->prev_balance,2) }} {{ $stock->wallet_currency__id }}</td>
			          			<td align="right">{{ number_format($stock->post_balance,2) }} {{ $stock->wallet_currency__id }}</td>
			          			<td>{{ $stock->status }}</td>
			          		</tr>
							<?php
						}
		            	?>
		          	@endforeach
		        </tbody>
		    </table>
			<?php echo $stocks->links();?>
		@else
		    There are no record!
		@endif
		</div>
		@stop
	</div>
</div>