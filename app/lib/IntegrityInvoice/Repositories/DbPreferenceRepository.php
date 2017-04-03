<?php namespace IntegrityInvoice\Repositories;

use IntegrityInvoice\Repositories\PreferenceRepositoryInterface;
use Preference;

class DbPreferenceRepository implements PreferenceRepositoryInterface{
 
	public function find($tenantID ="")
	{
		return Preference::where('tenantID','=', $tenantID)->first();
	}
	
	
	public function create($input = array())
	{
		return Preference::create($input);
	}
	
	public function update($tenantID ="", $input = array())
	{
		return Preference::where('tenantID', '=', $tenantID)->update($input);	
	}
	
	
	public function remove($tenantID ="")
	{
		return Preference::where('tenantID', '=', $tenantID)->delete();
	}
	
	public function ontime_setup_verify($tenantID ="")
	{
		$preference = $this->find($tenantID);
		if($preference->time_zone != null && $preference->time_zone != "" && $preference->currency_code != null && $preference->currency_code != "")
		{
			return true;
		}
		
		return false;
	}
	
}
