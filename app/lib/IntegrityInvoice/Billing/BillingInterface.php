<?php namespace IntegrityInvoice\Billing;

interface BillingInterface{
	
	public function charge(array $data);
}
