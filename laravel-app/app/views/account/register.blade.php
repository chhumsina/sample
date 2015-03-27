@section('title', 'Register')
@section('content')
	<?php
	$baseUrl = URL::to('/');
	?>
	<h2 class="title text-center">Register</h2>
	<div class="tabbable-panel">
		<div class="features_items">
			@include('layouts.partial.render-message')
			@include('layouts.partial.render-message-form')
		<?php echo Form::open(array('url' => 'register','role' => 'form', 'class'=>'form-horizontal','id'=>'validationForm')) ?>
			<fieldset>

				<div class="form-group">
					<input type="text" name="honey" value="" class="honey"/>
					{{ Form::label('username', 'Username*', array('class' => 'col-sm-4 control-label'))}}
					<div class="col-sm-5">
						{{ Form::text('username','', array('class' => 'form-control','id'=>'username')) }}
					</div>
				</div>
				<div class="form-group">
					{{ Form::label('', 'Your Page', array('class' => 'col-sm-4 control-label'))}}
					<div class="col-sm-5 pageName">
						www.khmermoo.com/<span>...</span>
					</div>
				</div>
				<div class="form-group">
					{{ Form::label('password', 'Password*', array('class' => 'col-sm-4 control-label'))}}
					<div class="col-sm-5">
						{{ Form::password('password', array('class' => 'form-control','id'=>'password')) }}
					</div>
				</div>
				<div class="form-group">
					{{ Form::label('cpassword', 'Confirm Password*', array('class' => 'col-sm-4 control-label'))}}
					<div class="col-sm-5">
						{{ Form::password('cpassword', array('class' => 'form-control','id'=>'cpassword')) }}
					</div>
				</div>
				<div class="form-group">
					{{ Form::label('email', 'Email*', array('class' => 'col-sm-4 control-label'))}}
					<div class="col-sm-5">
						{{ Form::text('email','', array('class' => 'form-control','id'=>'email')) }}
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label" id="captchaOperation"></label>
					<div class="col-sm-5">
						<input type="text" class="form-control" name="captcha" />
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-7">
						<div class="pull-right">
							<button type="submit" class="btn btn-primary" name="register" value="register">Register</button>
						</div>
					</div>
				</div>

			</fieldset>
			<?php echo Form::close() ?>
		</div>

	</div>
@stop