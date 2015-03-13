@section('title', 'Transaction Report')
@section('content')

<?php
$baseUrl = URL::to('/');
?>
<script type="text/javascript">
	$(document).ready(function(){
	});
</script>
@include('layouts.partial.render-message')
<h4>Transaction Report</h4>
<div class="row">
	<div class="col-md-12">
		<!-- Default panel contents -->
		<div class="panel panel-default">
		  <div class="panel-heading">
		  	{{ Form::open(array('url' => 'reports/transaction-report')) }}
		  		<div class="row">
	  	 			<div class="col-md-2">
  	 					<label>Date From:</label>
  	 				</div>	  	 				
  	 				<div class="col-md-2">
  	 					<input type="text" id="datepicker_start_datetime" name="start_date" value="<?php if(Input::has('start_date')) {echo  Input::get('start_date');}else{echo date("Y-m-j").' 00:00';} ?>" />
  	 				</div>
  	 				<div class="col-md-2">
  	 					<label>To:</label>
  	 				</div>
  	 				<div class="col-md-2">
  	 					<input type="text" id="datepicker_end_datetime" name="end_date" value="<?php if(Input::has('end_date')) {echo  Input::get('end_date');}else{echo date("Y-m-j").' 23:59';} ?>" />
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
  	 					<label>Txn Currency:</label>
  	 				</div>
  	 				<div class="col-md-2">
  	 					<?php $walletCurrencyId = ''; if (Input::has('tcy_currency_id')){$walletCurrencyId = Input::get('tcy_currency_id');} ?>
  	 					{{ Form::select('tcy_currency_id', $currencies, $walletCurrencyId, array('id' => 'tcy_currency_id','required'=>'required')) }}
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
  	 					<label>Created By:</label>
  	 				</div>
  	 				<div class="col-md-2">
			  	 	    <?php $staffName = ''; if (Input::has('staff_name')){$staffName = Input::get('staff_name');}?>
			  	 	    {{Form::text('staff_name',$staffName)}}
  	 				</div>
  	 				<div class="col-md-2">
  	 					<label>Group By Service Type:</label>
  	 				</div>
  	 				<div class="col-md-4">
  	 					<?php $group_service_type_id = ''; if (Input::has('group_service_type_id')){$group_service_type_id = Input::get('group_service_type_id');}
			  	 	   	?>
			  	 	    {{ Form::select('group_service_type_id', array('' => 'Please Select')+$arrayListGroupServiceType, $group_service_type_id, ['id' => 'group_service_type_id']) }}
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
						<input class="btn btn-primary export" type="submit" name="export_excel" value="Export To Excel" />
  	 				</div>
	  	 		</div>
  	 		{{ Form::close() }}
		  </div>	
		  <!-- Table -->
		@if ($transactions != null && $transactions->count())
		    <table class="table table-striped table-bordered">
		        <thead>
		            <tr>
		                <th>No</th>
				        <th style="min-width: 70px">Txn ID</th>
				        <th style="min-width: 70px">TSN</th>
				        <th>DID</th>
				        <!-- <th style="min-width: 100px">Dealer Name</th> -->
				        <th>Service Type</th>
				        <th>Currency</th>
				        <th>Requested Amount</th>
				        <th>Channel Commission</th>
				        <th>Transfered Amount</th>
				        <th>Prev Balance</th>
				        <th>Post Balance</th>
				        <!-- <th>Requested GateWay</th> -->
				        <th>Status</th>
				        <th>Created At</th>
				        <th>Created By</th>
				        <th width="115px">Actiont</th>
		            </tr>
		        </thead>
		        <tbody>
		        	<?php 
		        	$arrayIds = array();					
					$tcy_currency_id = '';
		        	$i = ($transactions->getCurrentPage() - 1) * $transactions->getPerPage(); ?>
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
								$transaction->service_type__id == 'pick5l') {
		          				echo $transaction->tsn;
		          			} else {
		          				echo $transaction->tsn_os;
		          			}
		          			?>	
		          			</td>
		          			<td>{{ $transaction->dealer__id }}</td>
		          			<!-- <td>{{ $transaction->name }}</td> -->
		          			<td>
		          				<?php
		          				echo '<span class="blue">'.$transaction->service_type__id.'</span>';
		          				?>	
		          			</td>
		          			<td>{{ $transaction->tcy_currency_id }}</td>
		          			<td align="right">{{ number_format($transaction->requested_value,2) }} {{ $transaction->tcy_currency_id }}</td>
		          			<td align="right">{{ number_format($transaction->channel_comm,2) }} {{ $transaction->tcy_currency_id }}</td>
		          			<td align="right">{{ number_format($transaction->transfer_value,2) }} {{ $transaction->tcy_currency_id }}</td>
		          			
		          			<td align="right"><?php if ($hasPrePost) { echo number_format($preB,2).' '.$transaction->tcy_currency_id; } ?></td>
		          			<td align="right"><?php if ($hasPrePost) { echo number_format($postB,2).' '.$transaction->tcy_currency_id; } ?></td>
		          			<!-- <td>{{ $transaction->request_gateway__id }}</td> -->
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
		          					if ($transaction->service_type__id == 'weiluy') { 
							    	  	if (Entrust::can('cancel_txn_weiluy')) {
							    	  		if ($transaction->atr1_value == 'no') {
							    	  			echo link_to('transactions/'.$transaction->transaction_id.'/detail','Cancel');
							    	  		}
										 } 
									 } else if ($transaction->service_type__id == 'withdraw' || $transaction->service_type__id == 'withdraw_game') {
									 	if (Entrust::can('approve_withdraw')) {
							    	  		if ($transaction->status == 'TI') {
							    	  			echo link_to('transactions/'.$transaction->transaction_id.'/detail','Approval');
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

									echo link_to('transactions/'.$transaction->transaction_id.'/detail','Detail');
									

						      	?>
						      	
		          			</td>
		          		</tr>
		          		<?php
						$tcy_currency_id = $transaction->tcy_currency_id;
		          		?>
		          	@endforeach
		          	
		        </tbody>
		    </table>
			<?php echo $transactions->links();?>
			<div class="row">
				<div class="col-md-4">
					<table class="table table-striped table-bordered">
						<tr>
							<td><h4>Total Txn: </h4></td>
		          			<td><h4>{{ $txnRecord }}</h4></td>
		          		</tr>
					</table>
				</div>	
			</div>
			<div class="row">
				<div class="col-md-12">	
				 <table class="table table-striped table-bordered">
			    	<tbody>
						<tr>
							<td>
								<?php
								if (isset($servicetypesSumTxns)) {
									//var_dump($servicetypesSumTxns);
								?>
		                    	<div class="row">
									<div class="col-md-12">
										<table class="table table-striped table-bordered">
										<thead>
											<td><h4>No</h4></td>
						          			<td><h4>Group By Service Type</h4></td>
						          			<td align="right"><h4>Total Request Value</h4></td>
						          			<td align="right"><h4>Total Channel Commission</h4></td>
						          			<td align="right"><h4>Total Transfer Value</h4></td>
						          			<td align="right"><h4>Total Prev Balance</h4></td>
						          			<td align="right"><h4>Total Post Balance</h4></td>
										</thead>
						          		<?php
										$e = 1;
										$sumrequestvalue = 0;
										$sumtransfervalue = 0;
										$sumchannelcomm = 0;
										$sumprevbalance = 0;
										$sumpostbalance = 0;
										
										if ($servicetypesSumTxns != null) {
											
											foreach ($servicetypesSumTxns as $servicetypesSumTxn) 
											{
												
												$sum_requestvalue = $servicetypesSumTxn->sumrequestvalue;
												$sum_channelcomm = $servicetypesSumTxn->sumchannelcomm;
												$sum_transfervalue = $servicetypesSumTxn->sumtransfervalue;
												$sum_prevbalance = $servicetypesSumTxn->sumprevbalance;
												$sum_postbalance = $servicetypesSumTxn->sumpostbalance;
												
												if ($servicetypesSumTxn->service_type__id == 'transfer_wallet') {// || $servicetypesSumTxn->service_type__id == 'buy_currency') {
													$sum_requestvalue = $sum_requestvalue / 2;
													$sum_channelcomm = $sum_channelcomm / 2;
													$sum_transfervalue = $sum_transfervalue / 2;
													//$sum_prevbalance = $sum_prevbalance / 2;
													//$sum_postbalance = $sum_postbalance / 2;
												}
												
											?>
											<tr>
							          			<td> <?php echo $e;?></td>
							          			<td><?php echo $servicetypesSumTxn->service_type__id;?></td>
							          			<td align="right"> <?php echo number_format($sum_requestvalue,2)." ".$tcy_currency_id;?> </td>
							          			<td align="right"> <?php echo number_format($sum_channelcomm,2)." ".$tcy_currency_id;?> </td>
							          			<td align="right"> <?php echo number_format($sum_transfervalue,2)." ".$tcy_currency_id;?> </td>
							          			<td align="right"> <?php echo number_format($sum_prevbalance,2)." ".$tcy_currency_id;?> </td>
							          			<td align="right"> <?php echo number_format($sum_postbalance,2)." ".$tcy_currency_id;?> </td>
							          		</tr>
							          		
											<?php	
											$e++;
											$sumrequestvalue = $sumrequestvalue + $sum_requestvalue;
											$sumchannelcomm = $sumchannelcomm + $sum_channelcomm;
											$sumtransfervalue = $sumtransfervalue + $sum_transfervalue;
											$sumprevbalance = $sumprevbalance + $sum_prevbalance;
											$sumpostbalance = $sumpostbalance + $sum_postbalance;
											}
										}	
										?>
										<tr>
						          			<td colspan="2" align="right"><h4>Total: </h4></td>
						          			<td align="right"><h4><?php echo number_format($sumrequestvalue,2)." ".$tcy_currency_id;?></h4></td>
						          			<td align="right"><h4><?php echo number_format($sumchannelcomm,2)." ".$tcy_currency_id;?></h4></td>
						          			<td align="right"><h4><?php echo number_format($sumtransfervalue,2)." ".$tcy_currency_id;?></h4></td>
						          			<td align="right"><h4><?php echo number_format($sumprevbalance,2)." ".$tcy_currency_id;?></h4></td>
						          			<td align="right"><h4><?php echo number_format($sumpostbalance,2)." ".$tcy_currency_id;?></h4></td>
						          		</tr>
									</table>
									</div>	
								</div>
								<?php
								}
								?>	
		                    </td>
		                    
		                </tr> 
		                <tr>
		                	<td>
			                    <div class="row">
									<div class="col-md-12">
			                    	<?php
			                    	if ($banksSumTxns != null) 
			                    	{
			                    	?>	
			                    	<table class="table table-striped table-bordered">
										<thead>
											<td><h4>No</h4></td>
						          			<td><h4>Bank Name</h4></td>
						          			<td align="right"><h4>Total Request Value</h4></td>
										</thead>
						          		<?php
										$t = 1;
										$totalSumRequest = 0;
										foreach ($banksSumTxns as $banksSumTxn) 
										{
										?>
										<tr>
						          			<td> <?php echo $t;?></td>
						          			<td><?php echo $banksSumTxn->bank_name;?></td>
						          			<td align="right"> <?php echo number_format($banksSumTxn->sumrequestvalue,2) ." ".$tcy_currency_id;?> </td>
						          		</tr>
										<?php	
										$t++;
										$totalSumRequest = $totalSumRequest + $banksSumTxn->sumrequestvalue;
										}	
										?>
										<tr>
						          			<td colspan="2" align="right"><h4>Total: </h4></td>
						          			<td align="right"><h4><?php echo number_format($totalSumRequest,2)." ".$tcy_currency_id;?></h4></td>
						          		</tr>
									</table>
									<?php
									}
									?>
									</div>
								</div>
								
		                    </td> 
		                </tr>
		                
			        </tbody>              
			     </table> 
					
				</div>
			</div>
			
		@else
		    There are no record!
		@endif
		</div>
		@stop
	</div>
</div>