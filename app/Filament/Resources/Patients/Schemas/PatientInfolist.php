<?php

namespace App\Filament\Resources\Patients\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PatientInfolist
{
    public static function configure(Schema $infolist): Schema
    {
        return $infolist
            ->schema([
                // Patient Header Information
                TextEntry::make('patient_name')
                    ->label('Patient Name')
                    ->getStateUsing(fn ($record) => trim("{$record->first_name} {$record->middle_name} {$record->last_name}"))
                    ->weight('bold')
                    ->size('lg')
                    ->icon('heroicon-o-user')
                    ->color('primary'),

                TextEntry::make('patient_age')
                    ->label('Age')
                    ->getStateUsing(fn ($record) => $record->birthday ? $record->birthday->age . ' years old' : 'Unknown')
                    ->icon('heroicon-o-calendar')
                    ->badge()
                    ->color('info'),

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

                        TextEntry::make('birthday')
                            ->label('Date of Birth')
                            ->getStateUsing(fn ($record) => $record->birthday ? $record->birthday->format('F j, Y') : 'Not provided')
                            ->icon('heroicon-o-cake')
                            ->color('warning'),
                    ]),

                // Address Information Section
                Section::make('Address Information')
                    ->icon('heroicon-o-map-pin')
                    ->schema([
                        TextEntry::make('present_address')
                            ->label('Present Address')
                            ->getStateUsing(fn ($record) => $record->present_address ?: 'No address provided')
                            ->icon('heroicon-o-home')
                            ->columnSpanFull(),
                    ]),

                // Emergency Contact Section
                Section::make('Emergency Contact')
                    ->icon('heroicon-o-exclamation-triangle')
                    ->schema([
                        TextEntry::make('emergency_contact_name')
                            ->label('Contact Name')
                            ->getStateUsing(fn ($record) => $record->emergency_contact_name ?: 'No emergency contact')
                            ->icon('heroicon-o-user-plus')
                            ->color('danger'),

                        TextEntry::make('emergency_contact_phone')
                            ->label('Contact Phone')
                            ->getStateUsing(fn ($record) => $record->emergency_contact_phone ?: 'No phone provided')
                            ->icon('heroicon-o-phone')
                            ->color('danger')
                            ->copyable()
                            ->copyMessage('Emergency contact copied!')
                            ->copyMessageDuration(1500),
                    ]),

                // Medical Information Section
                Section::make('Medical Information')
                    ->icon('heroicon-o-heart')
                    ->schema([
                        TextEntry::make('medical_history')
                            ->label('Medical History')
                            ->getStateUsing(fn ($record) => $record->medical_history ?: 'No medical history recorded')
                            ->columnSpanFull()
                            ->placeholder('No medical history available'),
                    ])
                    ->collapsible(),

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
                            ->getStateUsing(fn ($record) => $record->created_at->diffInDays(now()) . ' days ago')
                            ->badge()
                            ->color('info'),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }
}