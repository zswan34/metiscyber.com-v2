<?php

use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{

    protected $count = 20;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        for ($i = 0; $i < $this->count; $i++) {
            $user = new \App\User();
            $user->name = $faker->name;
            $user->email = $faker->safeEmail;
            $user->password = bcrypt('secret');
            $user->phone = $faker->phoneNumber;
            $user->client = true;
            $user->save();
        }
    }
}
