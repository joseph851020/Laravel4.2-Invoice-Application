<?php

use IntegrityInvoice\Repositories\UserRepositoryInterface;
use IntegrityInvoice\Services\User\Creator;
use IntegrityInvoice\Services\User\Reader;
use IntegrityInvoice\Services\User\Updater;
use IntegrityInvoice\Services\User\Remover;

use Carbon\Carbon;

class UsersController extends BaseController {
	
	public $tenantID;
	public $user;
	public $userId;
	public $accountPlan;
	public $tenantVerification;
	public $limitReached;
	public $perPage;
	public $totalUsers;
	public $usertLimit;

	
	public function __construct(UserRepositoryInterface $user)
	{
	  	$this->user = $user;
	  	$this->tenantID = Session::get('tenantID');
		$this->userId = Session::get('user_id');
		$this->accountPlan = Tenant::where('tenantID', '=', $this->tenantID)->pluck('account_plan_id');		
		$this->perPage = Preference::where('tenantID', '=', $this->tenantID)->pluck('page_record_number');
		$this->totalUsers = User::count("", $this->tenantID);		
		$this->userLimit = AccountPlan::where('id', '=', Tenant::where('account_plan_id', '=', $this->accountPlan)->pluck('account_plan_id'))->pluck('user_limit');		
		$this->tenantVerification = Tenant::where('tenantID', '=', $this->tenantID)->pluck('verified');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$searchquery = trim(Request::get('q'));
		$searchquery = ltrim($searchquery, '0');
 
		// Redirect to dashboard if Registration has not been verified!
		// if($this->tenantVerification == 0){		 
			// return Redirect::route('dashboard')->with('failed_flash_message', 'Please check your email and verify your account');
		// }
		 
		$readerService = new Reader($this->user, $this);
		$users = $readerService->readAll($searchquery);
	 
        return View::make('users.index')
		   ->with('title', 'List of admin users')
		   ->with(compact('users'))
		   ->with('total_records', User::count($searchquery, $this->tenantID))
		   ->with('searchquery', $searchquery)
		   ->with('preferences', Preference::where('tenantID', '=', $this->tenantID)->first());
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{ 
		// Restrictions
		if($this->totalUsers >= $this->userLimit){
			$this->limitReached = TRUE;
		}else{
			$this->limitReached = FALSE;
		}

		 return View::make('users.create')
		->with('title', 'Add new client')
		->with('limitReached', $this->limitReached);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$creator = new Creator($this->user, $this);		
		return $creator->create(array(
			'firstname' =>Input::get('firstname'),
			'lastname' =>Input::get('lastname'),
			'username' =>Input::get('username'),
			'phone' =>Input::get('phone'),
			'email' => Input::get('email'),
			'password' => Input::get('password'),
			'confirm_password' =>Input::get('confirm_password'),			 
			'created_at' => strftime("%Y-%m-%d %H:%M:%S", time()),
			'updated_at' => strftime("%Y-%m-%d %H:%M:%S", time()),
			'tenantID' => Session::get('tenantID')
		));
	}
	
	public function userCreationFails($errors){
		
		return Redirect::route('create_user')->withErrors($errors)->withInput();
	}
	
	public function userCreationSucceeds(){
		
		return Redirect::route('users')
					->with('flash_message', 'New admin user was created successfully');
	}
	
	public function userUpdateFails($id, $errors){
		
		return Redirect::route('edit_user', $id)->withErrors($errors)->withInput();
	}
	
	public function userUpdateSucceeds($id){
		
		return Redirect::route('edit_user', $id)
					->with('flash_message', 'Updated successfully');
	}
	
	
	
	public function remove_firsttimer(){
		
		$user = User::find(Session::get('user_id'));
		
		 $updateService = new Updater($this->user, $this);		
			return $updateService->update_firsttimer($user->id, array(
				'firsttimer' => 0			 
		 ));
			
	}

	public function close_notification(){		
		Session::put('close_notification', true);
	}
	
 
	public function show($id)
	{
        return View::make('users.show');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        return View::make('users.edit')
		->with('title', 'Edit user')
		->with('user', User::where('tenantID', '=', $this->tenantID)->where('id', '=', $id)->first());
	}
	
	

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update()
	{

		$id = Input::get('userId');
        //$email = Input::get('email');
        // $method = Input::get('notify') ? 'subscribeTo' : 'unsubscribeFrom';
        // $this->newsletterList->{$method}('integritySubscribers', $email);
        // $notify = Input::get('notify') ? 1 : 0;
		
		$admin_flag =Input::get('admin');	
        	$notify = 1;
	 
		$updateService = new Updater($this->user, $this);		
		$updateService->update($id, array(
			'firstname' =>Input::get('firstname'),
			'lastname' =>Input::get('lastname'),
			'username' =>Input::get('username'),
			'phone' => Input::get('phone'),
			'email' => Input::get('email'),
            'notify' => $notify,
			'updated_at' => Carbon::now()
		));
		
		if($admin_flag =='true')
			return Redirect::route('admin_accounts')->with('flash_message', 'Application account updated.');
		else
			return Redirect::route('edit_user', $id)->with('flash_message', 'Updated successfully');
	}
	
	public function password()
	{
		$id = Session::get('user_id');
        return View::make('users.password')
		->with('title', 'Edit Password')
		->with('user', User::where('tenantID', '=', $this->tenantID)->where('id', '=', $id)->first());
	}
	
	public function password_update(){
		
		$id = Input::get('userId');
		$current_password = Input::get('current_password');
		
		$readerService = new Reader($this->user, $this);
		$user = $readerService->read($id);
		$user_pass_hashed = $user->password;
	 
	 
		if (Hash::check($current_password, $user_pass_hashed))
		{
		    // The passwords match...
		    $updateService = new Updater($this->user, $this);		
			return $updateService->update_password($id, array(
				'password' => Input::get('password'),
				'confirm_password' =>Input::get('confirm_password')
			));
		 
		}
		else{
			return Redirect::route('password')->with('failed_flash_message', 'Incorrect current password');
		}
	 
	}
	
 
	public function userPasswordUpdateFails($errors){
		
		return Redirect::route('password')->withErrors($errors)->withInput();
	}
	
	public function userPasswordUpdateSucceeds(){
		
		return Redirect::route('password')
					->with('flash_message', 'Password updated successfully');
	}
	
	
	public function apptheme()
	{
		$userReader = new Reader($this->user, $this);	
		$theme_id = $userReader->read($user_id = Session::get('user_id'))->theme_id; 
		
		 return View::make('settings.app_theme')
		 ->with(compact('theme_id'))
		 ->with('title', 'App Theme');
	}

	public function apptheme_update()
	{ 
		$user_id = Session::get('user_id');
		 
		$updateService = new Updater($this->user, $this);		
		$updateService->update_theme($user_id, array(
			'theme_id' => Input::get('theme_id'),
			'updated_at' => strftime("%Y-%m-%d %H:%M:%S", time())
		));
		
		$userReader = new Reader($this->user, $this);	
		$theme_id = $userReader->read($user_id)->theme_id;
		
		Session::put('theme_id', $theme_id);
		return Redirect::route('app_theme')->with('flash_message', 'Application theme updated.');
	}
	

	public function destroy($id)
	{
		$removerService = new Remover($this->user, $this);		
		return $removerService->remove($id);
	}
 
	public function userDeletionFails(){
		
		return Redirect::route('users')
					->with('failed_flash_message', 'Admin user was not deleted');
	}
	
	public function userDeletionSucceeds(){
		
		return Redirect::route('users')
					->with('flash_message', 'User was deleted successfully');
	}

}
