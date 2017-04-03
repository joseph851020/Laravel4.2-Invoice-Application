<?php

class AdminLoginController extends BaseController {

	function __construct()
	{
		//Config::set('auth.model', 'Admin');
		//Config::set('auth.table', 'admin');
	}
 
	public function create()
	{
        return View::make('adminlogin.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{ 
		$input = Input::all();
		 
		//$userUser = Admin::where('email', '=', $input['email'])->where('password', '=', Hash::make($input['password']))
		//->where('pin', '=', Hash::make($input['pin']))->where('auth_code', '=', Hash::make($input['authcode']))->get();
		 
		$attempt =  Auth::admin()->attempt(array(
			'email' => Input::get('email'),
			'password' => Input::get('password'),
			// 'pin' => sha1(Input::get('pin')),
			// 'auth_code' => sha1(Input::get('authcode'))	
		));
		
		
		if($attempt){	
			
			$adminUser = Auth::admin()->get();
			 
			// we are now logged in, go to dashboard
			Session::put('admin_user_id', $adminUser->id);
			Session::put('email', $adminUser->email); 
			Session::put('firstname', $adminUser->firstname);
			Session::put('lastname', $adminUser->lastname);
			Session::put('user_level', $adminUser->level);
			Session::put('is_logged_in', true);
			
			return Redirect::to('admin/dashboard')->with('flash_message', 'You have been logged in');
		}else{
			return Redirect::to('admin/login')->withInput()->with('flash_message', 'Invalid credentials.');
		}
	  
	}

	

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy()
	{
		Auth::admin()->logout();
        Session::flush();
		return Redirect::to('admin/login')->with('flash_message', 'You have been logged out!');
	}

}
