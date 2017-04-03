<?php namespace IntegrityInvoice\Services\Validation;

class CurrencyRateValidator extends Validator{
	
		static $rules = array(
			'unit_exchange_rate' => 'required'
		);
	
}
