<?php namespace IntegrityInvoice\Services\Validation;

class CancelQueueValidator extends Validator{
	
		static $rules = array(
			'tenantID' => 'required',			
			'email' => 'required|email|unique:cancel_queue'
		);
	
}