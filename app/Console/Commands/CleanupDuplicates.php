<?php

namespace App\Console\Commands;

use App\Models\AppointmentType;
use App\Models\AppointmentStatus;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanupDuplicates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointments:cleanup-duplicates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up duplicate appointment types and statuses';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting cleanup of duplicate appointment types and statuses...');

        // Clean up duplicate appointment types
        $this->info('Cleaning up appointment types...');
        $appointmentTypes = AppointmentType::select('name', DB::raw('COUNT(*) as count'))
            ->groupBy('name')
            ->having('count', '>', 1)
            ->get();

        foreach ($appointmentTypes as $duplicateType) {
            $duplicates = AppointmentType::where('name', $duplicateType->name)->get();
            $keepFirst = $duplicates->first();
            
            // Update any appointments that reference the duplicates to use the first one
            foreach ($duplicates->skip(1) as $duplicate) {
                DB::table('appointments')
                    ->where('appointment_type_id', $duplicate->id)
                    ->update(['appointment_type_id' => $keepFirst->id]);
                
                $duplicate->delete();
                $this->line("Removed duplicate appointment type: {$duplicate->name} (ID: {$duplicate->id})");
            }
        }

        // Clean up duplicate appointment statuses
        $this->info('Cleaning up appointment statuses...');
        $appointmentStatuses = AppointmentStatus::select('name', DB::raw('COUNT(*) as count'))
            ->groupBy('name')
            ->having('count', '>', 1)
            ->get();

        foreach ($appointmentStatuses as $duplicateStatus) {
            $duplicates = AppointmentStatus::where('name', $duplicateStatus->name)->get();
            $keepFirst = $duplicates->first();
            
            // Update any appointments that reference the duplicates to use the first one
            foreach ($duplicates->skip(1) as $duplicate) {
                DB::table('appointments')
                    ->where('status_id', $duplicate->id)
                    ->update(['status_id' => $keepFirst->id]);
                
                $duplicate->delete();
                $this->line("Removed duplicate appointment status: {$duplicate->name} (ID: {$duplicate->id})");
            }
        }

        $this->info('Cleanup completed successfully!');
        
        // Show current counts
        $typeCount = AppointmentType::count();
        $statusCount = AppointmentStatus::count();
        
        $this->info("Current appointment types: {$typeCount}");
        $this->info("Current appointment statuses: {$statusCount}");
    }
}
