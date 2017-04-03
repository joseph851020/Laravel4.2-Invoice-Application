<?php namespace IntegrityInvoice\Repositories;

interface InvoicePaymentsRepositoryInterface{
	
	public function getAll($tenantID, $id);
	
	public function find($tenantID, $id);
	
	public function create($input);
	
	public function update($tenantID, $id, $input);
	
	public function update_single($tenantID, $tenant_invoice_id, $payment_id, $input);
	
	public function remove($tenantID, $tenant_invoice_id, $id);
	
	public function removeAll($tenantID);
	
	public function removeAllWithInvoice($tenantID, $tenant_invoice_id);
	
	public function count($tenantID = "", $tenant_invoice_id = 0);
	
	public function sum($tenantID = "", $tenant_invoice_id = 0);

	// method added by pc
	public function findByTransaction($txnId);

}
