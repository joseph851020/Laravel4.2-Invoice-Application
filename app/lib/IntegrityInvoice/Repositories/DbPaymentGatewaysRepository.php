<?php namespace IntegrityInvoice\Repositories;

use IntegrityInvoice\Repositories\PaymentGatewaysRepositoryInterface;
use PaymentGateway;

class DbPaymentGatewaysRepository implements PaymentGatewaysRepositoryInterface{
		
	public function find($tenantID ="")
	{
		return PaymentGateway::where('tenantID','=', $tenantID)->first();
	}
	
	
	public function create($input = array())
	{
		return PaymentGateway::create($input);
	}
	
	public function update($tenantID ="", $input = array())
	{
		return PaymentGateway::where('tenantID', '=', $tenantID)->update($input);	
	}
	
	
	public function remove($tenantID ="")
	{
		return PaymentGateway::where('tenantID', '=', $tenantID)->delete();
	}

 
}
