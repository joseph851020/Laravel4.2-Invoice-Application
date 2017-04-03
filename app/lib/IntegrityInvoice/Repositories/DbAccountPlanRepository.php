<?php namespace IntegrityInvoice\Repositories;

use IntegrityInvoice\Repositories\AccountPlanRepositoryInterface;
use AccountPlan;

class DbAccountPlanRepository implements AccountPlanRepositoryInterface{
	
	public function getAll()
	{	
		return AccountPlan::all();
	}
	
	
	public function find($id = 0)
	{
		return AccountPlan::where('id','=', $id)->first();
	}
	
	public function findByType($accountType)
	{
		return AccountPlan::where('account_type','=', $accountType)->first();
	}
	
	public function getAccountType($id = 0)
	{
		return AccountPlan::where('id','=', $id)->pluck('account_type');
	}
	
	
	public function create($input = array())
	{
		return AccountPlan::create($input);
	}
	
	public function update($id = 0, $input = array())
	{
		return AccountPlan::where('id', '=', $id)->update($input);	
	}
	
	
	public function remove($id = 0)
	{
		return AccountPlan::where('id', '=', $id)->delete();
	}
	
	public function getPlanPrice($plain_id)
	{
		return AccountPlan::where('id', '=', $plain_id)->pluck('price');	
	}
 
}
