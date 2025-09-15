<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;

class RemoveDuplicateRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'role:remove-duplicates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove duplicate roles and update user references';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Removing duplicate roles...');
        
        // Get all roles grouped by name
        $roleGroups = Role::all()->groupBy('name');
        
        foreach ($roleGroups as $roleName => $roles) {
            if ($roles->count() > 1) {
                $this->info("Found {$roles->count()} '{$roleName}' roles");
                
                // Keep the first one, remove the rest
                $keepRole = $roles->first();
                $duplicateRoles = $roles->slice(1);
                
                foreach ($duplicateRoles as $duplicateRole) {
                    // Update users who have this duplicate role
                    User::where('role_id', $duplicateRole->id)
                        ->update(['role_id' => $keepRole->id]);
                    
                    $this->info("Updated users from role ID {$duplicateRole->id} to {$keepRole->id}");
                    
                    // Delete the duplicate role
                    $duplicateRole->delete();
                    $this->info("Deleted duplicate '{$roleName}' role with ID {$duplicateRole->id}");
                }
            }
        }
        
        $this->info('Duplicate roles removed successfully!');
        return 0;
    }
}
