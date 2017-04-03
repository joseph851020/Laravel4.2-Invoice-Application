<?php namespace IntegrityInvoice\Repositories;

use IntegrityInvoice\Repositories\InvoiceRepositoryInterface;
use Invoice;

class DbInvoiceRepository implements InvoiceRepositoryInterface{
	
	public function getAll($tenantID = "", $perPage = "")
	{	
		return Invoice::where('tenantID','=', $tenantID)->orderBy('tenant_invoice_id','desc')->paginate($perPage);
	}
	
	
	public function find($tenantID ="", $tenant_invoice_id = 0)
	{
		return Invoice::where('tenantID','=', $tenantID)->where('tenant_invoice_id','=', $tenant_invoice_id)->first();
	}
	 
	public function create($input = array())
	{
		return Invoice::create($input);
	}
	
	public function update($tenantID ="", $id = 0, $input = array())
	{
		return Invoice::where('tenant_invoice_id', '=', $id)->where('tenantID', '=', $tenantID)->update($input);	
	}
	
	public function update_quote($tenantID ="", $id = 0, $input = array())
	{
		return Invoice::where('tenant_quote_id', '=', $id)->where('tenantID', '=', $tenantID)->update($input);	
	}
	
	
	public function convert($tenantID ="", $id = 0, $input = array())
	{
		return Invoice::where('tenant_quote_id', '=', $id)->where('tenantID', '=', $tenantID)->update($input);	
	}
	
	
	public function remove($tenantID ="", $tenant_invoice_id = 0)
	{
		return Invoice::where('tenantID', '=', $tenantID)->where('tenant_invoice_id', '=', $tenant_invoice_id)->delete();
	}
	
	public function removeQuote($tenantID ="", $tenant_quote_id = 0)
	{
		return Invoice::where('tenantID', '=', $tenantID)->where('tenant_quote_id', '=', $tenant_quote_id)->delete();
	}	
	
	public function removeAll($tenantID ="")
	{
		return Invoice::where('tenantID', '=', $tenantID)->delete();
	}

    public function getInvoicesRecurringToday(){
        $today = date('Y-m-d');
        return Invoice::where('recurring', '=', 1)->where('recur_next_date', '=', $today)->get();
    }

    public function getLastInvoiceID($tenantID =""){
       return Invoice::where('tenantID', '=', $tenantID)->where('quote', '=', 0)->orderBy('tenant_invoice_id', 'desc')->take(1)->pluck('tenant_invoice_id');
    }

    public function getInvoicesGeneratedToDayByRecurring()
    {
        $today = date('Y-m-d');
        return Invoice::where('created_from_recurring', '=', 1)->where('created_at', '>=', $today)->get();
    }

    public function getUnsentInvoicesGeneratedToDayByRecurringWithAutoSend()
    {
        // $today = strftime("%Y-%m-%d", time());
        $today = date('Y-m-d');
        return Invoice::where('created_from_recurring', '=', 1)->where('auto_send', '=', 1)->where('status', '=', 0)->where('created_at', '>=', $today)->get();
    }
 
}
