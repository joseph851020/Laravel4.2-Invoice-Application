<?php namespace IntegrityInvoice\Services\AdminNotification;

class Creator {
 
	protected $listener;
	protected $notification;
	
	public function __construct($notification, $listener)
    {
    	 
		$this->listener = $listener;
		$this->notification = $notification;
	}
	
	public function create($input, $redirect = TRUE)
	{
	 
		$this->notification->create($input);

		// Note: May ClientEvent::create later 
		if($redirect == TRUE || $redirect == NULL)
		{
			return $this->listener->notificationCreationSucceeds();
		}
  
	}
	
}
