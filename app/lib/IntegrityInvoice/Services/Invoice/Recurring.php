<?php namespace IntegrityInvoice\Services\Invoice;

use IntegrityInvoice\Utilities\AppHelper as AppHelper;
use IntegrityInvoice\Repositories\InvoiceRepositoryInterface;
use IntegrityInvoice\Repositories\PreferenceRepositoryInterface;
use IntegrityInvoice\Services\Invoice\Creator;
use IntegrityInvoice\Services\Invoice\Updater;
use IntegrityInvoice\Repositories\TenantRepositoryInterface;
use IntegrityInvoice\Mailers\AppMailer;
use Carbon\Carbon;
use Illuminate\Filesystem;

require_once base_path('vendor/knplabs/knp-snappy/src/autoload.php');
use Knp\Snappy\Pdf;

class Recurring {

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
        // $this->date_format = Preference::where('tenantID', '=',  $this->tenantID)->pluck('date_format');
        // $this->recurringLimit = Invoice::recurringLimit();
    }

    public function auto_copy($invoice){

        $invoice_to_copy = $invoice;
        $tenantID = $invoice_to_copy->tenantID;
        $client_name = $invoice_to_copy->client_name;
        $invoice_subj = $invoice_to_copy->subject;
        $client_id = $invoice_to_copy->client_id;
        $items = $invoice_to_copy->items;

        if($last_invoice_id = $this->invoice->getLastInvoiceID($invoice_to_copy->tenantID))
        {
            $new_inv_id = (int)$last_invoice_id + 1;
            $tenant_invoice_id = $new_inv_id;
        }else{
            // first invoice
            $tenant_invoice_id = 1;
        }

        $user_id = $invoice_to_copy->user_id;
        $payment = 0;
        if($invoice_to_copy->recur_due_date_interval > 0){
            $due_date = Carbon::now()->addDays($invoice_to_copy->recur_due_date_interval);
        }else{
            $due_date = Carbon::now();
        }

        $created_at = Carbon::now();
        $updated_at = $created_at;
        $currency_id = 	$invoice_to_copy->currency_id;
        $currency_code =  $invoice_to_copy->currency_code;
        $note = $invoice_to_copy->note;
        $subtotal = $invoice_to_copy->subtotal;
        $balance_due = $invoice_to_copy->balance_due;
        $discount_val = $invoice_to_copy->discount_val;
        $tax_val = $invoice_to_copy->tax_val;
        $status = 0;
        $receipt = 0;
        $quote = 0;
        $tenant_quote_id = 0;
        $enable_discount = $invoice_to_copy->enable_discount;
        $enable_tax = $invoice_to_copy->enable_tax;
        $business_model	= $invoice_to_copy->business_model;
        $bill_option = $invoice_to_copy->bill_option;
        $bankinfo = $invoice_to_copy->bankinfo;
        $token =  mt_rand();

        $creatorService = new Creator($this->invoice, $this);
        $newInvoice = $creatorService->auto_create(array(
            'client_name' => $client_name,
            'items' => $items,
            'bankinfo' => $bankinfo,
            'due_date' => $due_date,
            'payment' => $payment,
            'user_id' => $user_id,
            'created_at' => $created_at,
            'updated_at' => $updated_at,
            'currency_id' => $currency_id,
            'currency_code' => $currency_code,
            'note' => $note,
            'subtotal' => $subtotal,
            'balance_due' => $balance_due,
            'discount_val' => $discount_val,
            'tax_val' => $tax_val,
            'client_id' => $client_id,
            'tenant_invoice_id' => $tenant_invoice_id,
            'tenant_quote_id' => $tenant_quote_id,
            'quote' => $quote,
            'status' => $status,
            'receipt' => $receipt,
            'enable_discount' => $enable_discount,
            'enable_tax' => $enable_tax,
            'business_model' => $business_model,
            'bill_option' => $bill_option,
            'subject' => $invoice_subj,
            'tenantID' => $tenantID,
            'created_from_recurring' => 1,
            'token' => $token
        ));

    }// End auto_copy

    public function autoGenerateInvoiceCron(){

        // Set time limit
        set_time_limit(0);

        // Get all recurring invoice
        $recurring_invoices = $this->invoice->getInvoicesRecurringToday();

        if(count($recurring_invoices) > 0){
            // Loop over each recurring invoice
            foreach($recurring_invoices as $recurring_invoice){
                $tenantID = $recurring_invoice->tenantID;

                // Verify subscription is active and recurring is active
                if($this->tenant->isActive($tenantID) && $recurring_invoice->recur_status == 1){
                    // Copy the invoice
                    $this->auto_copy($recurring_invoice);

                    // Update the next recurring date
                    $recur_schedule = $recurring_invoice->recur_schedule;
                    $tenant_invoice_id = $recurring_invoice->tenant_invoice_id;
                    $recur_next_date = $this->getNextRecurringDate($recur_schedule);

                    $updateService = new Updater($this->invoice, $this);
                    $updateService->update_no_redirect($tenantID, $tenant_invoice_id, array(
                        'recur_next_date' => $recur_next_date,
                        'updated_at' => Carbon::now()
                    ));

                }

            }
        }

        return true;

    }// End Auto Cron

    public function getNextRecurringDate($recur_schedule){

        // $today = date('Y-m-d', strtotime('today'));
        $today = Carbon::now();

        switch($recur_schedule){

            case "Every week":
                $next_date = $today->addDays(7);
                break;

            case "Every two weeks":
                $next_date = $today->addDays(14);
                break;

            case "Every month":
                $next_date = $today->addMonth();
                break;

            case "Every two months":
                $next_date = $today->addMonths(2);
                break;

            case "Every three months":
                $next_date = $today->addMonths(3);
                break;

            case "Every four months":
                $next_date = $today->addMonths(4);
                break;
            case "Every six months":
                $next_date = $today->addMonths(6);
                break;

            case "Every twelve months":
                $next_date = $today->addYear();
                break;

            default:
                $next_date = $today->addMonth();
                break;
        }

        return $next_date;

    }//

    public function auto_download($tenantID, $invoice_id){

    }

}