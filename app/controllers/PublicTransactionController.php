<?php
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
use IntegrityInvoice\Mailers\AppMailer;
use Carbon\Carbon;

require_once base_path('vendor/knplabs/knp-snappy/src/autoload.php');
use Knp\Snappy\Pdf;

class PublicTransactionController extends BaseController {
	
	public $tenant;
	public $invoice;
	public $tenantID;	 
	public $paymentGateway;
	public $paypalGateway;
	public $companyDetails;
	public $invoicePayment;
	public $date_format;
	public $mailer;

	public function __construct(TenantRepositoryInterface $tenant, InvoiceRepositoryInterface $invoice, 
	PaymentGatewaysRepositoryInterface $paymentGateway, CompanyDetailsRepositoryInterface $companyDetails, InvoicePaymentsRepositoryInterface $invoicePayment, ClientPaypalPaymentGateway $paypalGateway, AppMailer $mailer)
    {
    	$this->tenant = $tenant;    
		$this->invoice = $invoice;	
		$this->invoicePayment = $invoicePayment; 
		$this->date_format;
		$this->paymentGateway = $paymentGateway; 
		$this->paypalGateway = $paypalGateway;
		$this->companyDetails = $companyDetails;
		$this->mailer = $mailer;	 
    }
	
 
	public function index()
	{  
        return View::make('subscription.index');
	}
	
	public function view_invoice($md5token, $tenantID, $tenant_invoice_id, $sha1token){
		
		//check TenantID
		$tenantReaderService = new TenantReader($this->tenant, $this);
		if(!$tenant = $tenantReaderService->read($tenantID)){
			return "Invalid link";
		}
		
		// Check Tenant Invoice Id
		$invoiceReaderService = new InvoiceReader($this->invoice, $this);
		if(!$invoice = $invoiceReaderService->public_read($tenantID, $tenant_invoice_id)){
			return "Invalid link";
		}
		
	  
		//Check md5Token
		if($md5token != md5($invoice->token.$invoice->token)){
			return "Invalid link";
		} 
		
		//Check sha1Token
		if($sha1token != sha1($invoice->token)){
			return "Invalid link";
		} 
		
		$stripe_gateway = PaymentGateway::where('tenantID', '=', $tenantID)->first();
		$paypal_gateway = PaymentGateway::where('tenantID', '=', $tenantID)->first();
		 
		return View::make('invoices.public_view')
				->with('title', AppHelper::invoiceId($tenant_invoice_id)) 
				->with('company', Company::where('tenantID', '=', $tenantID)->first())
				->with('preferences', Preference::where('tenantID', '=', $tenantID)->first())
				->with('invoice', $invoice)
				->with('client', Client::where('tenantID', '=', $tenantID)->where('id', '=', $invoice->client_id)->first())
				->with('tenant_paypal_email', PaymentGateway::where('tenantID', '=', $tenantID)->pluck('paypal_email'))		
				->with('stripe_gateway', PaymentGateway::where('tenantID', '=', $tenantID)->first())	 
				->with(compact('stripe_gateway'))
				->with(compact('paypal_gateway'))
				->with('part_paid_amount', InvoicePayment::where('tenantID', '=', $tenantID)->where('tenant_invoice_id', '=', $invoice->tenant_invoice_id)->sum('amount'));
		 
		
	}
 
	public function public_download_invoice($tenantID, $tenant_invoice_id)
	{
		//check TenantID
		$tenantReaderService = new TenantReader($this->tenant, $this);
		if(!$tenant = $tenantReaderService->read($tenantID)){
			return "Invalid link";
		}
		
		// Check Tenant Invoice Id
		$invoiceReaderService = new InvoiceReader($this->invoice, $this);
		if(!$invoice = $invoiceReaderService->public_read($tenantID, $tenant_invoice_id)){
			return "Invalid link";
		}
		
		
		$client = Client::where('tenantID', '=', $tenantID)->where('id', '=', $invoice->client_id)->first();		
		if(!$client){ return  'An error occured - invalid link'; }
	 
		
	    $preferences = Preference::where('tenantID', '=', $tenantID)->first(); 
		 
		$pdf = new Pdf();
		$pdf->setBinary(Config::get('app.binary_dir').Config::get('app.binary'));
		 

		$ts = strtotime($invoice->created_at);
	 
		$mytoday = date('Y-m-d', $ts);	
	 
		$pdf_file = AppHelper::convert_to_ddmmyyyy($mytoday, $preferences->date_format).'_'.$client->company.'_invoice_'.AppHelper::invoiceId($invoice->tenant_invoice_id).'.pdf'; 
		 
		$pdf_file_loc = public_path(). '/te_da/'.$tenantID.'/invoices/'.$pdf_file;
		
		$pdf_file_loc = str_replace(' ', '_', $pdf_file_loc);
	   
	   // If it's a quote	 	
	   $data = array(
			'title'         => 'Invoice '.AppHelper::invoiceId($invoice->tenant_invoice_id),
			'company'       => Company::where('tenantID', '=', $tenantID)->first(),
			'preferences'   => $preferences,
			'invoice'       => $invoice,
			'client'        => $client, 
			'part_paid_amount' => InvoicePayment::where('tenantID', '=', $tenantID)->where('tenant_invoice_id', '=', $invoice->tenant_invoice_id)->sum('amount')  				 
		); 
		
		// Test Template				
	    //return View::make('invoices.download'.$preferences->invoice_template, $data);
 
		 $pdf->generateFromHtml(View::make('invoices.download'.$preferences->invoice_template, $data), $pdf_file_loc, array(), true);				  
		 return Response::download($pdf_file_loc);
	  
	}

	 
	
