<?php namespace IntegrityInvoice\Utilities;

class MessagesHelper{
	
	// Default preferences strings
	public static function invoice_note(){
		return $invoice_note = "Thank you for your business, We accept payment by Paypal, bank transfer and cheque. Please send full payment before the due date.";
	}
	
	public static function quote_note(){
		return $quote_note = "If you're happy with this quote please let us know, we would be able to convert it into an invoice immediately. Thank you!";
	}
	
	//
	public static function reminder_message_subject(){
		return $reminder_message_subject = "Payment reminder for Invoice _INVOICE_NUMBER_";	
	}
	
	//
	public static function reminder_message(){
		return $reminder_message = "Dear _CLIENT_CONTACT_PERSON_,
		
Payment is due for Invoice _INVOICE_NUMBER_.

Breakdown
------------------------------------------------------------------------
Amount due: _AMOUNT_DUE_
Payment made to date: _PAYMENT_TO_DATE_
Outstanding: _STILL_TO_PAY_
------------------------------------------------------------------------

You can also view your invoice and pay online through this link:
_INVOICE_WEBPAGE_VIEW_

Please make payment immediately to avoid late charges.

Kind regards,
_SENDER_USER_
_SENDER_COMPANY_";
	
	}
	
	
	//
	public static function progress_payment_message_subject(){
		return $progress_payment_message_subject = "Receipt of payment _AMOUNT_PART_PAID_ for Invoice _INVOICE_NUMBER_";	
	}
	
	//
	public static function progress_payment_message(){
		return $progress_payment_message = "Dear _CLIENT_CONTACT_PERSON_,
		
We received payment of _AMOUNT_PART_PAID_ for Invoice _INVOICE_NUMBER_.
Payment method: _PAYMENT_METHOD_

Breakdown
------------------------------------------------------------------------
Total due: _AMOUNT_DUE_
Payment made to date: _PAYMENT_TO_DATE_
Outstanding: _STILL_TO_PAY_
------------------------------------------------------------------------

Please click the link below to view and download the updated invoice showing the payment status / receipt for this invoice:
_INVOICE_WEBPAGE_VIEW_

Kind regards,
_SENDER_USER_
_SENDER_COMPANY_";
	
	}
	
	
	
	//
	public static function invoice_send_message_subject(){
		return $invoice_send_message_subject = "Invoice _INVOICE_NUMBER_ for _CLIENT_COMPANY_";	
	}
	
	//
	public static function invoice_send_message(){
		return $invoice_send_message = "Dear _CLIENT_CONTACT_PERSON_,
				
Invoice _INVOICE_NUMBER_ is attached, please make payment by _DUE_DATE_.

You can also view your invoice and pay online through the link below:
_INVOICE_WEBPAGE_VIEW_

We appreciate your business as always.

Kind regards,
_SENDER_USER_
_SENDER_COMPANY_";
		
	}
	
	
	//
	public static function quote_send_message_subject(){
		return $invoice_send_message_subject = "Quote _QUOTE_NUMBER_ for _CLIENT_COMPANY_";	
	}
	
	//
	public static function quote_send_message(){
		return $invoice_send_message = "Dear _CLIENT_CONTACT_PERSON_,
				
Quote _QUOTE_NUMBER_ is attached.
 
We appreciate your business as always.

Kind regards,
_SENDER_USER_
_SENDER_COMPANY_";
		
	}
	
	//
	public static function thank_you_message_subject(){
		return $thank_you_message_subject = "We have received full payment for invoice _INVOICE_NUMBER_";
	}
	
	//
	public static function thank_you_message(){
		
return $thank_you_message = "Dear _CLIENT_CONTACT_PERSON_,
		
Thank you for making full payment of _AMOUNT_DUE_ towards invoice _INVOICE_NUMBER_.

An official receipt has been attached to this email.

If you have any queries please let me know.

Kind regards,
_SENDER_USER_
_SENDER_COMPANY_";
	}

}