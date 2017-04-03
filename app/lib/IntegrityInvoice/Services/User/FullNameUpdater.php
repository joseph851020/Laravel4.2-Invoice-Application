<?php namespace IntegrityInvoice\Services\User;
 
class FullNameUpdater {
	
	private $listener;	 
	protected $user;
 
	public function __construct($user, $listener)
    { 
		$this->listener = $listener;
		$this->user = $user;
	}
 
	
	public function update($id, $input)
	{
		if(!$input)
		{
			return false;
		} 
			
		return $this->user->update($this->listener->tenantID, $id, $input);
		
	}
 
}
 
