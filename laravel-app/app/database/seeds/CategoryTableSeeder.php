<?php
use Faker\Factory as Faker;

class CategoryTableSeeder extends Seeder {

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
      DB::table('tbl_category')->insert([
        'name' => $faker->word,
        'description' => $faker->word,
        'disable'=> $faker->boolean(),
        'created_at' => $faker->datetime,
        'updated_at' => $faker->datetime,
      ]);
    }
  }

}
