<?php

use Illuminate\Database\Seeder;

class LeadStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = ['New', 'Open', 'In Progress', 'Open Deal', 'Unqualified',
        'Attempted to Contact', 'Connected', 'Bad Timing'];

        foreach ($statuses as $status) {
            $value = str_replace(' ', '-', strtolower($status));
            \App\LeadStatus::create(['name' => $status, 'value' => $value]);
        }
    }
}
