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
		{{ Form::open(array('route' => 'dealer-banks.store')) }}
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('dealer__id', 'Dealer ID*:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		<?php
			  		//echo Form::text('dealer__id', '', array('class' => 'form-control','placeholder'=>'Dealer ID','required'=>'required'))
			  		foreach ($dealers as $key => $item) {
						$dealers[$key] = '('.$key.') ' . $item;
					}
					echo Form::select('dealer__id', array('' => 'Please Select')+$dealers, null, array('id' => 'dealer__id', 'class' => 'form-control','required'=>'required'))
			  		
			  		?>
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('bank__id', 'Bank:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::select('bank__id', array('' => 'Please Select')+$banks,null, array('id' => 'bank__id', 'class' => 'form-control','required'=>'required')) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('account_name', 'Bank Account Name:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('account_name', '', array('class' => 'form-control','placeholder'=>'Bank Account Name','required'=>'required')) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('account', 'Bank Account:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('account', '', array('class' => 'form-control','placeholder'=>'Bank Account','required'=>'required')) }}
			  	</div>
			</div>
			<div class="row">
				<div class="col-md-4 text-right"></div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::submit('Save',array('class' => 'btn btn-primary')) }}
			  	</div>
			</div>
			@include('layouts.partial.render-message-form')
		{{ Form::close() }}
	</div>
</div>
@stop