	public function error()
	{
		return View::make('invoice_payments/error')
		->with('title', 'Payment by card - error');
	}
	 
	 public function cancel() {
	 	return View::make('invoice_payments/error')
	 	->with('title', ' Payment by paypal - error');
	 }
	
	public function secure_card_process()
	{ 
		$billing = App::make('IntegrityInvoice\Billing\ClientBillingInterface');
	  
		try
		{
			$data = $billing->charge(array(
				'email' => Input::get('email'),
				'token' => Input::get('stripe-token'),
				'amount' => Input::get('token_mount') * 100,			
				'tenant_invoice_id' => Input::get('tenant_invoice_id'),	 	 
				'tenantID' => Input::get('tenantID'),
				'client_id' => Input::get('client_id'),
				'currency_code' => Input::get('currency_code'),
				'receiver_email' => Input::get('receiver_email'),
				'client_company' => Input::get('client_company'),
				'company_name' => Input::get('company_name')		 
			));
		 
		}
		catch(exception $e)
		{
			   return Redirect::back()->with('failed_flash_message', 'There was a problem processing the trasaction.')->withInput();
		    // return Redirect::back()->with('failed_flash_message', $e->getMessage())->withInput();
		}
		
		// Update payment records 
		
		$tenantID = $data['tenantID'];
		
		$this->date_format = Preference ::where('tenantID', '=', $tenantID)->pluck('date_format');
		
		$tenant_invoice_id = $data['tenant_invoice_id'];
		$valid_amount = $data['amount'] / 100;
		
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
	 
		$this->mailer->seller_invoice_payment_notification($user->firstname, $data['receiver_email'], $data['company_name'], $data['tenant_invoice_id'], $data['email'], $data['client_company'], 'Credit / Debit (Stripe)',  Carbon::now(), "Payment received for Invoice ".$data['tenant_invoice_id'].".", $data['client_company']);
		
		// Email Seller about Payment	
		$this->mailer->buyer_invoice_payment_notification($data['receiver_email'], $data['company_name'], $data['tenant_invoice_id'], $data['email'], 'Credit / Debit (Stripe)', 'Invoice Payment confirmation.');

		  
		unset($data['token']);
		unset($data['customerId']);
		unset($data['client_id']);
		
		
		// If fully paid mark as paid
		// If pard paid mark as part paid
		// Send Email to tenant of payment to invoice
		
	 	 Session::put('stripe_success_data', $data);
		
		 return Redirect::route('invoice_card_success')
					->with('flash_message', 'Payment was successful.');	 
		 
	}

 
	
	public function invoice_card_success()
	{
		
		$data = Session::get('stripe_success_data'); 
		return View::make('invoice_payments.card_success')->with(compact('data'));
	}
	
	
	
	
	
	//////////// PAYPAL //////////////
	public function paypal(){
 

        // $this->paypalGateway->add_field('business', 'demoseller@integritywebapp.com'); // TEST
        // $this->paypalGateway->add_field('business', 'sales@integrityinvoice.com'); // LIVE
        $this->paypalGateway->add_field('item_number',Input::get('item_number'));
		$this->paypalGateway->add_field('item_name', Input::get('item_name'));		
        $this->paypalGateway->add_field('business', Input::get('receiver_email'));
		$this->paypalGateway->add_field('amount', Input::get('amount'));
	 
		$this->paypalGateway->add_field('custom',  trim(Input::get('tenantID')).'&'.trim(Input::get('email')).'&'.trim(Input::get('client_id')).'&'.trim(Input::get('client_company')).'&'.trim(Input::get('tenant_invoice_id')).'&'.trim(Input::get('company_name')).'&'.trim(Input::get('currency_code')));
	    $this->paypalGateway->add_field('return', URL::route('client_paypal_successful'));
	    $this->paypalGateway->add_field('cancel_return', URL::route('client_paypal_cancel'));
	    $this->paypalGateway->add_field('notify_url', URL::route('client_paypal_ipn')); // <-- IPN url 
	    $this->paypalGateway->add_field('no_shipping', 1);
	    $this->paypalGateway->add_field('no_note', 1);
	    $this->paypalGateway->add_field('currency_code', Input::get('currency_code'));		 
	    $this->paypalGateway->submit_paypal_post();	
	 
	}


	public function client_paypal_successful()
	{
		$data = Session::get('paypal_success_data');
		return View::make('invoice_payments.card_success')->with(compact('data'));
	}
	
	public function download_invoice_file(){
		
		$tenantID = Request::segment(2);
		$tenant_invoice_id = (int)Request::segment(3);	
		 
		$invoice = Invoice::where('tenantID', '=', $tenantID)->where('tenant_invoice_id', '=', $tenant_invoice_id)->first(); 
		
		if($invoice == null){
			return Redirect::back()->with('failed_flash_message', 'File could not be downloaded.');
		} 
		
		$file = $invoice->file;
		 
		if(!is_int($tenant_invoice_id)  || $file == NULL || $file == ""){
			return Redirect::back()->with('failed_flash_message', 'File could not be downloaded.');
		}
	 
		$pathToFile = public_path().'/te_da/'.$tenantID.'/attachments/invoices/'. Apphelper::decrypt($file, $invoice->tenantID);
		
		if(file_exists($pathToFile)){		
			return Response::download($pathToFile);
		}else{
			return Redirect::back()->with('failed_flash_message', 'File could not be downloaded.');
		}
	}
	
 
}