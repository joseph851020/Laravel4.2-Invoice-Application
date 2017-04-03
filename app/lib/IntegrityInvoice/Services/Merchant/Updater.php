<?php namespace IntegrityInvoice\Services\Merchant;

use IntegrityInvoice\Services\Validation\MerchantValidator as Validator;
 
class Updater {
	
	private $listener;
	protected $validator;
	protected $merchant;
 
	public function __construct($merchant, $listener)
    {
    	$this->validator = new Validator;
		$this->listener = $listener;
		$this->merchant = $merchant;
	}
	
	public function update($id, $input)
	{
		if(! $this->validator->validate($input))
		{
			return $this->listener->merchantUpdateFails($id, $this->validator->errors());
	 
		} 
			
		$this->merchant->update($this->listener->tenantID, $id, $input);
		
		return $this->listener->merchantUpdateSucceeds($id);
		
	}
	
}
 
