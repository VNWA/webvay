<?php

namespace App\Filament\Resources\Contracts\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ContractInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Contract details'))
                    ->columns(2)
                    ->schema([
                        TextEntry::make('code')
                            ->label(__('field_code')),
                        TextEntry::make('loanApplication.reference')
                            ->label(__('field_reference')),
                        TextEntry::make('loanApplication.user.email')
                            ->label(__('Customer email')),
                        TextEntry::make('pdf_path')
                            ->label(__('field_pdf_path'))
                            ->placeholder(__('No data'))
                            ->columnSpanFull(),
                        TextEntry::make('signed_at')
                            ->label(__('field_signed_at'))
                            ->dateTime('d/m/Y H:i')
                            ->placeholder(__('No data')),
                        TextEntry::make('signing_ip')
                            ->label(__('field_signing_ip'))
                            ->placeholder(__('No data')),
                        TextEntry::make('meta')
                            ->label(__('field_meta'))
                            ->formatStateUsing(function ($state): string {
                                if (empty($state)) {
                                    return __('No data');
                                }

                                return json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                            })
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
