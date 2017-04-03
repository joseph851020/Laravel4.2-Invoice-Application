<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

use Carbon\Carbon;

class AddAccountplans extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Basic
		DB::table('accountplans')->insert(array(
			'account_type' => 'Starter',
			'invoice_limit' => '5',
			'quote_limit' => '5',
			'user_limit' => '1',
			'item_limit' => '999999',
			'recurring_limit' => '1',
			'description' => 'This is starter free plan',
			'created_at' => Carbon::now(),
			'updated_at' => Carbon::now(),
			'client_limit' => '2',
			'price' => 0.00,
			'expense_limit' => '10',
			'merchant_limit' => '10'
		));
		

		// Premium
		DB::table('accountplans')->insert(array(
			'account_type' => 'Premium',
			'invoice_limit' => '999999',
			'quote_limit' => '999999',
			'user_limit' => '1',
			'item_limit' => '999999',
			'recurring_limit' => '50',
			'description' => 'This is a premium plan',
			'created_at' => Carbon::now(),
			'updated_at' => Carbon::now(),
			'client_limit' => '100',
			'price' => 4.99,
			'expense_limit' => '999999',
			'merchant_limit' => '999999'
		));
		
		
		// Super Premium
		DB::table('accountplans')->insert(array(
			'account_type' => 'Super Premium',
			'invoice_limit' => '999999',
			'quote_limit' => '999999',
			'user_limit' => '3',
			'item_limit' => '999999',
			'recurring_limit' => '250',
			'description' => 'This is a super premium plan',
			'created_at' => Carbon::now(),
			'updated_at' => Carbon::now(),
			'client_limit' => '999999',
			'price' => 9.99,
			'expense_limit' => '999999',
			'merchant_limit' => '999999'
		));
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//

		DB::table('accountplans')->where('account_type', '=', 'Starter')->delete();
		DB::table('accountplans')->where('account_type', '=', 'Premium')->delete();
		DB::table('accountplans')->where('account_type', '=', 'SuperPremium')->delete();
	}

}