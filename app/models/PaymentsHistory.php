<?php 

class PaymentsHistory extends Eloquent{
	
	protected $guarded = array('id');
	// If the table is not named as plural
	protected $table = 'payments_history';
	
	public static function count($tenantID = "")
	{
		return PaymentsHistory::where('tenantID', '=', $tenantID)->count();
	}
}



	