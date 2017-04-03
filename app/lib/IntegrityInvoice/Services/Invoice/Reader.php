<?php namespace IntegrityInvoice\Services\Invoice;

class Reader {
	
	/**
	 *  @var IntegrityInvoice\Repositories\DbInvoiceRepository
	 */
	
	protected $listener;
	protected $invoice;
 	
	public function __construct($invoice, $listener)
    {
    	$this->listener = $listener;
		$this->invoice = $invoice;
	}
	
	public function read($tenant_invoice_id)
	{ 
		if($tenant_invoice_id == "" || $tenant_invoice_id ==  NULL){
			return Redirect::to('invoices')->with('failed_flash_message', 'Invalid Invoice ID');
		}
		
		return $this->invoice->find($this->invoice->tenantID, $tenant_invoice_id);
	}
	
	public function public_read($tenantID, $tenant_invoice_id)
	{ 
		if($tenant_invoice_id == "" || $tenant_invoice_id ==  NULL){
			return Redirect::to('invoices')->with('failed_flash_message', 'Invalid Invoice ID');
		}
		
		return $this->invoice->find($tenantID, $tenant_invoice_id);
	}
	
	
	public function readAll($searchquery = "")
	{
		return $this->invoice->getAll($searchquery, $this->listener->perPage);
	}
	
}