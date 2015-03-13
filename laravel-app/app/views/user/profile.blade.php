@section('title', 'Profile')
@section('content')
<h4>Profile</h4>
<div class="row">
	<div class="col-md-12">
		{{ Form::open(array('route' => 'sales.store')) }}
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('username', 'Name:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Auth::user()->name }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('username', 'Username:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Auth::user()->username }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('username', 'Role:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Auth::user()->role }}
			  	</div>
			</div>
			@include('layouts.partial.render-message-form')
		{{ Form::close() }}
	</div>
</div>
@stop