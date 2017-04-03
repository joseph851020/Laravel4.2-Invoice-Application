<?php namespace IntegrityInvoice\Services\Preference;
 
use IntegrityInvoice\Services\Validation\OnetimeSettingsValidator as Validator;
use Company;

class OnetimeUpdater {
	
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
			return $this->listener->onetimeUpdateFails($this->validator->errors());
	 
		} 
			
		$this->preference->update($this->listener->tenantID, $input);		
		return $this->listener->onetimeUpdateSucceeds($this->listener->tenantID);
	}
	
	public function update_company_details($input)
	{ 	
		return Company::where('tenantID', '=', $this->listener->tenantID)->update($input);
	 
	}
	
	 
	
	public function verify()
	{
		return $this->preference->ontime_setup_verify($this->listener->tenantID);
	}
  
}