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
		'domain' => 'app.sighted.com',
		'secret' => 'key-eb8f0fc119b91f560e8af22592933257',
	),

	'mandrill' => array(
		'secret' => 'p1qQj8n75CtJj3qeKP2wfg',
	),

	'stripe' => array(
		'model'  => 'User',
		'secret' => '',
	),

);
