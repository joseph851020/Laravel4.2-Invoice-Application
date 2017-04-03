<?php
use IntegrityInvoice\Repositories\TenantRepositoryInterface;
use IntegrityInvoice\Repositories\InvoiceRepositoryInterface;
use IntegrityInvoice\Repositories\InvoicePaymentsRepositoryInterface;
use IntegrityInvoice\Repositories\ClientRepositoryInterface;
use IntegrityInvoice\Repositories\PreferenceRepositoryInterface;
use IntegrityInvoice\Services\InvoicePayments\Creator;
use IntegrityInvoice\Services\Client\Reader;
use IntegrityInvoice\Utilities\AppHelper as AppHelper;
use IntegrityInvoice\Services\Invoice\Updater;
use IntegrityInvoice\Services\InvoicePayments\Updater as PaymentUpdater;
use IntegrityInvoice\Services\InvoicePayments\Remover;
use IntegrityInvoice\Mailers\AppMailer;

require_once base_path('vendor/knplabs/knp-snappy/src/autoload.php');
use Knp\Snappy\Pdf;


class PaymentsController extends BaseController {
	
	public $invoice;
	public $tenantID;
	public $tenant;
	public $client;
	public $userId;
	public $accountPlan;
	public $tenantVerification;
	public $invoicePayment;
	public $date_format;
	public $preference;
	public $total_records;
	private $mailer;
	
