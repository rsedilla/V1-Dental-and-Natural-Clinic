<?php

namespace Database\Seeders;

use App\Models\AppointmentStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AppointmentStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            ['name' => 'scheduled'],
            ['name' => 'confirmed'],
            ['name' => 'cancelled'],
            ['name' => 'completed'],
            ['name' => 'rescheduled'],
            ['name' => 'no-show'],
        ];

        foreach ($statuses as $status) {
            AppointmentStatus::create($status);
        }
    }
}
