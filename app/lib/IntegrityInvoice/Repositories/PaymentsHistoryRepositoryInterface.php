<?php namespace IntegrityInvoice\Repositories;

interface PaymentsHistoryRepositoryInterface{
	
	public function getAll($tenantID, $perPage);
	
	public function find($tenantID, $id);
	
	public function findByTransaction($txnID);
	
	public function findFirst($tenantID);
	
	public function create($input);
	
	public function update($tenantID, $input);
	
	public function count($tenantID);
	
	public function remove($tenantID);
	
	public function get_subscription($tenantID);
	
	public function get_plan_id($tenantID);
	
	public function get_plan($id);
	
	public function validateSubscription($tenantID);

}
