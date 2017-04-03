<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInvoicePaymentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('invoice_payments', function(Blueprint $table){
			$table->engine ='InnoDB';
			$table->increments('id');
			$table->string('tenantID');
			$table->decimal('amount',13,2);
			$table->integer('client_id')->unsigned();		
			$table->integer('tenant_invoice_id');
			$table->string('payment_method')->nullable();
			$table->string('cheque_number')->nullable();
			$table->string('online_ref')->nullable();
			$table->string('bank_transfer_ref')->nullable();
			$table->integer('user_id')->nullable();
			$table->integer('sent')->nullable();				
			$table->timestamps();
			$table->foreign('tenant_invoice_id')->references('tenant_invoice_id')->on('invoices')->onDelete('cascade');
			$table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
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
		Schema::drop('invoice_payments');
	}

}