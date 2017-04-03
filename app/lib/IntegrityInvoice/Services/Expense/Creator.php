<?php namespace IntegrityInvoice\Services\Expense;

use IntegrityInvoice\Services\Validation\ExpenseValidator as Validator;

class Creator {
	
	/**
	 *  @var IntegrityInvoice\Repositories\DbExpenseRepository
	 */
	
	protected $validator;
	protected $listener;
	protected $expense;
	
	public function __construct($expense, $listener)
    {
    	$this->validator = new Validator;
		$this->listener = $listener;
		$this->expense = $expense;
	}
	
	public function create($input, $redirect = TRUE)
	{
		 
		if(! $this->validator->validate($input))
		{
			return $this->listener->expenseCreationFails($this->validator->errors());
		} 
		
		$expense = $this->expense->create($input);
 
		if($redirect == TRUE || $redirect == NULL)
		{
			return $this->listener->expenseCreationSucceeds($expense);
		}
  
	}

    public function auto_create($input)
    {
        return $this->expense->create($input);
    }
	
}
