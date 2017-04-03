<?php namespace IntegrityInvoice\Services\Expense;

class Remover {
 
	protected $listener;
	protected $expense;
	
	public function __construct($expense, $listener)
    {
		$this->listener = $listener;
		$this->expense = $expense;
	}
	
	public function remove($id)
	{
		
		if(! is_numeric($id))
		{
			return $this->listener->expenseDeletionFails();	 
		}
		
		$affectedRows = $this->expense->remove($this->listener->tenantID, $id);
		
		if(!is_numeric($affectedRows) || $affectedRows < 1){
			return $this->listener->expenseDeletionFails();
		}
		
		// Note: May ExpenseEvent::create later 		
		return $this->listener->expenseDeletionSucceeds();
  
	}
	
	public function removeAll()
	{
		$this->expense->removeAll($this->listener->tenantID);
	}
	
}