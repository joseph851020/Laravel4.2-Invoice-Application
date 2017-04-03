<?php namespace IntegrityInvoice\Repositories;

use IntegrityInvoice\Repositories\MerchantRepositoryInterface;
use Merchant;

class DbMerchantRepository implements MerchantRepositoryInterface{
	
	public function getAll($tenantID = "", $searchquery, $perPage = "")
	{	
		return  $searchquery
		? Merchant::where('tenantID', '=',  $tenantID)->where('company', 'LIKE',  "%$searchquery%")->orderBy('company','asc')->paginate($perPage)
		: Merchant::where('tenantID', '=',  $tenantID)->orderBy('company','asc')->paginate($perPage);
	}
	
	
	public function find($tenantID ="", $id = 0)
	{
		return Merchant::where('tenantID','=', $tenantID)->where('id','=', $id)->first();
	}
	
	
	public function create($input = array())
	{
		return Merchant::create($input);
	}
	
	public function update($tenantID ="", $id = 0, $input = array())
	{
		return Merchant::where('id', '=', $id)->where('tenantID', '=', $tenantID)->update($input);	
	}
	
	
	public function remove($tenantID ="", $id = 0)
	{
		return Merchant::where('id', '=', $id)->where('tenantID', '=', $tenantID)->delete();
	}
	
	public function removeAll($tenantID ="")
	{
		return Merchant::where('tenantID', '=', $tenantID)->delete();
	}
 
}
