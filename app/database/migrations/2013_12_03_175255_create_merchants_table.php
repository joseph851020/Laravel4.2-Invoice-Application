<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMerchantsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('merchants', function(Blueprint $table){
			$table->engine ='InnoDB';
			$table->increments('id');
			$table->string('tenantID');
			$table->string('company')->nullable();
			$table->string('add_1')->nullable();
			$table->string('add_2')->nullable();
			$table->string('postal_code', 10)->nullable();
			$table->string('city', 150)->nullable();
			$table->string('state', 150)->nullable();
			$table->string('country', 150)->nullable();	
			$table->string('tax_id', 30)->nullable();
			$table->string('firstname', 100)->nullable();
			$table->string('lastname', 100)->nullable();
			$table->string('email')->nullable();
			$table->string('phone', 50)->nullable();			
			$table->string('firstname_secondary', 100)->nullable();
			$table->string('lastname_secondary', 100)->nullable();
			$table->string('email_secondary')->nullable();
			$table->string('phone_secondary', 50)->nullable();						
			$table->text('notes')->nullable();				
			$table->timestamps();
			$table->foreign('tenantID')->references('tenantID')->on('tenants')->onDelete('cascade');	
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
		Schema::drop('merchants');
	}

}