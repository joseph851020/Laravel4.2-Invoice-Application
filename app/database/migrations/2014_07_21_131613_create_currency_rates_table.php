<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCurrencyRatesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('currency_rates', function(Blueprint $table)
		{
	 
			$table->engine ='InnoDB';
			$table->increments('id');
			$table->string('currency_code')->nullable();
			$table->string('country_currency')->nullable();			
			$table->decimal('unit_exchange_rate',13,5);
			$table->string('tenantID');
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
		Schema::drop('currency_rates');
	}

}
