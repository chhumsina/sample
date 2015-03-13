@section('title', 'Stock Initiate View')
@section('content')

<?php
$baseUrl = URL::to('/');
?>
<script type="text/javascript">
	$(document).ready(function(){
		
	});
</script>
@include('layouts.partial.render-message')
<h4>Stock Initiate View</h4>
<div class="row">
	<div class="col-md-12">
		<!-- Default panel contents -->
		<div class="panel panel-default">
		  <div class="panel-heading">
		  	{{ Form::open(array('url' => 'stocks/stock-initiate-view')) }}
		  		<div class="row">
	  	 			<div class="col-md-2">
  	 					<label>Requested From:</label>
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
  	 					<label>Status:</label>
  	 				</div>
  	 				<div class="col-md-2">
			  	 	    <?php $status = ''; if (Input::has('status')){$status = Input::get('status');}
			  	 	    	  $listStatus = array('TI'=>'Initiate','TA'=>'Approval','TR'=>'Reject','TS'=>'Success');
			  	 	   	?>
			  	 	    {{ Form::select('status', array('' => 'Please Select')+$listStatus, $status, ['id' => 'status']) }}
  	 				</div>
  	 				<div class="col-md-2">
  	 					<input type="submit" name="search_terminal" value="Search" />
  	 				</div>
	  	 		</div>
  	 		{{ Form::close() }}
		  </div>	
		  <!-- Table -->
		@if ($stockInitiates->count())
		    <table class="table table-striped table-bordered">
		        <thead>
		            <tr>
		                <th>No</th>
				        <th style="min-width: 70px">Txn ID</th>
				        <th>Stock ID</th>
				        <th>Stock Name</th>
				        <th>Currency</th>
				        <th>Request Amount</th>
				        <th>Transfer Amount</th>
				        <th>Remark</th>
				        <th>Status</th>
				        <th>Requested At</th>
				        <th>Requested By</th>
				        <th width="115px">Actiont</th>
		            </tr>
		        </thead>
		        <tbody>
		        	<?php 
		        	$arrayIds = array();
		        	$i = ($stockInitiates->getCurrentPage() - 1)* $stockInitiates->getPerPage(); ?>
		            @foreach ($stockInitiates as $stockInitiate)
		            	<?php $i += 1;?>
						<tr>
		                    <td>{{ $i }}</td>
		          			<td>{{ $stockInitiate->transaction_id }}</td>
		          			<td>{{ $stockInitiate->dealer__id }}</td>
		          			<td>{{ $stockInitiate->name }}</td>
		          			<td>{{ $stockInitiate->tcy_currency_id }}</td>
		          			<td align="right">{{ number_format($stockInitiate->requested_value) }} {{ $stockInitiate->tcy_currency_id }}</td>
		          			<td align="right">{{ number_format($stockInitiate->transfer_value) }} {{ $stockInitiate->tcy_currency_id }}</td>
		          			<td>{{ $stockInitiate->remark }}</td>
		          			<td>
		          				<?php
		          					if ($stockInitiate->status == 'TI' || $stockInitiate->status == 'A1' || $stockInitiate->status == 'A2' || $stockInitiate->status == 'A3') {
		          						echo '<span class="blue">'.$stockInitiate->statusName.'</span>';
		          					} else if ($stockInitiate->status == 'TS') {
		          						echo '<span class="green">'.$stockInitiate->statusName.'</span>';
		          					} else if ($stockInitiate->status == 'TR') {
		          						echo '<span class="red">'.$stockInitiate->statusName.'</span>';
		          					} else {
		          						echo '<span>'.$stockInitiate->statusName.'</span>';
		          					}
		          				?>
		          			</td>
		          			<td>{{ $stockInitiate->datetime }}</td>
		          			<td>{{ $stockInitiate->requestBy }}</td>
		          			<td>
		          				<?php
						      		if (Entrust::can('stock_approval')) {
						      			$staffId = Auth::user()->id;
						      			if (($stockInitiate->status == 'TI' || $stockInitiate->status == 'A1' || $stockInitiate->status == 'A2' || $stockInitiate->status == 'A3') && $stockInitiate->staff__id != $staffId) {
						      				if ($stockInitiate->atr2_value  != $staffId && $stockInitiate->atr3_value  != $staffId && $stockInitiate->atr4_value  != $staffId) {
						      					$currency = $stockInitiate->tcy_currency_id;
												$approvalRangeStaffPrivileges = $collectionApprovalRange[$currency];
												foreach($approvalRangeStaffPrivileges as $approvalRange) {
													if ($approvalRange->start_range<= $stockInitiate->requested_value && $stockInitiate->requested_value<=$approvalRange->end_range) {
														echo link_to('stocks/'.$stockInitiate->transaction_id.'/stock-approval','Approval').' | ';
														break;
													}
												}		
						      				}				      				
						      			}
						      		}
									echo link_to('stocks/'.$stockInitiate->transaction_id.'/stock-initiate-detail','Detail');
						      	?>
		          			</td>
		          		</tr>
		          	@endforeach
		        </tbody>
		    </table>
			<?php echo $stockInitiates->links();?>
		@else
		    There are no record!
		@endif
		</div>
		@stop
	</div>
</div>