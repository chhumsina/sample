@section('title', 'New Customer Call')
@section('content')
<h4>New Customer Call</h4>
<div class="row">
	<div class="col-md-12">
		{{ Form::open(array('route' => 'customer-calls.store')) }}
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('phone', 'Phone:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('phone', '', array('class' => 'form-control')) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('name', 'Sale Name:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('name', '', array('class' => 'form-control','placeholder'=>'Sale Name')) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('position', 'Position:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('position', '', array('class' => 'form-control','placeholder'=>'Position')) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('phone', 'Phone:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('phone', '', array('class' => 'form-control','placeholder'=>'Phone')) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('email', 'Email:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('email', '', array('class' => 'form-control','placeholder'=>'Email')) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('parent_id', 'Report To Sale Id:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('parent_id', '', array('class' => 'form-control','placeholder'=>'Report To Sale Id')) }}
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