<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInvoicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('invoices', function(Blueprint $table){
			$table->engine ='InnoDB';
			$table->increments('id');
			$table->string('tenantID');
			$table->integer('quote')->default(0); // 0 for quote 1 for invoice
			$table->string('client_name');
			$table->string('purchase_order_number')->nullable();
			$table->text('items')->nullable();
			$table->date('due_date')->nullable();
			$table->integer('user_id');
			$table->integer('payment')->default(0);
			$table->string('cur', 50)->nullable();
			$table->integer('currency_id')->nullable();
			$table->string('currency_code')->nullable();
			$table->integer('discount_perc')->nullable();
			$table->integer('tax_perc')->nullable();
			$table->text('note')->nullable();
			$table->string('subject')->nullable();
			$table->string('file')->nullable();
			$table->string('token')->default(0);
			$table->integer('receipt')->default(0);
			$table->integer('bankinfo')->default(1);			
			$table->date('last_payment_date')->nullable();
			$table->decimal('subtotal',13,2);
			$table->decimal('balance_due',13,2);
			$table->decimal('discount_val',13,2)->nullable();
			$table->decimal('tax_val',13,2)->nullable();
			$table->integer('client_id')->unsigned();
			$table->integer('tenant_invoice_id')->default(0); // 0 for quote
			$table->index('tenant_quote_id');
			$table->index('tenant_invoice_id');
			$table->integer('tenant_quote_id')->default(0); // 
			$table->integer('status')->default(0);
			$table->integer('business_model')->default(0); // 0 is Product oriented, 1 is Service oriented
			$table->integer('bill_option')->default(0); // 0 is By the hour, 1 is by the project			
			$table->integer('enable_discount')->default(1); // 0 is not, 1 is 
			$table->integer('enable_tax')->default(1); // 0 is not, 1 is 
			$table->integer('reminder3')->default(0);
			$table->integer('reminder7')->default(0);
			$table->integer('set_reminder')->default(0);
			$table->integer('recurring')->default(0);
			$table->string('recur_schedule')->nullable();
            $table->integer('recur_due_date_interval')->nullable();
			$table->integer('recur_status')->default(1);
			$table->date('recur_next_date')->nullable();
            $table->integer('created_from_recurring')->default(0);
			$table->integer('auto_send')->default(1);
			$table->date('recurring_start_date')->nullable();
			$table->date('recurring_end_date')->nullable();		
			$table->timestamps();
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
		Schema::drop('invoices');
	}

}