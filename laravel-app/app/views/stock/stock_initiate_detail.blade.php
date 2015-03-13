nN@section('title', 'Stock Initiate Detial')
@section('content')
<?php
$baseUrl = URL::to('/');

?>
<script type="text/javascript">
	$(document).ready(function(){
		$baseUrl = '{{$baseUrl}}';
	});
</script>
<h4>Stock Initiate Detial</h4>
@include('layouts.partial.render-message')
<div class="row">
	<div class="col-md-12">
		{{ Form::open(array('url' => 'stocks/')) }}
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('id', 'Stock :') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		({{$stockInitiate->dealer__id}}) {{$stockInitiate->name}}
			  		{{ Form::hidden('transaction_id', $stockInitiate->transaction_id)}}
			  	</div>
			</div>
			
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('tcy_currency_id', 'Currency :') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ $stockInitiate->tcy_currency_id }}
			  	</div>
			</div>
			
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('requested_value', 'Request Amount :') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ number_format($stockInitiate->requested_value) }} {{ $stockInitiate->tcy_currency_id }}
			  	</div>
			</div>
			
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('requested_value', 'Requested By :') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ $stockInitiate->requestBy }}
			  	</div>
			</div>
			
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('requested_value', 'Requested At :') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ $stockInitiate->datetime }}
			  	</div>
			</div>
			
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('remark', 'Requested Remark :') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::textarea('', $stockInitiate->remark, array('class' => 'form-control','size' => '30x5','required'=>'required','disabled'=>'disabled')) }}
			  	</div>
			</div>
			
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('status', 'Transaction Status :') }}
			  	</div>
			  	<div class="col-md-4 text-left">
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
			  	</div>
			</div>
			
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
			
		@include('layouts.partial.render-message-form')
		{{ Form::close() }}
	</div>
</div>
@stop