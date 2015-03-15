@section('title', 'List Member')
@section('content')
	<?php
	$baseUrl = URL::to('/');
	?>
	<div class="col-sm-7 padding-right">
		<div class="features_items">
			<h2 class="title text-center">Register</h2>
			@include('layouts.partial.render-message')
		<?php echo Form::open(array('url' => 'login','role' => 'form', 'class'=>'form-inline')) ?>
					<table class="table table-user-information">
						<tbody>
						<tr>
							<td>{{ Form::label('username', 'Username:') }}</td>
							<td>{{ Form::text('username','', array('class' => 'form-control','id'=>'username')) }}</td>
						</tr>
						<tr>
							<td>{{ Form::label('password', 'Password:') }}</td>
							<td>{{ Form::password('password', array('class' => 'form-control','id'=>'password')) }}</td>
						</tr>
						</tbody>
					</table>

					{{ Form::submit('Submit') }}
					<?php echo Form::close() ?>
		</div>

	</div>

@stop