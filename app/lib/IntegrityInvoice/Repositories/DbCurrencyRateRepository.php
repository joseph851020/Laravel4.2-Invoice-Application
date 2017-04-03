<?php namespace IntegrityInvoice\Repositories;

use IntegrityInvoice\Repositories\CurrencyRateRepositoryInterface;
use CurrencyRate;

class DbCurrencyRateRepository implements CurrencyRateRepositoryInterface{
	
	public function getAll($tenantID = "", $perPage = "")
	{
		return CurrencyRate::where('tenantID','=', $tenantID)->orderBy('created_at','desc')->paginate($perPage);
	}
	
	
	public function find($tenantID ="", $id = 0)
	{
		return CurrencyRate::where('tenantID','=', $tenantID)->where('id','=', $id)->first();
	}
	
	public function findByCurrencyCode($tenantID, $code)
	{
		return CurrencyRate::where('tenantID','=', $tenantID)->where('currency_code','=', $code)->first();
	}
	
	
	public function create($input = array())
	{
		return CurrencyRate::create($input);
	}
	
	public function update($tenantID ="", $id = 0, $input = array())
	{
		return CurrencyRate::where('id', '=', $id)->where('tenantID', '=', $tenantID)->update($input);	
	}
	
	
	public function remove($tenantID ="", $currency_code = "")
	{
		return CurrencyRate::where('currency_code', '=', $currency_code)->where('tenantID', '=', $tenantID)->delete();
	}
	
	public function removeAll($tenantID ="")
	{
		return CurrencyRate::where('tenantID', '=', $tenantID)->delete();
	}
 
}
