<?php namespace IntegrityInvoice\Services\Invoice;

use IntegrityInvoice\Services\Validation\InvoiceValidator as Validator;

class QuoteConverter {
	
	/**
	 *  @var IntegrityInvoice\Repositories\DbInvoiceRepository
	 */
	
	protected $validator;
	protected $listener;
	protected $invoice;
	
	public function __construct($invoice, $listener)
    {
    	$this->validator = new Validator;
		$this->listener = $listener;
		$this->invoice = $invoice;
	}
	
	public function convert($id, $input)
	{
		// Update for Succeeds or Fails methods
	   if($this->invoice->convert($this->listener->tenantID, $id, $input))
	   {
	   	  return true; 
	   }
	   else
	   {
		  return false;  
	   }
	   
	}
	
}