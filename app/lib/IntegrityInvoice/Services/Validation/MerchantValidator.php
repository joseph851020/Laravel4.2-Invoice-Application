<?php namespace IntegrityInvoice\Services\Validation;

class MerchantValidator extends Validator{
	
		static $rules = array(
			'company' => 'required'
		);
	
}
