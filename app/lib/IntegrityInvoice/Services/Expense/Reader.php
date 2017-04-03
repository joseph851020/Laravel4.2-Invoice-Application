<?php namespace IntegrityInvoice\Services\Expense;

class Reader {
	
	/**
	 *  @var IntegrityInvoice\Repositories\DbExpenseRepository
	 */
	
	protected $listener;
	protected $expense;
 	
	public function __construct($expense, $listener)
    {
    	$this->listener = $listener;
		$this->expense = $expense;
	}
	
	public function read($id)
	{ 
		if(! is_numeric($id))
		return "FAILS";
		
		return $this->expense->find($this->listener->tenantID, $id);
	}
	
	
	public function readAll()
	{
		return $this->expense->getAll($this->listener->tenantID, $this->listener->perPage);
	}
	
}