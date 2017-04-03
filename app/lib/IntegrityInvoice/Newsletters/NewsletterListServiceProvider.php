<?php namespace IntegrityInvoice\Newsletters;

use Illuminate\Support\ServiceProvider;

class NewsletterListServiceProvider extends ServiceProvider{

    public function register()
    {
        $this->app->bind(
            'IntegrityInvoice\Newsletters\NewsletterList',
            'IntegrityInvoice\Newsletters\Mailchimp\NewsletterList'
        );
    }
}