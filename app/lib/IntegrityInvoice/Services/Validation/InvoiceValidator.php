<?php namespace IntegrityInvoice\Services\Validation;

class InvoiceValidator extends Validator{
	
		static $rules = array(
			'client_name' => 'required',
			'items' => 'required',
			'due_date' => 'required',			
			'balance_due' => 'required',
			'user_id' => 'required',
			'currency_id' => 'required',
			'tenant_invoice_id' => 'required',			
			'tenantID' => 'required'
		);
	
}