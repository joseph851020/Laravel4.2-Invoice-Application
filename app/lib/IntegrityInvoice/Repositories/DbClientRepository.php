<?php namespace IntegrityInvoice\Repositories;

use IntegrityInvoice\Repositories\ClientRepositoryInterface;
use Client;

class DbClientRepository implements ClientRepositoryInterface{
	
	public function getAll($tenantID = "", $searchquery, $perPage = "")
	{
		return  $searchquery
		? Client::where('tenantID', '=',  $tenantID)->where('company', 'LIKE',  "%$searchquery%")->orderBy('company','asc')->paginate($perPage)
		: Client::where('tenantID', '=',  $tenantID)->orderBy('company','asc')->paginate($perPage);
	}
	
	
	public function find($tenantID ="", $id = 0)
	{
		return Client::where('tenantID','=', $tenantID)->where('id','=', $id)->first();
	}
	
	
	public function create($input = array())
	{
		return Client::create($input);
	}
	
	public function update($tenantID ="", $id = 0, $input = array())
	{
		return Client::where('id', '=', $id)->where('tenantID', '=', $tenantID)->update($input);	
	}
	
	
	public function remove($tenantID ="", $id = 0)
	{
		return Client::where('id', '=', $id)->where('tenantID', '=', $tenantID)->delete();
	}
	
	public function removeAll($tenantID ="")
	{
		return Client::where('tenantID', '=', $tenantID)->delete();
	}
 
}
