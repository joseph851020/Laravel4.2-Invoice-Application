<?php namespace IntegrityInvoice\Services\CurrencyRate;

class Reader {
	
	/**
	 *  @var IntegrityInvoice\Repositories\DbExpenseRepository
	 */
	
	protected $listener;
	protected $currencyRate;
 	
	public function __construct($currencyRate, $listener)
    {
    	$this->listener = $listener;
		$this->currencyRate = $currencyRate;
	}
	
	public function read($id)
	{ 
		if(! is_numeric($id))
		return "FAILS";
		
		return $this->currencyRate->find($this->listener->tenantID, $id);
	}
	
	
	public function readAll()
	{
		return $this->currencyRate->getAll($this->listener->tenantID, $this->listener->perPage);
	}
	
}