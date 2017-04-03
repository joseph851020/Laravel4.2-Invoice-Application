<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('users', function(Blueprint $table){
			$table->engine ='InnoDB';
			$table->increments('id');
			$table->string('tenantID');
			$table->string('username', 100)->nullable();
			$table->string('firstname', 100)->nullable();
			$table->string('lastname', 100)->nullable();
			$table->integer('level')->default(1);			
			$table->string('email')->unique();
			$table->string('phone', 50)->nullable();
			$table->string('website')->nullable();
			$table->integer('remember')->nullable();			
			$table->string('password');
			$table->string('role', 50)->nullable();
			$table->integer('loggedin')->nullable();
			$table->integer('theme_id')->default(1);
			$table->integer('firsttimer')->default(1);
			$table->string('remember_token', 100)->nullable();
            $table->boolean('notify')->default(1);
            $table->dateTime('last_logged_in')->nullable();
            $table->string('last_logged_in_ip', 50)->nullable();
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
		Schema::drop('users');
	}

}