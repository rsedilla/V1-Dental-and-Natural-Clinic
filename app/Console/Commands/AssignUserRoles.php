<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;

class AssignUserRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:assign-roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign roles to users without roles';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $receptionistRole = Role::where('name', 'Receptionist')->first();
        
        // Assign Receptionist role to Assistant Mary
        $assistant = User::where('email', 'assistant@clinic.com')->first();
        if ($assistant && !$assistant->role_id) {
            $assistant->role_id = $receptionistRole->id;
            $assistant->save();
            $this->info("Assigned Receptionist role to Assistant Mary");
        }
        
        // Assign Admin role to rsedilla
        $adminRole = Role::where('name', 'Admin')->first();
        $rsedilla = User::where('email', 'rsedilla@gmail.com')->first();
        if ($rsedilla && !$rsedilla->role_id) {
            $rsedilla->role_id = $adminRole->id;
            $rsedilla->save();
            $this->info("Assigned Admin role to rsedilla");
        }
        
        $this->info("All users now have roles assigned!");
        return 0;
    }
}
