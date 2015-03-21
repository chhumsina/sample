@section('title', 'List Member')
@section('content')
	<?php
	$baseUrl = URL::to('/');
	?>
	@include('layouts.partial.menumember')
	<div class="row page">
		<?php echo Form::open(array('url' => 'member/my_profile','role' => 'form', 'class'=>'','id'=>'','enctype'=>'multipart/form-data')) ?>
			<div class="col-sm-12">
				<p class="pull-right visible-xs">
					<button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas">Toggle nav</button>
				</p>
				<div class="jumbotron" id="cover" style="background-image:url('{{ URL::asset('assets/images/member/cover/'.$acc->cover); }}');">
					<label for="image-upload" id="image-label">Choose Cover (830 x 300)</label>
					<input type="file" name="photo" id="image-upload" />
					<h5 id="name">{{ucfirst($acc->username)}}</h5>
					{{Form::textarea('bio',$acc->bio,['id' => 'bio','size' => '1x2','placeholder'=>'Type your bio here...'])}}
					<img id="pagePhoto" src="{{$baseUrl}}/assets/images/member/{{$acc->photo}}">
				</div>
				<div class="row">
				</div><!--/row-->
			</div><!--/.col-xs-12.col-sm-9-->
			<div class="col-sm-12">
				<div role="tabpanel" class="tabpanel">

					<!-- Nav tabs -->
					<ul class="nav nav-tabs" role="tablist">
						<li role="presentation" class="active"><a href="#product" aria-controls="home" role="tab" data-toggle="tab">Product</a></li>
						<li role="presentation"><a href="#about" aria-controls="profile" role="tab" data-toggle="tab">About Us</a></li>
						<li role="presentation"><a href="#contact" aria-controls="messages" role="tab" data-toggle="tab">Contact</a></li>
						<li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">Settings</a></li>
					</ul>

					<!-- Tab panes -->
					<div class="tab-content">
						<div role="tabpanel" class="tab-pane active" id="product">Preview</div>
						<div role="tabpanel" class="tab-pane" id="about">
							<textarea id="editor1" name="about" placeholder="Type your text here...">{{$acc->about}}</textarea>
						</div>
						<div role="tabpanel" class="tab-pane" id="contact">...</div>
						<div role="tabpanel" class="tab-pane" id="settings">...</div>
					</div>

				</div>
				<button type="submit" value="savePage" name="savePage" class="btn btn-primary">Save</button>
			</div>
		<?php echo Form::close() ?>
	</div>
	@include('layouts.partial.menumemberfooter')
@stop