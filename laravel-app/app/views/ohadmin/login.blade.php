@section('title', 'List Announcement')
@section('content')
	<?php echo Form::open(array('url' => '/ohadmin', 'role' => 'form')) ?>
		<fieldset>
			<div class="form-group">
				<input type="text" name="username" value="<?php echo Input::old('username') ?>" required="required" autofocus="autofocus" placeholder="Username"class="form-control"  />
			</div>
			<div class="form-group">
				<input name="password" placeholder="Password" required="required" type="password" class="form-control" />
			</div>
			<button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
		</fieldset>
	<?php echo Form::close() ?>
@stop