	public function __construct(InvoicePaymentsRepositoryInterface $invoicePayment, ClientRepositoryInterface $client, 
	InvoiceRepositoryInterface $invoice, PreferenceRepositoryInterface $preference,	TenantRepositoryInterface $tenant, AppMailer $mailer)
	{ 
		$this->tenant = $tenant;
		$this->client = $client;
		$this->preference = $preference;
		$this->invoice = $invoice;
		$this->invoicePayment = $invoicePayment;
		$this->tenantID = Session::get('tenantID');
		$this->userId = Session::get('user_id');
		$this->accountPlan = $this->tenant->find($this->tenantID)->account_plan_id;		 
		$this->tenantVerification = $this->tenant->find($this->tenantID)->verified; 
		$this->date_format = $this->preference->find($this->tenantID)->date_format;	
		$this->mailer = $mailer;
	}

	 
	public function index($id)
	{
		// if($this->tenantVerification == 0){
			// return Redirect::route('dashboard')->with('failed_flash_message', 'Please check your email and verify your account');
		// }
		
		if(!is_null($id) && is_numeric($id)){
			
			$invoice = $this->invoice->find($this->tenantID, $id);
			$client = $this->client->find($this->tenantID, $invoice->client_id);	 
			$scripts = 'number_formatter,datepicker';
			$payments = $this->invoicePayment->getAll($this->tenantID, $invoice->tenant_invoice_id);
					
			$total_records = $this->invoicePayment->count($this->tenantID, $invoice->tenant_invoice_id);
			$preferences = $this->preference->find($this->tenantID);			
			$total_payment = $this->invoicePayment->sum($this->tenantID, $invoice->tenant_invoice_id);
		}
		 
        return View::make('invoice_payments.index')
		->with(compact('invoice'))
		->with(compact('client'))
		->with(compact('scripts'))
		->with(compact('payments'))
		->with(compact('total_payment'))
		->with(compact('preferences'))
		->with(compact('total_records'))
        ->with('title', 'Payment records on Invoice: '. AppHelper::invoiceId($invoice->tenant_invoice_id));
	}
	
  
	public function glossary()
	{
         return View::make('invoice_payments.send')->with('title', 'Send Payment');
	}
	
	
	public function store($tenant_invoice_id)
	{
		if(Input::get('record_payment')){
			
				// validation has passed
				$valid_amount = (float)preg_replace('/,/', '', Input::get('amount'));
				
				$invoice = Invoice::where('tenantID', '=', $this->tenantID)->where('tenant_invoice_id', '=',$tenant_invoice_id)->first();
				$total_payment = InvoicePayment::where('tenantID', '=', $invoice->tenantID)->where('tenant_invoice_id', '=', $invoice->tenant_invoice_id)->sum('amount');				
				$remaning_balance = $invoice->balance_due - $total_payment;
		 
				
				// Do not allow overpayment
				if($valid_amount > $remaning_balance){
					return Redirect::to('payments/'.$tenant_invoice_id)->with('failed_flash_message', 'You can not record an over payment. The amount entered is greater than the amount outstanding.')->withInput();
				}
				
				 
				$record_data = array(
					'tenant_invoice_id' => $tenant_invoice_id,
					'amount' => $valid_amount,
					'payment_method' => Input::get('payment_method'),
					'cheque_number' => Input::get('cheque_number'), 
					'bank_transfer_ref' => Input::get('bank_transfer_ref'), 			
					'tenantID' => $this->tenantID,
					'user_id' => $this->userId,
					'client_id' => Input::get('client_id'),
					'created_at' => AppHelper::convert_to_mysql_yyyymmdd(Input::get('date'), $this->date_format)
			    );
				
				if(InvoicePayment::create($record_data)){
					
					// Update last payment date
					$this->last_payment_date($this->tenantID, $tenant_invoice_id, AppHelper::convert_to_mysql_yyyymmdd(Input::get('date'), $this->date_format));
					
					$total_payment = InvoicePayment::where('tenantID', '=', $this->tenantID)->where('tenant_invoice_id', '=', $tenant_invoice_id)->sum('amount');
					
					// Check to see if total amount to date is equal to amount due
					if($total_payment == 0){
						$update_data = array('payment' => 0);
						$updateService = new Updater($this->invoice, $this);		
						$updateService->update_no_redirect($this->tenantID, $tenant_invoice_id, $update_data); 
					 
					}elseif($total_payment < $invoice->balance_due){
						$update_data = array('payment' => 1);
						$updateService = new Updater($this->invoice, $this);		
						$updateService->update_no_redirect($this->tenantID, $tenant_invoice_id, $update_data);
						
					}elseif($total_payment >= $invoice->balance_due){							
						$update_data = array('payment' => 2);
						$updateService = new Updater($this->invoice, $this);		
						$updateService->update_no_redirect($this->tenantID, $tenant_invoice_id, $update_data);
					}
		 
					
					 return Redirect::to('payments/'.$tenant_invoice_id)->with('flash_message', 'New payment of ('. $valid_amount .') was recorded.');
		  
				}
	 
		  }	
	}

	
	public function payment_receipt()
	{
		
	    $tenant_invoice_id = (int)Request::segment(2);	
		$mode = Request::segment(4);
	  
		if($mode == NULL || $mode == ""){
			return Redirect::to('invoices')->with('failed_flash_message', 'Invalid Invoice ID');
		}
		
		if($tenant_invoice_id == NULL || $tenant_invoice_id == "" || !is_int($tenant_invoice_id)){
			return Redirect::to('invoices')->with('failed_flash_message', 'Invalid Invoice ID');
		}
		
		if($mode == 1){
			$download_mode = true;
		}else{
			$download_mode = false;
		}
			
	
		$invoice = Invoice::where('tenantID', '=', $this->tenantID)->where('tenant_invoice_id', '=',$tenant_invoice_id)->first();
		
		if($invoice == NULL){				
			return Redirect::to('invoices')->with('failed_flash_message', 'Invalid Invoice ID');
		}
		
		$client = Client::where('tenantID', '=', $this->tenantID)->where('id', '=', $invoice->client_id)->first();
		$total_payment = InvoicePayment::where('tenantID', '=', $invoice->tenantID)->where('tenant_invoice_id', '=', $invoice->tenant_invoice_id)->sum('amount');				
		$remaning_balance = $invoice->balance_due - $total_payment;
	 
			
			// If full payment has been recorded
			if($remaning_balance <= 0)
			{
				//Generate a receipt PDF
				
				$preferences = Preference::where('tenantID', '=', $this->tenantID)->first();
		 
				$pdf = new Pdf();
				$pdf->setBinary(Config::get('app.binary_dir').Config::get('app.binary'));
				 
		
				$ts = strtotime($invoice->created_at);
			 
				$mytoday = date('Y-m-d', $ts);	
				
				$pdf_file = 'Receipt_for_'.'invoice_'.AppHelper::invoiceId($invoice->tenant_invoice_id).'_'.$client->company.'.pdf'; 
				 
		 
				$pdf_file_loc = public_path(). '/te_da/'.$this->tenantID.'/receipts/'.$pdf_file;
				
				$pdf_file_loc = str_replace(' ', '_', $pdf_file_loc);
			  	
				$user = User::where('tenantID', '=', $this->tenantID)->first();
				
			    $data = array(
	   				'title'         => 'Invoice '.AppHelper::invoiceId($invoice->tenant_invoice_id),
	   				'company'       => Company::where('tenantID', '=', $this->tenantID)->first(),
	   				'preferences'   => Preference::where('tenantID', '=', $this->tenantID)->first(),
	   				'invoice'       => $invoice,
	   				'client'        => $client,	
	   				'tenant'   		=> Tenant::where('tenantID', '=', $this->tenantID)->first(), 
	   				'user'			=> $user,
	   				'paid_amount' => $total_payment 				 
				); 
			 
				$pdf->setOption('page-size', 'A5');
				
				if($download_mode == false){			   	 
				 	 $pdf->generateFromHtml(View::make('invoice_payments.downloadreceipt', $data), $pdf_file_loc, array(), true);					 
					 // Send Email Using Receipt template
					
			   	}else{			   		
			   	  
				  $pdf->generateFromHtml(View::make('invoice_payments.downloadreceipt', $data), $pdf_file_loc, array(), true);				  
				  return Response::download($pdf_file_loc);
			 
			  	}
			 
				
			}
		 
	}

 
	
