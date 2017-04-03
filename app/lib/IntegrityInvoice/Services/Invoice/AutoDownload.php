<?php namespace IntegrityInvoice\Services\Invoice;

use IntegrityInvoice\Utilities\AppHelper as AppHelper;
use IntegrityInvoice\Repositories\InvoiceRepositoryInterface;
use IntegrityInvoice\Repositories\PreferenceRepositoryInterface;
use IntegrityInvoice\Repositories\TenantRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Filesystem;
use Config;
use Invoice;
use Client;
use InvoicePayment;
use Response;
use Preference;
use Company;

require_once base_path('vendor/knplabs/knp-snappy/src/autoload.php');
use Knp\Snappy\Pdf;

class AutoDownload {

    public $invoice;
    public $preference;
    public $tenant;

    public function __construct(TenantRepositoryInterface $tenant, InvoiceRepositoryInterface $invoice, PreferenceRepositoryInterface $preference)
    {
        $this->invoice = $invoice;
        $this->preference = $preference;
        $this->tenant = $tenant;
    }

    public function autoDownload(){

        // Set time limit
        set_time_limit(0);

        // Get all recurring invoice
        $invoices = $this->invoice->getInvoicesGeneratedToDayByRecurring();

        if(count($invoices) > 0){
            // Loop over each recurring invoice
            foreach($invoices as $invoice){
                $tenantID = $invoice->tenantID;

                // Verify subscription is active and recurring is active
                if($this->tenant->isActive($tenantID)){

                    $this->download($tenantID, $invoice->tenant_invoice_id, true);

                }
            }
        }

        return true;

    }// End AutoDownload


    public function download($tenantID, $id, $download_mode=true)
    {
        if(is_null($id))
        {
            return false;
        }

        $invoice = Invoice::where('tenantID', '=', $tenantID)->where('tenant_invoice_id', '=', $id)->first();
        if(is_null($invoice))
        {
            return false;
        }

        $client = Client::where('tenantID', '=', $tenantID)->where('id', '=', $invoice->client_id)->first();
        if(!$client){ return Redirect::route('invoices')->with('failed_flash_message', 'An error occurred, please verify that the client exists.'); }

        $preferences = Preference::where('tenantID', '=', $tenantID)->first();
        $pdf = new Pdf();
        $pdf->setBinary(Config::get('app.binary_dir').Config::get('app.binary'));

        $ts = strtotime($invoice->created_at);
        $mytoday = date('Y-m-d', $ts);

        $pdf_file = AppHelper::convert_to_ddmmyyyy($mytoday, $preferences->date_format).'_'.$client->company.'_invoice_'.AppHelper::invoiceId($invoice->tenant_invoice_id).'.pdf';

        $pdf_file_loc = public_path(). '/te_da/'.$tenantID.'/invoices/'.$pdf_file;
        $pdf_file_loc = str_replace(' ', '_', $pdf_file_loc);

            $data = array(
                'title'         => 'Invoice '.AppHelper::invoiceId($invoice->tenant_invoice_id),
                'company'       => Company::where('tenantID', '=', $tenantID)->first(),
                'preferences'   => Preference::where('tenantID', '=', $tenantID)->first(),
                'invoice'       => $invoice,
                'client'        => $client,
                'part_paid_amount' => InvoicePayment::where('tenantID', '=', $tenantID)->where('tenant_invoice_id', '=', $invoice->tenant_invoice_id)->sum('amount')
            );

            // Test Template
            //return View::make('invoices.download'.$preferences->invoice_template, $data);

            if($download_mode == false){

                return $pdf->generateFromHtml(\View::make('invoices.download'.$preferences->invoice_template, $data), $pdf_file_loc, array(), true);

            }else{

                $pdf->generateFromHtml(\View::make('invoices.download'.$preferences->invoice_template, $data), $pdf_file_loc, array(), true);
                return Response::download($pdf_file_loc);
            }

    }



}