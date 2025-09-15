<?php

namespace App\Filament\Resources\Dentists\Tables;

use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DentistsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('full_name')
                    ->label('Full Name')
                    ->getStateUsing(fn ($record) => 'Dr. ' . $record->first_name . ' ' . ($record->middle_name ? $record->middle_name . ' ' : '') . $record->last_name)
                    ->searchable(['first_name', 'middle_name', 'last_name'])
                    ->sortable(),
                    
                TextColumn::make('license_number')
                    ->label('License Number')
                    ->searchable()
                    ->copyable(),
                    
                TextColumn::make('specialization')
                    ->badge()
                    ->color('info')
                    ->placeholder('General Dentist')
                    ->searchable(),
                    
                TextColumn::make('email')
                    ->label('Email Address')
                    ->searchable()
                    ->copyable(),
                    
                TextColumn::make('phone_number')
                    ->label('Phone Number')
                    ->searchable()
                    ->copyable(),
                    
                TextColumn::make('gender')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'male' => 'info',
                        'female' => 'success',
                        'other' => 'warning',
                    }),
                    
                TextColumn::make('created_at')
                    ->label('Registered')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                self::makeViewDentistAction(),
                EditAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function makeViewDentistAction(): Action
    {
        return Action::make('viewDentist')
            ->label('View Dentist')
            ->icon('heroicon-o-eye')
            ->color('gray')
            ->modalHeading(fn ($record) => "Dr. " . trim("{$record->first_name} {$record->last_name}"))
            ->modalWidth('2xl')
            ->modalCancelAction(false)
            ->modalSubmitAction(false)
            ->modalCloseButton(true)
            ->infolist(function ($record): array {
                return [
                    // Basic Info - Compact Layout
                    TextEntry::make('dentist_name')
                        ->label('Name')
                        ->getStateUsing(fn ($record) => "Dr. " . trim("{$record->first_name} {$record->middle_name} {$record->last_name}"))
                        ->weight('bold')
                        ->icon('heroicon-s-user')
                        ->iconColor('primary'),

                    TextEntry::make('license_number')
                        ->label('License Number')
                        ->getStateUsing(fn ($record) => $record->license_number ?: 'Not provided')
                        ->copyable()
                        ->icon('heroicon-s-identification')
                        ->iconColor('success'),

                    TextEntry::make('specialization')
                        ->label('Specialization')
                        ->getStateUsing(fn ($record) => $record->specialization ?: 'General Dentist')
                        ->badge()
                        ->icon('heroicon-s-academic-cap')
                        ->iconColor('info'),

                    TextEntry::make('email')
                        ->label('Email')
                        ->getStateUsing(fn ($record) => $record->email ?: 'Not provided')
                        ->copyable()
                        ->icon('heroicon-s-envelope')
                        ->iconColor('warning'),

                    TextEntry::make('phone_number')
                        ->label('Phone')
                        ->getStateUsing(fn ($record) => $record->phone_number ?: 'Not provided')
                        ->copyable()
                        ->icon('heroicon-s-phone')
                        ->iconColor('success'),

                    TextEntry::make('gender')
                        ->label('Gender')
                        ->getStateUsing(fn ($record) => ucfirst($record->gender ?? 'Not specified'))
                        ->badge()
                        ->icon('heroicon-s-identification')
                        ->iconColor(fn ($record): string => match ($record->gender ?? '') {
                            'male' => 'info',
                            'female' => 'success',
                            default => 'gray',
                        }),

                    TextEntry::make('address')
                        ->label('Address')
                        ->getStateUsing(fn ($record) => $record->address ?: 'Not provided')
                        ->icon('heroicon-s-map-pin')
                        ->iconColor('gray'),

                    TextEntry::make('registration_info')
                        ->label('Registered')
                        ->getStateUsing(fn ($record) => $record->created_at->format('M j, Y'))
                        ->icon('heroicon-s-calendar-days')
                        ->iconColor('info'),
                ];
            });
    }
}
