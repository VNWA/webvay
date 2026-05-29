<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\UserRole;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Profile'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required(),
                        FileUpload::make('avatar_path')
                            ->label(__('blog_user_avatar'))
                            ->helperText(__('blog_user_avatar_hint'))
                            ->image()
                            ->disk('public')
                            ->directory('avatars')
                            ->visibility('public')
                            ->maxSize(2048)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'])
                            ->imageEditor()
                            ->avatar(),
                        TextInput::make('email')
                            ->label(__('Email address'))
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true),
                        Select::make('role')
                            ->options([
                                UserRole::Customer->value => __('Customer'),
                                UserRole::Admin->value => __('Administrator'),
                            ])
                            ->required()
                            ->native(false),
                        DateTimePicker::make('email_verified_at'),
                        TextInput::make('password')
                            ->password()
                            ->revealable()
                            ->minLength(8)
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->helperText(__('Leave blank when editing to keep the current password.')),
                    ]),
            ]);
    }
}
