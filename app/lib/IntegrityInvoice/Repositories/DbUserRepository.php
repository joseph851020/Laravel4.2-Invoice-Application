<?php namespace IntegrityInvoice\Repositories;

use IntegrityInvoice\Repositories\UserRepositoryInterface;
use User;

class DbUserRepository implements UserRepositoryInterface{
	
	public function getAll($tenantID = "", $searchquery, $perPage = "")
	{ 
		return  $searchquery
		? User::where('tenantID', '=',  $tenantID)->where('firstname', 'LIKE',  "%$searchquery%")->orderBy('firstname','asc')->paginate($perPage)
		: User::where('tenantID', '=',  $tenantID)->orderBy('firstname','asc')->paginate($perPage);
	}
	
	
	public function find($tenantID ="", $id = 0)
	{
		return User::where('tenantID','=', $tenantID)->where('id','=', $id)->first();
	}
	
	public function findSuper($tenantID ="")
	{
		return User::where('tenantID','=', $tenantID)->first();
	}
	
	
	public function create($input = array())
	{
		//dd($input);
		
		return User::create($input);
	}
	
	public function update($tenantID ="", $id = 0, $input = array())
	{
		return User::where('id', '=', $id)->where('tenantID', '=', $tenantID)->update($input);	
	}
	
	
	public function remove($tenantID ="", $id = 0)
	{
		return User::where('id', '=', $id)->where('tenantID', '=', $tenantID)->delete();
	}
	
	public function removeAll($tenantID ="")
	{
		return User::where('tenantID', '=', $tenantID)->delete();
	}
 
}
