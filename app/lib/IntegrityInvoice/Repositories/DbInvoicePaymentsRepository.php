<?php namespace IntegrityInvoice\Repositories;

use IntegrityInvoice\Repositories\InvoicePaymentsRepositoryInterface;
use InvoicePayment;

class DbInvoicePaymentsRepository implements InvoicePaymentsRepositoryInterface{
	
	public function getAll($tenantID = "", $id = 0)
	{	
		return InvoicePayment::where('tenantID','=', $tenantID)->where('tenant_invoice_id','=', $id)->orderBy('tenant_invoice_id','desc')->get();
	}
	
	
	public function find($tenantID ="", $id = 0)
	{
		return InvoicePayment::where('tenantID','=', $tenantID)->where('tenant_invoice_id','=', $id)->first();
	}
	
	
	public function create($input = array())
	{
		return InvoicePayment::create($input);
	}
	
	public function update($tenantID ="", $id = 0, $input = array())
	{
		return InvoicePayment::where('id', '=', $id)->where('tenantID', '=', $tenantID)->update($input);	
	}
	
	
	public function update_single($tenantID="", $tenant_invoice_id="", $payment_id="", $input = array()){
		
		return InvoicePayment::where('tenantID', '=', $tenantID)->where('tenant_invoice_id', '=', $tenant_invoice_id)->where('id', '=', $payment_id)->update($input);
		
	}
	
	
	public function remove($tenantID ="", $tenant_invoice_id, $id = 0)
	{
		return InvoicePayment::where('id', '=', $id)->where('tenantID', '=', $tenantID)->where('tenant_invoice_id', '=', $tenant_invoice_id)->delete();
	}
	
	public function removeAll($tenantID ="")
	{
		return InvoicePayment::where('tenantID', '=', $tenantID)->delete();
	}
	
	public function removeAllWithInvoice($tenantID ="", $tenant_invoice_id)
	{
		return InvoicePayment::where('tenantID', '=', $tenantID)->where('tenant_invoice_id', '=', $tenant_invoice_id)->delete();
	}
	
	public function count($tenantID = "", $tenant_invoice_id = 0)
	{
		return InvoicePayment::where('tenantID','=', $tenantID)->where('tenant_invoice_id','=', $tenant_invoice_id)->count();
	}
	
	public function sum($tenantID = "", $tenant_invoice_id = 0)
	{ 
		return InvoicePayment::where('tenantID','=', $tenantID)->where('tenant_invoice_id','=', $tenant_invoice_id)->sum('amount');
	}
	
	// method added by pc
	public function findByTransaction($txnId) {
		return InvoicePayment::where('online_ref', '=', $txnId)->first();
	}
}
