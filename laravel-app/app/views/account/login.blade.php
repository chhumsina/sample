@section('title', 'Login')
@section('content')
	<?php
	$baseUrl = URL::to('/');
	?>

	<h2 class="title text-center">Login</h2>
	<div class="tabbable-panel">
		<div class="features_items">
			@include('layouts.partial.render-message')
		<?php echo Form::open(array('url' => 'login','role' => 'form', 'class'=>'form-horizontal','id'=>'')) ?>
			<fieldset>

				<div class="form-group">
					{{Form::label('username', 'Username', array('class' => 'col-sm-4 control-label'))}}
					<div class="col-sm-5">
						{{Form::text('username','',array('placeholder'=>'Username', 'id'=>'username','class'=>'form-control'))}}
					</div>
				</div>
				<div class="form-group">
					{{Form::label('password', 'Password', array('class' => 'col-sm-4 control-label'))}}
					<div class="col-sm-5">
						{{Form::password('password',array('placeholder'=>'Password', 'id'=>'password','class'=>'form-control'))}}
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-5 col-sm-offset-4">
						<a href="forget_password" title="Forget Password">Forget Password</a> |
						<a href="register" title="Register">Register to be member</a>
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-7">
						<div class="pull-right">
							<button type="submit" class="btn btn-primary" name="submit" value="">Login</button>
						</div>
					</div>
				</div>

			</fieldset>
			<?php echo Form::close() ?>
		</div>

	</div>

@stop