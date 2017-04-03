<?php namespace IntegrityInvoice\Services\Expense;

use IntegrityInvoice\Services\Validation\ExpenseValidator as Validator;

class Updater {
	
	protected $validator;
	protected $listener;
	protected $expense;
	
	public function __construct($expense, $listener)
    {
    	$this->validator = new Validator;
		$this->listener = $listener;
		$this->expense = $expense;
	}
	
	public function update($id, $input)
	{
		 
		if(! $this->validator->validate($input))
		{
			return $this->listener->expenseUpdateFails($id, $this->validator->errors());
		} 
			
		$this->expense->update($this->listener->tenantID, $id, $input);
		return $this->listener->expenseUpdateSucceeds($id);
	}
	
	
	public function update_no_redirect($id, $input)
	{  	
		return $this->expense->update($this->listener->tenantID, $id, $input);
	}

    public function updateAfterRecurring($tenantID, $id, $input)
    {
        return $this->expense->update($tenantID, $id, $input);
    }




 
}
