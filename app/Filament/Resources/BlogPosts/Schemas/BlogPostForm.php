<?php

namespace App\Filament\Resources\BlogPosts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BlogPostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('blog_post_section'))
                    ->schema([
                        TextInput::make('title')
                            ->label(__('field_title'))
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true),
                        TextInput::make('slug')
                            ->label(__('field_slug'))
                            ->maxLength(160)
                            ->nullable()
                            ->unique(ignoreRecord: true)
                            ->alphaDash()
                            ->helperText(__('blog_slug_hint')),
                        Textarea::make('excerpt')
                            ->label(__('field_excerpt'))
                            ->rows(3)
                            ->maxLength(500)
                            ->columnSpanFull(),
                        RichEditor::make('body')
                            ->label(__('field_body'))
                            ->columnSpanFull(),
                        DateTimePicker::make('published_at')
                            ->label(__('field_published_at'))
                            ->seconds(false)
                            ->native(false),
                        Toggle::make('is_published')
                            ->label(__('field_is_published'))
                            ->default(false),
                    ]),
            ]);
    }
}
