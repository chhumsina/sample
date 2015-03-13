@section('title', 'Edit Currency Conversion Rule')
@section('content')
<?php
$baseUrl = URL::to('/');

?>
<script type="text/javascript">
</script>
<h4>Edit Currency Conversion Rule Exchange Rate</h4>
@include('layouts.partial.render-message')
<div class="row">
	<div class="col-md-12">
		{{ Form::model($currencyConversionRule, array('method' => 'PATCH', 'route' =>array('currency-conversion-rules.update', $currencyConversionRule->c_conversion_rule_id))) }}
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('from_currency__id', 'From Currency:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		
			  		{{ Form::hidden('main_multiple_currency__id', $currencyConversionRule->main_multiple_currency__id)}}
			  		{{$currencyConversionRule->from_currency__id}}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('to_currency__id', 'To Currency:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{$currencyConversionRule->to_currency__id}}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('buy_rate', 'Buy Rate*:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('buy_rate', $currencyConversionRule->buy_rate, array('class' => 'form-control','placeholder'=>'Buy Rate','required'=>'required')) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('sell_rate', 'Sell Rate*:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('sell_rate', $currencyConversionRule->sell_rate, array('class' => 'form-control','placeholder'=>'Sell Rate','required'=>'required')) }}
			  	</div>
			</div>
			
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('mid_rate', 'Mid Rate*:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('mid_rate', $currencyConversionRule->mid_rate, array('class' => 'form-control','placeholder'=>'Mid Rate','required'=>'required')) }}
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