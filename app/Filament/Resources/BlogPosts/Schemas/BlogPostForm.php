<?php

namespace App\Filament\Resources\BlogPosts\Schemas;

use App\Enums\UserRole;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
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
                Section::make(__('blog_media_section'))
                    ->description(__('blog_media_section_hint'))
                    ->schema([
                        FileUpload::make('cover_image')
                            ->label(__('blog_field_cover'))
                            ->image()
                            ->disk('public')
                            ->directory('blog/covers')
                            ->visibility('public')
                            ->maxSize(4096)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'])
                            ->imageEditor()
                            ->columnSpanFull(),
                        FileUpload::make('author_avatar')
                            ->label(__('blog_field_author_avatar'))
                            ->helperText(__('blog_field_author_avatar_hint'))
                            ->image()
                            ->disk('public')
                            ->directory('blog/authors')
                            ->visibility('public')
                            ->maxSize(2048)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'])
                            ->imageEditor()
                            ->avatar()
                            ->columnSpanFull(),
                    ]),
                Section::make(__('blog_author_section'))
                    ->schema([
                        Select::make('author_id')
                            ->label(__('Author'))
                            ->relationship(
                                'author',
                                'name',
                                fn ($query) => $query->where('role', UserRole::Admin),
                            )
                            ->searchable()
                            ->preload()
                            ->native(false),
                        TextInput::make('author_display_name')
                            ->label(__('blog_field_author_name'))
                            ->maxLength(120)
                            ->helperText(__('blog_field_author_name_hint')),
                        TextInput::make('author_title')
                            ->label(__('blog_field_author_title'))
                            ->maxLength(120)
                            ->placeholder(__('blog_field_author_title_placeholder')),
                    ])
                    ->columns(2),
            ]);
    }
}
