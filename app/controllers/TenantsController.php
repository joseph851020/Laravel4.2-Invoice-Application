<?php
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

use IntegrityInvoice\Services\Tenant\Remover as TenantRemover;
use IntegrityInvoice\Services\Tenant\Updater as TenantUpdater;
use IntegrityInvoice\Services\Invoice\Remover as InvoiceRemover;
use IntegrityInvoice\Services\InvoicePayments\Remover as InvoicePaymentsRemover;
use IntegrityInvoice\Services\Item\Remover as ItemRemover;
use IntegrityInvoice\Services\Client\Remover as ClientRemover;
use IntegrityInvoice\Services\Merchant\Remover as MerchantRemover;
use IntegrityInvoice\Services\Expense\Remover as ExpenseRemover;
use IntegrityInvoice\Services\CompanyDetails\Remover as CompanyDetailsRemover;
use IntegrityInvoice\Services\Preference\Remover as PreferenceRemover;
use IntegrityInvoice\Services\User\Remover as UserRemover;

use IntegrityInvoice\Services\CancelQueue\Creator as CancelQueueCreator;



class TenantsController extends BaseController {
	
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
	 
	public function cancel()
	{
		$this->tenantID = Input::get('tenantID');
		$this->userInstance = $this->user->find($this->tenantID, Input::get('super_user_id'));
		$this->cancelToken = Input::get('cancel_token');
		 
		if($this->tenantID != null && $this->tenantID != "" && $this->userInstance != null && $this->cancelToken != null && $this->cancelToken != "")
		{
			$password = Input::get('password');
			// Verify password
			if($password == "")
			{
				return Redirect::to('company/cancel')
					->with('failed_flash_message', 'Please enter your password to confirm');
			}
			
			$user_password = $this->userInstance->password;
			
			if(!Hash::check($password, $user_password))
			{
				return Redirect::to('company/cancel')
					->with('failed_flash_message', 'Invalid password');
			}
		 
			
			// Deactivate the account for 3 days
			$updateService = new TenantUpdater($this->tenant, $this);	
	    	$updateService->updateStatus(array(
			'status' => -2,
			'updated_at' => strftime("%Y-%m-%d %H:%M:%S", time())
		));		
		
		// Enter record for cancel queue		
		$cancelQueueServiceCreator = new CancelQueueCreator($this->cancelQueue, $this);
		$cancelQueueServiceCreator->create(array(
			'tenantID' => $this->tenantID, 
			'firstname' => $this->userInstance->firstname,
			'email' => $this->userInstance->email,
			'cancel_token' => $this->cancelToken, 		
			'created_at' => strftime("%Y-%m-%d %H:%M:%S", time()),
			'updated_at' => strftime("%Y-%m-%d %H:%M:%S", time())
			
		));
		
		   // Logout and Send
		   Auth::logout();
		   Session::flush();
	  	
		   return Redirect::to('canceled')->with('flash_message', 'Sorry to see you go, your account is now canceled. All your account data will be automatically deleted after 3 days unless you advise otherwise.  A confirmation email will be sent to your primary email upon full deletion of your data.');
		 
		}
		else
		{
			return Redirect::to('/');	
		}

	}


	public function finallyCancel($tenantID)
	{
        $tenantID = $tenantID;

        if($tenantID == ""){
            return Redirect::to('admin/accounts')->with('failed_flash_message', 'Invalid TenantID');
        }

        $removerService = App::make('IntegrityInvoice\Services\Tenant\Remover');
        $removerService->commandCancel($tenantID);
        return Redirect::to('admin/accounts')->with('flash_message', 'Account data was successfully wiped from the system.');
	}

	
	public function cancelQueueCreationSucceeds()
	{
	
	}
	
	public function cancelQueueCreationFails()
	{
	
	}
	
	public function cancelQueueUpdateSucceeds()
	{
	
	}
	
	public function cancelQueueUpdateFails()
	{
	
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
