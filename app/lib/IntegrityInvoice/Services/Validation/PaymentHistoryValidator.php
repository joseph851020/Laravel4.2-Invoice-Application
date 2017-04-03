<?php namespace IntegrityInvoice\Services\Validation;

class PaymentHistoryValidator extends Validator{
	
		static $rules = array(
			'tenantID' => 'required',
			'txn_id' => 'required',
			'sender_email' => 'required',			
			'subscription_type' => 'required',
			'amount' => 'required',			
			'payment_system' => 'required',
			'valid_from' => 'required',
			'valid_to' => 'required',
		);
	
}
