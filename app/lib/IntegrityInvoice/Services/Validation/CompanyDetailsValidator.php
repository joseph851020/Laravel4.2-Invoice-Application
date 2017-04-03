<?php namespace IntegrityInvoice\Services\Validation;

class CompanyDetailsValidator extends Validator{
	
	static $rules = array(
		// 'company_name' => 'required',
		'email' => 'required|email'
	);
}
