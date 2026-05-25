<?php

namespace App\Filament\Resources\AuditLogs;

use App\Filament\Resources\AuditLogs\Pages\ManageAuditLogs;
use App\Models\AuditLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AuditLogResource extends Resource
{
    protected static ?string $model = AuditLog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    public static function getNavigationGroup(): string|\UnitEnum|null
    {
        return __('Lending');
    }

    public static function getNavigationLabel(): string
    {
        return __('Audit logs');
    }

    public static function getModelLabel(): string
    {
        return __('Audit log');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Audit logs');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label(__('field_id'))
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('field_created_at'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('user.email')
                    ->label(__('User'))
                    ->searchable(),
                TextColumn::make('action')
                    ->label(__('field_action'))
                    ->searchable()
                    ->wrap(),
                TextColumn::make('subject_type')
                    ->label(__('field_subject_type'))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('subject_id')
                    ->label(__('field_subject_id'))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('ip_address')
                    ->label(__('field_ip_address')),
            ])
            ->defaultSort('id', 'desc')
            ->recordActions([])
            ->toolbarActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageAuditLogs::route('/'),
        ];
    }
}
