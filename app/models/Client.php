<?php 

class Client extends Eloquent{
	
	protected $guarded = array('id');
	// If the table is not named as plural
	// public static $table = 'table name';
	
	/* 
	public static function validate($data){
		return Validator::make($data, static::$rules);
	} */
	
	public static function count($searchquery = null)
	{
		return $searchquery ? Client::where('tenantID', '=', Session::get('tenantID'))->where('company', 'LIKE', "%$searchquery%")->count()
			   : Client::where('tenantID', '=', Session::get('tenantID'))->count();		 
	}
	
	public function tenant()
    {
        return $this->belongsTo('Tenant', 'id');
    }
	
	public function invoices()
    {
        return $this->hasMany('Invoice');    
    }
	
	/*
	public function invoices()
    {
        return $this->hasMany('Invoice', 'tenant_invoice_id');
    }
	
	*/
	
	public function getRevenue($client_id)
	{
		$preferences = Preference::where('tenantID', '=',  $this->tenantID)->first();
	 
		$total_default_currency_amount = DB::table('invoice_payments')
		->join('invoices', 'invoice_payments.tenant_invoice_id', '=', 'invoices.tenant_invoice_id')
		->where('invoices.tenantID','=', $this->tenantID)->where('invoices.quote', '=', 0)->where('invoices.currency_code','=', $preferences->currency_code)->sum('invoice_payments.amount');
		
		$group_totals = DB::table('invoice_payments')
		->join('invoices', 'invoice_payments.tenant_invoice_id', '=', 'invoices.tenant_invoice_id')
		->select(DB::raw("invoices.currency_code, sum(invoice_payments.amount) as total"))
		->where('invoices.tenantID','=', $this->tenantID)
		->where('invoices.quote', '=', 0)->groupBy('invoices.currency_code')->get();
		$total_other_currencies_amount = 0;
		
		foreach($group_totals as $group_total){
			$total_other_currencies_amount += CurrencyRate::where('tenantID', '=', $this->tenantID)->where('currency_code','<>', $preferences->currency_code)->where('currency_code', '=', $group_total->currency_code)->pluck('unit_exchange_rate') * $group_total->total;
		}
	  
		return $all_totals_home_currency =  $total_default_currency_amount + $total_other_currencies_amount;
	}

}
