<?php

use Illuminate\Database\Seeder;

class LifeCycleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stages = [
            'Subscriber', 'Lead', 'Marketing Qualified Lead', 'Sales Qualified Lead',
            'Opportunity', 'Customer', 'Evangelist', 'Other'
        ];

        foreach ($stages as $stage) {
            $value = str_replace(' ', '-', strtolower($stage));
            \App\LifeCycle::create(['name' => $stage, 'value' => $value]);
        }
    }
}
