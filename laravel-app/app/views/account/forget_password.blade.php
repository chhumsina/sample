@section('title', 'List Member')
@section('content')
	<?php
	$baseUrl = URL::to('/');
	?>

	<h2 class="title text-center">Forget Password</h2>
	<div class="tabbable-panel">
		<div class="features_items">
			@include('layouts.partial.render-message')
		<?php echo Form::open(array('url' => 'forget_password','role' => 'form', 'class'=>'form-horizontal','id'=>'')) ?>
			<fieldset>

				<div class="form-group">
					{{Form::label('email', 'Email', array('class' => 'col-sm-4 control-label'))}}
					<div class="col-sm-5">
						{{Form::text('email','',array('placeholder'=>'Email', 'id'=>'email','class'=>'form-control'))}}
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-7">
						<div class="pull-right">
							<button type="submit" class="btn btn-primary" value="submit" name="submit" value="">Send</button>
							<a href="login" class="btn btn-primary" title="Cancel">Cancel</a>
						</div>
					</div>
				</div>

			</fieldset>
			<?php echo Form::close() ?>
		</div>

	</div>

@stop