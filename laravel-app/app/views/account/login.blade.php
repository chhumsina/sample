@section('title', 'List Member')
@section('content')
	<?php
	$baseUrl = URL::to('/');
	?>
	<div class="col-sm-12 padding-right">
		<div class="features_items">
			<h2 class="title text-center">Login</h2>
			@include('layouts.partial.render-message')
		<?php echo Form::open(array('url' => 'login','role' => 'form', 'class'=>'','id'=>'')) ?>
			<div class="form-group">
				{{ Form::label('username', 'Username:',array('class'=>'col-sm-2 col-sm-offset-2 control-label')) }}
				<div class="col-sm-5">
					{{ Form::text('username','', array('class' => 'form-control','id'=>'username')) }}
				</div>
			</div>
			<div class="form-group">
				{{ Form::label('password', 'Password:',array('class'=>'col-sm-2 col-sm-offset-2 control-label')) }}
				<div class="col-sm-5">
					{{ Form::password('password', array('class' => 'form-control','id'=>'password')) }}
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-12">
					<button type="submit" class="btn btn-primary" name="submit" value="">Submit</button>
				</div>
			</div>
					<?php echo Form::close() ?>
		</div>

	</div>

@stop