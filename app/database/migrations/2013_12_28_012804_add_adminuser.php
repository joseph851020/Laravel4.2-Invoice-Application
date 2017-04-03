<?php

use Illuminate\Database\Migrations\Migration;

class AddAdminuser extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::table('admin')->insert(array(
			'username' => 'nebestpal',			
			'email' => 'murray@murraynewlands.com',			
			'firstname' => 'Murray',
			'lastname' => 'Newlands',
			'level' =>  1,
			'pin' => sha1('1111'),
			'auth_code' => sha1('1111'),
			'password' => Hash::make('abc123'),
			'created_at' => date('Y-m-d H:m:s'),
			'updated_at' => date('Y-m-d H:m:s')
		));
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		 DB::table('admin')->where('username', '=', 'nebestpal')->delete();
	}

}
