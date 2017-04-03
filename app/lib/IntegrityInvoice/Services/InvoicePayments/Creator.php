<?php namespace IntegrityInvoice\Services\InvoicePayments;

use IntegrityInvoice\Services\Validation\InvoicePaymentsValidator as Validator;

class Creator {
	
	/**
	 *  @var IntegrityInvoice\Repositories\DbInvoiceRepository
	 */
	
	protected $validator;
	protected $listener;
	protected $invoicePayments;
	
	public function __construct($invoicePayments, $listener)
    {
    	$this->validator = new Validator;
		$this->listener = $listener;
		$this->invoice = $invoicePayments;
	}
	
	public function create($input, $redirect = TRUE)
	{
		
		if(! $this->validator->validate($input))
		{
			return $this->listener->invoicePaymentsCreationFails($this->validator->errors());
	 
		} 
			
		$invoicePayments = $this->invoice->create($input);
		
		if($redirect == TRUE || $redirect == NULL)
		{
			return $this->listener->invoicePaymentsCreationSucceeds();
		}
  
	}

	// methods added by pc
	public function createByCard($input)
	{
		return $this->invoice->create($input);  
	}
	
	public function createByPaypal($input){
		return $this->invoice->create($input);
	}

	
}