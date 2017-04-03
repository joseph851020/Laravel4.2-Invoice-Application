<?php namespace IntegrityInvoice\Services\CurrencyRate;

class Remover {
 
	protected $listener;
	protected $currencyRate;
	
	public function __construct($currencyRate, $listener)
    {
		$this->listener = $listener;
		$this->currencyRate = $currencyRate;
	}
	
	public function remove($currency_code)
	{
		
		if(! is_string($currency_code))
		{
			return $this->listener->currencyRateDeletionFails();	 
		}
		
		$affectedRows = $this->currencyRate->remove($this->listener->tenantID, $currency_code);
	 
		if(!is_numeric($affectedRows) || $affectedRows < 1){
			return $this->listener->currencyRateDeletionFails();
		}
		
		// Note: May ExpenseEvent::create later 		
		return $this->listener->currencyRateDeletionSucceeds();
  
	}
	
	public function removeAll()
	{
		$this->currencyRate->removeAll($this->listener->tenantID);
	}
	
}