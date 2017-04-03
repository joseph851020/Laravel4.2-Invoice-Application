<?php

class PasswordResetsController extends BaseController {
	
	public function __construct()
	{
 
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return View::make('passwordresets.index'); 
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        return View::make('passwordresets.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		 
		$user = User::where('email', '=', Input::get('email'))->first();
		
		if(!$user){
			return Redirect::route('passwordresets')->with('failed_flash_message', 'We can\'t find a user with that e-mail address.');
		}
		
	    Password::user()->remind(Input::only('email'), function($message){	    	
			$message->from('info@sighted.com',  'Sighted Invoice');
	    	$message->subject('Your Password Reminder');
	    });
	 
	    return Redirect::route('passwordresets')->with('flash_message', 'Email sent. Follow the instructions to reset your password. If you don’t see a password reset email within the next 5 minutes, check your junk mail folder in case the email is incorrectly filtered.');
		 
	}

 
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function reset($user, $token)
	{
        return View::make('passwordresets.reset')->withToken($token);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function postReset()
	{ 
	    $credentials = Input::only(
	    	'email', 'password', 'password_confirmation', 'token'
	    );
	 
		$response = Password::user()->reset($credentials, function($user, $password) {
		    $user->password = Hash::make($password);
		    $user->save();
	    });
     
		    switch ($response) {
		    case Password::INVALID_PASSWORD:
		    case Password::INVALID_TOKEN:
		    return Redirect::back()->with('failed_flash_message', Lang::get($response))->withInput();
		    case Password::INVALID_USER:
		    return Redirect::back()->with('failed_flash_message', Lang::get($response))->withInput();
		     
		    case Password::PASSWORD_RESET:
				
				$attempt = Auth::user()->attempt(array(
					'email' => Input::get('email'),
					'password' => Input::get('password')	
				));
				
				if($attempt){
					
					$user = Auth::user()->get();
					
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
					
					return Redirect::route('dashboard')->with('flash_message', 'Password Updated.');
				}
 
      }
			
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
