@section('title', 'Payout')
@section('content')
	<script>

		$(document).ready(function(){});
	</script>
	<?php
	$baseUrl = URL::to('/');
	?>

	<h4>Payout</h4>
@include('layouts.partial.render-message')
<div class="row form">
	<div class="col-md-12">

	</div>
</div>
<di class="row">
	@if (isset($item) && Input::has('payout'))

	@endif
</di>

@stop