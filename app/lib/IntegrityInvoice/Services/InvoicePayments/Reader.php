<?php namespace IntegrityInvoice\Services\InvoicePayments;

class Reader {
 
	protected $listener;
	protected $invoice_payment;
 	
	public function __construct($invoice_payment, $listener)
    {
    	$this->listener = $listener;
		$this->invoice_payment = $invoice_payment;
	}
	
	public function read($id)
	{ 
		if(! is_numeric($id))
		return "FAILS";
		
		return $this->invoice_payment->find($this->listener->tenantID, $id);
	}
	
	
	public function getAll($id)
	{
		return $this->invoice_payment->getAll($this->listener->tenantID, $id);
	}

	// method added by pc

	public function transactionExists($txnID) {
		if($this->invoice_payment->findByTransaction($txnID)) {
			return true;
		}
		return false;
	}
	
}