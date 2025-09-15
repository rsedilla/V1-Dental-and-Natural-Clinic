<?php

namespace App\Filament\Resources\Patients\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PatientInfolist
{
    public static function configure(Schema $infolist): Schema
    {
        return $infolist
            ->schema([
                Section::make('Personal Information')
                    ->icon('heroicon-o-user')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('first_name')
                                    ->label('First Name'),
                                
                                TextEntry::make('middle_name')
                                    ->label('Middle Name')
                                    ->placeholder('Not provided'),
                                
                                TextEntry::make('last_name')
                                    ->label('Last Name'),
                            ]),
                            
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('email')
                                    ->label('Email Address')
                                    ->icon('heroicon-o-envelope')
                                    ->copyable(),
                                
                                TextEntry::make('phone_number')
                                    ->label('Phone Number')
                                    ->icon('heroicon-o-phone')
                                    ->copyable(),
                            ]),
                            
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('birthday')
                                    ->label('Date of Birth')
                                    ->icon('heroicon-o-calendar')
                                    ->date('F j, Y'),
                                
                                TextEntry::make('gender')
                                    ->label('Gender')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'male' => 'blue',
                                        'female' => 'pink',
                                        default => 'gray',
                                    }),
                                
                                TextEntry::make('age')
                                    ->label('Current Age')
                                    ->icon('heroicon-o-clock')
                                    ->getStateUsing(fn ($record) => $record->birthday ? $record->birthday->age . ' years old' : 'Unknown'),
                            ]),
                    ]),
                    
                Section::make('Contact Information')
                    ->icon('heroicon-o-map-pin')
                    ->schema([
                        TextEntry::make('present_address')
                            ->label('Present Address')
                            ->icon('heroicon-o-home')
                            ->columnSpanFull(),
                            
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('emergency_contact_name')
                                    ->label('Emergency Contact Name')
                                    ->icon('heroicon-o-user-plus')
                                    ->placeholder('Not provided'),
                                
                                TextEntry::make('emergency_contact_phone')
                                    ->label('Emergency Contact Phone')
                                    ->icon('heroicon-o-phone')
                                    ->copyable()
                                    ->placeholder('Not provided'),
                            ]),
                    ]),
                    
                Section::make('Medical Information')
                    ->icon('heroicon-o-heart')
                    ->schema([
                        TextEntry::make('medical_history')
                            ->label('Medical History')
                            ->placeholder('No medical history recorded')
                            ->columnSpanFull(),
                    ]),
                    
                Section::make('Registration Details')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('created_at')
                                    ->label('Registration Date')
                                    ->icon('heroicon-o-calendar-days')
                                    ->dateTime('F j, Y \a\t g:i A'),
                                
                                TextEntry::make('updated_at')
                                    ->label('Last Updated')
                                    ->icon('heroicon-o-pencil-square')
                                    ->dateTime('F j, Y \a\t g:i A'),
                            ]),
                    ])
                    ->collapsible(),
            ]);
    }
}