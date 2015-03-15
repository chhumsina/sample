<!-- Stored in app/views/layouts/master.blade.php -->
<!DOCTYPE html>
<html lang="en">
@include('layouts.partial.head')
<body class="back">
<?php
$baseUrl = URL::to('/');
?>
  	@include('layouts.partial.header')
	<section>
		<div class="container">
			<div class="row">
				@include('layouts.partial.side-bar')
				@include('layouts.partial.content')
			</div>
		</div>
	</section>
  	@include('layouts.partial.footer')
</body>
</html>