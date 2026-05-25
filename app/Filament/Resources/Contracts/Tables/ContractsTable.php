<?php

namespace App\Filament\Resources\Contracts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ContractsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label(__('field_code'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('loanApplication.reference')
                    ->label(__('field_reference'))
                    ->searchable(),
                TextColumn::make('loanApplication.user.email')
                    ->label(__('Customer'))
                    ->searchable(),
                TextColumn::make('signed_at')
                    ->label(__('field_signed_at'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('signing_ip')
                    ->label(__('field_signing_ip'))
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('id', 'desc');
    }
}
