@section('title', 'Transaction View')
@section('content')

<?php
$baseUrl = URL::to('/');
?>
<script type="text/javascript">
	$(document).ready(function(){
		
	});
</script>
@include('layouts.partial.render-message')
<h4>Transaction View</h4>
<div class="row">
	<div class="col-md-12">
		<!-- Default panel contents -->
		<div class="panel panel-default">
		  <div class="panel-heading">
		  	{{ Form::open(array('url' => 'transactions/index')) }}
		  		<div class="row">
	  	 			<div class="col-md-2">
  	 					<label>Date From:</label>
  	 				</div>	  	 				
  	 				<div class="col-md-2">
  	 					<input type="text" id="datepicker_start_datetime" name="start_date" value="<?php if(Input::has('start_date')) {echo  Input::get('start_date');}//else{echo date("Y-m-j").' 00:00';} ?>" />
  	 				</div>
  	 				<div class="col-md-2">
  	 					<label>To:</label>
  	 				</div>
  	 				<div class="col-md-2">
  	 					<input type="text" id="datepicker_end_datetime" name="end_date" value="<?php if(Input::has('end_date')) {echo  Input::get('end_date');}//else{echo date("Y-m-j").' 23:59';} ?>" />
  	 				</div>
  	 			</div>
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
  	 					<label>Service Type:</label>
  	 				</div>	  	 				
  	 				<div class="col-md-2">
  	 					<?php $serviceType = ''; if (Input::has('service_type__id')){$serviceType = Input::get('service_type__id');}
			  	 	   	?>
			  	 	    {{ Form::select('service_type__id', array('' => 'Please Select')+$listServiceTypes, $serviceType, ['id' => 'service_type__id']) }}
  	 				</div>
  	 				<div class="col-md-2">
  	 					<label>Status:</label>
  	 				</div>
  	 				<div class="col-md-2">
			  	 	    <?php $status = ''; if (Input::has('status')){$status = Input::get('status');}
			  	 	    	  $listStatus = array('TI'=>'Initiate','TA'=>'Approval','TR'=>'Reject','TS'=>'Success','TF'=>'Fail','TC'=>'Cancel');
			  	 	   	?>
			  	 	    {{ Form::select('status', array('' => 'Please Select')+$listStatus, $status, ['id' => 'status']) }}
  	 				</div>
  	 			</div>
  	 			<div class="row">
	  	 			<div class="col-md-2">
  	 					<label>Txn ID:</label>
  	 				</div>	  	 				
  	 				<div class="col-md-2">
  	 					<?php $id = ''; if (Input::has('transaction_id')){$id = Input::get('transaction_id');} ?>
  	 					{{Form::text('transaction_id',$id,array('pattern' => '\d*'))}}
  	 				</div>
  	 				<div class="col-md-2">
  	 					<label>TSN:</label>
  	 				</div>
  	 				<div class="col-md-2">
			  	 	    <?php $tsnOs = ''; if (Input::has('tsn_os')){$tsnOs = Input::get('tsn_os');}?>
			  	 	    {{Form::text('tsn_os',$tsnOs)}}
  	 				</div>
  	 			</div>
  	 			<div class="row">
		  	 		<div class="col-md-2">
  	 					<label>Created By:</label>
  	 				</div>
  	 				<div class="col-md-2">
			  	 	    <?php $staffName = ''; if (Input::has('staff_name')){$staffName = Input::get('staff_name');}?>
			  	 	    {{Form::text('staff_name',$staffName)}}
  	 				</div>
  	 				<div class="col-md-2">
  	 					<label>Requested GateWay:</label>
  	 				</div>
  	 				<div class="col-md-2">
			  	 	    <?php $request_gateway = ''; if (Input::has('request_gateway__id')){$request_gateway = Input::get('request_gateway__id');}
			  	 	    	  $listGetWays = array('web'=>'web','terminal'=>'terminal');
			  	 	   	?>
			  	 	    {{ Form::select('request_gateway__id', array('' => 'Please Select')+$listGetWays, $request_gateway, ['id' => 'status']) }}
  	 				</div>
	  	 		</div>
	  	 		<div class="row">
		  	 		<div class="col-md-2">
  	 					<label>N-Record:</label>
  	 				</div>
  	 				<div class="col-md-2">
			  	 	    <?php $nRecord = ''; if (Input::has('n_record')){$nRecord = Input::get('n_record');}
			  	 	    	  $nRecords = array('10'=>'10','30'=>'30','50'=>'50','100'=>'100');
			  	 	   	?>
			  	 	    {{ Form::select('n_record', $nRecords, $nRecord, ['id' => 'n_record']) }}
  	 				</div>
  	 				<div class="col-md-2">
  	 					<input class="btn btn-primary" type="submit" name="search_terminal" value="Search" />
  	 				</div>
	  	 		</div>
  	 		{{ Form::close() }}
		  </div>	
		  <!-- Table -->
		@if ($transactions->count())
		    <table class="table table-striped table-bordered">
		        <thead>
		            <tr>
		                <th>No</th>
				        <th style="min-width: 70px">Txn ID</th>
				        <th style="min-width: 70px">TSN</th>
				        <th>DID</th>
				        <th>TID</th>
				        <!-- <th style="min-width: 100px">Dealer Name</th> -->
				        <th>Service Type</th>
				        <th>Currency</th>
				        <th>Requested Amount</th>
				        <th>Transfered Amount</th>
				        <th>Prev Balance</th>
				        <th>Post Balance</th>
				        <!--<th>Requested GateWay</th>-->
				        <th>Status</th>
				        <th>Created At</th>
				        <th>Created By</th>
				        <th width="115px">Actiont</th>
		            </tr>
		        </thead>
		        <tbody>
		        	<?php 
		        	$arrayIds = array();
		        	$i = ($transactions->getCurrentPage() - 1)* $transactions->getPerPage(); ?>
		            @foreach ($transactions as $transaction)
		            	<?php $i += 1;
		            	
		            	$preB = 0;
						$postB = 0;
						$hasPrePost = false;
						try {
							$obItem = DB::table('txn_transaction_items as ti')
								->select('ti.prev_balance','ti.post_balance')
								->join('w_wallet as w','w.wallet_id','=','ti.wallet_id')
								->where('ti.transaction__id',$transaction->transaction_id)
								->where('ti.dealer__id',$transaction->dealer__id)
								->where('w.wallet_currency__id',$transaction->tcy_currency_id)
								->first();
								$preB = $obItem->prev_balance;
								$postB = $obItem->post_balance;
								$hasPrePost = true;
						} catch (Exception $e) {
							
						}
		            	
		            	
		            	?>
						<tr>
		                    <td>{{ $i }}</td>
		          			<td>{{ $transaction->transaction_id }}</td>
		          			<td>
		          			<?php
		          			if ($transaction->service_type__id == 'pick5' ||
								$transaction->service_type__id == '639' ||
								$transaction->service_type__id == '639luk' ||
								$transaction->service_type__id == 'pick5l' ||
								$transaction->service_type__id == '639_pro' ||
								$transaction->service_type__id == 'pick5_pro'
								) {
		          				echo $transaction->tsn;
		          			} else {
		          				echo $transaction->tsn_os;
		          			}
		          			?>	
		          			</td>
		          			<td>{{ $transaction->dealer__id }}</td>
		          			<td>{{ $transaction->terminal__serial }}</td>
		          			<!-- <td>{{ $transaction->name }}</td> -->
		          			<td>
		          				<?php
		          				echo '<span class="blue">'.$transaction->service_type__id.'</span>';
		          				?>	
		          			</td>
		          			<td>{{ $transaction->tcy_currency_id }}</td>
		          			<td align="right">{{ number_format($transaction->requested_value,2) }} {{ $transaction->tcy_currency_id }}</td>
		          			<td align="right">{{ number_format($transaction->transfer_value,2) }} {{ $transaction->tcy_currency_id }}</td>
		          			
		          			<td align="right"><?php if ($hasPrePost) { echo number_format($preB,2).' '.$transaction->tcy_currency_id; } ?></td>
		          			<td align="right"><?php if ($hasPrePost) { echo number_format($postB,2).' '.$transaction->tcy_currency_id; } ?></td>
		          			<!--<td>{{ $transaction->request_gateway__id }}</td>-->
		          			<td>
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
		          			</td>
		          			<td>{{ $transaction->datetime }}</td>
		          			<td>{{ $transaction->createdBy }}</td>
		          			<td>
		          				<?php
		          					echo link_to('transactions/'.$transaction->transaction_id.'/detail','Detail').' ';
									
		          					if ($transaction->service_type__id == 'weiluy') { 
							    	  	if (Entrust::can('cancel_txn_weiluy')) {
							    	  		if ($transaction->atr1_value == 'no') {
							    	  			echo link_to('transactions/'.$transaction->transaction_id.'/detail','Cancel');
							    	  		}
										 } 
									 } else if ($transaction->service_type__id == 'withdraw' || $transaction->service_type__id == 'withdraw_game' 
									 	|| $transaction->service_type__id =='withdraw_game_req') {
									 	if (Entrust::can('approve_withdraw')) {
							    	  		if ($transaction->status == 'TI') {
							    	  			echo link_to('transactions/'.$transaction->transaction_id.'/detail','Approval');
							    	  		}
										} else if (Entrust::can('reject_withdraw')) {
							    	  		if ($transaction->status == 'TI') {
							    	  			echo link_to('transactions/'.$transaction->transaction_id.'/detail','Reject');
							    	  		}
										}
									 } else if ($transaction->service_type__id == 'deposit' || $transaction->service_type__id == 'deposit_game') {
									 	if (Entrust::can('approve_deposit')) {
							    	  		if ($transaction->status == 'TI') {
							    	  			echo link_to('transactions/'.$transaction->transaction_id.'/detail','Approval').' ';
							    	  		}
										} 
										if (Entrust::can('cancel_deposit')) {
							    	  		if ($transaction->status == 'TS') {
							    	  			echo link_to('transactions/'.$transaction->transaction_id.'/detail','Cancel').' ';
							    	  		}
										}
										if (Entrust::can('edit_deposit')) {
							    	  		if ($transaction->status == 'TS') {
							    	  			echo link_to('transactions/'.$transaction->transaction_id.'/edit','Edit');
							    	  		}
										}
									 } 
							    ?>
		          				<?php
						      		/*if (Entrust::can('stock_approval')) {
						      			$staffId = Auth::user()->id;
						      			if (($stockInitiate->status == 'TI' || $stockInitiate->status == 'A1' || $stockInitiate->status == 'A2' || $stockInitiate->status == 'A3') && $stockInitiate->staff__id != $staffId) {
						      				if ($stockInitiate->atr2_value  != $staffId && $stockInitiate->atr3_value  != $staffId && $stockInitiate->atr4_value  != $staffId) {
						      					$currency = $stockInitiate->tcy_currency_id;
												$approvalRangeStaffPrivileges = $collectionApprovalRange[$currency];
												foreach($approvalRangeStaffPrivileges as $approvalRange) {
													if ($approvalRange->start_range<= $stockInitiate->requested_value && $stockInitiate->requested_value<=$approvalRange->end_range) {
														echo link_to('transactions/'.$stockInitiate->transaction_id.'/stock-approval','Approval').' | ';
														break;
													}
												}		
						      				}				      				
						      			}
						      		}*/
						      	?>
						      	
		          			</td>
		          		</tr>
		          	@endforeach
		        </tbody>
		    </table>
			<?php echo $transactions->links();?>
		@else
		    There are no record!
		@endif
		</div>
		@stop
	</div>
</div>