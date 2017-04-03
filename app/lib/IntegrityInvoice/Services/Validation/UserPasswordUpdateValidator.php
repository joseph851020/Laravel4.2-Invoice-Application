<?php namespace IntegrityInvoice\Services\Validation;

class UserPasswordUpdateValidator extends Validator{
	
		static $rules = array(
			'password' => 'required|min:6',
			'confirm_password' => 'required|same:password'
		);
	
}
