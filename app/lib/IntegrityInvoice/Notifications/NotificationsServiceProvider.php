<?php namespace IntegrityInvoice\Notifications;

use Illuminate\Support\ServiceProvider;

class NotificationsServiceProvider extends ServiceProvider{

    public function register()
    {
        $this->app->bind(
            'IntegrityInvoice\Notifications\FeatureUpdates',
            'IntegrityInvoice\Notifications\Mailchimp\FeatureUpdates'
        );
    }
}