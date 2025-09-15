<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get roles
        $adminRole = Role::where('name', 'Admin')->first();
        $dentistRole = Role::where('name', 'Dentist')->first();
        $receptionistRole = Role::where('name', 'Receptionist')->first();

        // Create admin user
        User::updateOrCreate(
            ['email' => 'admin@clinic.com'],
            [
                'name' => 'Admin User',
                'email' => 'admin@clinic.com',
                'password' => Hash::make('admin123'),
                'role_id' => $adminRole?->id,
                'email_verified_at' => now(),
            ]
        );

        // Create dentist user
        User::updateOrCreate(
            ['email' => 'dentist@clinic.com'],
            [
                'name' => 'Dr. Smith',
                'email' => 'dentist@clinic.com',
                'password' => Hash::make('dentist123'),
                'role_id' => $dentistRole?->id,
                'email_verified_at' => now(),
            ]
        );

        // Create receptionist user
        User::updateOrCreate(
            ['email' => 'receptionist@clinic.com'],
            [
                'name' => 'Receptionist Mary',
                'email' => 'receptionist@clinic.com',
                'password' => Hash::make('receptionist123'),
                'role_id' => $receptionistRole?->id,
                'email_verified_at' => now(),
            ]
        );
    }
}