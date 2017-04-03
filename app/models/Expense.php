<?php 

class Expense extends Eloquent{
	
	protected $guarded = array('id');
	// If the table is not named as plural
	// public static $table = 'table name'	
	 
	public static function getTotalExpenses(){
		return Expense::where('tenantID', '=', Session::get('tenantID'))->count();
	}
	
	public function merchant()
    {
		return $this->belongsTo('Merchant');
    }
	
}



	