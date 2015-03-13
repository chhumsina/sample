@section('title', 'Change Password')
@section('content')
<h4>Change Password</h4>
@include('layouts.partial.render-message')
<div class="row">
	<div class="col-md-12">
		{{ Form::open(array('url' => 'home/update-password')) }}
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('username', 'Username:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('username', Auth::user()->username, array('class' => 'form-control','disabled'=>'disabled')) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('password_2', 'Old Password:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		<input type="password" name="password_2" class="form-control" autocomplete="off"/>
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('new_password', 'New Password:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		<input type="password" name="new_password" class="form-control" autocomplete="off"/>
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('confirm_password', 'Confirm New Password:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		<input type="password" name="confirm_password" class="form-control" autocomplete="off"/>
			  	</div>
			</div>
			<div class="row">
				<div class="col-md-4 text-right"></div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::submit('Change',array('class' => 'btn btn-primary')) }}
			  	</div>
			</div>
			@include('layouts.partial.render-message-form')
		{{ Form::close() }}
	</div>
</div>
@stop