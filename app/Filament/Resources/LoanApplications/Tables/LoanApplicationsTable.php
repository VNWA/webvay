<?php

namespace App\Filament\Resources\LoanApplications\Tables;

use App\Enums\LoanApplicationStatus;
use App\Filament\Resources\LoanApplications\LoanApplicationResource;
use App\Models\LoanApplication;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class LoanApplicationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('reference')
                    ->label(__('field_reference'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.email')
                    ->label(__('Customer'))
                    ->searchable(),
                TextColumn::make('status')
                    ->label(__('field_status'))
                    ->badge()
                    ->sortable()
                    ->formatStateUsing(function ($state): string {
                        if ($state instanceof LoanApplicationStatus) {
                            return $state->label();
                        }

                        return LoanApplicationStatus::tryFrom((string) $state)?->label() ?? (string) $state;
                    }),
                TextColumn::make('desired_amount')
                    ->label(__('field_desired_amount'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('approved_amount')
                    ->label(__('Approved amount'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('score')
                    ->label(__('field_score'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('risk_level')
                    ->label(__('field_risk_level'))
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'low' => __('risk_low'),
                        'medium' => __('risk_medium'),
                        'elevated' => __('risk_elevated'),
                        default => (string) $state,
                    }),
                TextColumn::make('updated_at')
                    ->label(__('field_updated_at'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->recordUrl(fn (LoanApplication $record): string => LoanApplicationResource::getUrl('view', ['record' => $record]))
            ->filters([
                SelectFilter::make('status')
                    ->label(__('field_status'))
                    ->options(collect(LoanApplicationStatus::cases())->mapWithKeys(
                        fn (LoanApplicationStatus $e) => [$e->value => $e->label()]
                    )),
            ])
            ->recordActionsColumnLabel(__('admin_table_actions'))
            ->recordActions([
                ViewAction::make()
                    ->label(__('admin_action_view_dossier')),
                EditAction::make()
                    ->label(__('admin_action_edit_dossier')),
            ], RecordActionsPosition::AfterColumns)
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('updated_at', 'desc');
    }
}
