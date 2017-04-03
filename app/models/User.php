<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');
	
	
	protected $guarded = array('id');  // Important
	//protected $fillable = array('tenantID, username, firstname, lastname, email, level, phone, website, remember, password, role, firsttimer, loggedin, theme_id');	 
 
	
	//public static $unguarded = true;
	
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
 
 
	public function tenant()
    {
       return $this->belongsTo('Tenant', 'tenantID');
    }
	
	public static function count($searchquery = null, $tenantID = "")
	{ 
		return $searchquery && $searchquery != ""
				? User::where('tenantID', '=', $tenantID)->where('firstname', 'LIKE', "%$searchquery%")->orWhere('lastname', 'LIKE', "%$searchquery%")->count()	 
	   			: User::where('tenantID', '=', $tenantID)->count();		 
	}
	
	public static function getFullName($user_id, $tenantID)
	{
		$user = User::where('tenantID', '=', $tenantID)->where('id', '=', $user_id)->first();
		
		return $user->firstname. " ". $user->lastname;
	}
	
	 
	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}
	
	public function getRememberToken()
	{
	    return $this->remember_token;
	}
	
	public function setRememberToken($value)
	{
	    $this->remember_token = $value;
	}
	
	public function getRememberTokenName()
	{
	    return 'remember_token';
	}

}
