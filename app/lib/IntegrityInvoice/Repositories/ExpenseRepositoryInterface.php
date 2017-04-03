<?php namespace IntegrityInvoice\Repositories;

interface ExpenseRepositoryInterface{
	
	public function getAll($tenantID, $perPage);
	
	public function find($tenantID, $id);
	
	public function create($input);
	
	public function update($tenantID, $id, $input);
	
	public function remove($tenantID, $id);
	
	public function removeAll($tenantID ="");

    public function getExpensesRecurringToday();
}
