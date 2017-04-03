<?php namespace IntegrityInvoice\Services\User;

use IntegrityInvoice\Services\Validation\UserUpdateValidator as Validator;
use IntegrityInvoice\Services\Validation\UserPasswordUpdateValidator as PasswordValidator;
use Hash;
 
class Updater {
	
	private $listener;
	protected $validator;
	protected $passwordValidator;
	protected $user;
 
	public function __construct($user, $listener)
    {
    	$this->validator = new Validator;
		$this->passwordValidator = new PasswordValidator;
		$this->listener = $listener;
		$this->user = $user;
	}
	
	public function update($id, $input)
	{
		if(! $this->validator->validate($input))
		{
			return $this->listener->userUpdateFails($id, $this->validator->errors());
		} 
			
		$this->user->update($this->listener->tenantID, $id, $input);
		//return $this->listener->userUpdateSucceeds($id);
	}
	public function updateFromAdmin($tenantID,$id, $input)
	{
		return $this->user->update($tenantID,$id, $input);
	}

	    public function update_login($tenantID, $id, $input)
	    {
	        return $this->user->update($tenantID, $id, $input);
	    }
	
	
	public function update_password($id, $input)
	{
		if(! $this->passwordValidator->validate($input))
		{
			return $this->listener->userPasswordUpdateFails($this->passwordValidator->errors());
		}
		
		$newPassword = array('password' => Hash::make($input['password'])); 
			
		$this->user->update($this->listener->tenantID, $id, $newPassword);
		return $this->listener->userPasswordUpdateSucceeds($id);
	}
	public function update_passwordFromAdmin($tenantID,$id, $input)
	{
				
		$newPassword = array('password' => Hash::make($input['password'])); 
		$this->user->update($tenantID, $id, $newPassword);
	}
	
	
	public function update_theme($id, $input)
	{ 	
		$this->user->update($this->listener->tenantID, $id, $input);
		return $this->listener->userUpdateSucceeds($id);
	}
	
	
	public function update_firsttimer($id, $input)
	{ 	
		return $this->user->update($this->listener->tenantID, $id, $input);
	//	return $this->listener->userUpdateSucceeds($id);
	}
 
}
 
