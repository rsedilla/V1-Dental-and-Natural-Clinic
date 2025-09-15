<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class UpdateAdminPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:update-admin-password';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update admin password to "admin"';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = User::where('email', 'admin@clinic.com')->first();
        
        if (!$user) {
            $this->error('Admin user not found!');
            return 1;
        }
        
        $user->password = Hash::make('admin');
        $user->save();
        
        $this->info('Admin password updated successfully!');
        $this->info('Email: admin@clinic.com');
        $this->info('Password: admin');
        
        return 0;
    }
}
