<?php

use IntegrityInvoice\Repositories\TenantRepositoryInterface;
use IntegrityInvoice\Services\Tenant\Reader as TenantReader;
use IntegrityInvoice\Services\Tenant\Updater as TenantUpdater;
use IntegrityInvoice\Services\PaymentHistory\Creator as PaymentHistoryCreator;
use IntegrityInvoice\Repositories\PaymentsHistoryRepositoryInterface;
use IntegrityInvoice\Repositories\UserRepositoryInterface;
use IntegrityInvoice\Services\User\Creator;
use IntegrityInvoice\Services\User\Reader;
use IntegrityInvoice\Services\User\Updater as UserUpdater;
use IntegrityInvoice\Services\User\Remover;

use IntegrityInvoice\Repositories\CompanyDetailsRepositoryInterface;
use IntegrityInvoice\Services\CompanyDetails\Updater as CompanyDetailsUpdater;

use IntegrityInvoice\Utilities\AppHelper as AppHelper;
use IntegrityInvoice\Mailers\SignupMailer as SignupMailer;
use Carbon\Carbon;


class AdminAccountsController extends BaseController {

 	public $tenantID;
	public $totalRecords;
	public $searchquery;
	public $perPage;
    public $history;
    private $signupMailer;
    private $user;

	function __construct(TenantRepositoryInterface $tenant, SignupMailer $signupMailer, UserRepositoryInterface $user, PaymentsHistoryRepositoryInterface $history,CompanyDetailsRepositoryInterface $company )
    {

    	$this->tenant = $tenant;

		$this->totalRecords = $this->tenant->count($this->searchquery);
		$this->perPage = 10;
	    $this->history = $history;

		$this->company =$company;
        $this->user = $user;
        $this->signupMailer = $signupMailer;
    }


	public function index()
	{
		$this->searchquery = trim(Request::get('q'));

		// Pass in Item Model implementation and this class
		$readerService = new TenantReader($this->tenant, $this);
		$tenants = $readerService->readAll($this->searchquery);

	 	return View::make('adminaccounts.index')
		       ->with('title', 'Accounts')
			   ->with(compact('tenants'))
			   ->with('totalRecords', $this->totalRecords)
			   ->with('searchquery', $this->searchquery);

	}


	public function status($tenantID)
	{
		$readerService = new TenantReader($this->tenant, $this);
		$tenant = $readerService->read($tenantID);

	        if(!$tenant){
	            return Redirect::route('account_status', $tenantID)->with('failed_flash_message', 'Tenant not found.');
	        }

		return View::make('adminaccounts.status')
		       ->with('title', 'Account status for:'. $tenantID)
			   ->with(compact('tenant'));
	}
	public function edit($tenantID)
	{
		$readerService = new TenantReader($this->tenant, $this);
		$tenant = $readerService->read($tenantID);

	        if(!$tenant){
	            return Redirect::route('account_status', $tenantID)->with('failed_flash_message', 'Tenant not found.');
	        }
		$user = $this->user->findSuper($tenant->tenantID);
		return View::make('adminaccounts.edit')
		       ->with('title', 'Account Edit')
		       ->with(compact('tenant'))
		       ->with(compact('user'));
	}
	public function update()
	{

		$id = Input::get('userId');
        	$tenantID= Input::get('tenantID');
        	$notify = 1;

	 	$updateService = new UserUpdater($this->user, $this);
	 	$updateService->updateFromAdmin($tenantID,$id, array(
			'firstname' =>Input::get('firstname'),
			'lastname' =>Input::get('lastname'),
			'username' =>Input::get('username'),
			'phone' => Input::get('phone'),
			'email' => Input::get('email'),
			'notify' => $notify,
			'updated_at' => Carbon::now()
		));
		//$updateService->update_passwordFromAdmin($tenantID,$id, array(
		//	'password' =>Input::get('password'),
		//));
		$updateTenant = new TenantUpdater($this->tenant, $this);
		$tenant = $updateTenant ->updateStatusFromAdmin($tenantID, array(
			'level' => Input::get('level')
		));

		$updateCompany = new CompanyDetailsUpdater($this->company, $this);
		$updateCompany->updateFromadmin($tenantID,
		array(
			'company_name' =>Input::get('company_name'),
		));
		return Redirect::route('admin_accounts')->with('flash_message', 'Application account updated.');
	}

	public function ImpersonateUser($tenantID)
	{

		$readerService = new TenantReader($this->tenant, $this);
		$tenant = $readerService->read($tenantID);
		$user = $this->user->findSuper($tenant->tenantID);

		Session::put('user_id', $user->id);
		Session::put('email', $user->email);
		Session::put('tenantID', $user->tenantID);
		Session::put('firstname', $user->firstname);
		Session::put('lastname', $user->lastname);
		Session::put('theme_id', $user->theme_id);
		Session::put('account_plan', Tenant::where('tenantID', '=', $user->tenantID)->pluck('account_plan_id'));
		Session::put('invoice_template', Preference::where('tenantID', '=', $user->tenantID)->pluck('invoice_template'));
		Session::put('user_level', $user->level);
		Session::put('is_logged_in', true);


		return Redirect::route('dashboard')->with('flash_message', 'Login Successful');

	}

	public function update_status($tenantID)
	{
		$updateService = new TenantUpdater($this->tenant, $this);
		$tenant = $updateService->updateStatusFromAdmin($tenantID, array(
			'status' => Input::get('status')
		));

		return Redirect::route('account_status', $tenantID)->with('flash_message', 'Status was successfully updated.');
	}

