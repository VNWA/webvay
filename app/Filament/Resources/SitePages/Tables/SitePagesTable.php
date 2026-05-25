<?php

namespace App\Filament\Resources\SitePages\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SitePagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('slug')
                    ->label(__('field_slug'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('title')
                    ->label(__('field_title'))
                    ->searchable()
                    ->wrap(),
                IconColumn::make('is_published')
                    ->label(__('field_is_published'))
                    ->boolean(),
                TextColumn::make('updated_at')
                    ->label(__('field_updated_at'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('slug')
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
