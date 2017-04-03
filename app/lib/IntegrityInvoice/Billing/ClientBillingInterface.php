<?php namespace IntegrityInvoice\Billing;

interface ClientBillingInterface{
	
	public function charge(array $data);
}
