<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddClients extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		 /*
		DB::table('clients')->insert(array(
			'tenantID' => 'b1000000000658',
			'company' => 'Pathway Housing Ltd',
			'country' => 'United Kingdom',
			'firstname' => 'Kelly',
			'lastname' => 'Bayowa',
			'email' => 'nebestpal@yahoo.com',
			'created_at' => date('Y-m-d H:m:s'),
			'updated_at' => date('Y-m-d H:m:s')
		));
		*/	
   }

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
		// DB::table('clients')->where('firstname', '=', 'Kelly')->delete();
	}

	
}