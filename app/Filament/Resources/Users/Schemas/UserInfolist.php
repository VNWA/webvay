<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\UserRole;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label(__('field_name')),
                TextEntry::make('email')
                    ->label(__('Email address')),
                TextEntry::make('email_verified_at')
                    ->label(__('Email verified at'))
                    ->dateTime('d/m/Y H:i')
                    ->placeholder(__('No data')),
                TextEntry::make('role')
                    ->label(__('Role'))
                    ->badge()
                    ->formatStateUsing(function ($state): string {
                        $role = $state instanceof UserRole ? $state : UserRole::tryFrom((string) $state);
                        if (! $role instanceof UserRole) {
                            return (string) $state;
                        }

                        return match ($role) {
                            UserRole::Customer => __('Customer'),
                            UserRole::Admin => __('Administrator'),
                        };
                    }),
                TextEntry::make('last_login_ip')
                    ->label(__('field_last_login_ip'))
                    ->placeholder(__('No data')),
                TextEntry::make('last_login_at')
                    ->label(__('field_last_login_at'))
                    ->dateTime('d/m/Y H:i')
                    ->placeholder(__('No data')),
                TextEntry::make('created_at')
                    ->label(__('field_created_at'))
                    ->dateTime('d/m/Y H:i')
                    ->placeholder(__('No data')),
                TextEntry::make('updated_at')
                    ->label(__('field_updated_at'))
                    ->dateTime('d/m/Y H:i')
                    ->placeholder(__('No data')),
            ]);
    }
}
