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
        // Clear existing treatment types safely
        TreatmentType::query()->delete();
        
        $treatmentTypes = [
            ['name' => 'Oral Prophylaxis (Cleaning)', 'description' => null],
            ['name' => 'Tooth Extraction', 'description' => null],
            ['name' => 'Dental Filling (Restoration)', 'description' => null],
            ['name' => 'Root Canal Treatment', 'description' => null],
            ['name' => 'Dental Crown', 'description' => null],
            ['name' => 'Dental Bridge', 'description' => null],
            ['name' => 'Denture (Full/Partial)', 'description' => null],
            ['name' => 'Orthodontic Braces', 'description' => null],
            ['name' => 'Scaling and Polishing', 'description' => null],
            ['name' => 'Tooth Whitening (Bleaching)', 'description' => null],
            ['name' => 'Sealant Application', 'description' => null],
            ['name' => 'Fluoride Treatment', 'description' => null],
            ['name' => 'X-ray/Imaging', 'description' => null],
            ['name' => 'Consultation', 'description' => null],
            ['name' => 'Periodontal Treatment', 'description' => null],
            ['name' => 'Oral Surgery', 'description' => null],
            ['name' => 'Veneers', 'description' => null],
            ['name' => 'Inlay/Onlay', 'description' => null],
            ['name' => 'Implant Placement', 'description' => null],
            ['name' => 'Apicoectomy', 'description' => null],
        ];

        foreach ($treatmentTypes as $type) {
            TreatmentType::create($type);
        }
    }
}
