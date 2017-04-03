<?php namespace IntegrityInvoice\Services\Tenant;

use Illuminate\Filesystem;
use IntegrityInvoice\Mailers\SignupMailer;

use IntegrityInvoice\Repositories\TenantRepositoryInterface;
use IntegrityInvoice\Repositories\InvoiceRepositoryInterface;
use IntegrityInvoice\Repositories\InvoicePaymentsRepositoryInterface;
use IntegrityInvoice\Repositories\ItemRepositoryInterface;
use IntegrityInvoice\Repositories\ExpenseRepositoryInterface;
use IntegrityInvoice\Repositories\ClientRepositoryInterface;
use IntegrityInvoice\Repositories\MerchantRepositoryInterface;
use IntegrityInvoice\Repositories\UserRepositoryInterface;
use IntegrityInvoice\Repositories\CompanyDetailsRepositoryInterface;
use IntegrityInvoice\Repositories\PreferenceRepositoryInterface;
use IntegrityInvoice\Repositories\CancelQueueRepositoryInterface;

use IntegrityInvoice\Services\Invoice\Remover as InvoiceRemover;
use IntegrityInvoice\Services\InvoicePayments\Remover as InvoicePaymentsRemover;
use IntegrityInvoice\Services\Item\Remover as ItemRemover;
use IntegrityInvoice\Services\Client\Remover as ClientRemover;
use IntegrityInvoice\Services\Merchant\Remover as MerchantRemover;
use IntegrityInvoice\Services\Expense\Remover as ExpenseRemover;
use IntegrityInvoice\Services\CompanyDetails\Remover as CompanyDetailsRemover;
use IntegrityInvoice\Services\Preference\Remover as PreferenceRemover;
use IntegrityInvoice\Services\User\Remover as UserRemover;


class Remover {

    public $tenantID;
    public $tenant;
    public $invoice;
    public $invoicePayment;
    public $item;
    public $expense;
    public $client;
    public $merchant;
    public $companyDetails;
    public $preference;
    public $user;
    public $userInstance;
    public $cancelToken;
    public $mailer;
    public $cancelQueue;
	
	public function __construct(TenantRepositoryInterface $tenant, InvoiceRepositoryInterface $invoice, InvoicePaymentsRepositoryInterface $invoicePayment, ItemRepositoryInterface $item,
                                ExpenseRepositoryInterface $expense, ClientRepositoryInterface $client, ExpenseRepositoryInterface $merchant, UserRepositoryInterface $user,
                                CompanyDetailsRepositoryInterface $companyDetails, PreferenceRepositoryInterface $preference, SignupMailer $mailer, CancelQueueRepositoryInterface $cancelQueue)
    {
        $this->tenant = $tenant;
        $this->invoice = $invoice;
        $this->invoicePayment = $invoicePayment;
        $this->item = $item;
        $this->user = $user;
        $this->client = $client;
        $this->merchant = $merchant;
        $this->companyDetails = $companyDetails;
        $this->preference = $preference;
        $this->expense = $expense;
        $this->mailer = $mailer;
        $this->cancelQueue = $cancelQueue;
    }
	
	public function remove()
	{

		if($this->listener->tenantID == "" || $this->listener->tenantID == null)
		{
			return $this->listener->tenantDeletionFails();
		}

		$affectedRows = $this->tenant->remove($this->listener->tenantID);

		if(!is_numeric($affectedRows) || $affectedRows < 1){
			return $this->listener->tenantDeletionFails();
		}

		return $this->listener->tenantDeletionSucceeds();
	}



