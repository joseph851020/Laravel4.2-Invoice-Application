<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAffiliatesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('affiliates', function(Blueprint $table){
			$table->engine ='InnoDB';
			$table->increments('id');
			$table->string('username')->nullable();
			$table->string('firstname')->nullable();
			$table->string('lastname')->nullable();
			$table->string('company')->nullable();
			$table->string('address')->nullable();	
			$table->string('city')->nullable();	
			$table->string('state')->nullable();
			$table->string('country')->nullable();
			$table->string('phone')->nullable();	
			$table->string('postcode')->nullable();	
			$table->string('email');
			$table->unique('email');
			$table->string('website')->nullable();		
			$table->string('password');
			$table->string('paypal_email')->nullable();		
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
		Schema::drop('affiliates');
	}

}