<?php namespace IntegrityInvoice\AdminAuth;

use Illuminate\Auth\Guard as AuthGuard;

class AdminGuard extends AuthGuard
{

    public function getName()
    {
        return 'login_' . md5('AdminAuth');
    }

    public function getRecallerName()
    {
        return 'remember_' . md5('AdminAuth');
    }
}