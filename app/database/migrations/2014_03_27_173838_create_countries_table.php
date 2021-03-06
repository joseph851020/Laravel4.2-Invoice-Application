<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountriesTable extends Migration {
	
	public function up()
	{
		Schema::create('countries', function(Blueprint $table){
			$table->engine ='InnoDB';
			$table->increments('id');
			$table->string('name');
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
		Schema::drop('countries');
	}

}
