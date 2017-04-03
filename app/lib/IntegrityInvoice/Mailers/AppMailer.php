<?php namespace IntegrityInvoice\Mailers;

use Mail;
use Config;


class AppMailer extends Mail{
 
	// Send invoice
	public function send_invoice($inv_email_subject="", $inv_email_body="", $client_email="", $client_name ="", $from_email="", $from_name="", $attachment=""){
	 
		$data = array('inv_email_subject' => $inv_email_subject, 'inv_email_body' => $inv_email_body, 'client_email'=> $client_email, 'client_name' => $client_name, 'from_email' => $from_email, 'from_name' => $from_name, 'attachment' => $attachment);
		
		try{
     		
			self::send('emails.invoice', $data, function($message) use ($data)
			{ 
				$message->from('no-reply@sighted.com',  $data['from_name']);
				$message->replyTo($data['from_email'],  $data['from_name']);
				//$message->getHeaders()->addTextHeader('x-mailgun-native-send','true');		
			    $message->to($data['client_email'], $data['client_name'])->subject( $data['inv_email_subject']);
				$message->attach($data['attachment']);
			}); 
		
		}
		catch(Exception $e){
		    // fail
		    return false;
		}		
		// success	  
		return true;
	 
	}
	
	
	// Send invoice reminder
	public function send_invoice_reminder($inv_email_subject="", $inv_email_body="", $client_email="", $client_name ="", $from_email="", $from_name="", $attachment=""){
		 
		$data = array('inv_email_subject' => $inv_email_subject, 'inv_email_body' => $inv_email_body, 'client_email'=> $client_email, 'client_name' => $client_name, 'from_email' => $from_email, 'from_name' => $from_name, 'attachment' => $attachment);
		
		try{
     		
			self::send('emails.invoice', $data, function($message) use ($data)
			{
				$message->from('no-reply@sighted.com',  $data['from_name']);
				$message->replyTo($data['from_email'],  $data['from_name']);				 
				//$message->getHeaders()->addTextHeader('x-mailgun-native-send','true');		
			    $message->to($data['client_email'], $data['client_name'])->subject( $data['inv_email_subject']);
				$message->attach($data['attachment']);
			});
		
		}
		catch(Exception $e){
		    // fail
		    return false;
		}		
		// success	  
		return true;
	 
	}
	
	
	// Send send payment acknowledgement
	public function send_payment_acknowledgement($inv_email_subject="", $inv_email_body="", $client_email="", $client_name ="", $from_email="", $from_name="", $attachment=""){
		 
		$data = array('inv_email_subject' => $inv_email_subject, 'inv_email_body' => $inv_email_body, 'client_email'=> $client_email, 'client_name' => $client_name, 'from_email' => $from_email, 'from_name' => $from_name);
		
		try{
     		
			self::send('emails.payment_acknowledgement', $data, function($message) use ($data)
			{
			 
				$message->from('no-reply@sighted.com',  $data['from_name']);
				$message->replyTo($data['from_email'],  $data['from_name']);
				//$message->getHeaders()->addTextHeader('x-mailgun-native-send','true');		
			    $message->to($data['client_email'], $data['client_name'])->subject( $data['inv_email_subject']);
				 
			}); 
		
		}
		catch(Exception $e){
		    // fail
		    return false;
		}		
		// success	  
		return true;
	 
	}
 
	
	// seller_invoice_payment_notification
	public function seller_invoice_payment_notification($firstname = "",  $seller_email="", $seller_company ="", $tenant_invoice_id="", $buyer_email="", $client_company="", $payment_system = "", $date_paid="", $email_subject =""){
		
		$data = array('email_subject'=> $email_subject, 'firstname' => $firstname, 'seller_email' => $seller_email, 'seller_company' => $seller_company, 'buyer_email' => $buyer_email, 'tenant_invoice_id' => $tenant_invoice_id, 'payment_system' => $payment_system, 'date_paid' => $date_paid, 'client_company' => $client_company);
		
		try{
     		
			self::send('emails.seller_invoice_payment', $data, function($message) use ($data)
			{
				$message->from('no-reply@sighted.com',  'Sighted');			 
				//$message->getHeaders()->addTextHeader('x-mailgun-native-send','true');		
			    $message->to($data['seller_email'], $data['seller_company'])->subject( $data['email_subject']);
			 
			}); 
		
		}
		catch(Exception $e){
		    // fail
		    return false;
		}		
		// success	  
		return true;
	 
	}
	
 
	// buyer_invoice_payment_notification
	public function buyer_invoice_payment_notification($company_email="",   $company_name="", $tenant_invoice_id="", $buyer_email="", $payment_system = "", $email_subject =""){
		
		$data = array('email_subject'=> $email_subject, 'company_name' => $company_name, 'company_email' => $company_email, 'buyer_email' => $buyer_email, 'tenant_invoice_id' => $tenant_invoice_id, 'payment_system' => $payment_system);
		
		try{
     		
			self::send('emails.buyer_invoice_payment', $data, function($message) use ($data)
			{
				 
				$message->from('no-reply@sighted.com',  $data['company_name']);
				$message->replyTo($data['company_email'],  $data['company_name']);				
				//$message->getHeaders()->addTextHeader('x-mailgun-native-send','true');		
			    $message->to($data['buyer_email'], $data['buyer_email'])->subject( $data['email_subject']);
			 
			}); 
		
		}
		catch(Exception $e){
		    // fail
		    return false;
		}		
		// success	  
		return true;
	 
	}
	
	 
	// Send Support Email (Complaints / Feature request)
	public function send_support($from_name="", $from_email="", $email_subject="", $email_body=""){
		
		$data = array('email_subject'=> $email_subject, 'email_body' => $email_body, 'from_email' => $from_email, 'from_name' => $from_name);
		
		try{
     		
			self::send('emails.support', $data, function($message) use ($data)
			{
				 
				$message->from('no-reply@sighted.com',  $data['from_name']);
				$message->replyTo($data['from_email'],  $data['from_name']);							 
				// $message->getHeaders()->addTextHeader('x-mailgun-native-send','true');		
			    $message->to(Config::get('app.support_email'), 'Sighted Support')->subject( $data['email_subject']);
			 
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