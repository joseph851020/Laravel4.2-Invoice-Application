<?php namespace IntegrityInvoice\Repositories;

interface AccountPlanRepositoryInterface{
	
	public function getAll();
	
	public function find($id);
	
	public function create($input);
	
	public function update($id, $input);
	
	public function remove($id);
	
	public function getAccountType($id);
	
	public function findByType($accountType);
	
	public function getPlanPrice($plain_id);
}
