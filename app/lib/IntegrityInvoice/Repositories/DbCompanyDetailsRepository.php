<?php namespace IntegrityInvoice\Repositories;

use IntegrityInvoice\Repositories\CompanyDetailsRepositoryInterface;
use Company;

class DbCompanyDetailsRepository implements CompanyDetailsRepositoryInterface{
	
	public function getAll($tenantID = "", $perPage = "")
	{	
		return Company::where('tenantID','=', $tenantID)->orderBy('company_name','asc')->paginate($perPage);
	}
	
	
	public function find($tenantID ="")
	{
		return Company::where('tenantID','=', $tenantID)->first();
	}
	
	
	public function create($input = array())
	{
		return Company::create($input);
	}
	
	public function update($tenantID ="", $input = array())
	{
		return Company::where('tenantID', '=', $tenantID)->update($input);	
	}
	
	
	public function remove($tenantID ="")
	{
		return Company::where('tenantID', '=', $tenantID)->delete();
	}
 
}
