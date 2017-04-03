<?php namespace IntegrityInvoice\Services\Validation;

class PaymentGatewayValidatorForCard extends Validator{
	
	static $rules = array(
		'stripe_secret_key' => 'required|min:3',
		'stripe_publishable_key' => 'required|min:3'		 
	);
}
