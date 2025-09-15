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
            $todayAppointments = Appointment::whereDate('appointment_date', today())->count();
            $monthlyRevenue = Payment::whereMonth('created_at', now()->month)
                ->whereNotNull('amount')
                ->sum('amount') ?? 0;
            $activeDentists = Dentist::count();
            $treatmentsToday = Treatment::whereDate('created_at', today())->count();
            $pendingPayments = Payment::where('status', 'pending')->count();
            
            return [
                Stat::make('Total Patients', $totalPatients)
                    ->description('Registered patients')
                    ->descriptionIcon('heroicon-m-user-group')
                    ->color('success'),
                
                Stat::make('Today\'s Appointments', $todayAppointments)
                    ->description('Scheduled for today')
                    ->descriptionIcon('heroicon-m-calendar-days')
                    ->color('info'),
                
                Stat::make('Monthly Revenue', 'PHP ' . number_format($monthlyRevenue, 2))
                    ->description('This month\'s earnings')
                    ->descriptionIcon('heroicon-m-currency-dollar')
                    ->color('success'),
                
                Stat::make('Active Dentists', $activeDentists)
                    ->description('Working dentists')
                    ->descriptionIcon('heroicon-m-user-plus')
                    ->color('warning'),
                
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
                Stat::make('Total Patients', '0')
                    ->description('Registered patients')
                    ->descriptionIcon('heroicon-m-user-group')
                    ->color('success'),
                
                Stat::make('Today\'s Appointments', '0')
                    ->description('Scheduled for today')
                    ->descriptionIcon('heroicon-m-calendar-days')
                    ->color('info'),
                
                Stat::make('Monthly Revenue', 'PHP 0.00')
                    ->description('This month\'s earnings')
                    ->descriptionIcon('heroicon-m-currency-dollar')
                    ->color('success'),
                
                Stat::make('Active Dentists', '0')
                    ->description('Working dentists')
                    ->descriptionIcon('heroicon-m-user-plus')
                    ->color('warning'),
            ];
        }
    }
}