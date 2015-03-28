<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Member::truncate();

		Eloquent::unguard();

		 $this->call('CategoryTableSeeder');
	}

}
