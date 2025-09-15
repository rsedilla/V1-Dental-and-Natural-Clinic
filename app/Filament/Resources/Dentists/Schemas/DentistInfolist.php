<?php

namespace App\Filament\Resources\Dentists\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DentistInfolist
{
    public static function configure(Schema $infolist): Schema
    {
        return $infolist
            ->schema([
                // Dentist Header Information
                TextEntry::make('dentist_name')
                    ->label('Dentist Name')
                    ->getStateUsing(fn ($record) => "Dr. " . trim("{$record->first_name} {$record->middle_name} {$record->last_name}"))
                    ->weight('bold')
                    ->size('lg')
                    ->icon('heroicon-o-user')
                    ->color('primary'),

                TextEntry::make('license_number')
                    ->label('License Number')
                    ->getStateUsing(fn ($record) => $record->license_number ?: 'Not provided')
                    ->icon('heroicon-o-identification')
                    ->badge()
                    ->color('success')
                    ->copyable()
                    ->copyMessage('License number copied!')
                    ->copyMessageDuration(1500),

                TextEntry::make('specialization')
                    ->label('Specialization')
                    ->getStateUsing(fn ($record) => $record->specialization ?: 'General Dentist')
                    ->badge()
                    ->color('info')
                    ->icon('heroicon-o-academic-cap'),

                TextEntry::make('gender')
                    ->label('Gender')
                    ->getStateUsing(fn ($record) => ucfirst($record->gender ?? 'Not specified'))
                    ->badge()
                    ->color(fn ($record): string => match (strtolower($record->gender ?? '')) {
                        'male' => 'blue',
                        'female' => 'pink',
                        default => 'gray',
                    }),

                // Contact Information Section
                Section::make('Contact Information')
                    ->icon('heroicon-o-phone')
                    ->schema([
                        TextEntry::make('email')
                            ->label('Email Address')
                            ->getStateUsing(fn ($record) => $record->email ?: 'No email provided')
                            ->icon('heroicon-o-envelope')
                            ->color('primary')
                            ->copyable()
                            ->copyMessage('Email copied!')
                            ->copyMessageDuration(1500),

                        TextEntry::make('phone_number')
                            ->label('Phone Number')
                            ->getStateUsing(fn ($record) => $record->phone_number ?: 'No phone provided')
                            ->icon('heroicon-o-device-phone-mobile')
                            ->color('success')
                            ->copyable()
                            ->copyMessage('Phone copied!')
                            ->copyMessageDuration(1500),
                    ]),

                // Address Information Section
                Section::make('Address Information')
                    ->icon('heroicon-o-map-pin')
                    ->schema([
                        TextEntry::make('address')
                            ->label('Address')
                            ->getStateUsing(fn ($record) => $record->address ?: 'No address provided')
                            ->icon('heroicon-o-home')
                            ->columnSpanFull(),
                    ]),

                // Registration Statistics Section
                Section::make('Registration Details')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        TextEntry::make('registration_date')
                            ->label('Registration Date')
                            ->getStateUsing(fn ($record) => $record->created_at->format('F j, Y \a\t g:i A'))
                            ->icon('heroicon-o-calendar-days')
                            ->color('success'),

                        TextEntry::make('last_updated')
                            ->label('Last Updated')
                            ->getStateUsing(fn ($record) => $record->updated_at->format('F j, Y \a\t g:i A'))
                            ->icon('heroicon-o-pencil-square')
                            ->color('warning'),

                        TextEntry::make('days_registered')
                            ->label('Days Since Registration')
                            ->getStateUsing(fn ($record) => $record->created_at->diffInDays(now()) . ' days')
                            ->badge()
                            ->color('info'),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }
}