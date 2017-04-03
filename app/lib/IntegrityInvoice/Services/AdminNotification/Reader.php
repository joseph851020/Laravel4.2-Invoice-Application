<?php namespace IntegrityInvoice\Services\AdminNotification;

class Reader {
	
	/**
	 *  @var IntegrityInvoice\Repositories\DbItemRepository
	 */
	
	protected $listener;
	protected $notification;
 
 	
	public function __construct($notification, $listener)
    {
    	$this->listener = $listener;
		$this->notification = $notification;
	 
	}
	
	public function read($id)
	{ 
		if(! is_numeric($id))
		return "FAILS";
		
		return $this->notification->find($id);
	}
	
	
	public function readAll($searchquery = "")
	{
		return $this->notification->getAll($searchquery, $this->listener->perPage);
	}
	
}