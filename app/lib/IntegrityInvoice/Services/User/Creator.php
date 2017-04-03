<?php namespace IntegrityInvoice\Services\User;

use IntegrityInvoice\Services\Validation\UserValidator as Validator;
use IntegrityInvoice\Services\Validation\UserAtSignupValidator as SignupUserValidator;
use Hash;

class Creator {
	
	/**
	 *  @var IntegrityInvoice\Repositories\DbUserRepository
	 */
	
	protected $validator;
	protected $signupUserValidator;
	protected $listener;
	protected $user;
	
	public function __construct($user, $listener)
    {
    	$this->validator = new Validator;
		$this->signupUserValidator = new SignupUserValidator;
		$this->listener = $listener;
		$this->user = $user;
	}
	
	public function create($input, $redirect = TRUE, $isSignup = FALSE)
	{
	 
		if($isSignup == TRUE)
		{
			// Signup User creation
			if(! $this->signupUserValidator->validate($input))
			{
				return $this->listener->userCreationFails($this->signupUserValidator->errors());
		 
			} 
			
		}
		else
		{
			// Normal User creation
			if(! $this->validator->validate($input))
			{ 
				return $this->listener->userCreationFails($this->validator->errors());
			}
			
			$password = Hash::make($input['password']);
			
			// Remove items not required	
			$input = array_except($input, array('confirm_password', 'password'));
			//Re add hashed password
			$input = array_merge($input, array('password' => $password));
		  
		}
	 
		//dd($required_input);
		 
		$user = $this->user->create($input);
		
		if($redirect == TRUE || $redirect == NULL)
		{
			return $this->listener->userCreationSucceeds($user);
		}
	 
	}
	
}