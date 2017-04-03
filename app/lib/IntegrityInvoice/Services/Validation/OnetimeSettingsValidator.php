<?php namespace IntegrityInvoice\Services\Validation;

class OnetimeSettingsValidator extends Validator{
	
		static $rules = array(
			'date_format' => 'required',			 
			'business_model' => 'required',
			'currency_code' => 'required'
		);
	
}
