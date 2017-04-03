<?php namespace IntegrityInvoice\Repositories;

interface TenantRepositoryInterface{
	
	public function getAll($searchquery, $perPage);
	
	public function find($tenantID);
	
	public function create($input);
	
	public function update($tenantID, $input);
	
	public function remove($tenantID);
	
	public function verification($tenantID);
	
	public function count($searchquery);
	
	public function isActive($tenantID);
	
	public function checkReferral($code = "");
}
