<?php namespace IntegrityInvoice\Services\PaymentHistory;

class Reader {
	
	/**
	 *  @var IntegrityInvoice\Repositories\DbItemRepository
	 */
	
	protected $listener;
	protected $paymenthistory;
 	
	public function __construct($paymenthistory, $listener)
    {
    	$this->listener = $listener;
		$this->paymenthistory = $paymenthistory;
	}
	
	public function read()
	{ 
		if($this->listener->tenantID == "" || is_null($this->listener->tenantID))
		return "FAILS";
		
		return $this->paymenthistory->find($this->listener->tenantID);
	}
	
	
	public function transactionExists($txn_id)
	{
		if($this->paymenthistory->findByTransaction($txn_id)){
			return true;
		}
		 
		 return false;
	}
	
}