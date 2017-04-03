<?php namespace IntegrityInvoice\Services\InvoicePayments;
 
class Updater {
	
	private $listener;
 	private $invoicePayment;
 
	public function __construct($invoicePayment, $listener)
    {
		$this->listener = $listener;
		$this->invoicePayment = $invoicePayment;
	}
	
	public function update($tenantID, $tenant_invoice_id, $input)
	{
		 	
		$invoice = $this->invoicePayment->update($tenantID, $tenant_invoice_id, $input);
		return $this->listener->invoicePaymentUpdateSucceeds($tenant_invoice_id);
		
	}
  
	public function update_single($tenantID, $tenant_invoice_id, $payment_id, $input)
	{		 	
		$invoicePayment = $this->invoicePayment->update_single($tenantID, $tenant_invoice_id, $payment_id, $input);
		return $payment_id;		
	}
  
}
 
