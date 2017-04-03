<?php namespace IntegrityInvoice\Api\Item;

use Item;
use Response;

class Reader {
 	
	public function __construct()
    {
    	
	}
	
	public function read($tenantID, $id)
	{
		// http://localhost:8000/integrityinvoice_api?controller=global&action=removeItem&id=11&tenantID=bl40000001138642718660&username=nebestpal@yahoo.com&password=0000
		
		// dd($id);
		
		if(! is_numeric($id))
		{
			//if(Auth::user()){ Auth::logout(); }
			return "FAILS";
		}
		
		$item = Item::where('id', '=', $id)->where('tenantID', '=', $tenantID)->get();
		
		if(!$item)
		{
			//if(Auth::user()){ Auth::logout(); }			
			return "FAILS";
		}
		
		// Note: May ExpenseEvent::create later 
		//if(Auth::user()){ Auth::logout(); }
		return $item;
  
	}
	
	
	//
	public function readAll($tenantID)
	{
		// http://localhost:8000/integrityinvoice_api?controller=global&action=removeItem&id=11&tenantID=bl40000001138642718660&username=nebestpal@yahoo.com&password=0000
		
		// dd($id);
		
		if(is_null($tenantID) || $tenantID == "")
		{
			//if(Auth::user()){ Auth::logout(); }
			return "FAILS - Invalid account!";
		}
		
		$item = Item::where('tenantID', '=', $tenantID)->get();
		
		 
		if(is_null($item))
		{
			//if(Auth::user()){ Auth::logout(); }			
			return Response::Json('FAILS - could not retrieve items at the moment');
		}
		
		if(count($item) == 0)
		{
			//if(Auth::user()){ Auth::logout(); }			
			return Response::Json('No items');
		}
		
		// Note: May ExpenseEvent::create later 
		//if(Auth::user()){ Auth::logout(); }
		return $item;
  
	}
	
}