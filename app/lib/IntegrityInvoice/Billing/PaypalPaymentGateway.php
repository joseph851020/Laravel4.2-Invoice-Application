<?php namespace IntegrityInvoice\Billing;

use Config;
use IntegrityInvoice\Repositories\PaymentsHistoryRepositoryInterface;
use IntegrityInvoice\Repositories\TenantRepositoryInterface;
use IntegrityInvoice\Services\PaymentHistory\Creator as PaymentHistoryCreator;
use IntegrityInvoice\Services\PaymentHistory\Reader as PaymentHistoryReader;
use IntegrityInvoice\Services\Tenant\Updater as TenantUpdater;

class PaypalPaymentGateway {

	public $last_error; // holds the last error encountered
	public $ipn_log; // bool: log IPN results to text file?

	public $ipn_log_file; // filename of the IPN log
	public $ipn_response; // holds the IPN response from paypal
	public $ipn_data = array(); // array contains the POST values for IPN
	public $fields = array(); // array holds the fields to submit to paypal
	public $paypal_url;

	public $submit_btn = ''; // Image/Form button
	public $button_path = ''; // The path of the buttons

	public $paymentHistory;
	public $tenant;
	public $tenantID;

	public function __construct(PaymentsHistoryRepositoryInterface $paymentHistory, TenantRepositoryInterface $tenant) {

		$this->paymentHistory = $paymentHistory;
		$this->tenant = $tenant;

		//$this->paypal_url = 'https://www.paypal.com/cgi-bin/webscr';  // Live
		// $this->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr'; // Test
		$this->paypal_url = Config::get('paypal.webscr_url');

		$this->last_error = '';
		$this->ipn_response = '';

		$this->ipn_log_file = Config::get('paypal.paypal_lib_ipn_log_file');
		$this->ipn_log = Config::get('paypal.paypal_lib_ipn_log');

		$this->button_path = Config::get('paypal.paypal_lib_button_path');

		// populate $fields array with a few default values.  See the paypal
		// documentation for a list of fields and their data types. These defaul
		// values can be overwritten by the calling script.
		$this->add_field('rm', '2'); // Return method = POST
		$this->add_field('cmd', '_xclick');

		$this->add_field('currency_code', Config::get('paypal.paypal_lib_currency_code'));
		//$this->add_field('quantity', '1');
		//$this->button('Pay Now!');

	}

	public function button($value) {
		// changes the default caption of the submit button
		//$this->submit_btn = form_submit('pp_submit', $value);
	}

	public function image($file) {
		//$this->submit_btn = '<input type="image" name="add" src="' . site_url($this->button_path .'/'. $file) . '" border="0" />';
	}

	public function add_field($field, $value) {
		// adds a key=>value pair to the fields array, which is what will be
		// sent to paypal as POST variables.  If the value is already in the
		// array, it will be overwritten.
		$this->fields[$field] = $value;
	}

	public function paypal_auto_form() {
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
		echo '<body onLoad="document.forms[\'paypal_auto_form\'].submit();">' . "\n";
		echo '<p>Please wait, your order is being processed and you will be redirected to the paypal website.</p>' . "\n";
		echo $this->paypal_form('paypal_auto_form');
		echo '</body></html>';
	}

	/* function paypal_form($form_name='paypal_form')
		{
			$str = '';
			$str .= '<form method="post" action="'.$this->paypal_url.'" name="'.$form_name.'"/>' . "\n";
			foreach ($this->fields as $name => $value)
				$str .= form_hidden($name, $value) . "\n";
			$str .= '<p>'. $this->submit_btn . '</p>';
			$str .= form_close() . "\n";

			return $str;
	*/

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
		echo "<body style=\"max-width:600px; margin:0 auto; padding:15px; background-color:#eee;\" onLoad=\"documentd.forms['paypal_form'].submit();\">\n";
		echo "<center><h2 style=\"font-weight:normal\">Please wait, your Sighted order is being processed and you";
		echo " will be redirected to the paypal website.</h2></center>\n";
		echo "<form method=\"post\" name=\"paypal_form\" ";
		echo "action=\"" . $this->paypal_url . "\">\n";

