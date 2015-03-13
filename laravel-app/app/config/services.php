<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Third Party Services
	|--------------------------------------------------------------------------
	|
	| This file is for storing the credentials for third party services such
	| as Stripe, Mailgun, Mandrill, and others. This file provides a sane
	| default location for this type of information, allowing packages
	| to have a conventional place to find your various credentials.
	|
	*/

	'mailgun' => array(
		'domain' => 'chumneang.luncist@gmail.com',
		'secret' => '13Cambodia@13',
	),

	'mandrill' => array(
		'secret' => '13Cambodia@13',
	),

	'stripe' => array(
		'model'  => 'User',
		'secret' => '',
	),

);
