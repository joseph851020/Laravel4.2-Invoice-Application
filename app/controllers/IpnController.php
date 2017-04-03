<?php

use IntegrityInvoice\Utilities\AppHelper;
use IntegrityInvoice\Billing\PaypalPaymentGateway;


class IpnController extends \BaseController {

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public $paypalGateway;
	
	public function __construct(PaypalPaymentGateway $paypalGateway)
    { 
		$this->paypalGateway = $paypalGateway;
    }
	
	public function store()
	{
		// Log
		//$file = storage_path().'/logs/paypal_ipn.log';
		// Open the file to get existing content
		//$current = file_get_contents($file);
		// Append a new person to the file
		// $current .= "Got here\n";
		//$current = json_encode($_POST);
		// Write the contents back to the file
		//file_put_contents($file, $current);
	  

		// load email lib and email results
	
		// Payment has been received and IPN is verified.  This is where you
		// update your database to activate or process the order, or setup
		// the database with the user's order details, email an administrator,
		// etc. You can access a slew of information via the ipn_data() array.
 
		// Check the paypal documentation for specifics on what information
		// is available in the IPN POST variables.  Basically, all the POST vars
		// which paypal sends, which we send back for validation, are now stored
		// in the ipn_data() array.
 
		// For this example, we'll just email ourselves ALL the data.
		// $to  = 'ipn@integrityinvoice.com';    //  your email

		if ($this->paypalGateway->validate_ipn()) 
		{
			// For this example, we'll just email ourselves ALL the data.
			 
			// Send email results		 		
			//$this->mailer->ipn_message($to, $this->paypalGateway->ipn_data['payer_name'], $this->paypalGateway->ipn_data['payer_email']);
			//mail('info@integrityinvoice.com', 'IPN Validated', 'IPN Validated');
		 		 
		}
	} // End IPN

}