<?php namespace IntegrityInvoice\Repositories;

interface AdminNotificationRepositoryInterface{
	
	public function getAll($searchquery, $perPage = "");
	
	public function find($id);
	
	public function create($input);
	
	public function update($id, $input);
	
	public function count($searchquery);
	
	public function remove($id);
 
}
