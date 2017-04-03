<?php

use IntegrityInvoice\Repositories\AdminNotificationRepositoryInterface;
use IntegrityInvoice\Services\AdminNotification\Reader as Reader;
use IntegrityInvoice\Services\AdminNotification\Creator as Creator;
use IntegrityInvoice\Services\AdminNotification\Updater as Updater;
use IntegrityInvoice\Services\AdminNotification\Remover as Remover;

use IntegrityInvoice\Utilities\AppHelper as AppHelper;
use Carbon\Carbon;

class AdminNotificationsController extends BaseController {
 
 	public $notification;
	public $totalRecords;
	public $searchquery;
	public $perPage;
	
	function __construct(AdminNotificationRepositoryInterface $notification)
    { 		
    	$this->notification = $notification;
		$this->totalRecords = $this->notification->count($this->searchquery);
		$this->perPage = 10;		 
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{ 
		$this->searchquery = trim(Request::get('q'));
		 
		// Pass in Item Model implementation and this class	
		$readerService = new Reader($this->notification, $this);
		$notifications = $readerService->readAll($this->searchquery);
	 
		return View::make('adminnotifications.index')
		       ->with('title', 'Notifications') 
			   ->with(compact('notifications'))
			   ->with('totalRecords', $this->totalRecords)
			   ->with('searchquery', $this->searchquery);	   
			    
	}
	
	
	public function status($notificationID)
	{
		$readerService = new Reader($this->notification, $this);
		$notification = $readerService->read($notificationID);
		 
		return View::make('adminnotifications.status')
		       ->with('title', 'Account status for:'. $notificationID)
			   ->with(compact('notification'));
	}
 
	public function create()
	{  
		 return View::make('adminnotifications.create')
		->with('title', 'Add new notification');
	}
	
	public function store(){
	 
		$creatorService = new Creator($this->notification, $this);  			
		return $creatorService->create(array(				
			'title' =>Input::get('title'),
			'type' =>Input::get('type'),
			'info' =>Input::get('info'),	
			'active' => Input::get('active') ? 1 : 0,		 				
			'display_start_date' => AppHelper::convert_to_mysql_yyyymmdd(Input::get('display_start_date')),
			'display_end_date' => AppHelper::convert_to_mysql_yyyymmdd(Input::get('display_end_date')), 
			'created_at' => Carbon::now(),
			'updated_at' => Carbon::now()
		));
	}
	
	public function notificationCreationFails($errors){
		
		return Redirect::route('create_notification')->withErrors($errors)->withInput();
	}
	
	public function notificationCreationSucceeds(){
		
		return Redirect::route('admin_notifications')
					->with('flash_message', 'New notification was created successfully');
	}
	
	public function edit($id)
	{
        return View::make('adminnotifications.edit')
		->with('title', 'Edit notification')
		->with('notification', AdminNotification::where('id', '=', $id)->first());
	}
	
	public function update()
	{
		$id = Input::get('notification_id');
	    $updateService = new Updater($this->notification, $this);	
		return $updateService->update($id, array(
			'title' =>Input::get('title'),
			'type' =>Input::get('type'),
			'info' =>Input::get('info'),	
			'active' => Input::get('active') ? 1 : 0,		 				
			'display_start_date' => AppHelper::convert_to_mysql_yyyymmdd(Input::get('display_start_date')),
			'display_end_date' => AppHelper::convert_to_mysql_yyyymmdd(Input::get('display_end_date')),
			'updated_at' => Carbon::now()		 
		));
	}
	
	public function notificationUpdateFails($errors){
		
		return Redirect::route('admin_notifications')->withErrors($errors)->withInput();
	}
	
	public function notificationUpdateSucceeds(){
		
		return Redirect::route('admin_notifications')
					->with('flash_message', 'Notification was updated successfully');
	}
	
 
	public function destroy($id)
	{
		 $removerService = new Remover($this->notification, $this);		
		 return $removerService->remove($id);
	}
	
	public function notificationDeletionFails(){
		
		return Redirect::route('admin_notifications')
					->with('failed_flash_message', 'Notification was not deleted');
	}
	
	public function notificationDeletionSucceeds(){
		
		return Redirect::route('admin_notifications')
					->with('flash_message', 'Notification was deleted successfully');
	}

}