<?php namespace IntegrityInvoice\Repositories;

interface PreferenceRepositoryInterface{
	
	public function find($tenantID);
	
	public function create($input);
	
	public function update($tenantID, $input);
	
	public function remove($tenantID);
	
}
