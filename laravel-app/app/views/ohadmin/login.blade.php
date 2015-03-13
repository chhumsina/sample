@section('title', 'List Announcement')
@section('content')
	<?php echo Form::open(array('url' => '/ohadmin', 'role' => 'form')) ?>
	<input type="text" name="username" value="<?php echo Input::old('username') ?>" required="required" autofocus="autofocus" placeholder="Username"class="form-control"  />
	<input name="password" placeholder="Password" required="required" type="password" class="form-control" />
	<button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
	<p>
		<?php  if(Session::has('flash_notice_error')): ?>
	<div id="flash_notice" class='red'><?php echo Session::get('flash_notice_error') ?></div>
	<?php endif; ?>
	<?php  if(Session::has('flash_notice')): ?>
	<div id="flash_notice" class='info'><?php echo Session::get('flash_notice') ?></div>
	<?php endif; ?>
	</p>
	<?php echo Form::close() ?>
@stop