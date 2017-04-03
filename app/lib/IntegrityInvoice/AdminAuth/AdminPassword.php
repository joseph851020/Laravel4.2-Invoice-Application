<?php namespace IntegrityInvoice\AdminAuth;

use Illuminate\Support\Facades\Facade;

class AdminPassword extends Facade
{

    protected static function getFacadeAccessor() 
    {
        return 'admin.reminder';
    }
}