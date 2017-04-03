<?php namespace IntegrityInvoice\Services\Validation;

class TenantValidator extends Validator{
	
		static $rules = array(
			'tenantID' => 'required',			 
			'email' => 'required|email|unique:users',
			'password' => 'required|min:6'
	 
		);
	
}
