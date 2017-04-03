<?php 

class Currency extends Eloquent{
	
	protected $guarded = array('id');
	
	// If the table is not named as plural
	protected $table = 'currency';

}
