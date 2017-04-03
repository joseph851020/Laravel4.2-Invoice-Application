<?php namespace IntegrityInvoice\Services\Preference;
use IntegrityInvoice\Services\Validation\PreferenceValidator as Validator;
 
class Updater {
	
	protected $validator;
	protected $listener;
	protected $preference;
 
	public function __construct($preference, $listener)
    {
    	$this->validator = new Validator;
		$this->listener = $listener;
		$this->preference = $preference;
	}
	
	public function update($input)
	{
		if(! $this->validator->validate($input))
		{
			return $this->listener->preferenceUpdateFails($this->validator->errors());
	 
		} 
			
		$this->preference->update($this->listener->tenantID, $input);
		
		return $this->listener->preferenceUpdateSucceeds($this->listener->tenantID);
	}
	
	
	public function update_template($input)
	{  	
		return $this->preference->update($this->listener->tenantID, $input);
	}
	
	public function invoice_settings($input, $type)
	{
		$this->preference->update($this->listener->tenantID, $input);
		return $this->listener->invoiceSettingsSucceeds($type);
	}
 
}