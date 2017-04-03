<?php namespace IntegrityInvoice\Providers;
 
use Illuminate\Support\ServiceProvider;
 
class BillingServiceProvider extends ServiceProvider {
 
  public function register()
  {
  	
	$this->app->bind(
      'IntegrityInvoice\Billing\BillingInterface',
      'IntegrityInvoice\Billing\StripeBilling'
    );
	
	
	$this->app->bind(
      'IntegrityInvoice\Billing\ClientBillingInterface',
      'IntegrityInvoice\Billing\ClientStripeBilling'
    );
	 
  }
 
}