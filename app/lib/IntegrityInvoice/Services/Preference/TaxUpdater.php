<?php namespace IntegrityInvoice\Services\Preference;

class TaxUpdater {
 
	protected $listener;
	protected $preference;
 
	public function __construct($preference, $listener)
    {
		$this->listener = $listener;
		$this->preference = $preference;
	}
	
	public function update($input)
	{  	
		if($this->preference->update($this->listener->tenantID, $input))
		{
			return $this->listener->taxUpdateSucceeds($this->listener->tenantID);
		}
		else
		{
			return $this->listener->taxUpdateFails();
		}
	}
 
}