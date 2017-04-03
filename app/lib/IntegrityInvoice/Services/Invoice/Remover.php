<?php namespace IntegrityInvoice\Services\Invoice;

class Remover {
 
	protected $listener;
	protected $invoice;
	
	public function __construct($invoice, $listener)
    {
		$this->listener = $listener;
		$this->invoice = $invoice;
	}
	
	public function remove($id)
	{
		
		if(! is_numeric($id))
		{
			return $this->listener->invoiceDeletionFails();	 
		}
		
		$affectedRows = $this->invoice->remove($this->listener->tenantID, $id);
		
		if(!is_numeric($affectedRows) || $affectedRows < 1){
			return $this->listener->invoiceDeletionFails();
		}
	 		
		return $this->listener->invoiceDeletionSucceeds();
  
	}
	
	
	public function removeQuote($id)
	{
		
		if(! is_numeric($id))
		{
			return $this->listener->quoteDeletionFails();	 
		}
		
		$affectedRows = $this->invoice->removeQuote($this->listener->tenantID, $id);
		
		if(!is_numeric($affectedRows) || $affectedRows < 1){
			return $this->listener->quoteDeletionFails();
		}
	 
		return $this->listener->quoteDeletionSucceeds();  
	}
 
	
	public function removeAll()
	{
		$this->invoice->removeAll($this->listener->tenantID);
	}
	
}