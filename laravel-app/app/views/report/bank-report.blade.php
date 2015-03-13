@section('title', 'Transaction Report')
@section('content')

<?php
$baseUrl = URL::to('/');
$khanCode = '';
$communeCode = '';
$villageCode = '';
if (Input::has('khan__code')) {
	$khanCode = Input::get('khan__code');
}
?>
<script type="text/javascript">
	$(document).ready(function(){
		$baseUrl = '{{$baseUrl}}';
		$khanCode = '{{$khanCode}}';
		var seleteProvince = $("#province");
		var seleteKhan = $("#khan__code");
		var plsSelect = 'All';
		seleteProvince.on("change",function(){
			setProvince($(this));
		});
		
		setProvince(seleteProvince);
		function setProvince(ob) {
			seleteKhan.html('');
			seleteKhan.append('<option value="">'+plsSelect+'</option>');
			
			$val = ob.val();
			if ($val == '') {
				$val = 'xx';
			} else {
				loadDistrictByProvinceCode($val);
			}
			$("#idProvince").html($val);
		}
		function loadDistrictByProvinceCode($code) {
			seleteKhan.html('');
			seleteKhan.append('<option value="">'+plsSelect+'</option>');
						
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
		
		{{--// export--}}
		{{--$('form').on('click','.export', function (e) {--}}

			{{--var data_save = $('form').serializeArray();--}}
			{{--data_save.push({ name: "export_excel", value: "Export to Excel" });--}}

			{{--e.preventDefault();--}}
			{{--$.ajax({--}}
				{{--type: "POST",--}}
				{{--url: $(this).attr('action'),--}}
				{{--data: data_save,--}}
				{{--success: function () {--}}
					{{--window.location.href = '{{$baseUrl}}/laravel-app/sample.xls';--}}
				{{--}--}}
			{{--});--}}
			{{--return false;--}}
		{{--});--}}
	});
</script>
@include('layouts.partial.render-message')
<h4>Transaction Bank Report</h4>
<div class="row">
	<div class="col-md-12">
		<!-- Default panel contents -->
		<div class="panel panel-default">
		  <div class="panel-heading">
		  	{{ Form::open(array('url' => 'reports/bank-report')) }}
		  		<div class="row">
	  	 			<div class="col-md-2">
  	 					<label>Date From:</label>
  	 				</div>	  	 				
  	 				<div class="col-md-2">
  	 					<input class="form-control" type="text" id="datepicker_start_datetime" name="start_date" value="<?php if(Input::has('start_date')) {echo  Input::get('start_date');}else{echo date("Y-m-j").' 00:00';} ?>" />
  	 				</div>
  	 				<div class="col-md-2">
  	 					<label>To:</label>
  	 				</div>
  	 				<div class="col-md-2">
  	 					<input class="form-control" type="text" id="datepicker_end_datetime" name="end_date" value="<?php if(Input::has('end_date')) {echo  Input::get('end_date');}else{echo date("Y-m-j").' 23:59';} ?>" />
  	 				</div>
  	 			</div>
  	 			<div class="row">
	  	 			<div class="col-md-2">
  	 					<label>DID:</label>
  	 				</div>	  	 				
  	 				<div class="col-md-2">
  	 					<?php $did = ''; if (Input::has('did')){$did = Input::get('did');} ?>
  	 					{{Form::text('did',$did,array('pattern' => '\d*','class' => 'form-control'))}}
  	 				</div>
  	 				<div class="col-md-2">
  	 					<label>Dealer Name:</label>
  	 				</div>
  	 				<div class="col-md-2">
			  	 	    <?php $name = ''; if (Input::has('name')){$name = Input::get('name');}?>
			  	 	    {{Form::text('name',$name, ['class' => 'form-control'])}}
  	 				</div>
  	 			</div>
  	 			<div class="row">
	  	 			<div class="col-md-2">
  	 					<label>Service Type:</label>
  	 				</div>	  	 				
  	 				<div class="col-md-2">
  	 					<?php $serviceType = ''; if (Input::has('service_type__id')){$serviceType = Input::get('service_type__id');}
			  	 	   	?>
			  	 	    {{ Form::select('service_type__id', 
			  	 	    array('' => 'All','deposit' => 'Deposit', 'deposit_game' => 'Deposit Game', 'withdraw' => 'Withdraw', 'withdraw_game' => 'Withdraw Game', 'refund' => 'Refund', 'refund_game' => 'Refund Game'),
			  	 	    $serviceType, ['id' => 'service_type__id', 'class' => 'form-control']) }}
  	 				</div>
  	 				<div class="col-md-2">
  	 					<label>Status:</label>
  	 				</div>
  	 				<div class="col-md-2">
			  	 	    <?php $status = ''; if (Input::has('status')){$status = Input::get('status');}
			  	 	    	  $listStatus = array('TI'=>'Initiate','TA'=>'Approval','TR'=>'Reject','TS'=>'Success','TF'=>'Fail','TC'=>'Cancel');
			  	 	   	?>
			  	 	    {{ Form::select('status', array('' => 'All')+$listStatus, $status, ['id' => 'status', 'class' => 'form-control']) }}
  	 				</div>
  	 			</div>
  	 			<div class="row">
		  	 		<div class="col-md-2">
  	 					<label>Txn Currency:</label>
  	 				</div>
  	 				<div class="col-md-2">
  	 					<?php $walletCurrencyId = ''; if (Input::has('tcy_currency_id')){$walletCurrencyId = Input::get('tcy_currency_id');} ?>
  	 					{{ Form::select('tcy_currency_id', $currencies, $walletCurrencyId, array('id' => 'tcy_currency_id','required'=>'required', 'class' => 'form-control')) }}
  	 				</div>
  	 				<div class="col-md-2">
  	 					<label>Requested GateWay:</label>
  	 				</div>
  	 				<div class="col-md-2">
			  	 	    <?php $request_gateway = ''; if (Input::has('request_gateway__id')){$request_gateway = Input::get('request_gateway__id');}
			  	 	    	  $listGetWays = array('web'=>'web','terminal'=>'terminal');
			  	 	   	?>
			  	 	    {{ Form::select('request_gateway__id', array('' => 'All')+$listGetWays, $request_gateway, ['id' => 'status', 'class' => 'form-control']) }}
  	 				</div>
  	 				
	  	 		</div>
	  	 		
	  	 		<div class="row">
		  	 		<div class="col-md-2">
  	 					<label>Created By:</label>
  	 				</div>
  	 				<div class="col-md-2">
			  	 	    <?php $staffName = ''; if (Input::has('staff_name')){$staffName = Input::get('staff_name');}?>
			  	 	    {{Form::text('staff_name',$staffName, ['class' => 'form-control'])}}
  	 				</div>
  	 				<div class="col-md-2">
  	 					<label>Bank:</label>
  	 				</div>
  	 				<div class="col-md-2">
			  	 	    <?php $bank = ''; if (Input::has('bank_name')){$bank = Input::get('bank_name');}?>			  	 	   
			  	 	    {{ Form::select('bank_name', array('' => 'Please Select')+$bank_name, $bank, ['id' => 'bank_name', 'class' => 'form-control']) }}
  	 				</div>
	  	 		</div>
	  	 		<div class="row">
		  	 			<div class="col-md-2">
	  	 					<label>Province/City:</label>
	  	 				</div>	  	 				
	  	 				<div class="col-md-2">
	  	 					<?php $provinceCode = ''; if (Input::has('province__code')){$provinceCode = Input::get('province__code');} ?>
	  	 					<?php
					  		foreach ($listProvinces as $key => $item) {
								$listProvinces[$key] = '('.$key.') ' . $item;
							}
					  		?>
					  		{{ Form::select('province__code', array('' => 'All')+$listProvinces, $provinceCode, ['id' => 'province','class' => 'form-control']) }}
	  	 				
	  	 				</div>
	  	 				<div class="col-md-2">
	  	 					<label>District/Khan:</label>
	  	 				</div>
	  	 				<div class="col-md-2">
				  	 	    <?php $khan = ''; if (Input::has('khan__code')){$khan = Input::get('khan__code');}?>
				  	 	    {{ Form::select('khan__code', array('' => 'All'),null, array('id' => 'khan__code','class' => 'form-control')) }}
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
			  	 	    {{ Form::select('n_record', $nRecords, $nRecord, ['id' => 'n_record','class' => 'form-control']) }}
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
				        <th>Bank</th>
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
		          			<td>{{ $transaction->bank_name }}</td>
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
											?>
											<tr>
							          			<td> <?php echo $e;?></td>
							          			<td><?php echo $servicetypesSumTxn->service_type__id;?></td>
							          			<td align="right"> <?php echo number_format($servicetypesSumTxn->sumrequestvalue,2)." ".$tcy_currency_id;?> </td>
							          			<td align="right"> <?php echo number_format($servicetypesSumTxn->sumchannelcomm,2)." ".$tcy_currency_id;?> </td>
							          			<td align="right"> <?php echo number_format($servicetypesSumTxn->sumtransfervalue,2)." ".$tcy_currency_id;?> </td>
							          			<td align="right"> <?php echo number_format($servicetypesSumTxn->sumprevbalance,2)." ".$tcy_currency_id;?> </td>
							          			<td align="right"> <?php echo number_format($servicetypesSumTxn->sumpostbalance,2)." ".$tcy_currency_id;?> </td>
							          		</tr>
							          		
											<?php	
											$e++;
											$sumrequestvalue = $sumrequestvalue + $servicetypesSumTxn->sumrequestvalue;
											$sumchannelcomm = $sumchannelcomm + $servicetypesSumTxn->sumchannelcomm;
											$sumtransfervalue = $sumtransfervalue + $servicetypesSumTxn->sumtransfervalue;
											$sumprevbalance = $sumprevbalance + $servicetypesSumTxn->sumprevbalance;
											$sumpostbalance = $sumpostbalance + $servicetypesSumTxn->sumpostbalance;
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
		                
		                <!-- By Bank -->
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
						          			<td><h4>Group By Bank Name</h4></td>
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