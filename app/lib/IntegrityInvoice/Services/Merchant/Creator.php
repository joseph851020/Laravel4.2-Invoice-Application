<?php namespace IntegrityInvoice\Services\Merchant;

use IntegrityInvoice\Services\Validation\MerchantValidator as Validator;

class Creator {
	
	/**
	 *  @var IntegrityInvoice\Repositories\DbMerchantRepository
	 */
	
	protected $validator;
	protected $listener;
	protected $merchant;
	
	public function __construct($merchant, $listener)
    {
    	$this->validator = new Validator;
		$this->listener = $listener;
		$this->merchant = $merchant;
	}
	
	public function create($input, $redirect = TRUE)
	{
		
		if(! $this->validator->validate($input))
		{
			return $this->listener->merchantCreationFails($this->validator->errors());
	 
		} 
		
		$merchant = $this->merchant->create($input);
		
		if($redirect == TRUE || $redirect == NULL)
		{
			return $this->listener->merchantCreationSucceeds();
		}
  
	}
	
}