<?php namespace IntegrityInvoice\Services\CurrencyRate;

use IntegrityInvoice\Services\Validation\CurrencyRateValidator as Validator;

class Updater {
	
	protected $validator;
	protected $listener;
	protected $currencyRate;
	
	public function __construct($currencyRate, $listener)
    {
    	$this->validator = new Validator;
		$this->listener = $listener;
		$this->currencyRate = $currencyRate;
	}
	
	public function update($id, $input)
	{
		 
		if(! $this->validator->validate($input))
		{
			return $this->listener->currencyRateUpdateFails($id, $this->validator->errors());
	 
		} 
			
		$this->currencyRate->update($this->listener->tenantID, $id, $input);
				
		return $this->listener->currencyRateUpdateSucceeds($id);
  
	}
	
}
