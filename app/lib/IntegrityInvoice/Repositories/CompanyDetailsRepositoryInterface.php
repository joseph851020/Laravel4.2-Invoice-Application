<?php namespace IntegrityInvoice\Repositories;

interface CompanyDetailsRepositoryInterface{
	
	public function getAll($tenantID, $perPage);
	
	public function find($tenantID);
	
	public function create($input);
	
	public function update($tenantID, $input);
	
	public function remove($tenantID);

}
