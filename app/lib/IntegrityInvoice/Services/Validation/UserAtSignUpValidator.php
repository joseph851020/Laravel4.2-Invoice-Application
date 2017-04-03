<?php namespace IntegrityInvoice\Services\Validation;

class UserAtSignupValidator extends Validator{
	
		static $rules = array(
			'tenantID' => 'required',
			'email' => 'required|email',
			'password' => 'required|min:6'
		);
	
}
