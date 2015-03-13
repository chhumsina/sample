<?php

if (Session::has('msgs')) {
	$msgs= Session::get('msgs');
	foreach ($msgs as $msg) {
		if ($msg['type'] == 'success') {
			echo '<div class="alert alert-success" role="alert">'.$msg['msg'].'</div>';
		} else if ($msg['type'] == 'error') {
			echo '<div class="alert alert-danger" role="alert">'.$msg['msg'].'</div>';
		}
	}
}?>