	public function update_level($tenantID)
	{
		$updateService = new TenantUpdater($this->tenant, $this);
		$tenant = $updateService->updateStatusFromAdmin($tenantID, array(
			'account_plan_id' => Input::get('level')
		));

		return Redirect::route('account_status', $tenantID)->with('flash_message', 'Status was successfully updated.');
	}

	public function verify($tenantID)
	{
		$updateService = new TenantUpdater($this->tenant, $this);
		$tenant = $updateService->updateStatusFromAdmin($tenantID, array(
			'verified' => 1,
			'activation_key'=>'',
			'status' => 1
		));

		return Redirect::route('account_status', $tenantID)->with('flash_message', 'Account has been successfully verified.');

	}

	public function destroy($tenantID)
	{

	}


    public function extendSubscription($tenantID)
    {
        $readerService = new TenantReader($this->tenant, $this);
        $tenant = $readerService->read($tenantID);

        if(!$tenant){
            return Redirect::route('account_status', $tenantID)->with('failed_flash_message', 'Tenant not found.');
        }

        $histories = $this->history->getAll($tenantID);
        $date_format = Preference::where('tenantID', '=', $tenantID)->pluck('date_format');

        return View::make('adminaccounts.extend_subscription')
            ->with('title', 'Extend Subscription for:'. $tenantID)
            ->with(compact('tenant'))
            ->with(compact('histories'))
            ->with(compact('date_format'));
    }


    public function processExtendSubscription($tenantID)
    {

        $readerService = new TenantReader($this->tenant, $this);
        $tenant = $readerService->read($tenantID);

        if(!$tenant){
            return Redirect::route('extend_subscription', $tenantID)->with('failed_flash_message', 'Tenant not found.');
        }

        // Process Subscription extension
        $date_format = Preference::where('tenantID', '=', $tenant->tenantID)->pluck('date_format');
        $valid_from = $this->history->validTo($tenant->tenantID);

        // $new_valid_to_date = date ( 'Y-m-d' , strtotime ( '+'.(int)$duration.' day' , strtotime ($valid_from)));
        $duration = trim(Input::get('duration'));
        $account_plan_id = trim(Input::get('subscription_level'));
        $notify = Input::get('notify') ? 1 : 0;

        if($duration == "" || $duration == NULL){
            return Redirect::route('extend_subscription', $tenantID)->with('failed_flash_message', 'Invalid duration')->withInput();
        }

        $new_valid_to_date = $this->getExtensionDuration($duration, $valid_from);

        $paymentHistoryCreatorService = new PaymentHistoryCreator($this->history, $this);
        if($paymentHistoryCreatorService->createByExtension(array(
            'txn_id' => 'Admin'.time(),
            'sender_email' => 'admin@sighted.com',
            'tenantID' => $tenant->tenantID,
            'amount' => 0,
            'valid_from' => Carbon::now(),
            'valid_to' => $new_valid_to_date,
            'subscription_type' => $account_plan_id,
            'payment_system' => 'Integrity',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        )))
        {
            // upgrade
            $tenantUpdateService = new TenantUpdater($this->tenant, $this);
            $tenantUpdateService->updateStatusFromExtension(array(
                'account_plan_id' => $account_plan_id,
                'status' => 1,
                'updated_at' => Carbon::now()
            ), $tenant->tenantID);

        }

        // Send email notifying user of subscription extention.

        $subscription_level = AppHelper::get_subscription_plan($this->tenant->find($tenant->tenantID)->account_plan_id);

        $subscription_to_date = AppHelper::date_to_text($new_valid_to_date->toDateString(), $date_format);


        if($notify == 1){
            $tenant_user = $this->user->findSuper($tenant->tenantID);
            $this->signupMailer->send_admin_extension_upgrade($tenant_user->email, $tenant_user->firstname, $subscription_level, $subscription_to_date);

            return Redirect::route('extend_subscription', $tenantID)->with('flash_message', 'Subscription Extension was successful');

        }else{
            return Redirect::route('extend_subscription', $tenantID)->with('flash_message', 'Subscription Extension was successful');
        }

    }


    public function getExtensionDuration($duration, $valid_from){

       // $fromDate = Carbon::createFromFormat('Y-m-d', $valid_from);

        $fromDate = Carbon::now();
        $newToDate;

        switch ($duration) {
            case '1 week':
                $newToDate = $fromDate->addWeek();
                break;

            case '2 weeks':
                $newToDate = $fromDate->addWeeks(2);
                break;

            case '1 month':
                $newToDate = $fromDate->addMonth();
                break;

            case '2 months':
                $newToDate = $fromDate->addMonths(2);
                break;

            case '3 months':
                $newToDate = $fromDate->addMonths(3);
                break;

            case '6 months':
                $newToDate = $fromDate->addMonths(6);
                break;

            case '1 year':
                $newToDate = $fromDate->addYear();
                break;

            case '1 year 6 months':
                $newToDate = $fromDate->addMonths(18);
                break;

            case '2 years':
                $newToDate = $fromDate->addYears(2);
                break;

            case '2 years 6 months':
                $newToDate = $fromDate->addMonths(30);
                break;

            case '3 years':
                $newToDate = $fromDate->addYears(3);
                break;

            case '4 years':
                $newToDate = $fromDate->addYears(4);
                break;

            case '5 years':
                $newToDate = $fromDate->addYears(5);
                break;

            default:
                $newToDate = $fromDate->addWeek();
               break;
        }

        return $newToDate;
    }

}
