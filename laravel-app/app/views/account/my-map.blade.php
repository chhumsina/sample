@section('title', 'List Member')
@section('content')
	<?php
	$baseUrl = URL::to('/');
	?>
	@include('layouts.partial.menumember')
	map
	@include('layouts.partial.menumemberfooter')
@stop