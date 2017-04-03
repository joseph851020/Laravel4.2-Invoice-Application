<?php namespace IntegrityInvoice\Services\PaymentHistory;
use IntegrityInvoice\Services\Validation\PaymentHistoryValidator as Validator;

use PaymentHistory;

class Creator {
	
	private $listener;
	protected $validator;
	protected $paymentHistory;
 
	public function __construct($paymentHistory, $listener)
    {
    	$this->validator = new Validator;
		$this->listener = $listener;
		$this->paymentHistory = $paymentHistory;
	}
	
	public function create($input)
	{
		if(! $this->validator->validate($input))
		{
			return $this->listener->paymentHistoryCreationFails($this->validator->errors());
	 
		} 
			
		$paymentHistory = $this->PaymentHistory->create($input);		
		// Note: May ClientEvent::create later 
		
		return $this->listener->paymentHistoryCreationSucceeds();
		
	}
	
	
	
	public function createByCard($input)
	{
		return $this->paymentHistory->create($input);  
	}
	
	public function createByPaypal($input){
		return $this->paymentHistory->create($input);
	}
	
	public function createByReferral($input){
		return $this->paymentHistory->create($input);
	}

    public function createByExtension($input){
        return $this->paymentHistory->create($input);
    }

}