	public function payment_acknowledgement($tenant_invoice_id, $payment_id){
		
		   $preferences = Preference::where('tenantID', '=', $this->tenantID)->first();
		   $company = Company::where('tenantID', '=', $this->tenantID)->first();			 
				 
		   $invoice = Invoice::where('tenantID', '=', $this->tenantID)->where('tenant_invoice_id', '=', $tenant_invoice_id)->first();
		   
		   //$this_payment = InvoicePayment::where('tenantID', '=', $invoice->tenantID)->where('id', '=', $payment_id)->first();	   
		   $this_payment = InvoicePayment::where('tenantID', '=', $invoice->tenantID)->where('id', '=', $payment_id)->first();
		   
		   $total_paid_todate = InvoicePayment::where('tenantID', '=', $invoice->tenantID)->where('tenant_invoice_id', '=', $invoice->tenant_invoice_id)->sum('amount');
		   
		   $client = Client::find($invoice->client_id);
		   $public_url = '<a href="'.Config::get('app.app_domain').'/view_invoice/'. md5($invoice->token.$invoice->token). '/'.$invoice->tenantID.'/'.$tenant_invoice_id.'/'.sha1($invoice->token).'">View online</a>';
			 
		   return View::make('invoice_payments.send')
		   ->with('title', 'Sending invoice '.Apphelper::invoiceId($invoice->tenant_invoice_id))
		   ->with(compact('invoice'))
		   ->with(compact('client'))
		   ->with(compact('company'))
		   ->with(compact('this_payment'))
		   ->with(compact('total_paid_todate'))
		   ->with(compact('public_url'))
		   ->with('payment_id', $payment_id)
		   ->with(compact('preferences')); 		
				 
		
	}



