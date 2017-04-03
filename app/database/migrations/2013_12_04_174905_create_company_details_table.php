<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCompanyDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('company_details', function(Blueprint $table){
			$table->engine ='InnoDB';
			$table->increments('id');
			$table->string('tenantID');
			$table->string('company_name')->nullable();
			$table->string('add_1')->nullable();
			$table->string('add_2')->nullable();
			$table->string('city', 150)->nullable();
			$table->string('state', 150)->nullable();
			$table->string('postal_code', 10)->nullable();			
			$table->string('country', 150)->nullable();
			$table->string('email');
			$table->string('cardBillingId')->nullable();
			$table->string('phone', 50)->nullable();
			$table->string('fax', 50)->nullable();
			$table->string('website')->nullable();
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
		Schema::drop('company_details');
	}

}