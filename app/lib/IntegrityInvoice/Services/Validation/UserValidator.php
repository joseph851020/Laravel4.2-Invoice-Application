<?php namespace IntegrityInvoice\Services\Validation;

class UserValidator extends Validator{
	
		static $rules = array(
			'tenantID' => 'required',
			'firstname' => 'required',			 
			'email' => 'required|email|unique:users',
			'password' => 'required|min:6',
			'confirm_password' => 'required|same:password'
		);
	
}
