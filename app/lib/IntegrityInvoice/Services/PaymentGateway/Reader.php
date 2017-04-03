<?php namespace IntegrityInvoice\Services\PaymentGateway;

class Reader {
	
	/**
	 *  @var IntegrityInvoice\Repositories\DbItemRepository
	 */
	
	protected $listener;
	protected $paymentgateway;
 	
	public function __construct($paymentgateway, $listener)
    {
    	$this->listener = $listener;
		$this->paymentgateway = $paymentgateway;
	}
	
	public function read()
	{ 
		if($this->listener->tenantID == "" || is_null($this->listener->tenantID))
		return "FAILS";
		
		return $this->paymentgateway->find($this->listener->tenantID);
	}
	
}