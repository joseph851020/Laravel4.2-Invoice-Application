<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTenantsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('tenants', function(Blueprint $table){
			$table->engine ='InnoDB';
			$table->increments('id');		 
			$table->string('tenantID');
			$table->index('tenantID');
			$table->string('access_url')->nullable();
			$table->string('admin_email')->nullable(); // Just Data migration
			$table->integer('level');
			$table->integer('status')->nullable();
			$table->integer('account_plan_id')->default(1);
			$table->integer('verified')->default(0);
			$table->string('activation_key')->nullable();
			$table->string('affiliate_code')->nullable();
			$table->string('referral_code')->nullable(); // Account Code to refer others
			$table->string('referrer')->nullable(); // Code others used to refer this tenant
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
		Schema::drop('tenants');
	}

}