<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MetisCyberSetup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'metis:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Metis Seeder';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
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

        $options = $this->choice("What would you like to do?", ['Setup']);

        switch($options) {
            case 'Setup':
                $this->setup();
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

    protected function setup() {
        $this->call('migrate:refresh');
        $this->call('db:seed');
    }
}
