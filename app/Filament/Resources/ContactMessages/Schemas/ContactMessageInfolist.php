<?php

namespace App\Filament\Resources\ContactMessages\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ContactMessageInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('contact_message_section'))
                    ->schema([
                        TextEntry::make('name')->label(__('field_name')),
                        TextEntry::make('email')->label(__('Email')),
                        TextEntry::make('phone')->label(__('field_phone'))->placeholder(__('No data')),
                        TextEntry::make('subject')->label(__('contact_subject_optional'))->placeholder(__('No data')),
                        TextEntry::make('message')->label(__('contact_message'))->columnSpanFull(),
                        TextEntry::make('ip_address')->label(__('field_ip_address'))->placeholder(__('No data')),
                        TextEntry::make('created_at')->label(__('field_created_at'))->dateTime('d/m/Y H:i'),
                        TextEntry::make('read_at')->label(__('field_read_at'))->dateTime('d/m/Y H:i')->placeholder(__('No data')),
                    ])
                    ->columns(2),
            ]);
    }
}
