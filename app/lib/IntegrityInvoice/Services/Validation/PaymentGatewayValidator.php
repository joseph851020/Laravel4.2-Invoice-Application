<?php namespace IntegrityInvoice\Services\Validation;

class PaymentGatewayValidator extends Validator{
	
		static $rules = array(
			'paypal_email' => 'required|email'		 
		);
	
}
