<?php

namespace App\Console\Commands;

use Adldap\AdldapInterface;
use App\User;
use Illuminate\Console\Command;

class MetisBuild extends Command
{

    protected $ldap;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'metis:build';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Script to build Metis Cyber';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(AdldapInterface $ldap)
    {
        $this->ldap = $ldap;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $options = $this->choice("What would you like to do?", ['Development', 'Production']);

        switch($options) {
            case 'Development':
                if ($this->validated()) {
                    $this->buildDev();
                }
                break;
            case 'Production':
                $this->buildProd();
                break;
            default:
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

    protected function buildDev()
    {
        $this->comment('Compiling node packages...');
        exec('npm run dev', $output, $return);
        if ($return === 0) {
            $this->info('Build successful');
        } else {
            $this->error('Error: unable to complete');
        }
    }

    protected function buildProd() {
        $this->comment('Compiling node packages...');
        exec('npm run prod', $output, $return);
        if ($return === 0) {
            $this->info('Build successful');
        } else {
            $this->error('Error: unable to complete');
        }
    }

    protected function validated()
    {
        $sid = $this->ask('Enter SID: ');
        if ($user = User::where('sid', $sid)->first()) {

            $password = $this->secret('Enter password: ');

            if ($user->ldap_user) {
                if ($this->ldapCheck($sid, $password)) {
                    $this->info('Authentication successful');
                    return true;
                } else {
                    $this->warn('Authentication failed');
                    return false;
                }
            }

            if ($user->password === bcrypt($password)) {
                $this->info('Authentication successful');
                return true;
            }

            $this->warn('Unable to authenticate');
            return false;

        }
        $this->warn('Unable to find user');
        return false;
    }

    protected function ldapCheck ($sid, $password)
    {
        $userDN = 'uid=' . $sid . ',' . config('ldap.connections.default.settings.base_dn');
        return ($this->ldap->auth()->attempt($userDN, $password, $bindAsUser = true));
    }

}
