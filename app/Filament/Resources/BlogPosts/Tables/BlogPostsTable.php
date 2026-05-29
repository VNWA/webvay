<?php

namespace App\Filament\Resources\BlogPosts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BlogPostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('cover_image')
                    ->label(__('blog_field_cover'))
                    ->disk('public')
                    ->square()
                    ->defaultImageUrl(fn (): string => asset('images/blog/covers/guide.svg')),
                TextColumn::make('title')
                    ->label(__('field_title'))
                    ->searchable()
                    ->wrap(),
                TextColumn::make('slug')
                    ->label(__('field_slug'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('is_published')
                    ->label(__('field_is_published'))
                    ->boolean(),
                TextColumn::make('published_at')
                    ->label(__('field_published_at'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('author_display_name')
                    ->label(__('blog_field_author_name'))
                    ->placeholder(fn ($record) => $record->author?->name)
                    ->toggleable(),
                TextColumn::make('author.name')
                    ->label(__('Author'))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('field_updated_at'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('published_at', 'desc')
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
