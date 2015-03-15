@section('title', 'List Member')
@section('content')
	<?php
	$baseUrl = URL::to('/');
	?>
	@include('layouts.partial.menumember')
	profile
	@include('layouts.partial.menumemberfooter')
@stop