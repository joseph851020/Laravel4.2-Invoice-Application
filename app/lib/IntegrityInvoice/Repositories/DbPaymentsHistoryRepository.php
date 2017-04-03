<?php namespace IntegrityInvoice\Repositories;

use IntegrityInvoice\Repositories\PaymentsHistoryRepositoryInterface;
use PaymentsHistory;
use Tenant;

class DbPaymentsHistoryRepository implements PaymentsHistoryRepositoryInterface{
	
	public function getAll($tenantID = "", $perPage = "")
	{	
		return PaymentsHistory::where('tenantID','=', $tenantID)->orderBy('created_at','desc')->paginate($perPage);
	}
	
	
	public function find($tenantID ="", $id = 0)
	{
		return PaymentsHistory::where('tenantID','=', $tenantID)->where('id','=', $id)->first();
	}
	
	public function findFirst($tenantID = "")
	{
		return PaymentsHistory::where('tenantID','=', $tenantID)->orderBy('created_at', 'desc')->first();
	}
	
	public function findByTransaction($txnID){
		return PaymentsHistory::where('txn_id','=', $txnID)->first();
	}
	
	
	public function create($input = array())
	{
		return PaymentsHistory::create($input);
	}
	
	public function update($tenantID ="", $input = array())
	{
		return PaymentsHistory::where('tenantID', '=', $tenantID)->update($input);	
	}
	
	
	public function count($tenantID ="")
	{
		return PaymentsHistory::where('tenantID', '=', $tenantID)->count();
	}
	
	public function remove($tenantID ="")
	{
		return PaymentsHistory::where('tenantID', '=', $tenantID)->delete();
	}
	
	 
	public function due($tenantID ="")
	{
		$validToDate = $this->validTo($tenantID) != false ? $this->validTo($tenantID) : strtotime('yesterday', time());
		
		$today = strftime("%Y-%m-%d", time());
		
		if($today > $validToDate)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function validateSubscription($tenantID = ""){
		
		$tenant = Tenant::where('tenantID','=', $tenantID)->first();
		
		// Check that it's not freemium
		if($tenant->account_plan_id > 1){
			 
			 // If there are subscription
			if(PaymentsHistory::count($tenantID) > 0){
				
				if($this->due($tenantID))
				{
					// Subscription expired
					return false;
				}				
			 
			}else{
				 
				 // No subscription history
				  
			}
			
		}
		
		return true;
	}
	
	
	
	public function restrictAccess($tenantID ="", $planId = 0)
	{
		if($this->due($tenantID) && $planId != 1)
		{
			return true;
		}
		else
		{
			return false;
		}		
		
	} 
	
	
	public function validTo($tenantID ="")
	{
		if($this->count($tenantID) > 0)
		{			
			return PaymentsHistory::where('tenantID', '=', $tenantID)->orderBy('id', 'desc')->pluck('valid_to');
		}
		else
		{
			return date('Y-m-d', strtotime('today'));
		}
		
	} 
	
	public function validFrom($tenantID =""){
	  
		if($this->count($tenantID) > 0)
		{			
			return PaymentsHistory::where('tenantID', '=', $tenantID)->orderBy('id', 'desc')->pluck('valid_from');
		}
		else
		{
			return date('Y-m-d', strtotime('today'));
		}	 
		
	}
	
	public function getValidToDaysRemaining($tenantID ="")
	{
		$validTo = $this->validTo($tenantID);
		
		$today = date('Y-m-d', strtotime('today'));	
			
		$diff = strtotime($validTo) - strtotime($today);
		
		$days = floor($diff / (60*60*24));
		
		return $days;
	}
	
	// Get tenant
	public function get_subscription($tenantID){
		// result query
		return Tenant::where('id', '=', $tenantID)->get();
	}
	
	public function get_plan_id($tenantID){
		// result query
		return Tenant::where('id', '=', $tenantID)->pluck('account_plan_id');
	}
	 
	// Get By name
	public function get_plan($id){
		return AccountPlan::where('id', '=', $id);
	} // END	
 
}
