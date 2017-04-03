<?php namespace IntegrityInvoice\Services\CurrencyRate;

use IntegrityInvoice\Services\Validation\CurrencyRateValidator as Validator;

class Creator {
	
	/**
	 *  @var IntegrityInvoice\Repositories\DbCurrencyRateRepository
	 */
	
	protected $validator;
	protected $listener;
	protected $currencyRate;
	
	public function __construct($currencyRate, $listener)
    {
    	$this->validator = new Validator;
		$this->listener = $listener;
		$this->currencyRate = $currencyRate;
	}
	
	public function create($input, $redirect = TRUE)
	{
		 
		if(! $this->validator->validate($input))
		{
			return $this->listener->currencyRateCreationFails($this->validator->errors());
		} 
		
		$this->currencyRate->create($input);
 
		if($redirect == TRUE || $redirect == NULL)
		{
			return $this->listener->currencyRateCreationSucceeds();
		}
  
	}
	
}
