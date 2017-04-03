<?php namespace IntegrityInvoice\Services\Preference;

class Reader {
	
	/**
	 *  @var IntegrityInvoice\Repositories\DbPreferenceRepository
	 */
	
	protected $listener;
	protected $preference;
 	
	public function __construct($preference, $listener)
    {
    	$this->listener = $listener;
		$this->preference = $preference;
	}
	
	public function read()
	{ 
		return $this->preference->find($this->listener->tenantID);
	}
 
}