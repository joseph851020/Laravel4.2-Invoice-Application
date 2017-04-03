<?php namespace IntegrityInvoice\Repositories;

use IntegrityInvoice\Repositories\ExpenseRepositoryInterface;
use Expense;

class DbExpenseRepository implements ExpenseRepositoryInterface{
	
	public function getAll($tenantID = "", $perPage = "")
	{
		return Expense::where('tenantID','=', $tenantID)->orderBy('expense_date','desc')->orderBy('created_at','desc')->paginate($perPage);
	}
	
	
	public function find($tenantID ="", $id = 0)
	{
		return Expense::where('tenantID','=', $tenantID)->where('id','=', $id)->first();
	}
	
	
	public function create($input = array())
	{
		return Expense::create($input);
	}
	
	public function update($tenantID ="", $id = 0, $input = array())
	{
		return Expense::where('id', '=', $id)->where('tenantID', '=', $tenantID)->update($input);	
	}
	
	
	public function remove($tenantID ="", $id = 0)
	{
		return Expense::where('id', '=', $id)->where('tenantID', '=', $tenantID)->delete();
	}
	
	public function removeAll($tenantID ="")
	{
		return Expense::where('tenantID', '=', $tenantID)->delete();
	}

    public function getExpensesRecurringToday(){
        $today = strftime("%Y-%m-%d", time());
        return Expense::where('recurring', '=', 1)->where('recur_next_date', '=', $today)->get();
    }
 
}
