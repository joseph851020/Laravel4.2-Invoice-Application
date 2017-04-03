<?php namespace IntegrityInvoice\Services\Validation;

class PreferenceValidator extends Validator{
	
		static $rules = array(
			'date_format' => 'required'
		);
	
}
