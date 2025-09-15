<?php

namespace Database\Seeders;

use App\Models\PaymentStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            ['name' => 'paid'],
            ['name' => 'pending'],
            ['name' => 'refunded'],
            ['name' => 'partial'],
            ['name' => 'cancelled'],
        ];

        foreach ($statuses as $status) {
            PaymentStatus::create($status);
        }
    }
}