	public function payment_acknowledgement_email($tenant_invoice_id, $payment_id){
		
		$invoice = Invoice::where('tenantID', '=', $this->tenantID)->where('tenant_invoice_id', '=', $tenant_invoice_id)->first();
		 
		$client = Client::find($invoice->client_id);
		$company = Company::where('tenantID', '=', $this->tenantID)->first();
		$public_url = Config::get('app.app_domain').'get_invoice/show/'. $tenant_invoice_id.'/' .md5($invoice->token). '/'.$invoice->tenantID.'/';
		$preferences = Preference::where('tenantID', '=', $this->tenantID)->first();
		
		// Send email
		$inv_email_subject = Input::get('progress_payment_email_subject');
		$inv_email_body = Input::get('progress_payment_email_body');
		
		$inv_email_body = str_replace("\r\n","<br />",$inv_email_body); // Replaces Blank lines with <br />
	    $inv_email_body = str_replace("\n","<br />",$inv_email_body); 
		
		// Do multile options		
		$client_email = $client->email;		 
			 
		$from_email = $company->email;
		$from_name = $company->company_name;
		 
		
		// Only Primary
		if(isset($_POST['email_primary']) && !isset($_POST['email_secondary'])){
		 
			if($this->mailer->send_payment_acknowledgement($inv_email_subject, $inv_email_body, trim($_POST['email_primary']), trim($_POST['firstname_primary']), $from_email, $from_name))
			{			 
				 
	 			// Update receipt sent status on invoice
				$this->update_receipt_sent_status($this->tenantID, $tenant_invoice_id, 1);
				// Update this payment record as sent
				$this->update_progress_sent_status($this->tenantID, $tenant_invoice_id, $payment_id);
				 
				 return Redirect::route('create_payment', $tenant_invoice_id)->with('flash_message', 'Receipt Sent to '.$client->company. ' : '.trim($_POST['email_primary']).'');
			}
			else 
			{
				return Redirect::to('invoices')->with('failed_flash_message', 'Receipt not sent.');		 
			}
		}
		
		
		// Only Secondary
		if(isset($_POST['email_secondary']) && !isset($_POST['email_primary'])){
		 
			if($this->mailer->send_payment_acknowledgement($inv_email_subject, $inv_email_body, trim($_POST['email_secondary']), trim($_POST['firstname_secondary']), $from_email, $from_name))
			{			 
				// Update receipt sent status on invoice
				$this->update_receipt_sent_status($this->tenantID, $tenant_invoice_id, 1);
				// Update this payment record as sent
				$this->update_progress_sent_status($this->tenantID, $tenant_invoice_id, $payment_id);
				
				 return Redirect::route('create_payment', $tenant_invoice_id)->with('flash_message', 'Receipt Sent to '.$client->company. ' : '.trim($_POST['email_secondary']).'');
			}
			else 
			{
				return Redirect::to('invoices')->with('failed_flash_message', 'Receipt not sent.');		 
			}
		}
		
		
		// If Both		 
		if(isset($_POST['email_primary']) && isset($_POST['email_secondary'])){
			
			$this->mailer->send_payment_acknowledgement($inv_email_subject, $inv_email_body, trim($_POST['email_primary']), trim($_POST['firstname_primary']), $from_email, $from_name);
			$this->mailer->send_payment_acknowledgement($inv_email_subject, $inv_email_body, trim($_POST['email_secondary']), trim($_POST['firstname_secondary']), $from_email, $from_name); 
			
			// Update receipt sent status on invoice
			$this->update_receipt_sent_status($this->tenantID, $tenant_invoice_id, 1);
			// Update this payment record as sent
			$this->update_progress_sent_status($this->tenantID, $tenant_invoice_id, $payment_id);
			return Redirect::route('create_payment', $tenant_invoice_id)->with('flash_message', 'Receipt sent to '.$client->company. ' : '.trim($_POST['email_primary']).' and '.trim($_POST['email_secondary']) .'');
		}
		
		// No contact selected, send to default
		if(!isset($_POST['email_primary']) && !isset($_POST['email_secondary'])){
			
			if($this->mailer->send_payment_acknowledgement($inv_email_subject, $inv_email_body, $client->email, $client->firstname, $from_email, $from_name))
			{			 
				 
				 // Update receipt sent status on invoice
				$this->update_receipt_sent_status($this->tenantID, $tenant_invoice_id, 1);
				// Update this payment record as sent
				$this->update_progress_sent_status($this->tenantID, $tenant_invoice_id, $payment_id);
				 return Redirect::route('create_payment', $tenant_invoice_id)->with('flash_message', 'Receipt Sent to '.$client->company. ' : '.$client->email.'');
			}
			else 
			{
				return Redirect::to('invoices')->with('failed_flash_message', 'Receipt not sent.');		 
			}
		}
	 
  
	}

 
 
	public function update_receipt_sent_status($tenantID, $tenant_invoice_id, $status){
		 
		$update_data = array('receipt' => $status);
		$updateService = new Updater($this->invoice, $this);		
		$updateService->update_no_redirect($tenantID, $tenant_invoice_id, $update_data); 
		
		
	} // End update_receipt_sent_status

