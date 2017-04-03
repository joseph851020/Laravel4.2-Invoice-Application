<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('items', function(Blueprint $table){
			$table->engine ='InnoDB';
			$table->increments('id');
			$table->string('tenantID');
			$table->string('item_name');
			$table->text('description')->nullable();	
			$table->string('item_type')->nullable();
			$table->float('unit_price');
			$table->integer('tax_type')->nullable();
			$table->integer('user_id')->nullable();
			$table->string('category')->nullable();			
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
		Schema::drop('items');
	}

}