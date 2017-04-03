<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePaymentGatewaysTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('payment_gateways', function(Blueprint $table){
			$table->engine ='InnoDB';
			$table->increments('id');
			$table->string('tenantID');
			$table->string('paypal_email')->nullable();
			$table->string('paypal_currency')->nullable();
			$table->string('stripe_secret_key')->nullable();
			$table->string('stripe_publishable_key')->nullable();
			$table->string('stripe_currency')->nullable();
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
		Schema::drop('payment_gateways');
	}

}