<?php 

class CancelQueue extends Eloquent{
	
	protected $table = "cancel_queue";
	
	protected $guarded = array('id');
	 
	public static function count($searchquery = null)
	{
		return $searchquery ? CancelQueue::where('tenantID', '=', Session::get('tenantID'))->where('tenant_invoice_id', 'LIKE', "%$searchquery%")->count()
			   : CancelQueue::where('tenantID', '=', Session::get('tenantID'))->count();
		 
	}
}