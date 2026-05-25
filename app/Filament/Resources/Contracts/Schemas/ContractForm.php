<?php

namespace App\Filament\Resources\Contracts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ContractForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Contract details'))
                    ->columns(2)
                    ->schema([
                        Select::make('loan_application_id')
                            ->relationship('loanApplication', 'reference')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->label(__('field_contract_loan_application')),
                        TextInput::make('code')
                            ->label(__('field_code'))
                            ->required()
                            ->maxLength(64),
                        TextInput::make('pdf_path')
                            ->label(__('field_pdf_path'))
                            ->maxLength(512)
                            ->columnSpanFull(),
                        DateTimePicker::make('signed_at')
                            ->label(__('field_signed_at'))
                            ->seconds(false)
                            ->native(false),
                        TextInput::make('signing_ip')
                            ->label(__('field_signing_ip'))
                            ->maxLength(45),
                        KeyValue::make('meta')
                            ->label(__('field_meta'))
                            ->keyLabel(__('Key'))
                            ->valueLabel(__('Value'))
                            ->addActionLabel(__('Add row'))
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
