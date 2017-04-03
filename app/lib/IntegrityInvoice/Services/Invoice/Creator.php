<?php namespace IntegrityInvoice\Services\Invoice;

use IntegrityInvoice\Services\Validation\InvoiceValidator as Validator;

class Creator {
	
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
	
	public function create($input, $redirect = TRUE)
	{
		
		if(! $this->validator->validate($input))
		{
			return $this->listener->invoiceCreationFails($this->validator->errors());
	 
		} 
			
		$invoice = $this->invoice->create($input);
		 
		if($redirect == TRUE || $redirect == NULL)
		{
		    return $this->listener->invoiceCreationSucceeds($invoice);			
		}
  
	}


    public function auto_create($input)
    {
        return $this->invoice->create($input);
    }

}