<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;


class CreateAccountplansTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('accountplans', function(Blueprint $table){
			$table->engine ='InnoDB';
			$table->increments('id');
			$table->string('account_type');
			$table->integer('invoice_limit')->nullable();
			$table->integer('quote_limit')->nullable();
			$table->integer('user_limit')->nullable();
			$table->integer('item_limit')->nullable();
			$table->integer('recurring_limit')->nullable();
			$table->text('description')->nullable();			
			$table->integer('client_limit')->nullable();
			$table->integer('expense_limit')->nullable();
			$table->integer('merchant_limit')->nullable();
			$table->decimal('price',13,2);
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
		Schema::drop('accountplans');
	}

}