nN@section('title', 'Stock Initiate')
@section('content')
<?php
$baseUrl = URL::to('/');

?>
<script type="text/javascript">
	$(document).ready(function(){
		$baseUrl = '{{$baseUrl}}';
	});
</script>
<h4>Stock Initiate</h4>
@include('layouts.partial.render-message')
<div class="row">
	<div class="col-md-12">
		{{ Form::open(array('url' => 'stocks/stock-initiate')) }}
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('id', 'Stock *:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		<?php
			  		foreach ($stocks as $key => $item) {
						$stocks[$key] = '('.$key.') ' . $item;
					}
			  		?>
			  		{{ Form::select('dealer__id', array('' => 'Please Select')+$stocks, null, ['id' => 'id', 'class' => 'form-control','required'=>'required']) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('tcy_currency_id', 'Currency *:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::select('tcy_currency_id', array('' => 'Please Select')+$currencies, null, ['id' => 'tcy_currency_id', 'class' => 'form-control','required'=>'required']) }}
			  	</div>
			</div>
			
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('requested_value', 'Request Amount *:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('requested_value', '', array('class' => 'form-control','placeholder'=>'Request Amount','required'=>'required','pattern' => '\d*')) }}
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
			  		{{ Form::submit('Save',array('class' => 'btn btn-primary')) }}
			  	</div>
			</div>
			@include('layouts.partial.render-message-form')
		{{ Form::close() }}
	</div>
</div>
@stop