<?php

namespace App\Filament\Resources\Patients\Tables;

use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
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
                ViewAction::make(),
                EditAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
