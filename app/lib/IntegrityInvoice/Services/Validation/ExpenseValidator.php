<?php namespace IntegrityInvoice\Services\Validation;

class ExpenseValidator extends Validator{
	
		static $rules = array(
			'amount' => 'required',
			'created_at' => 'required'		
		);
	
}
