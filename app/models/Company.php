<?php 

class Company extends Eloquent {
	
	 protected $table = "company_details";
	
	 protected $guarded = array('id');
	 
	 public function tenant()
     {
        return $this->belongsTo('Tenant', 'id');
     }
}