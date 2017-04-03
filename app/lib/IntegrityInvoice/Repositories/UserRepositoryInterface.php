<?php namespace IntegrityInvoice\Repositories;

interface UserRepositoryInterface {
	
	public function getAll($tenantID, $searchquery, $perPage);
	
	public function find($tenantID, $id);
	
	public function findSuper($tenantID);
	
	public function create($input);
	
	public function update($tenantID, $id, $input);
	
	public function remove($tenantID, $id);
	
	public function removeAll($tenantID ="");
}
