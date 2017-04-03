<?php

class AdminNotification extends \Eloquent {

	protected $table = "adminnotifications";	
	protected $guarded = array('id');	
	
	public static function count($searchquery = null)
	{
		return $searchquery ? AdminNotification::where('company', 'LIKE', "%$searchquery%")->count()
			   : AdminNotification::where('title', '<>', '')->count();		 
	}

}