<?php

namespace App\Filament\Resources\LoanApplications;

use App\Filament\Resources\LoanApplications\Pages\CreateLoanApplication;
use App\Filament\Resources\LoanApplications\Pages\EditLoanApplication;
use App\Filament\Resources\LoanApplications\Pages\ListLoanApplications;
use App\Filament\Resources\LoanApplications\Pages\ViewLoanApplication;
use App\Filament\Resources\LoanApplications\Schemas\LoanApplicationForm;
use App\Filament\Resources\LoanApplications\Schemas\LoanApplicationInfolist;
use App\Filament\Resources\LoanApplications\Tables\LoanApplicationsTable;
use App\Models\LoanApplication;
use BackedEnum;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LoanApplicationResource extends Resource
{
    protected static ?string $model = LoanApplication::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    public static function getNavigationGroup(): string|\UnitEnum|null
    {
        return __('Lending');
    }

    public static function getModelLabel(): string
    {
        return __('Loan application');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Loan applications');
    }

    public static function form(Schema $schema): Schema
    {
        return LoanApplicationForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return LoanApplicationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LoanApplicationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLoanApplications::route('/'),
            'create' => CreateLoanApplication::route('/create'),
            'view' => ViewLoanApplication::route('/{record}'),
            'edit' => EditLoanApplication::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['user', 'profile', 'ekyc', 'contract']);
    }
}
