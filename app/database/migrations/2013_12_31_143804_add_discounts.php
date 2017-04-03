<?php

use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;
class AddDiscounts extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::table('discounts')->insert(array(
			'month' => 1,
			'value' => 0,				  
			'created_at' => Carbon::now(),
			'updated_at' => Carbon::now()
		));
		
		DB::table('discounts')->insert(array(
			'month' => 3,
			'value' => 10,				  
			'created_at' => Carbon::now(),
			'updated_at' => Carbon::now()
		));
		
		DB::table('discounts')->insert(array(
			'month' => 6,
			'value' => 20,				  
			'created_at' => Carbon::now(),
			'updated_at' => Carbon::now()
		));
		
		DB::table('discounts')->insert(array(
			'month' => 12,
			'value' => 25,				  
			'created_at' => Carbon::now(),
			'updated_at' => Carbon::now()
		));
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		 DB::table('discounts')->where('month', '=', 1)->delete();
		 DB::table('discounts')->where('month', '=', 3)->delete();
		 DB::table('discounts')->where('month', '=', 6)->delete();
		 DB::table('discounts')->where('month', '=', 12)->delete();
	}

}