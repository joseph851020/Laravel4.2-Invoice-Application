<?php namespace IntegrityInvoice\Services\expense;
use IntegrityInvoice\Utilities\AppHelper as Apphelper;
use Illuminate\Filesystem;
use Expense;

class BulkRemover {
 
	protected $listener;
	protected $expense;
	
	public function __construct($expense, $listener)
    {
		$this->listener = $listener;
		$this->expense = $expense;
	}
	
	public function remove($input)
	{
			
		$count = count($input);
		
		if($count == 0 || is_null($count))
		{
			return $this->listener->expenseDeletionFails();
		}
		
		for($i=0; $i<$count; $i++)
		{
			$id = $input[$i];
			 
			$expense = Expense::where('tenantID', '=', $this->listener->tenantID)->where('id', '=', $id)->first(); 
		 
			if($expense->file != NULL && $expense->file != ""){
				// Delete file
				$pathToFile = base_path().'/te_da/'.$expense->tenantID.'/attachments/expenses/'. Apphelper::decrypt($expense->file, $expense->tenantID);
				
				if(file_exists($pathToFile)){
					\File::delete($pathToFile);
				 } 
			} 
			 
			$affectedRows = $this->expense->remove($this->listener->tenantID, $id);	
		}
		
		if(!is_numeric($affectedRows) || $affectedRows < 1)
		{
			return $this->listener->expenseBulkDeletionFails();
		}
			
		return $this->listener->expenseBulkDeletionSucceeds();
  
	}
	
}