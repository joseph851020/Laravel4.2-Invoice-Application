<?php

class LoginController extends BaseController {

	 
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('login.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		// Validate 
		
		$input = Input::all();
		
		$attempt = Auth::user()->attempt(array(
			'email' => $input['email'],
			'password' => $input['password']			
		));
		
		if($attempt){
			
			$user = Auth::user()->get();

            Event::fire('user.login', ['user' => $user]);
			
			if(Tenant::where('tenantID', '=', $user->tenantID)->pluck('status') == -1)
			{
				Auth::user()->logout();
				return Redirect::back()->with('flash_message', 'Your account was suspended, please contact us at info@sighted.com');
			}
			
			if(Tenant::where('tenantID', '=', $user->tenantID)->pluck('status') == -2)
			{
				Auth::user()->logout();
				return Redirect::back()->with('flash_message', 'Your account has been deactivated, please contact us at info@sighted.com');
			}
		
			// we are now logged in, go to dashboard
			Session::put('user_id', $user->id);
			Session::put('email', $user->email);
			Session::put('tenantID', $user->tenantID);
			Session::put('firstname', $user->firstname);
			Session::put('lastname', $user->lastname);
			Session::put('theme_id', $user->theme_id);
			Session::put('account_plan', Tenant::where('tenantID', '=', $user->tenantID)->pluck('account_plan_id'));
			Session::put('invoice_template', Preference::where('tenantID', '=', $user->tenantID)->pluck('invoice_template'));
			Session::put('user_level', $user->level);
			Session::put('is_logged_in', true);
			
			return Redirect::intended('dashboard')->with('flash_message', 'Login Successful');
		}
		
		// IF not user Authenticated
		return Redirect::back()->with('failed_flash_message', 'Invalid Credentials')->withInput();
	}

	

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy()
	{
        $user = Auth::user()->get();
        Event::fire('user.logout', ['user' => $user]);
		Auth::user()->logout();
		
		Session::flush();
		
		return Redirect::to('login')->with('flash_message', 'You have been logged out!');
	}

}
