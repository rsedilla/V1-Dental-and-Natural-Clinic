<?php

namespace Database\Seeders;

use App\Models\AppointmentType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AppointmentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $appointmentTypes = [
            ['name' => 'Consultation', 'description' => 'Initial consultation and examination'],
            ['name' => 'Dental Cleaning', 'description' => 'Professional teeth cleaning and prophylaxis'],
            ['name' => 'Tooth Extraction', 'description' => 'Surgical or simple tooth removal'],
            ['name' => 'Filling/Restoration', 'description' => 'Cavity filling and tooth restoration'],
            ['name' => 'Root Canal Treatment', 'description' => 'Endodontic treatment of infected tooth'],
            ['name' => 'Crown/Bridge Placement', 'description' => 'Dental crown or bridge installation'],
            ['name' => 'Orthodontic Checkup', 'description' => 'Braces adjustment and monitoring'],
            ['name' => 'Denture Fitting', 'description' => 'Denture fitting and adjustment'],
            ['name' => 'X-ray/Imaging', 'description' => 'Dental radiography and imaging'],
            ['name' => 'Follow-up', 'description' => 'Post-treatment follow-up appointment'],
            ['name' => 'Emergency Visit', 'description' => 'Urgent dental care'],
            ['name' => 'Periodontal Treatment', 'description' => 'Gum disease treatment'],
            ['name' => 'Cosmetic Procedure', 'description' => 'Teeth whitening and cosmetic treatments'],
            ['name' => 'Pediatric Dentistry', 'description' => 'Specialized care for children'],
            ['name' => 'Oral Surgery', 'description' => 'Surgical dental procedures'],
        ];

        foreach ($appointmentTypes as $type) {
            AppointmentType::firstOrCreate(['name' => $type['name']], $type);
        }
    }
}
