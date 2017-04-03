<?php namespace IntegrityInvoice\Mailers;

use Mail;


class BillingMailer extends Mail{
 
	public function ipn_message($to = "", $ipn_data)
	{
		
		try{
     		
			self::send('emails.ipn_message', $ipn_data, function($message) use ($ipn_data)
			{
				$message->from('no-reply@sighted.com',  'Sighted');				 
				//$message->getHeaders()->addTextHeader('x-mailgun-native-send','true');				
			    $message->to($to, 'Sighted')->subject('Instant Payment Notification - Recieved Payment from '.$ipn_data['name']);
			});
		
		}
		catch(Exception $e){
		    // fail
		    return false;
		}		
		// success	  
		return true;
	 
	}
	
	
	
	public function subscription_payment($firstname = "", $amount = "", $payment_system = "", $email = "", $date_paid="", $email_subject =""){
		
		$data = array('email_subject'=> $email_subject, 'firstname' => $firstname, 'to_email' => $email, 'payment_system' => $payment_system, 'amount' => $amount, 'date_paid' => $date_paid);
		
		try{
     		
			self::send('emails.subscription_payment', $data, function($message) use ($data)
			{
				$message->from('no-reply@sighted.com', 'Sighted');				 
				//$message->getHeaders()->addTextHeader('x-mailgun-native-send','true');		
			    $message->to( $data['to_email'],  $data['firstname'])->subject( $data['email_subject']);
			 
			}); 
		
		}
		catch(Exception $e){
		    // fail
		    return false;
		}		
		// success	  
		return true;
	 
	}
 
}