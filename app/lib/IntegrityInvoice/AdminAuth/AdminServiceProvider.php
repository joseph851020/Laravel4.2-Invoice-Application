<?php namespace IntegrityInvoice\AdminAuth;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Hashing\BcryptHasher;
use Illuminate\Auth\Reminders\PasswordBroker;
use Illuminate\Auth\Reminders\DatabaseReminderRepository;
use IntegrityInvoice\AdminAuth\AdminGuard;
use IntegrityInvoice\AdminAuth\AdminAuth;

class AdminServiceProvider extends ServiceProvider 
{	

    public function boot()
    {
      //  $this->registerAuthEvents();
    }

    public function register()
    {
        $this->registerAuth();
        $this->registerReminders();
    }

    protected function registerAuth()
    {
        $this->registerAdminCrypt();
        $this->registerAdminProvider();
        $this->registerAdminGuard();
    }

    protected function registerAdminCrypt()
    {
        $this->app['admin.auth.crypt'] = $this->app->share(function($app)
        {
            return new BcryptHasher;
        });
    }

    protected function registerAdminProvider()
    {
        $this->app['admin.auth.provider'] = $this->app->share(function($app)
        {
            return new EloquentUserProvider(
                $app['admin.auth.crypt'], 
                'Admin'
            );
        });
    }

    protected function registerAdminGuard()
    {
        $this->app['admin.auth'] = $this->app->share(function($app)
        {
            $guard = new AdminGuard(
                $app['admin.auth.provider'], 
                $app['session.store']
            );

            $guard->setCookieJar($app['cookie']);
            return $guard;
        });
    }

    protected function registerReminders()
    {
        # DatabaseReminderRepository
        $this->registerReminderDatabaseRepository();

        # PasswordBroker
        $this->app['admin.reminder'] = $this->app->share(function($app)
        {
            return new PasswordBroker(
                $app['admin.reminder.repository'], 
                $app['admin.auth.provider'], 
                $app['redirect'], 
                $app['mailer'], 
                'emails.admin.reminder' // email template for the reminder
            );
        });
    }

    protected function registerReminderDatabaseRepository()
    {
        $this->app['admin.reminder.repository'] = $this->app->share(function($app)
        {
            $connection   = $app['db']->connection();
            $table        = 'admin_reminders';
            $key          = $app['config']['app.key'];

            return new DatabaseReminderRepository($connection, $table, $key);
        });
    }

    protected function registerAuthEvents()
    {
		/*
        $app = $this->app;

        $app->after(function($request, $response) use ($app) {
            foreach (AdminAuth::getQueuedCookies() as $cookie) {
                $response->headers->setCookie($cookie);
            }
        });
		*/
    }

    public function provides()
    {
        return array(
            'admin.auth', 
            'admin.auth.provider', 
            'admin.auth.crypt', 
            'admin.reminder.repository', 
            'admin.reminder', 
        );
    }
}