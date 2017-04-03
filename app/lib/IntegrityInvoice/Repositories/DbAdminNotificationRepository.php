<?php namespace IntegrityInvoice\Repositories;

use IntegrityInvoice\Repositories\AdminNotificationRepositoryInterface;
use AdminNotification;

class DbAdminNotificationRepository implements AdminNotificationRepositoryInterface{
	
	public function getAll($searchquery, $perPage = "")
	{		 	 
		return  $searchquery
		? AdminNotification::where('title', 'LIKE',  "%$searchquery%")->orderBy('updated_at','desc')->paginate($perPage)
		: AdminNotification::where('title', '<>',  '')->orderBy('updated_at','desc')->paginate($perPage);
	}
	
 
	public function find($id = 0)
	{
		return AdminNotification::where('id','=', $id)->first();
	}
 
	public function create($input = array())
	{
		return AdminNotification::create($input);
	}
	
	public function update($id = 0, $input = array())
	{
		return AdminNotification::where('id', '=', $id)->update($input);	
	}
	
	public function count($searchquery)
	{
		return $searchquery ? AdminNotification::where('title', 'LIKE', "%$searchquery%")->count()
			   : AdminNotification::count();		 
	}	
	
	public function remove($id = 0)
	{
		return AdminNotification::where('id', '=', $id)->delete();
	}
  
}
