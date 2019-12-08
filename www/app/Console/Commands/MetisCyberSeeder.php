<?php

namespace App\Console\Commands;

use App\User;
use Faker\Factory;
use Illuminate\Console\Command;

class MetisCyberSeeder extends Command
{

    protected $faker;
    protected $count;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'metis:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup Metis Cyber Application';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->faker = Factory::create();

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->comment($this->asciiArt());

        $options = $this->choice("What would you like to seed?", ['Accounts', 'Customers']);

        switch($options) {
            case 'Customers':
                $this->seedCustomers();
                break;
        }
    }

    protected function asciiArt() {
        return "
            __  ___       __   _        _   __       __ 
           /  |/  /___   / /_ (_)_____ / | / /___   / /_
          / /|_/ // _ \ / __// // ___//  |/ // _ \ / __/
         / /  / //  __// /_ / /(__  )/ /|  //  __// /_  
        /_/  /_/ \___/ \__//_//____//_/ |_/ \___/ \__/  
        ";
    }

    protected function seedCustomers() {
        $this->count = $this->ask("How many customers would you like to seed?");
        $this->line('Seeding Customers');

        $bar = $this->getOutput()->createProgressBar($this->count);
        $bar->start();
        for ($i = 0; $i < $this->count; $i++) {
            $user = new User();
            $user->name = $this->faker->name;
            $user->email = $this->faker->safeEmail;
            $user->password = bcrypt('secret');
            $user->phone = $this->faker->phoneNumber;
            $user->customer = true;
            $user->save();
            $bar->advance();
        }

        $bar->finish();
        $this->comment("\n");
    }

}
