<?php

use Illuminate\Database\Seeder;

class UserTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userTypes = [
            'affiliate',
            'contractor',
            'temporary',
            'seasonal',
            'freelancer',
            'consultant',
        ];

        foreach($userTypes as $type) {
            \App\UserType::create(['name' => $type]);
        }
    }
}
