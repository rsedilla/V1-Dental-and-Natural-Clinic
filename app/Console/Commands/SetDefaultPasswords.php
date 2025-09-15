<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class SetDefaultPasswords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:set-default-passwords';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set default passwords for all users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = [
            'admin@clinic.com' => 'admin',
            'dentist@clinic.com' => 'dentist', 
            'assistant@clinic.com' => 'assistant',
            'receptionist@clinic.com' => 'receptionist',
            'rsedilla@gmail.com' => 'admin',
        ];

        foreach ($users as $email => $password) {
            $user = User::where('email', $email)->first();
            if ($user) {
                $user->password = Hash::make($password);
                $user->save();
                $this->info("Updated password for {$email} to '{$password}'");
            }
        }

        $this->info("All user passwords updated!");
        return 0;
    }
}