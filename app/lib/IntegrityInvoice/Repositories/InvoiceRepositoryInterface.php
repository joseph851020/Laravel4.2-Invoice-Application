<?php namespace IntegrityInvoice\Repositories;

interface InvoiceRepositoryInterface{
	
	public function getAll($tenantID, $perPage);
	
	public function find($tenantID, $tenant_invoice_id);
	
	public function create($input);
	
	public function update($tenantID, $tenant_invoice_id, $input);
	
	public function update_quote($tenantID, $tenant_quote_id, $input);	
	
	public function convert($tenantID, $tenant_quote_id, $input);
	
	public function remove($tenantID, $tenant_invoice_id);
	
	public function removeQuote($tenantID, $tenant_quote_id);
	
	public function removeAll($tenantID ="");

    public function getInvoicesRecurringToday();

    public function getLastInvoiceID($tenantID ="");

    public function getInvoicesGeneratedToDayByRecurring();

    public function getUnsentInvoicesGeneratedToDayByRecurringWithAutoSend();

}
