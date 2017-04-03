<?php namespace IntegrityInvoice\Billing;

use Config;
// use IntegrityInvoice\Repositories\PaymentsHistoryRepositoryInterface;
// use IntegrityInvoice\Services\PaymentHistory\Creator as PaymentHistoryCreator;
// use IntegrityInvoice\Services\PaymentHistory\Reader as PaymentHistoryReader;
//testing
use IntegrityInvoice\Utilities\AppHelper;
use IntegrityInvoice\Repositories\TenantRepositoryInterface; 
use IntegrityInvoice\Repositories\CompanyDetailsRepositoryInterface;
use IntegrityInvoice\Repositories\InvoiceRepositoryInterface; 
use IntegrityInvoice\Repositories\InvoicePaymentsRepositoryInterface;
use IntegrityInvoice\Repositories\PaymentGatewaysRepositoryInterface;
use IntegrityInvoice\Billing\ClientPaypalPaymentGateway;
use IntegrityInvoice\Services\PaymentGateway\Reader as PaymentGatewayReader;
use IntegrityInvoice\Services\Tenant\Reader as TenantReader;
use IntegrityInvoice\Services\Invoice\Reader as InvoiceReader;
use IntegrityInvoice\Services\Invoice\Updater as InvoiceUpdater;
use IntegrityInvoice\Services\InvoicePayments\Reader as InvoicePaymentReader;
use IntegrityInvoice\Services\InvoicePayments\Updater as InvoicePaymentUpdater;
use IntegrityInvoice\Mailers\AppMailer;
use Carbon\Carbon;
use Log;
use Preference;
use Invoice;
use InvoicePayment;
use User;
use Session;

class ClientPaypalPaymentGateway {

	public $last_error;			// holds the last error encountered
	public $ipn_log;				// bool: log IPN results to text file?

	public $ipn_log_file;			// filename of the IPN log
	public $ipn_response;			// holds the IPN response from paypal	
	public $ipn_data = array();	  // array contains the POST values for IPN
	public $fields = array();		// array holds the fields to submit to paypal
	public $paypal_url;

	public $submit_btn = '';		// Image/Form button
	public $button_path = '';		// The path of the buttons
	 
	public $invoicePaymentHistory;
	public $tenant;
	public $invoice;
	public $tenantID;
	
	public function __construct(InvoiceRepositoryInterface $invoice, InvoicePaymentsRepositoryInterface $invoicePayments, TenantRepositoryInterface $tenant, AppMailer $mailer)
	{
		$this->InvoicePayments = $invoicePayments;	
		$this->tenant = $tenant;	 
		$this->invoice = $invoice;
		$this->mailer = $mailer;
	 
		//$this->paypal_url = 'https://www.paypal.com/cgi-bin/webscr';  // Live
		//$this->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr'; // Test
		$this->paypal_url = Config::get('paypal.webscr_url');

		$this->last_error = '';
		$this->ipn_response = '';

		$this->ipn_log_file = Config::get('paypal.client_paypal_lib_ipn_log_file');
		$this->ipn_log = Config::get('paypal.client_paypal_lib_ipn_log'); 
		
		$this->button_path = Config::get('paypal.paypal_lib_button_path');
		
		// populate $fields array with a few default values.  See the paypal
		// documentation for a list of fields and their data types. These defaul
		// values can be overwritten by the calling script.
		$this->add_field('rm','2');			  // Return method = POST
		$this->add_field('cmd','_xclick');
		// update this to curency code in invoice ?
		$this->add_field('currency_code', Config::get('paypal.paypal_lib_currency_code'));
	   
		
	}

	public function button($value)
	{
		// changes the default caption of the submit button
		//$this->submit_btn = form_submit('pp_submit', $value);
	}

	public function image($file)
	{
		//$this->submit_btn = '<input type="image" name="add" src="' . site_url($this->button_path .'/'. $file) . '" border="0" />';
	}


	public function add_field($field, $value) 
	{
		// adds a key=>value pair to the fields array, which is what will be 
		// sent to paypal as POST variables.  If the value is already in the 
		// array, it will be overwritten.
		$this->fields[$field] = $value;
	}

