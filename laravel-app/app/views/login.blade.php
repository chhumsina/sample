@include('layouts.backendpartial.head')
<body>
<div class="container">
	<div class="row vertical-offset-100">
		<div class="col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Please sign in</h3>
				</div>
				<div class="panel-body">
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
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>