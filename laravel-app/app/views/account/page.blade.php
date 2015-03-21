@section('title', 'List Member')
@section('content')
	<?php
	$baseUrl = URL::to('/');
	?>
	@include('layouts.partial.menumember')
	<div class="row page">
			<div class="col-sm-12">
				<p class="pull-right visible-xs">
					<button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas">Toggle nav</button>
				</p>
				<div class="jumbotron" id="cover">
					<label for="image-upload" id="image-label">Choose Cover (830 x 300)</label>
					<input type="file" name="photo" id="image-upload" />
					<h5 id="name">{{ucfirst(Auth::user()->username)}}</h5>
					<p id="bio">This is an example to show the potential of an offcanvas layout pattern in Bootstrap. Try some responsive-range viewport sizes to see it in action.</p>
					<img id="pagePhoto" src="{{$baseUrl}}/assets/images/member/{{Auth::user()->photo}}">
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
							<textarea id="editor1" name="editor" placeholder="Type your text here...">
    Lorem ipsum dolor sit amet, consectetur adipisici elit, sed eiusmod tempor incidunt ut labore et dolore magna aliqua.
    Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquid ex ea commodi consequat. Quis aute iure reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
    Excepteur sint obcaecat cupiditat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
  </textarea>
						</div>
						<div role="tabpanel" class="tab-pane" id="contact">...</div>
						<div role="tabpanel" class="tab-pane" id="settings">...</div>
					</div>

				</div>
			</div>
	</div>
	@include('layouts.partial.menumemberfooter')
@stop