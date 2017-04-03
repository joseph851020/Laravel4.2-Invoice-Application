<?php 

class ExpenseCategory extends Eloquent{
	
	protected $guarded = array('id');
	// If the table is not named as plural
	protected $table = 'expense_categories';

	 
	public static function getAll(){
		return ExpenseCategory::orderBy('expense_name', 'asc')->get();
	}
	
}


