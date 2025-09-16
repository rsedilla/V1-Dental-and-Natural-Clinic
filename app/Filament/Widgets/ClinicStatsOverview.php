<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Treatment;
use App\Models\Payment;
use App\Models\Dentist;

class ClinicStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 'full';
    
    // Disable caching to ensure real-time data
    public function getCacheKey(): string
    {
        return 'clinic-stats-' . now()->timestamp;
    }
    
    protected ?string $pollingInterval = '10s';
    
    protected function getStats(): array
    {
        try {
            $totalPatients = Patient::count();
            $todayAppointments = Appointment::whereDate('date', today())->count();
            $monthlyRevenue = Payment::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->whereNotNull('amount')
                ->sum('amount') ?? 0;
            
            // Fix upcoming appointments - should include today and future dates
            $upcomingAppointments = Appointment::whereDate('date', '>=', today()->toDateString())->count();
            
            $totalAppointmentsThisMonth = Appointment::whereMonth('date', now()->month)
                ->whereYear('date', now()->year)
                ->count();
            
            // Get patients for today with their names - use the full_name accessor
            $todayPatients = Appointment::whereDate('date', today())
                ->with('patient')
                ->get()
                ->map(function($appointment) {
                    return $appointment->patient ? $appointment->patient->full_name : null;
                })
                ->filter()
                ->unique()
                ->values();
            $todayPatientsCount = $todayPatients->count();
            $todayPatientsNames = $todayPatientsCount > 0 ? $todayPatients->implode(', ') : 'No patients today';
            
            // Fix treatments today - should use treatment_date instead of created_at
            $treatmentsToday = Treatment::whereDate('treatment_date', today())->count();
            
            // Fix pending payments - check if status column exists or use a different approach
            $pendingPayments = Payment::where(function($query) {
                $query->where('status', 'pending')
                      ->orWhere('status', 'unpaid')
                      ->orWhereNull('status');
            })->count();
            
            return [
                
                Stat::make('Today\'s Appointments', $todayAppointments)
                    ->description('Scheduled for today')
                    ->descriptionIcon('heroicon-m-calendar-days')
                    ->color('info'),

                Stat::make('Upcoming Appointments', $upcomingAppointments)
                    ->description('Including today and future')
                    ->descriptionIcon('heroicon-m-calendar-days')
                    ->color('info'),

                Stat::make('Total Appointments (This Month)', $totalAppointmentsThisMonth)
                    ->description('All scheduled this month')
                    ->descriptionIcon('heroicon-m-calendar-days')
                    ->color('success'),

                Stat::make('Patients for Today', $todayPatientsCount)
                    ->description($todayPatientsNames)
                    ->descriptionIcon('heroicon-m-user-group')
                    ->color('info'),
                
                Stat::make('Total Patients', $totalPatients)
                    ->description('Registered patients')
                    ->descriptionIcon('heroicon-m-user-group')
                    ->color('success'),
                
                Stat::make('Monthly Revenue', 'PHP ' . number_format($monthlyRevenue, 2))
                    ->description('This month\'s earnings')
                    ->descriptionIcon('heroicon-m-currency-dollar')
                    ->color('success'),
                
                Stat::make('Treatments Today', $treatmentsToday)
                    ->description('Completed today')
                    ->descriptionIcon('heroicon-m-heart')
                    ->color('danger'),
                
                Stat::make('Pending Payments', $pendingPayments)
                    ->description('Awaiting payment')
                    ->descriptionIcon('heroicon-m-clock')
                    ->color('warning'),
            ];
        } catch (\Exception $e) {
            // Return default stats if there's any database error
            return [
                
                Stat::make('Today\'s Appointments', '0')
                    ->description('Scheduled for today')
                    ->descriptionIcon('heroicon-m-calendar-days')
                    ->color('info'),

                Stat::make('Upcoming Appointments', '0')
                    ->description('Including today and future')
                    ->descriptionIcon('heroicon-m-calendar-days')
                    ->color('info'),

                Stat::make('Total Appointments (This Month)', '0')
                    ->description('All scheduled this month')
                    ->descriptionIcon('heroicon-m-calendar-days')
                    ->color('success'),

                Stat::make('Patients for Today', '0')
                    ->description('No patients today')
                    ->descriptionIcon('heroicon-m-user-group')
                    ->color('info'),
                
                Stat::make('Total Patients', '0')
                    ->description('Registered patients')
                    ->descriptionIcon('heroicon-m-user-group')
                    ->color('success'),
                
                Stat::make('Monthly Revenue', 'PHP 0.00')
                    ->description('This month\'s earnings')
                    ->descriptionIcon('heroicon-m-currency-dollar')
                    ->color('success'),
                
                Stat::make('Treatments Today', '0')
                    ->description('Completed today')
                    ->descriptionIcon('heroicon-m-heart')
                    ->color('danger'),
                
                Stat::make('Pending Payments', '0')
                    ->description('Awaiting payment')
                    ->descriptionIcon('heroicon-m-clock')
                    ->color('warning'),
            ];
        }
    }

    public static function getDefaultColumnSpan(): int|string|array
    {
        return 12; // Full width for this widget
    }
}