<?php

namespace App\Filament\Resources\LoanApplications\Schemas;

use App\Enums\LoanApplicationStatus;
use App\Models\EkycDocument;
use App\Services\UploadService;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\ImageEntry;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Operation;

class LoanApplicationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Application'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('reference')
                            ->label(__('field_reference'))
                            ->disabled()
                            ->dehydrated(false)
                            ->visibleOn(Operation::Edit),
                        Select::make('user_id')
                            ->label(__('Customer'))
                            ->relationship('user', 'email')
                            ->searchable()
                            ->preload()
                            ->required(fn ($livewire): bool => $livewire instanceof CreateRecord)
                            ->disabled(fn ($livewire): bool => $livewire instanceof EditRecord)
                            ->dehydrated(fn ($livewire): bool => $livewire instanceof CreateRecord),
                        TextInput::make('desired_amount')
                            ->label(__('field_desired_amount'))
                            ->numeric()
                            ->required(),
                        TextInput::make('tenure_months')
                            ->label(__('admin_form_tenure_months'))
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(120)
                            ->required(),
                        DateTimePicker::make('submitted_at')
                            ->label(__('field_submitted_at'))
                            ->seconds(false)
                            ->native(false)
                            ->visibleOn(Operation::Edit),
                    ]),
                Section::make(__('Profile'))
                    ->relationship('profile')
                    ->visibleOn(Operation::Edit)
                    ->columns(2)
                    ->schema([
                        TextInput::make('full_name')
                            ->label(__('Full name'))
                            ->maxLength(255),
                        DatePicker::make('birthday')
                            ->label(__('Birthday'))
                            ->native(false)
                            ->displayFormat('d/m/Y'),
                        Select::make('gender')
                            ->label(__('Gender'))
                            ->options([
                                'male' => __('Male'),
                                'female' => __('Female'),
                                'other' => __('Other'),
                            ])
                            ->native(false),
                        TextInput::make('phone')
                            ->label(__('Phone number'))
                            ->maxLength(32),
                        TextInput::make('cccd_number')
                            ->label(__('CCCD number'))
                            ->maxLength(32),
                        TextInput::make('address')
                            ->label(__('Address'))
                            ->maxLength(2000)
                            ->columnSpanFull(),
                        TextInput::make('province')
                            ->label(__('Province'))
                            ->maxLength(128),
                        TextInput::make('district')
                            ->label(__('District'))
                            ->maxLength(128),
                        TextInput::make('ward')
                            ->label(__('Ward'))
                            ->maxLength(128),
                        TextInput::make('company')
                            ->label(__('Company'))
                            ->maxLength(255),
                        TextInput::make('job_title')
                            ->label(__('Job title'))
                            ->maxLength(255),
                        TextInput::make('monthly_income')
                            ->label(__('Monthly income (VND)'))
                            ->numeric()
                            ->minValue(0),
                        TextInput::make('bank_name')
                            ->label(__('Bank name'))
                            ->maxLength(128),
                        TextInput::make('bank_account')
                            ->label(__('Bank account'))
                            ->maxLength(64),
                    ]),
                Section::make(__('eKYC'))
                    ->relationship('ekyc')
                    ->visibleOn(Operation::Edit)
                    ->columns(1)
                    ->schema([
                        ImageEntry::make('preview_cccd_front')
                            ->label(__('CCCD front'))
                            ->dehydrated(false)
                            ->state(fn (EkycDocument $record): ?string => filled($record->front_path)
                                ? route('staff.files.ekyc', ['loanApplication' => $record->loanApplication, 'field' => 'front'])
                                : null)
                            ->checkFileExistence(false)
                            ->imageHeight('16rem')
                            ->placeholder(__('No data')),
                        ImageEntry::make('preview_cccd_back')
                            ->label(__('CCCD back'))
                            ->dehydrated(false)
                            ->state(fn (EkycDocument $record): ?string => filled($record->back_path)
                                ? route('staff.files.ekyc', ['loanApplication' => $record->loanApplication, 'field' => 'back'])
                                : null)
                            ->checkFileExistence(false)
                            ->imageHeight('16rem')
                            ->placeholder(__('No data')),
                        ImageEntry::make('preview_cccd_holding_front')
                            ->label(__('CCCD holding front'))
                            ->dehydrated(false)
                            ->state(fn (EkycDocument $record): ?string => filled($record->holding_front_path)
                                ? route('staff.files.ekyc', ['loanApplication' => $record->loanApplication, 'field' => 'holding_front'])
                                : null)
                            ->checkFileExistence(false)
                            ->imageHeight('16rem')
                            ->placeholder(__('No data')),
                        FileUpload::make('admin_replace_holding_front')
                            ->label(__('admin_ekyc_replace_holding_front'))
                            ->helperText(__('admin_ekyc_replace_holding_hint'))
                            ->image()
                            ->disk('local')
                            ->visibility('private')
                            ->maxSize(UploadService::MAX_KB)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->dehydrated(false)
                            ->downloadable(false)
                            ->columnSpanFull(),
                        ImageEntry::make('preview_cccd_holding_back')
                            ->label(__('CCCD holding back'))
                            ->dehydrated(false)
                            ->state(fn (EkycDocument $record): ?string => filled($record->holding_back_path)
                                ? route('staff.files.ekyc', ['loanApplication' => $record->loanApplication, 'field' => 'holding_back'])
                                : null)
                            ->checkFileExistence(false)
                            ->imageHeight('16rem')
                            ->placeholder(__('No data')),
                        FileUpload::make('admin_replace_holding_back')
                            ->label(__('admin_ekyc_replace_holding_back'))
                            ->helperText(__('admin_ekyc_replace_holding_hint'))
                            ->image()
                            ->disk('local')
                            ->visibility('private')
                            ->maxSize(UploadService::MAX_KB)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->dehydrated(false)
                            ->downloadable(false)
                            ->columnSpanFull(),
                        ImageEntry::make('preview_selfie')
                            ->label(__('Selfie (legacy)'))
                            ->dehydrated(false)
                            ->state(fn (EkycDocument $record): ?string => filled($record->selfie_path)
                                ? route('staff.files.ekyc', ['loanApplication' => $record->loanApplication, 'field' => 'selfie'])
                                : null)
                            ->checkFileExistence(false)
                            ->imageHeight('16rem')
                            ->placeholder(__('No data')),
                    ]),
                Section::make(__('Decision'))
                    ->schema([
                        Select::make('status')
                            ->label(__('field_status'))
                            ->helperText(__('admin_form_status_syncs_wizard_hint'))
                            ->options(collect(LoanApplicationStatus::cases())->mapWithKeys(
                                fn (LoanApplicationStatus $e) => [$e->value => $e->label()]
                            ))
                            ->required(),
                        TextInput::make('approved_amount')
                            ->label(__('Approved amount'))
                            ->numeric(),
                        TextInput::make('monthly_payment')
                            ->label(__('field_monthly_payment'))
                            ->numeric(),
                        TextInput::make('score')
                            ->label(__('field_score'))
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100),
                        Select::make('risk_level')
                            ->label(__('field_risk_level'))
                            ->options([
                                'low' => __('risk_low'),
                                'medium' => __('risk_medium'),
                                'elevated' => __('risk_elevated'),
                            ])
                            ->native(false),
                        Select::make('admin_decision')
                            ->label(__('field_admin_decision'))
                            ->options([
                                'approved' => __('Approved'),
                                'rejected' => __('Rejected'),
                                'needs_revision' => __('admin_decision_needs_revision'),
                            ])
                            ->native(false),
                        Textarea::make('revision_message')
                            ->label(__('field_revision_message'))
                            ->rows(3)
                            ->columnSpanFull(),
                        Textarea::make('admin_notes')
                            ->label(__('field_admin_notes'))
                            ->rows(4)
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }
}
