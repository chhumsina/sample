@section('title', 'List Member')
@section('content')
	<?php
	$baseUrl = URL::to('/');
	?>
	@include('layouts.partial.menumember')
	<br/>
	<div class="row">
		<div class="col-md-7 col-md-offset-1">
			<form class="form-horizontal" role="form">
				<fieldset>

					<div class="form-group">
						<label class="col-sm-2 control-label" for="first_name">Username</label>
						<div class="col-sm-5">
							{{Form::text('first_name',Auth::user()->username,array('placeholder'=>'First Name', 'id'=>'first_name','class'=>'form-control'))}}
						</div>
						<div class="col-sm-5">
							{{Form::text('last_name','',array('placeholder'=>'Last Name', 'class'=>'form-control'))}}
						</div>
					</div>

					<div class="form-group">
						{{Form::label('phone', 'Phone', array('class' => 'col-sm-2 control-label'))}}
						<div class="col-sm-5">
							{{Form::text('phone','',array('placeholder'=>'Phone', 'id'=>'phone','class'=>'form-control'))}}
						</div>
					</div>

					<div class="form-group">
						{{Form::label('location', 'Location', array('class' => 'col-sm-2 control-label'))}}
						<div class="col-sm-5">
							{{ Form::select('location', $locations, null, array('class' => 'form-control'))}}
						</div>
					</div>

					<div class="form-group">
						{{Form::label('dddress', 'Address', array('class' => 'col-sm-2 control-label'))}}
						<div class="col-sm-10">
							{{ Form::textarea('address','',array('size' => '30x5','placeholder'=>'Address', 'id'=>'phone','class'=>'form-control'))}}
						</div>
					</div>

					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<div class="pull-right">
								<button type="submit" class="btn btn-primary">Save</button>
							</div>
						</div>
					</div>

				</fieldset>
			</form>
		</div><!-- /.col-lg-12 -->
	</div><!-- /.row -->
	@include('layouts.partial.menumemberfooter')
@stop