<?php namespace IntegrityInvoice\Handlers;

use Carbon\Carbon;


use IntegrityInvoice\Mailers\SignupMailer as SignupMailer;
use IntegrityInvoice\Services\User\Updater;
use IntegrityInvoice\Repositories\UserRepositoryInterface;
use IntegrityInvoice\Services\PaymentHistory\Creator as PaymentHistoryCreator;
use IntegrityInvoice\Repositories\TenantRepositoryInterface;
use IntegrityInvoice\Repositories\PaymentsHistoryRepositoryInterface;
use IntegrityInvoice\Services\Tenant\Updater as TenantUpdater;



class UserEventHandler {

    private $signupMailer;
    private $user;
    public  $paymentHistory;
    private $tenant;


    function __construct(TenantRepositoryInterface $tenant, SignupMailer $signupMailer, UserRepositoryInterface $user, PaymentsHistoryRepositoryInterface $paymentHistory)
    {
        $this->signupMailer = $signupMailer;
        $this->user = $user;
        $this->tenant = $tenant;
        $this->paymentHistory = $paymentHistory;
    }

    public function subscribe($events)
    {
        $events->listen('user.signup', 'IntegrityInvoice\Handlers\UserEventHandler@onSignup');
        $events->listen('user.login', 'IntegrityInvoice\Handlers\UserEventHandler@onUserLogin');
        $events->listen('user.logout', 'IntegrityInvoice\Handlers\UserEventHandler@onUserLogout');
        //$events->listen('user.resend_account_verification_mail', 'IntegrityInvoice\Handlers\UserEventHandler@resend_account_verification_mail');

    }

    public function onSignup($user, $activationCode, $referral_tenant)
    {
    	// Welcome Email
        $this->signupMailer->welcome_message($user->email, $activationCode);
	// Subscribe User to Mailchimp	
        $this->signupMailer->addUserToMailingList($user->email);

        // Process the rest
        $paymentHistoryCreatorService = new PaymentHistoryCreator($this->paymentHistory, $this);

        // Get user with the referral code
        if($referral_tenant != null)
        {
            $duration = 30;

            if($referral_tenant->account_plan_id < 2){
                // Upgrade to Medium plan by one month
                $valid_from = strftime("%Y-%m-%d", time());
                $new_valid_to_date = date ( 'Y-m-d' , strtotime ( '+'.(int)$duration.' day' , strtotime ($valid_from)));
                $account_plan_id = 2;
            }
            else
            {
                // Extend current subscription by one month
                $valid_from = $this->paymentHistory->validTo($referral_tenant->tenantID);
                $new_valid_to_date = date ( 'Y-m-d' , strtotime ( '+'.(int)$duration.' day' , strtotime ($valid_from)));
                $account_plan_id = $referral_tenant->account_plan_id;
            }

            if($paymentHistoryCreatorService->createByReferral(array(
                'txn_id' => 'Referral'.time(),
                'sender_email' => 'referral@integrityinvoice.com',
                'tenantID' => $referral_tenant->tenantID,
                'amount' => 0,
                'valid_from' => $valid_from,
                'valid_to' => $new_valid_to_date,
                'subscription_type' => $account_plan_id,
                'payment_system' => 'Referral',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            )))
            {
                // upgrade
                $tenantUpdateService = new TenantUpdater($this->tenant, $this);
                $tenantUpdateService->updateStatusFromReferral(array(
                    'account_plan_id' => $account_plan_id,
                    'status' => 1,
                    'updated_at' => Carbon::now()
                ), $referral_tenant->tenantID);

            }

            // Send email notifying user of FREE upgrade to premium for one month.
            $referral_user = $this->user->findSuper($referral_tenant->tenantID);
            $this->signupMailer->send_referral_free_upgrade($referral_user->email, $referral_user->firstname, $referral_tenant->account_plan_id, $referral_tenant->referral_code);

        } // $referral_tenant != NULL
	
        // 5. Create New new directory inside the Tenant Data Dir
        $path = public_path().'/te_da/'.$user->tenantID;
        if(!is_dir($path))
	{
	    mkdir($path , 0777);
	    chmod($path , 0777);
	    mkdir($path.'/invoices' , 0777);
            chmod($path.'/invoices' , 0777);
            mkdir($path.'/quotes' , 0777);
            chmod($path.'/quotes' , 0777);
            mkdir($path.'/expenses' , 0777);
            chmod($path.'/expenses' , 0777);
            mkdir($path.'/products' , 0777);
            chmod($path.'/products' , 0777);
            mkdir($path.'/services' , 0777);
            chmod($path.'/services' , 0777);
            mkdir($path.'/receipts' , 0777);
            chmod($path.'/receipts' , 0777);
            mkdir($path.'/credit_notes' , 0777);
            chmod($path.'/credit_notes' , 0777);
            mkdir($path.'/attachments' , 0777);
            chmod($path.'/attachments' , 0777);
            mkdir($path.'/attachments/invoices' , 0777);
            chmod($path.'/attachments/invoices' , 0777);
            mkdir($path.'/attachments/quotes' , 0777);
            chmod($path.'/attachments/quotes' , 0777);
            mkdir($path.'/attachments/expenses' , 0777);
            chmod($path.'/attachments/expenses' , 0777);
            mkdir($path.'/user_data' , 0777);
            chmod($path.'/user_data' , 0777);
            mkdir($path.'/other' , 0777);
            chmod($path.'/other' , 0777);
            $fileHandle = fopen($path.'/index.php', 'w');
            fclose($fileHandle);
        } // End If !is_dir
	
    }


    public function onUserLogin($user)
    {
        $ip = $_SERVER["REMOTE_ADDR"];

        // Record Last Loggedin
        $updateService = new Updater($this->user, $this);
        return $updateService->update_login($user->tenantID, $user->id, array(
            'last_logged_in' => Carbon::now(),
            'last_logged_in_ip' => $ip
        ));
    }


    public function onUserLogout($user)
    {
        // Record Last Loggedin
        // Log::info('User signed out'. $user. $datetime);
    }

    public function resend_account_verification_mail($user, $activationCode) {
        $this->signupMailer->resend_account_verification_message($user->email, $activationCode);
    }

} 
