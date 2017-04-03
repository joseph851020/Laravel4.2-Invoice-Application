<?php namespace IntegrityInvoice\Services\Item;

class Reader {
	
	/**
	 *  @var IntegrityInvoice\Repositories\DbItemRepository
	 */
	
	protected $listener;
	protected $item;
	protected $itemType;
 	
	public function __construct($item, $listener, $itemType)
    {
    	$this->listener = $listener;
		$this->item = $item;
		$this->itemType = $itemType;
	}
	
	public function read($id)
	{ 
		if(! is_numeric($id))
		return "FAILS";
		
		return $this->item->find($this->listener->tenantID, $id);
	}
	
	
	public function readAll($searchquery = "")
	{
		return $this->item->getAll($this->listener->tenantID, $this->itemType, $searchquery, $this->listener->perPage);
	}
	
}