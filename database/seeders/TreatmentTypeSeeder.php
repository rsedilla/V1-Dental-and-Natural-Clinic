<?php

namespace Database\Seeders;

use App\Models\TreatmentType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TreatmentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $treatmentTypes = [
            ['name' => 'Filling', 'description' => 'Cavity filling with composite or amalgam'],
            ['name' => 'Tooth Extraction', 'description' => 'Simple or surgical tooth removal'],
            ['name' => 'Dental Cleaning', 'description' => 'Professional teeth cleaning and polishing'],
            ['name' => 'Root Canal Treatment', 'description' => 'Endodontic therapy for infected roots'],
            ['name' => 'Crown Placement', 'description' => 'Dental crown installation'],
            ['name' => 'Bridge Placement', 'description' => 'Dental bridge installation'],
            ['name' => 'Denture Fitting', 'description' => 'Complete or partial denture fitting'],
            ['name' => 'Orthodontic Adjustment', 'description' => 'Braces tightening and adjustment'],
            ['name' => 'X-ray/Imaging', 'description' => 'Dental radiography and diagnostic imaging'],
            ['name' => 'Periodontal Treatment', 'description' => 'Deep cleaning and gum treatment'],
            ['name' => 'Cosmetic Procedure', 'description' => 'Whitening, veneers, and aesthetic treatments'],
            ['name' => 'Oral Surgery', 'description' => 'Surgical procedures in the oral cavity'],
            ['name' => 'Pediatric Dentistry', 'description' => 'Specialized treatments for children'],
        ];

        foreach ($treatmentTypes as $type) {
            TreatmentType::create($type);
        }
    }
}
