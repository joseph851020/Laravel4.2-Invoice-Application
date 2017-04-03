<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLabelsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
			//
		Schema::create('labels', function(Blueprint $table){
			$table->engine ='InnoDB';
			$table->increments('id');
			$table->string('tenantID');
			$table->string('label', 255)->nullable();
			$table->string('value', 102550)->nullable();			 
			$table->integer('active')->default(0);	 
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
		Schema::drop('labels');
	}

}
