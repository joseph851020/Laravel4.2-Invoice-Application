<?php namespace IntegrityInvoice\Repositories;

use IntegrityInvoice\Repositories\TenantRepositoryInterface;
use Tenant;

class DbTenantRepository implements TenantRepositoryInterface{
	
	public function getAll($searchquery, $perPage = "")
	{
		//dd(Tenant::orderBy('created_at','desc')->paginate($perPage));
		
		// return Tenant::all();		 
		return  $searchquery
		? Tenant::where('tenantID', 'LIKE',  "%$searchquery%")->orderBy('created_at','desc')->paginate($perPage)
		: Tenant::where('tenantID', '<>',  '')->orderBy('created_at','desc')->paginate($perPage);
	}
	
	
	public function find($tenantID ="")
	{
		return Tenant::where('tenantID','=', $tenantID)->first();
	}
	
	
	public function create($input = array())
	{
		return Tenant::create($input);
	}
	
	public function update($tenantID ="", $input = array())
	{
		return Tenant::where('tenantID', '=', $tenantID)->update($input);	
	}
	
	
	public function remove($tenantID ="")
	{
		// Ensure Delete
		// Do Due Diligence house keeping
		return Tenant::where('tenantID', '=', $tenantID)->delete();
	}
	
	public function verification($tenantID = "")
	{
		return Tenant::where('tenantID', '=', $tenantID)->pluck('verified');
	}
	
	public function count($searchquery)
	{
		return $searchquery ? Tenant::where('tenantID', 'LIKE', "%$searchquery%")->count()
			   : Tenant::count();		 
	}
	
	public function isActive($tenantID ="")
	{	 
		$tenantStatus = $this->find($tenantID)->status;
				 
		if($tenantStatus == 1){
			return true;
		}else{
			return false;
		}
	} 
	
	
	public function checkReferral($code = ""){
		return Tenant::where('referral_code', '=', $code)->first();
	}
	
}
