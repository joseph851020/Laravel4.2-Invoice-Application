<?php 

class Invoice extends Eloquent{
	
	protected $guarded = array('id');
	
	// Get all invoices in the last 30 days
	public static function total_invoices_this_month(){
		return Invoice::where('tenantID', '=', Session::get('tenantID'))
		->where(DB::raw('MONTH(created_at)'), '=', DB::raw('MONTH(CURRENT_DATE)'))
		->where(DB::raw('YEAR(created_at)'), '=', DB::raw('YEAR(CURRENT_DATE)'))
		->where('quote', '=', 0)
		->count();
	}
	
	// Get all quotes in the last 30 days
	public static function total_quotes_this_month(){
		return Invoice::where('tenantID', '=', Session::get('tenantID'))
		->where(DB::raw('MONTH(created_at)'), '=', DB::raw('MONTH(CURRENT_DATE)'))
		->where(DB::raw('YEAR(created_at)'), '=', DB::raw('YEAR(CURRENT_DATE)'))
		->where('quote', '=', 1)
		->count();
	}
	
	public function invoice_payments()
    {
        return $this->hasMany('InvoicePayment');    
    }
 
	public function client()
    {
		return $this->belongsTo('Client', 'client_id');
    }
			
	// get last tenant invoice id
	public static function tenant_last_invoice_id(){
		$last_invoice_id = Invoice::where('tenantID', '=', Session::get('tenantID'))->where('quote', '=', 0)->orderBy('tenant_invoice_id', 'desc')->take(1)->pluck('tenant_invoice_id');
	 
		if($last_invoice_id <= 0 || is_null($last_invoice_id))
		{
			return 0;
		}else{
			return $last_invoice_id;
		}
	}
	
	
	// get last tenant invoice id
	public static function tenant_last_quote_id(){
		$last_quote_id = Invoice::where('tenantID', '=', Session::get('tenantID'))->where('quote', '=', 1)->orderBy('tenant_quote_id', 'desc')->take(1)->pluck('tenant_quote_id');
	 
		if($last_quote_id <= 0 || is_null($last_quote_id))
		{
			return 0;
		}else{
			return $last_quote_id;
		}
	}


	// get last used tenant invoice id
	public static function tenant_last_used_invoice_id(){
		$last_invoice = Invoice::where('tenantID', '=', Session::get('tenantID'))->where('quote', '=', 0)->orderBy('created_at', 'desc')->first();

        if($last_invoice != null)
        {
            if($last_invoice->tenant_invoice_id <= 0)
            {
                return 0;

            }
            else
            {
                return $last_invoice->tenant_invoice_id;
            }
        }
        else
        {
            return 0;
        }

	}
	
	
	// get last used tenant invoice id
	public static function tenant_last_used_quote_id(){
		$last_quote = Invoice::where('tenantID', '=', Session::get('tenantID'))->where('quote', '=', 1)->orderBy('created_at', 'desc')->first();

        if($last_quote != null)
        {
            if($last_quote->tenant_quote_id <= 0)
            {
                return 0;

            }
            else
            {
                return $last_quote->tenant_quote_id;
            }
        }
        else
        {
            return 0;
        }

	}

	public static function count($searchquery = null, $quote = 0)
	{
		return $searchquery ? Invoice::where('tenantID', '=', Session::get('tenantID'))->where('quote', '=', $quote)->where('tenant_invoice_id', 'LIKE', "%$searchquery%")->count()
			   : Invoice::where('tenantID', '=', Session::get('tenantID'))->where('quote', '=', $quote)->count();
		 
	}
	
	public static function count_filter($filter)
	{
		return  Invoice::where('tenantID', '=', Session::get('tenantID'))->where('quote', '=', 0)->where('payment', '=', $filter)->count();		 
	}
	
	
	public static function update_status($tenantID = null, $tenant_invoice_id, $val){
		 
		$affRow = Invoice::where('tenantID', '=', $tenantID)->where('tenant_invoice_id', '=', $tenant_invoice_id)->update(array('status' => $val));
		
		if(!$affRow){ return false;	} 		
		return true;
	}
	
	public static function update_quote_status($tenantID = null, $tenant_quote_id, $val){
		 
		$affRow = Invoice::where('tenantID', '=', $tenantID)->where('tenant_quote_id', '=', $tenant_quote_id)->update(array('status' => $val));
		
		if(!$affRow){ return false;	} 		
		return true;
	}
	
 
	 
}