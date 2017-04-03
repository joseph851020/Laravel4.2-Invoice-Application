<?php namespace IntegrityInvoice\Billing;

use Config;
use Stripe;
use Stripe_Charge;
use Stripe_Customer;
use Stripe_InvalidRequestError;
use Stripe_CardError;
use Stripe_AuthenticationError;
use Stripe_ApiConnectionError;
use Stripe_Error;
use Exception;

use IntegrityInvoice\Repositories\PaymentsHistoryRepositoryInterface;
use IntegrityInvoice\Repositories\TenantRepositoryInterface;
use IntegrityInvoice\Repositories\InvoicePaymentsRepositoryInterface;
use IntegrityInvoice\Repositories\PaymentGatewaysRepositoryInterface;
use IntegrityInvoice\Repositories\InvoiceRepositoryInterface;
use IntegrityInvoice\Services\InvoicesPayments\Updater as InvoicePaymentsUpdater;

use IntegrityInvoice\Utilities\AppHelper as AppHelper;

// Stripe_Customer::retrieve($companyDetails->billingId);

class ClientStripeBilling implements ClientBillingInterface{
	
	public $invoice;
	public $tenant;
	public $tenantID;
	public $invoicePayments;
	public $paymentGateways;
	
	public function __construct(InvoiceRepositoryInterface $invoice, InvoicePaymentsRepositoryInterface $invoicePayments, TenantRepositoryInterface $tenant, PaymentGatewaysRepositoryInterface $paymentGateways)
	{
		$this->invoice = $invoice;
		$this->tenant = $tenant;	
		$this->invoicePayments = $invoicePayments;
		$this->paymentGateways = $paymentGateways;	
	   // Stripe::setApiKey(Config::get('stripe.secret_key'));
	}
	
	public function charge(array $data)
	{
		 
		$stripe_gateway = $this->paymentGateways->find($data['tenantID']);		
	    Stripe::setApiKey(AppHelper::decrypt($stripe_gateway->stripe_secret_key, $data['tenantID']));
	  
		try
		{
			$customer = Stripe_Customer::create(array(			
				'description' => $data['email'],
				'card' => $data['token']
			));
			 
		   Stripe_Charge::create(array(
				'customer' => $customer->id,
				'amount' => (int)$data['amount'], // £10
				'currency' => strtolower($data['currency_code'])		
			));
			
			
			
			// Update Subscription
			return $this->chargeComplete($data, $customer->id);
			
			// return $customer->id;
		} 
		
		catch(Stripe_InvalidRequestError $e)
		{
			// Invalid parameters were supplied to Stripe's API
			throw new Exception($e->getMessage());
		}
		
		catch (Stripe_CardError $e)
		{
			throw new Exception($e->getMessage());
		}
		
		catch(Stripe_AuthenticationError $e)
		{
			// Authentication with Stripe API failed
			// (Maybe you changed API keys recently)
			throw new Exception($e->getMessage());
		}
		
		catch(Stripe_ApiConnectionError $e)
		{
			// Network Communication with Stripe failed
			throw new Exception($e->getMessage());
		}
		
		catch(Stripe_Error $e)
		{
			// Display a very generic error to the user
			throw new Exception($e->getMessage());
		}
		
		catch(Exception $e)
		{
			// Something else happened, completely unrelated to Stripe
			throw new Exception($e->getMessage());
		}
	}
	
	 
	public function chargeComplete($data, $customerId)
	{
		$data['customerId'] = $customerId;		
		return $data;	 
	} 
	
 
}
