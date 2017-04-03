<?php namespace IntegrityInvoice\Services\Validation;

class ItemValidator extends Validator{
	
		static $rules = array(
			'item_name' => 'required',
			'item_type' => 'required',
			'unit_price' => 'required'
		);
	
}
