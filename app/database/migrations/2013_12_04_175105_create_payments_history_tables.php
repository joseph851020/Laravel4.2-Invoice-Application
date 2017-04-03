<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePaymentsHistoryTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('payments_history', function(Blueprint $table){
			$table->engine ='InnoDB';
			$table->increments('id');
			$table->string('tenantID');			
			$table->string('txn_id');
			$table->string('cardBillingId')->nullable();
			$table->string('sender_email');
			$table->integer('subscription_type');
			$table->decimal('amount',13,2);
			$table->date('valid_from');
			$table->date('valid_to');
			$table->string('payment_system', 100);					
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
		Schema::drop('payments_history');
	}

}