<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateExpensesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('expenses', function(Blueprint $table){
			$table->engine ='InnoDB';
			$table->increments('id');
			$table->string('tenantID');
			$table->decimal('amount',13,2);
			$table->integer('category_id')->nullable();			
			$table->integer('user_id')->nullable();			
			$table->text('note')->nullable();
			$table->string('ref_no')->nullable();
			$table->string('currency_code')->nullable();
			$table->unsignedInteger('merchant_id')->nullable();
			$table->date('expense_date')->nullable();
            $table->integer('recurring')->default(0);
            $table->string('recur_schedule')->nullable();
            $table->integer('recur_status')->default(1);
            $table->date('recur_next_date')->nullable();
            $table->integer('created_from_recurring')->default(0);
            $table->date('recurring_start_date')->nullable();
            $table->date('recurring_end_date')->nullable();
            $table->string('file')->nullable();
			$table->integer('archived')->nullable();		
			$table->decimal('tax1_val',13,2)->nullable();
			$table->decimal('tax2_val',13,2)->nullable();
			$table->foreign('merchant_id')->references('id')->on('merchants')->onDelete('cascade');
			$table->foreign('tenantID')->references('tenantID')->on('tenants')->onDelete('cascade');					
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
		Schema::drop('expenses');
	}

}