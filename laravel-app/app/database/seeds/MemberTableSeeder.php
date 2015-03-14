<?php

use Faker\Factory as Faker;

class MemberTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$faker = Faker::create();

		foreach(range(1, 30) as $index)
		{
			Member::create([
				'username' => $faker->userName,
				'email' => $faker->email,
				'password'=> $faker->word,
				'first_name' => $faker->firstName,
				'last_name' => $faker->lastName,
				'location' => $faker->word,
				'phone' => $faker->phoneNumber,
				'address' => $faker->address,
				'photo' => $faker->word,
				'status' => $faker->boolean(),
				'status' => $faker->boolean(),
			]);
		}
	}

}
