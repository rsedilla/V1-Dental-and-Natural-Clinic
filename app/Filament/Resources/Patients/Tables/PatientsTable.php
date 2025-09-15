<?php

namespace App\Filament\Resources\Patients\Tables;

use App\Filament\Resources\Patients\Schemas\PatientInfolist;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PatientsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('first_name')
                    ->label('First Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('last_name')
                    ->label('Last Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label('Email Address')
                    ->searchable()
                    ->copyable(),

                TextColumn::make('birthday')
                    ->label('Date of Birth')
                    ->date('M d, Y')
                    ->sortable(),

                TextColumn::make('gender')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'male' => 'info',
                        'female' => 'success',
                    }),

                TextColumn::make('age')
                    ->getStateUsing(fn ($record) => $record->birthday ? $record->birthday->age . ' years' : 'N/A')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Registration Date')
                    ->dateTime('M d, Y')
                    ->sortable(),
            ])
            ->recordActions([
                self::makeViewPatientAction(),
                EditAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function makeViewPatientAction(): Action
    {
        return Action::make('viewPatient')
            ->label('View Patient')
            ->icon('heroicon-o-eye')
            ->color('gray')
            ->modalHeading(fn ($record) => trim("{$record->first_name} {$record->last_name}"))
            ->modalWidth('2xl')
            ->modalCancelAction(false)
            ->modalSubmitAction(false)
            ->modalCloseButton(true)
            ->infolist(function ($record): array {
                return [
                    // Basic Info - Compact Layout
                    TextEntry::make('patient_name')
                        ->label('Name')
                        ->getStateUsing(fn ($record) => trim("{$record->first_name} {$record->middle_name} {$record->last_name}"))
                        ->weight('bold')
                        ->icon('heroicon-s-user')
                        ->iconColor('primary'),

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

                    TextEntry::make('age_gender')
                        ->label('Age & Gender')
                        ->getStateUsing(fn ($record) => 
                            ($record->birthday ? $record->birthday->age . ' years' : 'Unknown age') . 
                            ' • ' . 
                            ucfirst($record->gender ?? 'Not specified')
                        )
                        ->icon('heroicon-s-identification')
                        ->iconColor('info'),

                    TextEntry::make('birthday')
                        ->label('Birthday')
                        ->getStateUsing(fn ($record) => $record->birthday ? $record->birthday->format('M j, Y') : 'Not provided')
                        ->icon('heroicon-s-cake')
                        ->iconColor('primary'),

                    TextEntry::make('present_address')
                        ->label('Address')
                        ->getStateUsing(fn ($record) => $record->present_address ?: 'Not provided')
                        ->icon('heroicon-s-map-pin')
                        ->iconColor('gray'),

                    TextEntry::make('emergency_contact')
                        ->label('Emergency Contact')
                        ->getStateUsing(fn ($record) => 
                            ($record->emergency_contact_name ?: 'Not provided') . 
                            ($record->emergency_contact_phone ? ' • ' . $record->emergency_contact_phone : '')
                        )
                        ->copyable()
                        ->icon('heroicon-s-exclamation-triangle')
                        ->iconColor('danger'),

                    TextEntry::make('registration_info')
                        ->label('Registered')
                        ->getStateUsing(fn ($record) => $record->created_at->format('M j, Y'))
                        ->icon('heroicon-s-calendar-days')
                        ->iconColor('info'),
                ];
            });
    }
}
