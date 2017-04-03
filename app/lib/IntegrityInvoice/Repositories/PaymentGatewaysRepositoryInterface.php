<?php namespace IntegrityInvoice\Repositories;

interface PaymentGatewaysRepositoryInterface{
 
	
	public function find($tenantID);
	
	public function create($input);
	
	public function update($tenantID, $input);
	
	public function remove($tenantID);
 
}
