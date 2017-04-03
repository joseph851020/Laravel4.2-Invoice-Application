<?php namespace IntegrityInvoice\Repositories;

interface ItemRepositoryInterface{
	
	public function getAll($tenantID, $itemType, $searchquery, $perPage);
	
	public function find($tenantID, $id);
	
	public function create($input);
	
	public function update($tenantID, $id, $input);
	
	public function remove($tenantID, $id);
	
	public function removeAll($tenantID ="");
}
