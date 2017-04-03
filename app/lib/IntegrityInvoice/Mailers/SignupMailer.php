<?php namespace IntegrityInvoice\Mailers;

use Mail;
use IntegrityInvoice\Newsletters\NewsletterList;

class SignupMailer extends Mail {

    private $newsletterList;

    function __construct(NewsletterList $newsletterList)
    {
        $this->newsletterList = $newsletterList;
    }

    // Welcome Email
	public function welcome_message($email = "", $activationUrl = "")
	{
		$data = array('email' => $email, 'activationUrl' => $activationUrl);
		
		try
		{

			self::send('emails.welcome', $data, function($message) use ($data)
			{
				$message->from('info@sighted.com', 'Sighted');
				//$message->getHeaders()->addTextHeader('x-mailgun-native-send','true');
			        $message->to($data['email'])->subject('Welcome to Sighted, please confirm your account');
			
			});
			

		}
		catch(Exception $e)
		{
		    // fail
           return Redirect::to('dashboard')->with('flash_message', 'We are unable to send a welcome email to the email address you provided, please contact support@sighted.com, thank you for signing up.');
          // return false;
		}
		// success
		return true;

	}


        // Welcome Email
        public function resend_account_verification_message($email = "", $activationUrl = "")
        {
            $data = array('email' => $email, 'activationUrl' => $activationUrl);
            
            try 
            {

                self::send('emails.resend_account_verification_message', $data, function($message) use ($data)
                {
                    $message->from('info@sighted.com', 'Sighted');
                    //$message->getHeaders()->addTextHeader('x-mailgun-native-send','true');
                        $message->to($data['email'])->subject('Sighted account verification code');
                
                });
                

            }
            catch(Exception $e)
            {
                // fail
               return Redirect::to('dashboard')->with('flash_message', 'We are unable to send a verification email to the email address you provided, please contact support@sighted.com, Inconvience regretted!');
              // return false;
            }
            // success
            return true;

        }

	public function signup_notification($firstname = "", $email = "")
	{

		$data = array('firstname' => $firstname, 'email' => $email);

		try{

			 self::send('emails.signup_notification', $data, function($message) use ($data)
			{
				//$message->getHeaders()->addTextHeader('x-mailgun-native-send','true');
				$message->from('no-reply@sighted.com', 'Sighted');
			    $message->to('signup@sighted.com', 'Sighted Signup')->subject($data['firstname']. ' has signed up!');
			});

		}
		catch(Exception $e){
		    // fail
		    return false;
		}
		// success
		return true;

	}


    public function addUserToMailingList($email)
    {
        $this->newsletterList->subscribeTo('integritySubscribers', $email);
    }


	public function signup_found_via($found_integrity =  "")
	{

		$data = array('found_integrity' => $found_integrity);

		try{

			 self::send('emails.signup_found_via', $data, function($message) use ($data)
			{
				//$message->getHeaders()->addTextHeader('x-mailgun-native-send','true');
				$message->from('no-reply@sighted.com', 'Sighted');
			    $message->to('integrityinvoicediscovery@sighted.com', 'Sighted marketing')->subject('Sighted found via: '. $data['found_integrity']);
			});

		}
		catch(Exception $e){
		    // fail
		    return false;
		}
		// success
		return true;

	}






	public function cancellation_notification($firstname = "", $email = "")
	{

		$data = array('firstname' => $firstname, 'email' => $email);

		try{

			self::send('emails.cancel_notification', $data, function($message) use ($data)
			{
				$message->from('info@sighted.com', 'Sighted');
				//$message->getHeaders()->addTextHeader('x-mailgun-native-send','true');
			    $message->to($data['email'], $data['firstname'])->subject($data['firstname']. ' We\'re Sorry to See you Go! ');
			});

		}
		catch(Exception $e){
		    // fail
		    return false;
		}
		// success
		return true;

	}


	public function tenant_cancel_info($firstname = "", $tenantID = "", $email = "")
	{

		$data = array('firstname' => $firstname, 'email' => $email, 'tenantID' => $tenantID);

		try{

			self::send('emails.cancellation_info', $data, function($message) use ($data)
			{
				$message->from('no-reply@sighted.com', 'Sighted');
				//$message->getHeaders()->addTextHeader('x-mailgun-native-send','true');
			    $message->to('info@sighted.com', 'Sighted Invoice')->subject($data['firstname']. ' has canceled account');
			});

		}
		catch(Exception $e){
		    // fail
		    return false;
		}
		// success
		return true;

	}




	public function send_referral_free_upgrade($email = "", $firstname = "", $former_plan_id = "", $referral_code= "")
	{

		if($former_plan_id < 2){
			$subject = "Congratulations we've upgraded your account";
		}else{
			$subject = "Congratulations your subscription has been extended";
		}

		$data = array('subject' => $subject, 'firstname' => $firstname, 'email' => $email, 'former_plan_id' => $former_plan_id, 'referral_code' => $referral_code);

		try{
			self::send('emails.referrer_upgrade', $data, function($message) use ($data)
			{
				$message->from('info@sighted.com', 'Sighted');
			    $message->to($data['email'], $data['firstname'])->subject($data['subject']);
			});
		}
		catch(Exception $e){
		    // fail
		    return false;
		}
		// success
		return true;
	}


    public function send_admin_extension_upgrade($email = "", $firstname = "", $subscription_level = "", $to_date)
    {
        $subject = "Your subscription has been extended";
        $data = array('subject' => $subject, 'firstname' => $firstname, 'email' => $email, 'subscription_level' => $subscription_level, 'to_date' => $to_date);

        try{
            self::send('emails.extention_upgrade', $data, function($message) use ($data)
            {
                $message->from('info@sighted.com', 'Sighted');
                $message->replyTo('support@sighted.com', 'Sighted Support');
                $message->to($data['email'], $data['firstname'])->subject($data['subject']);
            });

        }
        catch(Exception $e){
            // fail
            return false;
        }
        // success
        return true;

    }



    /*
   // Password Changed Email
   public function password_changed($email=""){
       $from_email = "support@integrityinvoice.com";
       $from_name = 'IntegrityInvoice Support';
       $email_body = "Hi, Your Integrity Invoice account password was recently changed, if you did not initiate this operation please contact support@integrityinvoice.com as soon as possible.<br /><br />  Do not reply to this email as it is auto generated.<br /><br />Thank you,<br />The Integrity Invoice Team<br /><br /><img src='https://www.integrityinvoice.com/img/logo.png' alt='' />";

       $this->from($from_email, $from_name);
       $this->to($email);

       $this->subject("Your Integrity Invoice account password was changed.");
       $this->message($email_body);
       $this->set_alt_message($email_body);
       if($this->send()){
           return true;
       }
   }


   // Reminder day 3 Email
   public function send_invoice_reminder_day3(){

   }


   // Reminder day 7 Email
   public function send_invoice_reminder_day7(){

   }


   public function send_backup($subject, $message, $to, $from, $from_name, $attachment){

       $this->from($from, $from_name);
       $this->to($to);

       $this->subject($subject);
       $this->message($message);
       $this->set_alt_message($message);
       $this->attach($attachment);
       if($this->send()){
           return true;
       }
   }//

    */

}
