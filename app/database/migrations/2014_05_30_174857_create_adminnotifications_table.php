<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdminNotificationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('adminnotifications', function(Blueprint $table){
			$table->engine ='InnoDB';
			$table->increments('id');
			$table->integer('active')->default(0);
			$table->string('title')->nullable();
			$table->integer('type')->nullable();
			$table->text('info')->nullable();
			$table->dateTime('display_start_date')->nullable();
			$table->dateTime('display_end_date')->nullable();	 
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
		Schema::drop('adminnotifications');
	}

}
