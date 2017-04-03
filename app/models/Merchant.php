<?php 

class Merchant extends Eloquent{
	
	protected $guarded = array('id');
	// If the table is not named as plural
	// public static $table = 'table name';
	
	
	public static function getAll($tenantID){
		
		return Merchant::where('tenantID', '=', $tenantID)->orderBy('company', 'asc')->get();
	 
	}
	
	public function tenant()
    {
        return $this->belongsTo('Tenant', 'id');
    }
	
	
	public function expenses()
    {
        return $this->hasMany('Expense');    
    }
}
