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
    protected function getStats(): array
    {
        try {
            $totalPatients = Patient::count();
            $todayAppointments = Appointment::whereDate('date', today())->count();
            $monthlyRevenue = Payment::whereMonth('created_at', now()->month)
                ->whereNotNull('amount')
                ->sum('amount') ?? 0;
            $upcomingAppointments = Appointment::where('date', '>=', now()->toDateString())->count();
            $totalAppointmentsThisMonth = Appointment::whereMonth('date', now()->month)
                ->whereYear('date', now()->year)
                ->count();
            
            // Get patients for today with their names
            $todayPatients = Appointment::whereDate('date', today())
                ->with('patient')
                ->get()
                ->pluck('patient.name')
                ->filter()
                ->unique()
                ->values();
            $todayPatientsCount = $todayPatients->count();
            $todayPatientsNames = $todayPatientsCount > 0 ? $todayPatients->implode(', ') : 'No patients today';
            
            $treatmentsToday = Treatment::whereDate('created_at', today())->count();
            $pendingPayments = Payment::where('status', 'pending')->count();
            
            return [
                
                Stat::make('Today\'s Appointments', $todayAppointments)
                    ->description('Scheduled for today')
                    ->descriptionIcon('heroicon-m-calendar-days')
                    ->color('info'),

                Stat::make('Upcoming Appointments', $upcomingAppointments)
                    ->description('Scheduled appointments')
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
                    ->description('Scheduled appointments')
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