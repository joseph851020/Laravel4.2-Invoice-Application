<?php namespace IntegrityInvoice\Services\Tenant;
use IntegrityInvoice\Services\Validation\TenantValidator as Validator;
 
class Updater {
	
	protected $validator;
	protected $listener;
	protected $tenant;
 
	public function __construct($tenant, $listener)
    {
    	$this->validator = new Validator;
		$this->listener = $listener;
		$this->tenant = $tenant;
	}
	
	public function update($input)
	{
		if(! $this->validator->validate($input))
		{
			return $this->listener->tenantUpdateFails($this->listener->tenantID, $this->validator->errors());
	 
		} 
			
		$this->tenant->update($this->listener->tenantID, $input);
		
		return $this->listener->tenantUpdateSucceeds($this->listener->tenantID);
	}
	
	public function updateStatus($input)
	{
		return $this->tenant->update($this->listener->tenantID, $input);
	}
	
	
	public function updateStatusFromReferral($input, $tenantID)
	{
		return $this->tenant->update($tenantID, $input);
	}

    public function updateStatusFromExtension($input, $tenantID)
    {
        return $this->tenant->update($tenantID, $input);
    }
	
	
	public function updateStatusFromAdmin($tenantID, $input)
	{
		return $this->tenant->update($tenantID, $input);
	}
 
}