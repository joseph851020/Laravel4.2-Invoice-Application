<?php namespace IntegrityInvoice\Services\Validation;

class ClientValidator extends Validator{
	
		static $rules = array(
			'company' => 'required',			 
			'firstname' => 'required',			
			'email' => 'required|email'
		);
	
}