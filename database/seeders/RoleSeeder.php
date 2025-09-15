<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Admin',
                'description' => 'Full system access and management capabilities'
            ],
            [
                'name' => 'Dentist',
                'description' => 'View assigned patients, appointments, and add treatment notes'
            ],
            [
                'name' => 'Receptionist',
                'description' => 'Manage patient records, appointments, and payments'
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
