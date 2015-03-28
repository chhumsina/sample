@section('title', 'List Member')
@section('content')
	<?php
	$baseUrl = URL::to('/');
	?>
	@include('layouts.partial.menumember')
	<br/>
	<div class="row">
		<div class="col-md-7 col-md-offset-1">
			<?php echo Form::open(array('url' => 'member/my_profile','role' => 'form', 'class'=>'form-horizontal','id'=>'','enctype'=>'multipart/form-data')) ?>
				<fieldset>
					<div class="form-group">
						{{Form::label('phone', 'Phone', array('class' => 'col-sm-2 control-label'))}}
						<div class="col-sm-5">

							<div id="image-preview" style="background-size: 100px 100px !important; background-image:url('{{ URL::asset('assets/images/member/'.$acc->photo); }}');">
								<label for="image-upload" id="image-label">Choose Photo</label>
								<input type="file" name="photo" id="image-upload" />
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="first_name">Name</label>
						<div class="col-sm-5">
							@if(empty($acc->first_name))
								<?php $first_name = $acc->username; ?>
							@else
								<?php $first_name = $acc->first_name; ?>
							@endif
							{{Form::text('first_name',$first_name,array('placeholder'=>'First Name', 'id'=>'first_name','class'=>'form-control'))}}
						</div>
						<div class="col-sm-5">
							{{Form::text('last_name',$acc->last_name,array('placeholder'=>'Last Name', 'class'=>'form-control'))}}
						</div>
					</div>

					<div class="form-group">
						{{Form::label('phone', 'Phone', array('class' => 'col-sm-2 control-label'))}}
						<div class="col-sm-5">
							{{Form::text('phone',$acc->phone,array('placeholder'=>'Phone', 'id'=>'phone','class'=>'form-control'))}}
						</div>
					</div>

					<div class="form-group">
						{{Form::label('location', 'Location', array('class' => 'col-sm-2 control-label'))}}
						<div class="col-sm-5">
							{{ Form::select('location', $locations, array($acc->location_id), array('class' => 'form-control')) }}
						</div>
					</div>

					<div class="form-group">
						{{Form::label('dddress', 'Address', array('class' => 'col-sm-2 control-label'))}}
						<div class="col-sm-10">
							{{ Form::textarea('address',$acc->address,array('size' => '30x5','placeholder'=>'Address', 'id'=>'phone','class'=>'form-control'))}}
						</div>
					</div>

					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<div class="pull-right">
								<button type="submit" value="save" name="save" class="btn btn-primary">Save</button>
							</div>
						</div>
					</div>

				</fieldset>
			<?php echo Form::close() ?>
				<hr/>
				<a class="btn btn-primary" data-toggle="collapse" href="#changePassword" aria-expanded="false" aria-controls="changePassword">
					Change Password
				</a>
				<div class="collapse" id="changePassword">
					<div class="well">
						<?php echo Form::open(array('url' => 'member/change_password','role' => 'form', 'class'=>'form-horizontal','id'=>'validationForm')) ?>
						<fieldset>

							<div class="form-group">
								{{Form::label('curPassword', 'Current Password', array('class' => 'col-sm-4 control-label'))}}
								<div class="col-sm-8">
									{{Form::password('curPassword',array('placeholder'=>'Current Password', 'id'=>'curPassword','class'=>'form-control'))}}
								</div>
							</div>
							<div class="form-group">
								{{Form::label('newPassword', 'New Password', array('class' => 'col-sm-4 control-label'))}}
								<div class="col-sm-8">
									{{Form::password('newPassword',array('placeholder'=>'New Password', 'id'=>'newPassword','class'=>'form-control'))}}
								</div>
							</div>
							<div class="form-group">
								{{Form::label('conPassword', 'Confirm Password', array('class' => 'col-sm-4 control-label'))}}
								<div class="col-sm-8">
									{{Form::password('conPassword',array('placeholder'=>'Confirm Password', 'id'=>'conPassword','class'=>'form-control'))}}
								</div>
							</div>

							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-10">
									<div class="pull-right">
										<button type="submit" value="save" name="save" class="btn btn-primary">Change</button>
									</div>
								</div>
							</div>

						</fieldset>
						<?php echo Form::close() ?>
					</div>
				</div>
		</div><!-- /.col-lg-12 -->
	</div><!-- /.row -->
	@include('layouts.partial.menumemberfooter')
@stop