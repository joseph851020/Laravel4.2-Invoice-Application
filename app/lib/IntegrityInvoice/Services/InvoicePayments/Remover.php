<?php namespace IntegrityInvoice\Services\InvoicePayments;

class Remover {
 
	protected $listener;
	protected $invoicePayments;
	
	public function __construct($invoicePayments, $listener)
    {
		$this->listener = $listener;
		$this->invoicePayments = $invoicePayments;
	}
	
	public function remove($tenant_invoice_id, $id)
	{
		
		if(! is_numeric($id))
		{
			return $this->listener->invoicePaymentsDeletionFails();	 
		}
		
		$affectedRows = $this->invoicePayments->remove($this->listener->tenantID, $tenant_invoice_id,  $id);
		
		if(!is_numeric($affectedRows) || $affectedRows < 1){
			return $this->listener->invoicePaymentsDeletionFails($tenant_invoice_id);
		}
		
		// Note: May ExpenseEvent::create later 		
		return $this->listener->invoicePaymentsDeletionSucceeds($tenant_invoice_id);
  
	}
	
	public function removeAll()
	{
		$this->invoicePayments->removeAll($this->listener->tenantID);
	}
	
	public function removeAllWithInvoice($tenant_invoice_id)
	{
		$this->invoicePayments->removeAllWithInvoice($this->listener->tenantID, $tenant_invoice_id);
	}
	
}