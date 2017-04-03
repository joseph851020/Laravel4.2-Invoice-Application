<?php 

class InvoicePayment extends Eloquent{
	
	protected $table = "invoice_payments";
	
	protected $guarded = array('id');
	
	public function invoice()
    {
		return $this->belongsTo('Invoice', 'tenant_invoice_id');
    }
	 
	public static function count($searchquery = null)
	{
		return $searchquery ? InvoicePayment::where('tenantID', '=', Session::get('tenantID'))->where('tenant_invoice_id', 'LIKE', "%$searchquery%")->count()
			   : InvoicePayment::where('tenantID', '=', Session::get('tenantID'))->count();
		 
	}
}