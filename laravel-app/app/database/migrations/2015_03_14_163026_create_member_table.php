<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMemberTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('member', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('username');
			$table->string('email');
			$table->string('password');
			$table->string('remember_token');
			$table->string('first_name');
			$table->string('last_name');
			$table->string('location');
			$table->string('phone');
			$table->string('address');
			$table->string('photo');
			$table->boolean('status');
			$table->integer('use_type');
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
		Schema::drop('member');
	}

}