	public function update_progress_sent_status($tenantID, $tenant_invoice_id, $payment_id){
		
		$update_data = array('sent' => 1);	
		$updateService = new PaymentUpdater($this->invoicePayment, $this);		
		$updateService->update_single($tenantID, $tenant_invoice_id, $payment_id, $update_data); 
		
	} // End update_progress_sent_status
	 
	
	public function last_payment_date($tenantID, $tenant_invoice_id, $date){
		
		$update_data = array('last_payment_date' => $date);
		$updateService = new Updater($this->invoice, $this);		
		$updateService->update_no_redirect($tenantID, $tenant_invoice_id, $update_data); 
	 
	} // End last_payment_date
	
	
	public function mark_paid($tenant_invoice_id, $ajax = 1){
		
		// Get 
		$invoice = Invoice::where('tenantID', '=', $this->tenantID)->where('tenant_invoice_id', '=',$tenant_invoice_id)->first();	 
		$paid_amount = InvoicePayment::where('tenantID', '=', $this->tenantID)->where('tenant_invoice_id', '=', $tenant_invoice_id)->sum('amount');
		
		$amount_to_pay;
		
			// If no payments
			if($paid_amount == NULL){
				 
				$amount_to_pay = $invoice->balance_due;
		 
			}else if($paid_amount != NULL && $invoice->balance_due > $paid_amount){
				
				$amount_to_pay = $invoice->balance_due - $paid_amount;
			}
			
			$record_data = array(
				'tenant_invoice_id' => $tenant_invoice_id,
				'amount' => $amount_to_pay,
				'payment_method' => Input::get('payment_method'),
				'cheque_number' => Input::get('cheque_number'), 
				'bank_transfer_ref' => Input::get('bank_transfer_ref'),							 		
				'tenantID' => $this->tenantID,
				'user_id' => $this->userId,
				'client_id' => $invoice->client_id,
				'created_at' => AppHelper::convert_to_mysql_yyyymmdd(Input::get('date'), $this->date_format)
		    );
			
			
			if(InvoicePayment::create($record_data)){				
				// Update last payment date	and payment status			 
			    $update_data = array('payment' => 2, 'last_payment_date' => strftime("%Y-%m-%d %H:%M:%S", time()));
				$updateService = new Updater($this->invoice, $this);		
				$updateService->update_no_redirect($this->tenantID, $tenant_invoice_id, $update_data); 
			 
			 	if($ajax = 0){
					return Redirect::to('invoices/'.$tenant_invoice_id)->with('flash_message', 'Marked as paid.');
				} 
			}
  
	}

	
	public function destroy($tenant_invoice_id, $payment_id){
		
		$removerService = new Remover($this->invoicePayment, $this);		 	
		return $removerService->remove($tenant_invoice_id, $payment_id);
	}
	 
	
	public function invoicePaymentsDeletionFails($tenant_invoice_id){
		
		 return Redirect::to('payments/'.$tenant_invoice_id)->with('failed_flash_message', 'Payment was not deleted');
	}
	
	public function invoicePaymentsDeletionSucceeds($tenant_invoice_id){
		
		// Update the status of payment
		$invoice_balance = Invoice::where('tenantID', '=', $this->tenantID)->where('tenant_invoice_id', '=', $tenant_invoice_id)->pluck('balance_due');
		$paid_amount = InvoicePayment::where('tenantID', '=', $this->tenantID)->where('tenant_invoice_id', '=', $tenant_invoice_id)->sum('amount');
		
		// If no payments
		if($paid_amount == NULL){
			
			$update_data = array('payment' => 0);
			$updateService = new Updater($this->invoice, $this);		
			$updateService->update_no_redirect($this->tenantID, $tenant_invoice_id, $update_data); 
		}
		
		// If part paid
		if($paid_amount != NULL && $invoice_balance > $paid_amount){
			
			$update_data = array('payment' => 1);
			$updateService = new Updater($this->invoice, $this);		
			$updateService->update_no_redirect($this->tenantID, $tenant_invoice_id, $update_data); 
			
		}
		
	   return Redirect::to('payments/'.$tenant_invoice_id)->with('flash_message', 'Payment was deleted from invoice'); 
	}
	
	
	
	 
}