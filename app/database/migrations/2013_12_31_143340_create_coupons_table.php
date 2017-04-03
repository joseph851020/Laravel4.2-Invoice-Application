<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCouponsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('coupons', function(Blueprint $table){
			$table->engine ='InnoDB';
			$table->increments('id');
			$table->string('code')->unique();
			$table->integer('value');
			$table->integer('plan_validity');
			$table->timestamp('valid_from');
			$table->timestamp('valid_to');
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
		Schema::drop('coupons');
	}

}