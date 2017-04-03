<?php namespace IntegrityInvoice\Services\Preference;
use IntegrityInvoice\Services\Validation\PreferenceValidator as Validator;

use Preference;

class Creator {
	
	protected $validator;
	protected $listener;
 
	public function __construct($listener)
    {
    	$this->validator = new Validator;
		$this->listener = $listener;
	}
	
	public function create($input)
	{
		if(! $this->validator->validate($input))
		{
			return $this->listener->preferenceCreationFails($this->validator->errors());
	 
		} 
			
		Preference::create($input);		
		// Note: May ClientEvent::create later 
		
		return $this->listener->preferenceCreationSucceeds();
		
	}
	
}