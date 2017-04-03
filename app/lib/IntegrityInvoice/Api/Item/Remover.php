<?php namespace IntegrityInvoice\Api\Item;

use Item;
use Session;
use Response;

class Remover {
 	
	public function __construct()
    {
    	
	}
	
	public function remove($tenantID, $id)
	{
		// http://localhost:8000/integrityinvoice_api?controller=global&action=removeItem&id=11&tenantID=bl40000001138642718660&username=nebestpal@yahoo.com&password=0000
		
		// dd($id);
		
		if(! is_numeric($id))
		{
			//if(Auth::user()){ Auth::logout(); }
			return "FAILS";
		}
		
		$item_name = Item::where('id', '=', $id)->where('tenantID', '=', $tenantID)->pluck('item_name');
		$affectedRows = Item::where('id', '=', $id)->where('tenantID', '=', $tenantID)->delete();
		
		if(!is_numeric($affectedRows) || $affectedRows < 1)
		{
			//if(Auth::user()){ Auth::logout(); }			
			return Response::Json('FAILS');
		}
		
		// Note: May ExpenseEvent::create later 
		//if(Auth::user()){ Auth::logout(); }
		return Response::Json('The item: '.$item_name .' was deleted.');
  
	}
	
}