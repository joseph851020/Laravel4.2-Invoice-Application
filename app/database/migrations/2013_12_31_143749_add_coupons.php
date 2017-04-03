<?php

use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class AddCoupons extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::table('coupons')->insert(array(
			'code' => '50OFF',
			'value' => 50,
			'plan_validity' => 0,
			'valid_from' => Carbon::now(),			  
			'valid_to' => Carbon::now()->addDays(10),
			'created_at' => Carbon::now(),
			'updated_at' => Carbon::now()
		));
		
		
		DB::table('coupons')->insert(array(
			'code' => 'XA90SIUUI5',
			'value' => 100,
			'plan_validity' => 1,
			'valid_from' => Carbon::now(),			  
			'valid_to' => Carbon::now()->addDays(2),
			'created_at' => Carbon::now(),
			'updated_at' => Carbon::now()
		));
		
		DB::table('coupons')->insert(array(
			'code' => 'CCD08khUYX',
			'value' => 60,
			'plan_validity' => 2,
			'valid_from' => Carbon::now(),			  
			'valid_to' => Carbon::now()->addDays(3),
			'created_at' => Carbon::now(),
			'updated_at' => Carbon::now()
		));
		
		DB::table('coupons')->insert(array(
			'code' => '66895',
			'value' => 40,
			'plan_validity' => 3,
			'valid_from' => Carbon::now(),			  
			'valid_to' => Carbon::now()->addDays(7),
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
		DB::table('coupons')->where('code', '=', '50OFF')->delete();
		DB::table('coupons')->where('code', '=', 'CCD08khUYX')->delete();
		DB::table('coupons')->where('code', '=', '50OFF')->delete();
		DB::table('coupons')->where('code', '=', '66895')->delete();
	}

}