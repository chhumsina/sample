nN@section('title', 'Stock Approval')
@section('content')
<?php
$baseUrl = URL::to('/');

?>
<script type="text/javascript">
	$(document).ready(function(){
		$baseUrl = '{{$baseUrl}}';
	});
</script>
<h4>Stock Approval</h4>
@include('layouts.partial.render-message')
<div class="row">
	<div class="col-md-12">
		{{ Form::open(array('url' => 'stocks/stock-approval')) }}
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('id', 'Stock *:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		({{$stockInitiate->dealer__id}}) {{$stockInitiate->name}}
			  		{{ Form::hidden('transaction_id', $stockInitiate->transaction_id)}}
			  	</div>
			</div>
			
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('tcy_currency_id', 'Currency *:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ $stockInitiate->tcy_currency_id }}
			  	</div>
			</div>
			
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('requested_value', 'Request Amount *:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ number_format($stockInitiate->requested_value) }} {{ $stockInitiate->tcy_currency_id }}
			  	</div>
			</div>
			
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('requested_value', 'Requested By *:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ $stockInitiate->requestBy }}
			  	</div>
			</div>
			
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('requested_value', 'Requested At *:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ $stockInitiate->datetime }}
			  	</div>
			</div>
			
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('remark', 'Requested Remark *:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::textarea('', $stockInitiate->remark, array('class' => 'form-control','size' => '30x5','required'=>'required','disabled'=>'disabled')) }}
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
				<div class="col-md-4 text-right"></div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::submit('Approve',array('class' => 'btn btn-primary','name'=>'approve','value'=>'approve')) }}
			  		{{ Form::submit('Reject',array('class' => 'btn btn-primary','name'=>'reject','value'=>'reject')) }}
			  	</div>
			</div>
			@include('layouts.partial.render-message-form')
		{{ Form::close() }}
	</div>
</div>
@stop