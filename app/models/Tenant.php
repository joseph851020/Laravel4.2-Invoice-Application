<?php

class Tenant extends Eloquent{

	protected $guarded = array('id, account_plan_id');

	public function company()
    {
       return $this->hasOne('Company', 'id');

    }

	public function users()
    {
        // return $this->hasMany('User', 'id');
        return $this->hasMany('User', 'tenantID', 'tenantID');
    }

	public function label()
    {
        return $this->hasOne('Label', 'id');
    }

	public function client()
    {
       return $this->hasOne('Client', 'id');

    }


	public function merchant()
    {
       return $this->hasOne('Merchant', 'id');

    }

}
