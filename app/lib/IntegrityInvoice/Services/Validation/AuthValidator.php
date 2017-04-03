<?php namespace IntegrityInvoice\Services\Validation;

class AuthValidator extends Validator{
	
		static $rules = array(
			'email' => 'required|email',
			'password' => 'required|min:6',
			'tenantID' => 'required',
		);
	
}
