<?php

if (Session::has('msgs')) {
	$msgs= Session::get('msgs');
	foreach ($msgs as $msg) {
		if ($msg['type'] == 'success') {
			echo '<div class="alert alert-success text-center" role="alert">'.$msg['msg'].'</div>';
		} else if ($msg['type'] == 'error') {
			echo '<div class="alert alert-danger text-center" role="alert">'.$msg['msg'].'</div>';
		}
	}
}?>

