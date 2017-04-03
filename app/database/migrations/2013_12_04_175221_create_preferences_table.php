<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePreferencesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('preferences', function(Blueprint $table){
			$table->engine ='InnoDB';
			$table->increments('id');
			$table->string('tenantID');
			$table->string('date_format', 12);
			$table->string('time_zone')->nullable();
			$table->integer('currency')->nullable();
			$table->string('currency_code', 3)->nullable();
			$table->string('industry')->nullable();
			$table->integer('page_record_number')->nullable();		 
			$table->text('invoice_note')->nullable();
			$table->text('quote_note')->nullable();
			$table->text('reminder_message')->nullable();
			$table->text('reminder_message_subject')->nullable();
			$table->text('invoice_send_message')->nullable();
			$table->text('invoice_send_message_subject')->nullable();
			$table->text('quote_send_message')->nullable();
			$table->text('quote_send_message_subject')->nullable();
			$table->text('thank_you_message')->nullable();
			$table->text('thank_you_message_subject')->nullable();
			$table->text('progress_payment_message_subject')->nullable();
			$table->text('progress_payment_message')->nullable();			
			$table->float('tax_perc')->nullable();
			$table->string('tax_1name', 40)->nullable();
			$table->string('tax_2name', 40)->nullable();
			$table->float('tax_perc1')->nullable();
			$table->float('tax_perc2')->nullable();				
			$table->string('vat_id')->nullable();
			$table->string('company_reg')->nullable();
			$table->string('invoice_prefix')->nullable();
			$table->text('payment_details')->nullable();
			$table->text('footnote1')->nullable();
			$table->text('footnote2')->nullable();
			$table->integer('pro_rated_due_date')->default(0)->nullable();
			$table->integer('custom_pro_rated_due_date')->default(0)->nullable();		
			$table->integer('invoice_template')->default(1);
			$table->integer('receipt_template')->default(1);
			$table->integer('use_purchase_order')->default(0);
			$table->integer('use_invoice_sticker')->default(1); // 1 Yes 0 is No
			$table->integer('invoice_sticker_id')->default(1); // 1 Yes 0 is No
			$table->integer('business_model')->default(0); // 0 is Product oriented, 1 is Service oriented
			$table->integer('bill_option')->default(0); // 0 is By the hour, 1 is by the project
			$table->integer('enable_discount')->default(0); // 0 is not, 1 is 
			$table->integer('enable_tax')->default(0); // 0 is not, 1 is 
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
		Schema::drop('preferences');
	}

}