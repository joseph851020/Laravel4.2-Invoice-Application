<?php namespace IntegrityInvoice\Services\Validation;

use Input;

class UserUpdateValidator extends Validator{
	
		public $userId;
		static $rules;		
		
		public function __construct()
		{
			$this->userId = Input::get('userId');
			static::$rules = array(
				'firstname' => 'required',
				'email' => 'required|unique:users,email,'.$this->userId
		   );
		}
 
}
