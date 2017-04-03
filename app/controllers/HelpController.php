<?php

class HelpController extends BaseController {

 
	public function index()
	{
        return View::make('help.index')->with('title', 'Getting Started');
	}
	
	public function getting_started()
	{
         return View::make('help.getting_started')->with('title', 'Getting started');
	}
 
	public function videos()
	{
         return View::make('help.videos')->with('title', 'Video tutorials');
	}
 
	/* INTRODUCTION */
	 
	public function about_sighted()
	{
         return View::make('help.docs.introduction.about_integrity')->with('title', 'About Sighted');
	}
	
	public function why_use_sighted()
	{
         return View::make('help.docs.introduction.why_use_integrity')->with('title', 'Why Use Sighted');
	}
	
	public function faq()
	{
         return View::make('help.docs.introduction.faq')->with('title', 'Frequently Asked Questions');
	}
 
	public function glossary()
	{
         return View::make('help.docs.introduction.glossary')->with('title', 'Glossary of Terms');
	}
	
	
	/* INVOICES */
    public function invoice_create()
    {
        return View::make('help.docs.invoices.how-to-create-and-send-invoice')->with('title', 'Creating a new invoice');
    }


    /* EXPENSES */
    public function expense_create()
    {
        return View::make('help.docs.expenses.how-to-create-an-expense')->with('title', 'Creating a new expense');
    }


    /* CLIENTS */
    public function client_create()
    {
        return View::make('help.docs.clients.how-to-create-a-client')->with('title', 'Creating a new client');
    }


    /* MERCHANTS */
    public function merchant_create()
    {
        return View::make('help.docs.merchants.how-to-create-a-merchant')->with('title', 'Creating a new merchant');
    }


    /* PRODUCTS */
    public function product_create()
    {
        return View::make('help.docs.products.how-to-create-a-product')->with('title', 'Creating a new product');
    }


    /* SERVICES */
    public function service_create()
    {
        return View::make('help.docs.services.how-to-create-a-service')->with('title', 'Creating a new service');
    }
	
	
	/* SETTINGS */
    public function settings_general()
    {
        return View::make('help.docs.settings.general-settings')->with('title', 'Updating your settings');
    }


    /* DATA PROTECTION AND SECURITY */

    public function security_privacy()
    {
        return View::make('help.docs.security.security-privacy')->with('title', 'Security and Privacy of Data');
    }
	

}