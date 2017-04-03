<?php namespace IntegrityInvoice\Services\PaymentGateway;
use IntegrityInvoice\Utilities\AppHelper;
use IntegrityInvoice\Services\Validation\PaymentGatewayValidator as Validator;
use IntegrityInvoice\Services\Validation\PaymentGatewayValidatorForCard as CardValidator;
use Carbon\Carbon;
 
class Updater {
	
	private $listener;
	protected $validator;
	protected $cardValidator;
	protected $paymentgateway;
 
	public function __construct($paymentgateway, $listener)
    {
    	$this->validator = new Validator;
		$this->cardValidator = new CardValidator;
		$this->listener = $listener;
		$this->paymentgateway = $paymentgateway;
	}
	
	public function update($input)
	{ 
		if($input['paypal_email'] != null && $input['paypal_email'] != "")
		{
			// Use Validation for Paypal email format
			
			if(! $this->validator->validate($input))
			{
				return $this->listener->paymentGatewayUpdateFails($this->validator->errors());	 
			} 
		}
		
		if($input['stripe_secret_key'] != "" || $input['stripe_publishable_key'] != "")
		{
			// Use Validation for Credit card
			
			if(! $this->cardValidator->validate($input))
			{ 
				return $this->listener->paymentGatewayUpdateFails($this->cardValidator->errors());	 
			} 
		}
		
		if($input['stripe_secret_key'] == "" || $input['stripe_secret_key'] == null)
		{
			$secret_key_value = $input['stripe_secret_key'];
		}
		else
		{
			$secret_key_value = AppHelper::encrypt($input['stripe_secret_key'], $this->listener->tenantID);
		}
		
		if($input['stripe_publishable_key'] == "" || $input['stripe_publishable_key'] == null)
		{
			$publishable_key_value = $input['stripe_secret_key'];
		}
		else
		{
			$publishable_key_value = AppHelper::encrypt($input['stripe_publishable_key'], $this->listener->tenantID);
		}
		 
		
		// Entrypt before inserting into DB	 
		$secure_input = array(
			'paypal_email' => $input['paypal_email'],
			'stripe_secret_key' => $secret_key_value,
			'stripe_publishable_key' => $publishable_key_value,
			'updated_at' => Carbon::now()
		);
		 
		$this->paymentgateway->update($this->listener->tenantID, $secure_input);
		
		return $this->listener->paymentGatewayUpdateSucceeds();
		
	}
	
}
 
