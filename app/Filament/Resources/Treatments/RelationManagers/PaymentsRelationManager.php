<?php

namespace App\Filament\Resources\Treatments\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->prefix('₱')
                    ->helperText(function ($get, $livewire) {
                        $treatment = $livewire->ownerRecord;
                        if ($treatment) {
                            $totalPaid = $treatment->total_paid;
                            $remainingBalance = $treatment->remaining_balance;
                            return "Treatment Cost: ₱" . number_format($treatment->cost, 2) . 
                                   " | Total Paid: ₱" . number_format($totalPaid, 2) . 
                                   " | Remaining Balance: ₱" . number_format($remainingBalance, 2);
                        }
                        return null;
                    }),
                DatePicker::make('payment_date')
                    ->required()
                    ->default(now()),
                Select::make('payment_method')
                    ->required()
                    ->options([
                        'cash' => 'Cash',
                        'credit_card' => 'Credit Card',
                        'debit_card' => 'Debit Card',
                        'bank_transfer' => 'Bank Transfer',
                        'installment' => 'Installment',
                        'hmo_card' => 'HMO Card',
                    ])
                    ->default('cash'),
                Select::make('status_id')
                    ->relationship('status', 'name')
                    ->required(),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('amount')
                    ->money('PHP'),
                TextEntry::make('payment_date')
                    ->date(),
                TextEntry::make('payment_method')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'cash' => 'success',
                        'credit_card', 'debit_card' => 'info',
                        'bank_transfer' => 'warning',
                        'installment' => 'gray',
                        'hmo_card' => 'danger',
                        default => 'gray',
                    }),
                TextEntry::make('status.name')
                    ->label('Status')
                    ->badge(),
                TextEntry::make('notes')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('payment_id')
            ->columns([
                TextColumn::make('amount')
                    ->money('PHP')
                    ->sortable(),
                TextColumn::make('payment_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('payment_method')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'cash' => 'success',
                        'credit_card', 'debit_card' => 'info',
                        'bank_transfer' => 'warning',
                        'installment' => 'gray',
                        'hmo_card' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('status.name')
                    ->badge()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Add Payment'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
