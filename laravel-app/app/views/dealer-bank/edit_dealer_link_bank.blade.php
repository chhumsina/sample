@section('title', 'New Dealer-Link-Bank Account')
@section('content')
<?php
$baseUrl = URL::to('/');
?>
<script type="text/javascript">
	$(document).ready(function(){
	});
</script>
<h4>New Dealer-Link-Bank Account</h4>
@include('layouts.partial.render-message')
<div class="row">
	<div class="col-md-12">
		{{ Form::model($dealerBank, array('method' => 'PATCH', 'route' =>array('dealer-banks.update', $dealerBank->dealer_bank_id))) }}
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('dealer__id', 'Dealer ID*:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		({{$dealerBank->did}}) {{$dealerBank->dName}}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('bank__id', 'Bank:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{$dealerBank->bank_name;}}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('account_name', 'Bank Account Name:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('account_name', $dealerBank->account_name, array('class' => 'form-control','placeholder'=>'Bank Account Name','required'=>'required')) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('account', 'Bank Account:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('account', $dealerBank->account, array('class' => 'form-control','placeholder'=>'Bank Account','required'=>'required')) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('status', 'Status:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		<?php 
			  			echo Form::select('status',array('active' => 'active', 'inactive' => 'inactive'),$dealerBank->dealer_bank_status,array('class' => 'form-control','required'=>'required'));
			  		?>
			  	</div>
			</div>
			<div class="row">
				<div class="col-md-4 text-right"></div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::submit('Update',array('class' => 'btn btn-primary')) }}
			  	</div>
			</div>
			@include('layouts.partial.render-message-form')
		{{ Form::close() }}
	</div>
</div>
@stop