	public function paypal_auto_form() 
	{
		// this function actually generates an entire HTML page consisting of
		// a form with hidden elements which is submitted to paypal via the 
		// BODY element's onLoad attribute.  We do this so that you can validate
		// any POST vars from you custom form before submitting to paypal.  So 
		// basically, you'll have your own form which is submitted to your script
		// to validate the data, which in turn calls this function to create
		// another hidden form and submit to paypal.

		$this->button('Click here if you\'re not automatically redirected...');

		echo '<html>' . "\n";
		echo '<head><title>Processing Payment...</title></head>' . "\n";
		echo '<body onLoad="documentd.forms[\'paypal_auto_form\'].submit();">' . "\n";
		echo '<p>Please wait, you will be redirected to the paypal website.</p>' . "\n";
		echo $this->paypal_form('paypal_auto_form');
		echo '</body></html>';
	}
 
	
	 public function submit_paypal_post() {
 
      // this function actually generates an entire HTML page consisting of
      // a form with hidden elements which is submitted to paypal via the 
      // BODY element's onLoad attribute.  We do this so that you can validate
      // any POST vars from you custom form before submitting to paypal.  So 
      // basically, you'll have your own form which is submitted to your script
      // to validate the data, which in turn calls this function to create
      // another hidden form and submit to paypal.
 
      // The user will briefly see a message on the screen that reads:
      // "Please wait, your order is being processed..." and then immediately
      // is redirected to paypal.      
	
      echo "<html>\n";
      echo "<head><title>Processing secure paypal payment...</title></head>\n";
      echo "<body style=\"max-width:600px; margin:0 auto; padding:15px; background-color:#eee;\" onLoad=\"document.forms['paypal_form'].submit();\">\n";
      echo "<center><h2 style=\"font-weight:normal\">Please wait, you";
      echo " will be redirected to the paypal website.</h2></center>\n";
      echo "<form method=\"post\" name=\"paypal_form\" ";
      echo "action=\"".$this->paypal_url."\">\n";

      foreach ($this->fields as $name => $value) {
         echo "<input type=\"hidden\" name=\"$name\" value=\"$value\"/>\n";
      }
      echo "<center><br/><br/>If you are not automatically redirected to ";
      echo "paypal within 15 seconds...<br/><br/>\n";
      echo "<input type=\"submit\" value=\"Click Here\"></center>\n";
      
      echo "</form>\n";
      echo "</body></html>\n";
    
   }
 
	
	public function validate_ipn()
	{

		Log::info('inside : '. __CLASS__.'@'.__FUNCTION__);
		 
		// parse the paypal URL
		$url_parsed = parse_url($this->paypal_url);		  

		// generate the post string from the _POST vars aswell as load the
		// _POST vars into an arry so we can play with them from the calling
		// script.
		$post_string = '';	 
	 
		
		if (isset($_POST))
        {
            foreach ($_POST as $field=>$value)
            {       // str_replace("\n", "\r\n", $value)
                    // put line feeds back to CR+LF as that's how PayPal sends them out
                    // otherwise multi-line data will be rejected as INVALID

                $value = str_replace("\n", "\r\n", $value);
				$value = str_replace(';', '', $value);
                $this->ipn_data[$field] = $value;
                $post_string .= $field.'='.urlencode(stripslashes($value)).'&';

            }
        }  
		
		$post_string.="cmd=_notify-validate"; // append ipn command
		// open the connection to paypal
		// $fp = fsockopen($url_parsed['host'],"80",$err_num,$err_str,30);
		//$fp = fsockopen('ssl://www.paypal.com',443,$err_num,$err_str,30); // FOR LIVE
		//$fp = fsockopen('ssl://www.sandbox.paypal.com',443,$err_num,$err_str,30);  // TEST
		$fp = fsockopen(Config::get('paypal.fsockopen_url'), 443, $err_num, $err_str, 30); 
		 
		if(!$fp)
		{
			// could not open the connection.  If loggin is on, the error message
			// will be in the log.
			$this->last_error = "fsockopen error no. $errnum: $errstr";
			$this->log_ipn_results(false);		 
			return false;
		} 
		else
		{ 
			// Post the data back to paypal
			fputs($fp, "POST $url_parsed[path] HTTP/1.1\r\n"); 
			fputs($fp, "Host: $url_parsed[host]\r\n"); 
			fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n"); 
			fputs($fp, "Content-length: ".strlen($post_string)."\r\n"); 
			fputs($fp, "Connection: close\r\n\r\n"); 
			fputs($fp, $post_string . "\r\n\r\n"); 

			// loop through the response from the server and append to variable
			while(!feof($fp))
				$this->ipn_response .= fgets($fp, 1024); 

			fclose($fp); // close connection
		}

		
		if (preg_match("/VERIFIED/", $this->ipn_response))
		{
			 // mail('moses@divinitymedia.co.uk', 'IPN data', $this->ipn_response);
			 

			 $item_number = $this->ipn_data['item_number'];
			 $item_name = $this->ipn_data['item_name'];
			 $payment_status = $this->ipn_data['payment_status'];
			 $payment_amount = $this->ipn_data['mc_gross'];
			 $payment_currency = $this->ipn_data['mc_currency'];
			 $txn_id = $this->ipn_data['txn_id'];
			 $receiver_email = $this->ipn_data['receiver_email'];
			 $payer_email = $this->ipn_data['payer_email'];
			 			 
			  
			 $custom = str_replace(';', '', $this->ipn_data['custom']);
			 Log::info($this->ipn_data);
			 // CUSTOM VARIABLES
			 list($tenant_id, $client_email, $client_id, $client_company, $tenant_invoice_id, $company_name, $currency_code ) = explode('&', $custom);
			 
			 // Trim any White space
			 $tenant_id = trim($tenant_id);
			 $client_email = trim($client_email);
			 $client_id = trim($client_id);
			 $client_company = trim($client_id);
			 $tenant_invoice_id = trim($tenant_invoice_id);
			 $company_name = trim($company_name);
			 $currency_code = trim($currency_code);
			  





			 
			// $data = array(
			// 	'txn_id' => $txn_id,
			// 	'sender_email' => $payer_email,
			// 	'client_email' => $client_email,
			// 	'tenantID' => $tenant_id,
			// 	'amount' => $payment_amount,
			// 	'payment_system' => 'Paypal',
			// 	'created_at' => strftime("%Y-%m-%d %H:%M:%S", time()),
			// 	'updated_at' => strftime("%Y-%m-%d %H:%M:%S", time()),
			// );
			 
			 
			  
		   // validate other stuffs
		  if($payment_status == "Completed"){

			// $paymentHistoryReaderService = new PaymentHistoryReader($this->paymentHistory, $this);

			// $invoicePaymentReaderService = new InvoicePaymentReader($this->invoicePaymentHistory, $this);
			// if($invoicePaymentReaderService->transactionExists($txn_id)) {
			// 	return; 
			// }

			$data = array(
				'client_email' => $client_email,
				// 'token' => Input::get('stripe-token'),
				'amount' => $payment_amount,			
				'tenant_invoice_id' => $tenant_invoice_id,	 	 
				'tenantID' => $tenant_id,
				'client_id' => $client_id,
				'currency_code' => $currency_code,
				'receiver_email' => $receiver_email,
				'client_company' => $client_company,
				'company_name' => $company_name		 
			);

			//  // RECORD PROCESSING LOGIC
			// $invoicePaymentCreatorService = new InvoicePaymentCreator($this->invoicePaymentHistory, $this);

			// $invoicePaymentCreatorService->createByPaypal(array(
			// 	'online_ref' => $txn_id,
			// 	'sender_email' => $payer_email,
			// 	'tenant_invoice_id' => $tenant_invoice_id,
			// 	'tenantID' => $tenant_id,
			// 	'client_id' => $client_id,
			// 	'amount' => $payment_amount,
			// 	'currency_code' => $currency_code,
			// 	'receiver_email' => $receiver_email,
			// 	'client_company' => $client_company,
			// 	'company_name' => $company_name,		 
			// 	'payment_method' => 'Online',
			// 	'created_at' => strftime("%Y-%m-%d %H:%M:%S", time()),
			// 	'updated_at' => strftime("%Y-%m-%d %H:%M:%S", time()),
			// ));
		// $billing = \App::make('IntegrityInvoice\Billing\ClientBillingInterface');
	  
		// try
		// {
		// 	$data = $billing->charge(array(
		// 		// 'email' => Input::get('email'),
		// 		// 'token' => Input::get('stripe-token'),
		// 		'amount' => $payment_amount,			
		// 		'tenant_invoice_id' => $tenant_invoice_id,	 	 
		// 		'tenantID' => $tenant_id,
		// 		'client_id' => $client_id,
		// 		'currency_code' => $currency_code,
		// 		'receiver_email' => $receiver_email,
		// 		'client_company' => $client_company,
		// 		'company_name' => $company_name		 
		// 	));
		 
		// }
		// catch(exception $e)
		// {
		// 	   return Redirect::back()->with('failed_flash_message', 'There was a problem processing the trasaction.')->with(['data' => $data]);
		//     // return Redirect::back()->with('failed_flash_message', $e->getMessage())->withInput();
		// }
		
		// Update payment records 
		
		$tenantID = $data['tenantID'];
		
		$this->date_format = Preference::where('tenantID', '=', $tenantID)->pluck('date_format');
		
		$tenant_invoice_id = $data['tenant_invoice_id'];
		$valid_amount = $data['amount'];
		
		$invoice = Invoice::where('tenantID', '=', $tenantID)->where('tenant_invoice_id', '=',$tenant_invoice_id)->first();
		$total_payment = InvoicePayment::where('tenantID', '=', $invoice->tenantID)->where('tenant_invoice_id', '=', $invoice->tenant_invoice_id)->sum('amount');				
		$remaning_balance = $invoice->balance_due - $total_payment;
		 
  
		$record_data = array(
			'tenant_invoice_id' => $tenant_invoice_id,
			'amount' => $valid_amount,
			'payment_method' => 'Online',			  			
			'tenantID' => $tenantID,		 
			'client_id' => $data['client_id'],
			'created_at' => Carbon::now()
	    );
		
		
		
		if(InvoicePayment::create($record_data)){
			 
			$total_payment = InvoicePayment::where('tenantID', '=', $tenantID)->where('tenant_invoice_id', '=', $tenant_invoice_id)->sum('amount');
		 
			
			// Check to see if total amount to date is equal to amount due
			if($total_payment == 0){
				$update_data = array('payment' => 0);
				$updateService = new InvoiceUpdater($this->invoice, $this);		
				$updateService->update_no_redirect($$tenantID, $tenant_invoice_id, $update_data); 
			 
			}elseif($total_payment < $invoice->balance_due){
				$update_data = array('payment' => 1);
				$updateService = new InvoiceUpdater($this->invoice, $this);		
				$updateService->update_no_redirect($tenantID, $tenant_invoice_id, $update_data);
				
			}elseif($total_payment >= $invoice->balance_due){							
				$update_data = array('payment' => 2);
				$updateService = new InvoiceUpdater($this->invoice, $this);		
				$updateService->update_no_redirect($tenantID, $tenant_invoice_id, $update_data);
			}
			 
			// Update last payment date
			$update_data = array('last_payment_date' => Carbon::now());
			$updateService = new InvoiceUpdater($this->invoice, $this);		
			$updateService->update_no_redirect($tenantID, $tenant_invoice_id, $update_data); 
  
		}

		
		//// EMAILS ////
		
		$user = User::where('tenantID', '=', $tenantID)->where('level', '=', 2)->first();
	  
		// Email Buyer about the successful payment.		
	 
		$this->mailer->seller_invoice_payment_notification($user->firstname, $data['receiver_email'], $data['company_name'], $data['tenant_invoice_id'], $data['client_email'], $data['client_company'], 'Paypal',  Carbon::now(), "Payment received for Invoice ".$data['tenant_invoice_id'].".", $data['client_company']);
		
		// Email Seller about Payment	
		$this->mailer->buyer_invoice_payment_notification($data['receiver_email'], $data['company_name'], $data['tenant_invoice_id'], $data['client_email'], 'Paypal', 'Invoice Payment confirmation.');

		  
		unset($data['token']);
		unset($data['customerId']);
		unset($data['client_id']);
		//processing ends - parijat
			 
		
		// If fully paid mark as paid
		// If pard paid mark as part paid
		// Send Email to tenant of payment to invoice
		
	 	 Session::put('paypal_success_data', $data);


			//  Session::put('paypal_success_data', $data);
			
		  } // End if payment status completed

			$this->log_ipn_results(true);
			return true;		 
		} 
		else 
		{
			// Invalid IPN transaction.  Check the log for details.
			$this->last_error = 'IPN Validation Failed.';
			$this->log_ipn_results(false);	
			return false;
		}
		
	} // END validate_ipn
	

