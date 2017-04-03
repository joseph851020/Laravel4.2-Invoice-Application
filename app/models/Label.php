<?php

class Label extends \Eloquent {

	 protected $guarded = array('id');
	 
	 public function tenant()
     {
        return $this->belongsTo('Tenant', 'id');
     }
}