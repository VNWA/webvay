<?php

namespace App\Filament\Resources\SitePages\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SitePageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('site_page_section'))
                    ->schema([
                        TextInput::make('slug')
                            ->label(__('field_slug'))
                            ->required()
                            ->maxLength(100)
                            ->unique(ignoreRecord: true)
                            ->alphaDash()
                            ->helperText(__('site_page_slug_hint')),
                        TextInput::make('title')
                            ->label(__('field_title'))
                            ->required()
                            ->maxLength(255),
                        RichEditor::make('body')
                            ->label(__('field_body'))
                            ->columnSpanFull(),
                        Toggle::make('is_published')
                            ->label(__('field_is_published'))
                            ->default(true),
                    ]),
            ]);
    }
}