		foreach ($this->fields as $name => $value) {
			echo "<input type=\"hidden\" name=\"$name\" value=\"$value\"/>\n";
		}
		echo "<center><br/><br/>If you are not automatically redirected to ";
		echo "paypal within 15 seconds...<br/><br/>\n";
		echo "<input type=\"submit\" value=\"Click Here\"></center>\n";

		echo "</form>\n";
		echo "</body></html>\n";

	}

	public function get_valid_duration_from_item_number($item_number) {

		switch ((int) $item_number) {

		case 1:
			$data = array('plan' => 2, 'duration' => 30);
			break;

		case 2:
			$data = array('plan' => 2, 'duration' => 91);
			break;

		case 3:
			$data = array('plan' => 2, 'duration' => 183);
			break;

		case 4:
			$data = array('plan' => 2, 'duration' => 365);
			break;

		case 5:
			$data = array('plan' => 3, 'duration' => 30);
			break;

		case 6:
			$data = array('plan' => 3, 'duration' => 91);
			break;

		case 7:
			$data = array('plan' => 3, 'duration' => 183);
			break;

		case 8:
			$data = array('plan' => 3, 'duration' => 365);
			break;

		case 9:
			$data = array('plan' => 4, 'duration' => 30);
			break;

		case 10:
			$data = array('plan' => 4, 'duration' => 91);
			break;

		case 11:
			$data = array('plan' => 4, 'duration' => 183);
			break;

		case 12:
			$data = array('plan' => 4, 'duration' => 365);
			break;

		default:
			$data = array('plan' => 0, 'duration' => 0);
			break;
		}

		return $data;

	} // End get_valid_duration_from_item_number

	public function validate_ipn() {

		// parse the paypal URL
		$url_parsed = parse_url($this->paypal_url);

		// generate the post string from the _POST vars aswell as load the
		// _POST vars into an arry so we can play with them from the calling
		// script.
		$post_string = '';

		if (isset($_POST)) {
			foreach ($_POST as $field => $value) {
				// str_replace("\n", "\r\n", $value)
				// put line feeds back to CR+LF as that's how PayPal sends them out
				// otherwise multi-line data will be rejected as INVALID

				$value = str_replace("\n", "\r\n", $value);
				$value = str_replace(';', '', $value);
				$this->ipn_data[$field] = $value;
				$post_string .= $field . '=' . urlencode(stripslashes($value)) . '&';

			}
		}

		$post_string .= "cmd=_notify-validate"; // append ipn command

		// open the connection to paypal
		// $fp = fsockopen($url_parsed['host'],"80",$err_num,$err_str,30);
		//$fp = fsockopen('ssl://www.paypal.com',443,$err_num,$err_str,30); // FOR LIVE
		//$fp = fsockopen('ssl://www.sandbox.paypal.com',443,$err_num,$err_str,30);  // TEST
		$fp = fsockopen(Config::get('paypal.fsockopen_url'), 443, $err_num, $err_str, 30);

		if (!$fp) {
			// could not open the connection.  If loggin is on, the error message
			// will be in the log.
			$this->last_error = "fsockopen error no. $errnum: $errstr";
			$this->log_ipn_results(false);
			return false;
		} else {
			// Post the data back to paypal
			fputs($fp, "POST $url_parsed[path] HTTP/1.1\r\n");
			fputs($fp, "Host: $url_parsed[host]\r\n");
			fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
			fputs($fp, "Content-length: " . strlen($post_string) . "\r\n");
			fputs($fp, "Connection: close\r\n\r\n");
			fputs($fp, $post_string . "\r\n\r\n");

			// loop through the response from the server and append to variable
			while (!feof($fp)) {
				$this->ipn_response .= fgets($fp, 1024);
			}

			fclose($fp); // close connection
		}

		if (preg_match("/VERIFIED/", $this->ipn_response)) {

			$item_number = $this->ipn_data['item_number'];
			$item_name = $this->ipn_data['item_name'];
			$payment_status = $this->ipn_data['payment_status'];
			$payment_amount = $this->ipn_data['mc_gross'];
			$payment_currency = $this->ipn_data['mc_currency'];
			$txn_id = $this->ipn_data['txn_id'];
			$receiver_email = $this->ipn_data['receiver_email'];
			$payer_email = $this->ipn_data['payer_email'];

			// mail('sales@integrityinvoice.com', 'test2 data', json_encode($this->ipn_data));

			$custom = str_replace(';', '', $this->ipn_data['custom']);

			// CUSTOM VARIABLES
			list($tenant_id, $renewing_expired_account, $extending_account, $upgrading_from_unexpired_account, $last_recorded_start_date, $last_recorded_end_date) = explode('&', $custom);

			// Trim any White space
			$tenant_id = trim($tenant_id);
			$renewing_expired_account = trim($renewing_expired_account);
			$extending_account = trim($extending_account);
			$upgrading_from_unexpired_account = trim($upgrading_from_unexpired_account);
			$last_recorded_start_date = trim($last_recorded_start_date);
			$last_recorded_end_date = trim($last_recorded_end_date);

			$message = $tenant_id . " , " . $renewing_expired_account . " , " . $extending_account . " , " . $upgrading_from_unexpired_account . " , " . $last_recorded_start_date . " , " . $last_recorded_end_date;

			// RECORD PROCESSING LOGIC

			// Renewing expired account logic
			if ($renewing_expired_account == "yes") {
				$valid_from = date('Y-m-d', strtotime('today'));
				$v = $this->get_valid_duration_from_item_number($item_number);
				$duration = $v['duration'];
				$plan = $v['plan'];

				$new_date = strtotime('+' . (int) $duration . ' day', strtotime($valid_from));
				$valid_to = date('Y-m-d', $new_date);
			}

			// Extending current account logic
			if ($extending_account == "yes") {

				$valid_from = $last_recorded_end_date;
				$v = $this->get_valid_duration_from_item_number($item_number);
				$duration = $v['duration'];
				$plan = $v['plan'];
				// Find the difference of today and last valid to date on the account
				$diff = strtotime($last_recorded_end_date) - strtotime($valid_from);
				$days = floor($diff / (60 * 60 * 24));
				// Add difference of days still active to the number of days newly paid for
				$total_duration = $duration + $days;

				$new_date = strtotime('+' . (int) $total_duration . ' day', strtotime($valid_from));
				$valid_to = date('Y-m-d', $new_date);
			}

			// Upgrading from unexpired account logic
			if ($upgrading_from_unexpired_account == "yes") {

				$valid_from = date('Y-m-d', strtotime('today'));
				$v = $this->get_valid_duration_from_item_number($item_number);
				$duration = $v['duration'];
				$plan = $v['plan'];

				// Find the difference of today and last valid to date on the account
				$diff = strtotime($last_recorded_end_date) - strtotime($valid_from);
				$days = floor($diff / (60 * 60 * 24));

				// Add difference of days still active to the number of days newly paid for
				$total_duration = $duration + $days;

				$new_date = strtotime('+' . (int) $total_duration . ' day', strtotime($valid_from));
				$valid_to = date('Y-m-d', $new_date);
			}

			// validate other stuffs
			if ($payment_status == "Completed") {

				$paymentHistoryReaderService = new PaymentHistoryReader($this->paymentHistory, $this);

				// check that txn_id has not been processed
				if (!$paymentHistoryReaderService->transactionExists($txn_id)) {

					//if($receiver_email == "sales@integrityinvoice.com"){  // LIVE
					if ($receiver_email == Config::get('paypal.seller_email')) {

						// Continue
						if ($payment_currency == "USD") {

							// Add Transaction
							//$today = strftime("%Y-%m-%d", time());

							// set new expiry date
							//$new_exp = strtotime ( '+'.(int)$new_plan_duration.' day' , strtotime ($today));
							//$new_exp = date ( 'Y-m-j' , $new_exp);

							$payment_data = array(
								'txn_id' => $txn_id,
								'sender_email' => $payer_email,
								'tenantID' => $tenant_id,
								'amount' => $payment_amount,
								'valid_from' => $valid_from,
								'valid_to' => $valid_to,
								'subscription_type' => $plan,
								'payment_system' => 'Paypal',
								'created_at' => strftime("%Y-%m-%d %H:%M:%S", time()),
								'updated_at' => strftime("%Y-%m-%d %H:%M:%S", time()),
							);

							// Record new subscription payment

							$paymentHistoryCreatorService = new PaymentHistoryCreator($this->paymentHistory, $this);
							if ($paymentHistoryCreatorService->createByPaypal(array(
								'txn_id' => $txn_id,
								'sender_email' => $payer_email,
								'tenantID' => $tenant_id,
								'amount' => $payment_amount,
								'valid_from' => $valid_from,
								'valid_to' => $valid_to,
								'subscription_type' => $plan,
								'payment_system' => 'Paypal',
								'created_at' => strftime("%Y-%m-%d %H:%M:%S", time()),
								'updated_at' => strftime("%Y-%m-%d %H:%M:%S", time()),
							))) {
								// Log Valid IPN transaction.
								$this->log_ipn_results(true);

								// upgrade
								$tenantUpdateService = new TenantUpdater($this->tenant, $this);
								$tenantUpdateService->updateStatus(array(
									'account_plan_id' => $plan,
									'status' => 1,
									'created_at' => strftime("%Y-%m-%d %H:%M:%S", time()),
									'updated_at' => strftime("%Y-%m-%d %H:%M:%S", time()),
								));

							}

						} // End if $payment_currency

					} // End if receiver email

				} // End if check_transaction_exists

			} // End if payment status completed

			return true;
		} else {
			// Invalid IPN transaction.  Check the log for details.
			$this->last_error = 'IPN Validation Failed.';
			$this->log_ipn_results(false);
			return false;
		}

	} // END validate_ipn

	public function log_ipn_results($success) {
		if (!$this->ipn_log) {
			return;
		}
		// is logging turned off?

		// Timestamp
		$text = '[' . date('m/d/Y g:i A') . '] - ';

		// Success or failure being logged?
		if ($success) {
			$text .= "SUCCESS!\n";
		} else {
			$text .= 'FAIL: ' . $this->last_error . "\n";
		}

		// Log the POST variables
		$text .= "IPN POST Vars from Paypal:\n";
		foreach ($this->ipn_data as $key => $value) {
			$text .= "$key=$value, ";
		}

		// Log the response from the paypal server
		$text .= "\nIPN Response from Paypal Server:\n " . $this->ipn_response;

		// Write to log
		$fp = fopen($this->ipn_log_file, 'a');
		fwrite($fp, $text . "\n\n");

		fclose($fp); // close file
	}

	public function dump() {
		// Used for debugging, this function will output all the field/value pairs
		// that are currently defined in the instance of the class using the
		// add_field() function.

		ksort($this->fields);
		echo '<h2>ppal->dump() Output:</h2>' . "\n";
		echo '<code style="font: 12px Monaco, \'Courier New\', Verdana, Sans-serif;  background: #f9f9f9; border: 1px solid #D0D0D0; color: #002166; display: block; margin: 14px 0; padding: 12px 10px;">' . "\n";
		foreach ($this->fields as $key => $value) {
			echo '<strong>' . $key . '</strong>:	' . urldecode($value) . '<br/>';
		}

		echo "</code>\n";
	}

}

?>