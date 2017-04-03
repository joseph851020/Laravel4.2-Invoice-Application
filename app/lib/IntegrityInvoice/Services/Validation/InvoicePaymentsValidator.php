<?php namespace IntegrityInvoice\Services\Validation;

class InvoicePaymentsValidator extends Validator{
	
		static $rules = array(			
			'amount' => 'required',
			'tenant_invoice_id' => 'required',
			'user_id' => 'required',		
			'tenantID' => 'required',
			'date' => 'required'
		);
	
}