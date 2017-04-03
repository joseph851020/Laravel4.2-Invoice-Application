<?php namespace IntegrityInvoice\Repositories;

use IntegrityInvoice\Repositories\CancelQueueRepositoryInterface;
use CancelQueue;

class DbCancelQueueRepository implements CancelQueueRepositoryInterface{
 
	public function find($tenantID ="")
	{
		return CancelQueue::where('tenantID','=', $tenantID)->first();
	}
	
	
	public function create($input = array())
	{
		return CancelQueue::create($input);
	}
	
	public function update($tenantID ="", $input = array())
	{
		return CancelQueue::where('tenantID', '=', $tenantID)->update($input);	
	}
	
	
	public function remove($tenantID ="")
	{
		return CancelQueue::where('tenantID', '=', $tenantID)->delete();
	}
	
}
