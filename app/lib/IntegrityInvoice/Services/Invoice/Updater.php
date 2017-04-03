<?php namespace IntegrityInvoice\Services\Invoice;
 
class Updater {
	
	private $listener;
 	private $invoice;
 
	public function __construct($invoice, $listener)
    {
		$this->listener = $listener;
		$this->invoice = $invoice;
	}
	
	public function update($tenantID, $tenant_invoice_id, $input)
	{
		 	
		$invoice = $this->invoice->update($tenantID, $tenant_invoice_id, $input);
		return $this->listener->invoiceUpdateSucceeds($tenant_invoice_id);
		
	}
	
	public function update_quote($tenantID, $tenant_quote_id, $input)
	{
		 	
		$invoice = $this->invoice->update_quote($tenantID, $tenant_quote_id, $input);
		return $this->listener->quoteUpdateSucceeds($tenant_quote_id);
		
	}
	
	public function update_no_redirect($tenantID, $tenant_invoice_id, $input)
	{ 
		$invoice = $this->invoice->update($tenantID, $tenant_invoice_id, $input);
		return $tenant_invoice_id;		
	}
  
}
 