	public function log_ipn_results($success) 
	{
		if (!$this->ipn_log) return;  // is logging turned off?

		// Timestamp
		$text = '['.date('m/d/Y g:i A').'] - '; 

		// Success or failure being logged?
		if ($success) $text .= "SUCCESS!\n";
		else $text .= 'FAIL: '.$this->last_error."\n";

		// Log the POST variables
		$text .= "IPN POST Vars from Paypal:\n";
		foreach ($this->ipn_data as $key=>$value)
			$text .= "$key=$value, ";

		// Log the response from the paypal server
		$text .= "\nIPN Response from Paypal Server:\n ".$this->ipn_response;

		// Write to log
		$fp=fopen($this->ipn_log_file,'a');
		fwrite($fp, $text . "\n\n"); 

		fclose($fp);  // close file
	}


	public function dump() 
	{
		// Used for debugging, this function will output all the field/value pairs
		// that are currently defined in the instance of the class using the
		// add_field() function.

		ksort($this->fields);
		echo '<h2>ppal->dump() Output:</h2>' . "\n";
		echo '<code style="font: 12px Monaco, \'Courier New\', Verdana, Sans-serif;  background: #f9f9f9; border: 1px solid #D0D0D0; color: #002166; display: block; margin: 14px 0; padding: 12px 10px;">' . "\n";
		foreach ($this->fields as $key => $value) echo '<strong>'. $key .'</strong>:	'. urldecode($value) .'<br/>';
		echo "</code>\n";
	}

}

?>