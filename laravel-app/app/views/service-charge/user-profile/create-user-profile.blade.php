@section('title', 'Create User Profile Service Charge')
@section('content')
<?php
$baseUrl = URL::to('/');
$dealerId = '';
if (Input::old('dealer__id')) {
	$dealerId = Input::old('dealer__id');
}
?>
<script type="text/javascript">
	$(document).ready(function(){
		
		$baseUrl = '{{$baseUrl}}';
		$dealerId = '{{$dealerId}}';
		
	});
	
</script>

<h4>Create User Profile Service Charge</h4>
@include('layouts.partial.render-message')
<div class="row">
	<div class="col-md-12">
		{{ Form::open(array('url' => 'service-charges/store_user_profile_service_charge')) }}
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('dealer__id', 'Dealer ID*:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
		         	{{ Form::text('dealer__id', '', array('class' => 'form-control','id' => 'deposit_dealer_select' , 'list' =>'dealer_list' , 'placeholder'=>'Dealer ID','required'=>'required')) }}
		         	<datalist id="dealer_list">
						@foreach ($dealers as $dealer)
						<option value="{{ $dealer->id }}">
					 	{{ $dealer->id }} - {{$dealer->name}}
						</option>
					 	@endforeach
					</datalist> 
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('class_of_service_charge__id', 'Class of service charge*:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::select('class_of_service_charge__id', array('' => 'Please Select')+$classOfServices, '', array('id'=>'class_of_service_charge__id','class' => 'form-control','required'=>'required')) }}
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