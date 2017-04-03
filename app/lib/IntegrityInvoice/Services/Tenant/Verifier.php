<?php namespace IntegrityInvoice\Services\Tenant;

use Tenant;

class Verifier {
	
	protected $verifystring;
	protected $plan;
	protected $listener;
	private $tenantID;
	
	public function __construct($listener)
    {
		$this->listener = $listener;
	}
	
	public function verify($input)
	{
		if(is_null($input) || !is_array($input) || empty($input) )
		{
			return $this->listener->tenantVerificationFails(array('token' => 'Incorrect / expired parameter given'));
		}
		
		$this->verifystring = $input['verifystring'];
		$this->plan = $input['plan'];
	 		
		$this->tenantID = Tenant::where('activation_key', '=', $this->verifystring)->pluck('tenantID');
		
		if(!is_null($this->tenantID) && $this->tenantID != "")
		{
			if(Tenant::where('tenantID', '=', $this->tenantID)->update(array('activation_key'=>'','verified'=>1, 'status' => 1)))
			{
				// Determine where to go depending on selected plan
				if($this->plan == 1 || $this->plan == 0){
					return $this->listener->tenantVerificationSucceeds();		 
				}
				else
				{
					// redirect('subscriptions/cart');
					return $this->listener->tenantVerificationSucceedsToUpgrade();
				}  
				
			} 

		}
		else
		{
			return $this->listener->tenantAlreadyVerified();			
		}
  
	}
	
}