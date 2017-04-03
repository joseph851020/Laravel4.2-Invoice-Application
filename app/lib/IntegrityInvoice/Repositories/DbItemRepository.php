<?php namespace IntegrityInvoice\Repositories;

use IntegrityInvoice\Repositories\ItemRepositoryInterface;
use Item;

class DbItemRepository implements ItemRepositoryInterface{
	
	public function getAll($tenantID = "", $itemType="", $searchquery, $perPage = "")	
	{
		return $searchquery
		? Item::where('tenantID','=', $tenantID)->where('item_type','=', $itemType)->where('item_name', 'LIKE',  "%$searchquery%")->orderBy('item_name','asc')->paginate($perPage)					
		: Item::where('tenantID','=', $tenantID)->where('item_type','=', $itemType)->orderBy('item_name','asc')->paginate($perPage);							 
	}
	
	
	public function find($tenantID ="", $id = 0)
	{
		return Item::where('tenantID','=', $tenantID)->where('id','=', $id)->first();
	}
	
	
	public function create($input = array())
	{
		return Item::create($input);
	}
	
	public function update($tenantID ="", $id = 0, $input = array())
	{
		return Item::where('id', '=', $id)->where('tenantID', '=', $tenantID)->update($input);	
	}
	
	
	public function remove($tenantID ="", $id = 0)
	{
		return Item::where('id', '=', $id)->where('tenantID', '=', $tenantID)->delete();
	}
	
	public function removeAll($tenantID ="")
	{
		return Item::where('tenantID', '=', $tenantID)->delete();
	}
 
}
