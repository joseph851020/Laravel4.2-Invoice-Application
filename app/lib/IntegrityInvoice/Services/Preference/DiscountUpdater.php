<?php namespace IntegrityInvoice\Services\Preference;

use Request;
use Redirect;
use Session;

class DiscountUpdater {
 
	protected $listener;
	protected $preference;
 
	public function __construct($preference, $listener)
    {
		$this->listener = $listener;
		$this->preference = $preference;
	}
	
	public function update($input)
	{	  	
		if($this->preference->update($this->listener->tenantID, $input))
		{
			return $this->listener->discountUpdateSucceeds($this->listener->tenantID);
		}
		else
		{
			return $this->listener->discountUpdateFails();
		}	
		
	}
	
	
	public function update_with_redirect($tenant_invoice_id, $input)
	{
	 	  	
		if($this->preference->update($this->listener->tenantID, $input))
		{
			Session::flash('discount_or_tax_updated', 1);
			
			if(str_contains(Request::url(), 'invoice'))
			{
				return Redirect::route('edit_invoice', $tenant_invoice_id);
			}
			else
			{
				return Redirect::route('edit_quote', $tenant_invoice_id);
			}
			
			
	 	
		}
		else
		{
			return $this->listener->discountUpdateFails();
		}	
		
	}
	
	
	
	public function update_with_redirect_copy($tenant_invoice_id, $input)
	{
	 	  	
		if($this->preference->update($this->listener->tenantID, $input))
		{
			Session::flash('discount_or_tax_updated', 1);
			
			if(str_contains(Request::url(), 'invoice'))
			{
				return Redirect::route('copy_invoice', $tenant_invoice_id);
			}
			else
			{
				return Redirect::route('copy_quote', $tenant_invoice_id);
			}
			
			
	 	
		}
		else
		{
			return $this->listener->discountUpdateFails();
		}	
		
	}
	
 
}