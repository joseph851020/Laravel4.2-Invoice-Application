<?php namespace IntegrityInvoice\Repositories;

interface CurrencyRateRepositoryInterface{
	
	public function getAll($tenantID, $perPage);
	
	public function find($tenantID, $id);
	
	public function findByCurrencyCode($tenantID, $code);
	
	public function create($input);
	
	public function update($tenantID, $id, $input);
	
	public function remove($tenantID, $currency_code);
	
	public function removeAll($tenantID ="");
}
