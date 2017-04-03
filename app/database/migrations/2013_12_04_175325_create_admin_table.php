<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdminTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('admin', function(Blueprint $table){
			$table->engine ='InnoDB';
			$table->increments('id');
			$table->string('username', 100)->nullable();
			$table->string('firstname', 100)->nullable();
			$table->string('lastname',100);			
			$table->string('email')->unique();
			$table->string('password');
			$table->integer('level')->default(1);
			$table->string('pin')->unique();
			$table->string('auth_code')->unique();
			$table->string('remember_token', 100)->nullable();		
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
		Schema::drop('admin');
	}

}