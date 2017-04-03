<?php 

class Item extends Eloquent{
	
	protected $guarded = array('id');
	
	// If the table is not named as plural
	// public static $table = 'table name';
 
	
	public static function count($searchquery = null, $item_type = null)
	{
		return $searchquery ? Item::where('tenantID', '=', Session::get('tenantID'))->where('item_type', '=', $item_type)->where('item_name', 'LIKE', "%$searchquery%")->count()
			   : Item::where('tenantID', '=', Session::get('tenantID'))->where('item_type', '=', $item_type)->count();
		 
	}
	
}
