<?php

namespace App\Filament\Resources\SitePages;

use App\Filament\Resources\SitePages\Pages\CreateSitePage;
use App\Filament\Resources\SitePages\Pages\EditSitePage;
use App\Filament\Resources\SitePages\Pages\ListSitePages;
use App\Filament\Resources\SitePages\Schemas\SitePageForm;
use App\Filament\Resources\SitePages\Tables\SitePagesTable;
use App\Models\SitePage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SitePageResource extends Resource
{
    protected static ?string $model = SitePage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentDuplicate;

    protected static ?int $navigationSort = 10;

    public static function getNavigationGroup(): string|\UnitEnum|null
    {
        return __('Website');
    }

    public static function getModelLabel(): string
    {
        return __('Site page');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Site pages');
    }

    public static function form(Schema $schema): Schema
    {
        return SitePageForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return SitePagesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSitePages::route('/'),
            'create' => CreateSitePage::route('/create'),
            'edit' => EditSitePage::route('/{record}/edit'),
        ];
    }
}
