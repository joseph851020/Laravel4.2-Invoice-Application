<?php namespace IntegrityInvoice\Services\PaymentGateway;
use IntegrityInvoice\Services\Validation\PaymentGatewayValidator as Validator;

// use PaymentGateway;

class Creator {
	
	private $listener;
	protected $validator;
	protected $paymentgateway;
 
	public function __construct($paymentgateway, $listener)
    {
    	$this->validator = new Validator;
		$this->listener = $listener;
		$this->paymentgateway = $paymentgateway;
	}
	
	public function create($input)
	{
		/*
		if(! $this->validator->validate($input))
		{
			return $this->listener->paymentGatewayCreationFails($this->validator->errors()); 
		}
		*/
			
		$paymentGateway = $this->paymentgateway->create($input);		
		// Note: May ClientEvent::create later 
		
		return $this->listener->paymentGatewayCreationSucceeds();
		
	}
	
}