    public function commandCancel($tenantID)
    {
        $this->tenantID = $tenantID;
        // $this->cancelToken = $cancelToken;

        $theTenant = $this->tenant->find($tenantID);

        //if($this->tenantID != null && $this->tenantID != "" && $this->userInstance != null && $this->cancelToken != null && $this->cancelToken != "")

        if($this->tenantID != null && $this->tenantID != "")
        {
            // Delete All Tenant files on disk
            $folderPath = base_path().'/te_da/'.$this->tenantID;

            if(\File::isDirectory($folderPath))
            {
                \File::deleteDirectory($folderPath);
            }

            // Delete All Associated Invoices
            $invoiceRemoverAllService = new InvoiceRemover($this->invoice, $this);
            $invoiceRemoverAllService->removeAll();

            // Delete All Associated Invoice Payments
            $invoicePaymentRemoverAllService = new InvoicePaymentsRemover($this->invoicePayment, $this);
            $invoicePaymentRemoverAllService->removeAll();


            // Delete All associated Items (Products and Services)
            $itemRemoverAllService = new ItemRemover($this->item, $this);
            $itemRemoverAllService->removeAll();


            // Delete All associated Expenses
            $expenseRemoverAllService = new expenseRemover($this->expense, $this);
            $expenseRemoverAllService->removeAll();


            // Delete All associated Clients
            $clientRemoverAllService = new clientRemover($this->client, $this);
            $clientRemoverAllService->removeAll();


            // Delete All associated Merchants
            $merchantRemoverAllService = new merchantRemover($this->merchant, $this);
            $merchantRemoverAllService->removeAll();


            // Delete Company details
            $companyDetailsRemoverService = new companyDetailsRemover($this->companyDetails, $this);
            $companyDetailsRemoverService->remove();


            // Delete Preferences
            $preferenceRemoverService = new preferenceRemover($this->preference, $this);
            $preferenceRemoverService->remove();

            // Delete All associated Users
            $userRemoverAllService = new userRemover($this->user, $this);
            $userRemoverAllService->removeAll();


            // Delete Tenant account
            $affectedRows = $this->tenant->remove($tenantID);

            if(!is_numeric($affectedRows) || $affectedRows < 1){
                return false;
            }

            // Send email if tenant is verfied and cancelled by herself
            if($theTenant->verified == 1 && $theTenant->status == -2){
                // Send Email to Client to confirm that account has been permanently deleted
                $this->userInstance = $this->user->findSuper($tenantID);
                if($this->userInstance != null){

                    if($this->mailer->cancellation_notification($this->userInstance->firstname, $this->userInstance->email))
                    {
                        $this->mailer->tenant_cancel_info($this->userInstance->firstname, $this->tenantID, $this->userInstance->email);
                        return true;
                    }
                }

            }
        }
        else
        {
            return false;
        }

    }


    public function tenantUpdateSucceeds()
    {

    }

    public function tenantUpdateFails()
    {

    }


    public function invoiceDeletionSucceeds()
    {

    }

    public function invoicePaymentsDeletionSucceeds()
    {

    }

    public function itemsDeletionSucceeds()
    {

    }

    public function expenseDeletionSucceeds()
    {

    }

    public function clientsDeletionSucceeds()
    {

    }

    public function merchantDeletionSucceeds()
    {

    }

    public function companyDetailsDeletionSucceeds()
    {

    }

    public function preferenceDeletionSucceeds()
    {

    }

    public function userDeletionSucceeds()
    {

    }
    public function tenantDeletionSucceeds()
    {

    }




    public function invoiceDeletionFails()
    {
        return "INVOICE DELETION FAILS";
    }

    public function invoicePaymentsDeletionFails()
    {
        return "INVOICE PAYMENTS DELETION FAILS";
    }

    public function itemDeletionFails()
    {
        return "ITEMS DELETION FAILS";
    }

    public function expenseDeletionFails()
    {
        return "EXPENSES DELETION FAILS";
    }

    public function clientDeletionFails()
    {
        return "CLIENTS DELETION FAILS";
    }

    public function merchantDeletionFails()
    {
        return "MERCHANTS DELETION FAILS";
    }

    public function companyDetailsDeletionFails()
    {
        return "COMPANY DETAILS DELETION FAILS";
    }

    public function preferenceDeletionFails()
    {
        return "PREFERENCE DELETION FAILS";
    }

    public function userDeletionFails()
    {
        return "USERS DELETION FAILS";
    }
    public function tenantDeletionFails()
    {
        return "TENANT DELETION FAILS";
    }

}