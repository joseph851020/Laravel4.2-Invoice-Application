<?php namespace IntegrityInvoice\Services\Invoice;

use IntegrityInvoice\Utilities\AppHelper as AppHelper;
use IntegrityInvoice\Repositories\InvoiceRepositoryInterface;
use IntegrityInvoice\Repositories\PreferenceRepositoryInterface;
use IntegrityInvoice\Services\Invoice\Updater;
use IntegrityInvoice\Repositories\TenantRepositoryInterface;
use IntegrityInvoice\Mailers\AppMailer;
use Carbon\Carbon;
use Illuminate\Filesystem;
use Config;
use Invoice;
use Client;
use Response;
use Preference;
use Company;
use User;
use View;
use Knp\Snappy\Pdf;


class SendRecurring {

    public $invoice;
    public $preference;
    private $mailer;
    public $tenant;

    public function __construct(TenantRepositoryInterface $tenant, InvoiceRepositoryInterface $invoice, PreferenceRepositoryInterface $preference, AppMailer $mailer)
    {
        $this->invoice = $invoice;
        $this->preference = $preference;
        $this->mailer = $mailer;
        $this->tenant = $tenant;

    }

    public function autoSendInvoiceCron(){

        // Set time limit
        set_time_limit(0);

        // Get all recurring invoice
        $invoices = $this->invoice->getUnsentInvoicesGeneratedToDayByRecurringWithAutoSend();
		 
        if(count($invoices) > 0){
            // Loop over each recurring invoice
            foreach($invoices as $invoice){
                $tenantID = $invoice->tenantID;
                // Verify subscription is active and recurring is active
                if($this->tenant->isActive($tenantID)){
                    // Update the next recurring date
                    $this->send_email($invoice->tenantID, $invoice->tenant_invoice_id);
                }

            }
        }

        return true;

    }// End Auto Cron


    public function send_email($tenantID, $tenant_invoice_id){
    	 
        $invoice = Invoice::where('tenantID', '=',  $tenantID)->where('tenant_invoice_id', '=', $tenant_invoice_id)->first();
        $client = Client::where('tenantID', '=', $tenantID)->where('id', '=', $invoice->client_id)->first();
        $company = Company::where('tenantID', '=', $tenantID)->first();
        //$public_url = Config::get('app.app_domain').'/get_invoice/show/'. $tenant_invoice_id.'/' .md5($invoice->token). '/'.$tenantID.'/';
        $public_url = '<a href="'.Config::get('app.app_domain').'view_invoice/'. md5($invoice->token.$invoice->token). '/'.$tenantID.'/'.$tenant_invoice_id.'/'.sha1($invoice->token).'">View online</a>';
        $preferences = Preference::where('tenantID', '=', $tenantID)->first();
        $inv_email_subject = $preferences->invoice_send_message_subject;
        $inv_email_body = $preferences->invoice_send_message;
 
		
		// LEGENDS
		$legends = array();
		$legends[0] = '/\b_INVOICE_NUMBER_\b/';
		$legends[1] = '/\b_CLIENT_COMPANY_\b/';
		$legends[2] = '/\b_CLIENT_CONTACT_PERSON_\b/';
		$legends[3] = '/\b_AMOUNT_DUE_\b/';
		$legends[4] = '/\b_DUE_DATE_\b/';
		$legends[5] = '/\b_SENDER_USER_\b/';
		$legends[6] = '/\b_SENDER_COMPANY_\b/';
		$legends[7] = '/\b_INVOICE_WEBPAGE_VIEW_\b/';
		
		$prex = $preferences->invoice_prefix != "" ? $preferences->invoice_prefix : "";
		
		$replacements = array();
		$replacements[0] = $prex. AppHelper::invoiceId($invoice->tenant_invoice_id);
		$replacements[1] = $client->company;
		$replacements[2] = $client->firstname ." ". $client->lastname;
		$replacements[3] = AppHelper::dumCurrencyCode($invoice->currency_code). "" . number_format($invoice->balance_due, 2, '.', ',');
		$replacements[4] = AppHelper::date_to_text($invoice->due_date, $preferences->date_format);
		$replacements[5] = User::getFullName($invoice->user_id, $tenantID);
		$replacements[6] = $company->company_name;
		$replacements[7] = $public_url;		
		

        $inv_email_subject = preg_replace($legends, $replacements, $inv_email_subject);
        $inv_email_body = preg_replace($legends, $replacements, $inv_email_body);

        $inv_email_body = str_replace("\r\n","<br />",$inv_email_body);  // Replaces Blank lines with <br />
        $inv_email_body = str_replace("\n","<br />",$inv_email_body);

        // Do multiple options
        $client_email = $client->email;
        $client_firstname = $client->firstname;
        $from_email = $company->email;
        $from_name = $company->company_name;
        $ts = strtotime($invoice->created_at);
        $mytoday = date('Y-m-d', $ts);
        
        //generate pdf file which happens in download method in InvoiceController
        
        $pdf = new Pdf();
        $pdf->setBinary(Config::get('app.binary_dir').Config::get('app.binary'));
        //$pdf_file = underscore(convert_to_ddmmyyyy($mytoday, $this->date_format).'_'.$client->company).'_invoice_'.invoiceId($invoice->tenant_invoice_id).'.pdf';
        $pdf_file = AppHelper::convert_to_ddmmyyyy($mytoday, $preferences->date_format).'_'.$client->company.'_invoice_'.AppHelper::invoiceId($invoice->tenant_invoice_id).'.pdf';
        $pdf_file_loc = public_path(). '/te_da/'.$tenantID.'/invoices/'.$pdf_file;

        //$pdf_file_loc = public_path(). '/te_da/'.$pdf_file;

        $pdf_file_loc = str_replace(' ', '_', $pdf_file_loc);

        //testin 
        
            $data = array(
                'title'         => 'Invoice '.AppHelper::invoiceId($invoice->tenant_invoice_id),
                'company'       => $company,
                'preferences'   => $preferences,
                'invoice'       => $invoice,
                'client'        => $client,
                //'part_paid_amount' => InvoicePayment::where('tenantID', '=', $this->tenantID)->where('tenant_invoice_id', '=', $invoice->tenant_invoice_id)->sum('amount')  				 
            ); 
        //testing end
        $pdf->generateFromHtml(View::make('invoices.download'.$preferences->invoice_template, $data), $pdf_file_loc, array(), true);
        $attachment = public_path() . '/te_da/'.$tenantID.'/invoices/'.$pdf_file;
        $attachment = str_replace(' ', '_', $attachment);

        // Only Primary
        if($this->mailer->send_invoice($inv_email_subject, $inv_email_body, $client_email, $client_firstname, $from_email, $from_name, $attachment))
        {
            // // Update sending status
            Invoice::update_status($tenantID, $tenant_invoice_id, 1);
            return true;
        }
        else
        {
            return false;
        }

    }

}
