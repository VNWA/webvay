<?php

namespace App\Filament\Resources\LoanApplications\Schemas;

use App\Enums\LoanApplicationStatus;
use App\Models\LoanApplication;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LoanApplicationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Application'))
                    ->description(__('admin_infolist_dossier_hint'))
                    ->columns(3)
                    ->schema([
                        TextEntry::make('reference')->label(__('field_reference')),
                        TextEntry::make('status')
                            ->label(__('field_status'))
                            ->formatStateUsing(function ($state): string {
                                if ($state instanceof LoanApplicationStatus) {
                                    return $state->label();
                                }

                                return LoanApplicationStatus::tryFrom((string) $state)?->label() ?? (string) $state;
                            }),
                        TextEntry::make('desired_amount')->label(__('field_desired_amount'))->numeric(),
                        TextEntry::make('tenure_months')->label(__('admin_form_tenure_months'))->numeric(),
                        TextEntry::make('submitted_at')->label(__('field_submitted_at'))->dateTime('d/m/Y H:i')->placeholder(__('No data')),
                        TextEntry::make('score')->label(__('field_score'))->numeric(),
                        TextEntry::make('approved_amount')->label(__('Approved amount'))->numeric(),
                        TextEntry::make('risk_level')
                            ->label(__('field_risk_level'))
                            ->formatStateUsing(fn (?string $state): string => match ($state) {
                                'low' => __('risk_low'),
                                'medium' => __('risk_medium'),
                                'elevated' => __('risk_elevated'),
                                default => (string) $state,
                            }),
                        TextEntry::make('monthly_payment')->label(__('field_monthly_payment'))->numeric(),
                        TextEntry::make('admin_decision')
                            ->label(__('field_admin_decision'))
                            ->formatStateUsing(fn (?string $state): string => match ($state) {
                                'approved' => __('Approved'),
                                'rejected' => __('Rejected'),
                                'needs_revision' => __('admin_decision_needs_revision'),
                                default => (string) $state,
                            }),
                        TextEntry::make('admin_notes')->label(__('field_admin_notes'))->columnSpanFull(),
                        TextEntry::make('revision_message')->label(__('field_revision_message'))->columnSpanFull()->placeholder(__('No data')),
                        TextEntry::make('user.email')->label(__('Customer email')),
                        TextEntry::make('user.phone')->label(__('Phone number'))->placeholder(__('No data')),
                    ]),
                Section::make(__('Profile'))
                    ->relationship('profile')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('full_name')->label(__('Full name')),
                        TextEntry::make('birthday')->label(__('Birthday'))->date('d/m/Y'),
                        TextEntry::make('gender')
                            ->label(__('Gender'))
                            ->formatStateUsing(fn (?string $state): string => match ($state) {
                                'male' => __('Male'),
                                'female' => __('Female'),
                                'other' => __('Other'),
                                default => (string) $state,
                            }),
                        TextEntry::make('phone')->label(__('Phone number')),
                        TextEntry::make('cccd_number')->label(__('CCCD number')),
                        TextEntry::make('address')->label(__('Address'))->columnSpanFull(),
                        TextEntry::make('province')->label(__('Province')),
                        TextEntry::make('district')->label(__('District')),
                        TextEntry::make('ward')->label(__('Ward')),
                        TextEntry::make('company')->label(__('Company')),
                        TextEntry::make('job_title')->label(__('Job title')),
                        TextEntry::make('monthly_income')->label(__('Monthly income (VND)'))->numeric(),
                        TextEntry::make('bank_name')->label(__('Bank name')),
                        TextEntry::make('bank_account')->label(__('Bank account')),
                    ]),
                Section::make(__('eKYC'))
                    ->columns(1)
                    ->schema([
                        ImageEntry::make('cccd_front_preview')
                            ->label(__('CCCD front'))
                            ->state(fn (LoanApplication $record): ?string => filled($record->ekyc?->front_path)
                                ? route('staff.files.ekyc', ['loanApplication' => $record, 'field' => 'front'])
                                : null)
                            ->checkFileExistence(false)
                            ->imageHeight('16rem')
                            ->placeholder(__('No data')),
                        ImageEntry::make('cccd_back_preview')
                            ->label(__('CCCD back'))
                            ->state(fn (LoanApplication $record): ?string => filled($record->ekyc?->back_path)
                                ? route('staff.files.ekyc', ['loanApplication' => $record, 'field' => 'back'])
                                : null)
                            ->checkFileExistence(false)
                            ->imageHeight('16rem')
                            ->placeholder(__('No data')),
                        ImageEntry::make('cccd_holding_front_preview')
                            ->label(__('CCCD holding front'))
                            ->state(fn (LoanApplication $record): ?string => filled($record->ekyc?->holding_front_path)
                                ? route('staff.files.ekyc', ['loanApplication' => $record, 'field' => 'holding_front'])
                                : null)
                            ->checkFileExistence(false)
                            ->imageHeight('16rem')
                            ->placeholder(__('No data')),
                        ImageEntry::make('cccd_holding_back_preview')
                            ->label(__('CCCD holding back'))
                            ->state(fn (LoanApplication $record): ?string => filled($record->ekyc?->holding_back_path)
                                ? route('staff.files.ekyc', ['loanApplication' => $record, 'field' => 'holding_back'])
                                : null)
                            ->checkFileExistence(false)
                            ->imageHeight('16rem')
                            ->placeholder(__('No data')),
                        ImageEntry::make('selfie_preview')
                            ->label(__('Selfie (legacy)'))
                            ->state(fn (LoanApplication $record): ?string => filled($record->ekyc?->selfie_path)
                                ? route('staff.files.ekyc', ['loanApplication' => $record, 'field' => 'selfie'])
                                : null)
                            ->checkFileExistence(false)
                            ->imageHeight('16rem')
                            ->placeholder(__('No data')),
                    ]),
                Section::make(__('Contract'))
                    ->description(__('admin_contract_section_hint'))
                    ->columns(2)
                    ->visible(fn (LoanApplication $record): bool => $record->contract !== null)
                    ->schema([
                        TextEntry::make('contract.code')->label(__('field_code')),
                        TextEntry::make('contract.signed_at')->label(__('field_signed_at'))->dateTime('d/m/Y H:i')->placeholder(__('No data')),
                        TextEntry::make('contract.signing_ip')->label(__('field_signing_ip'))->placeholder(__('No data')),
                        TextEntry::make('contract.pdf_path')
                            ->label(__('Contract PDF'))
                            ->formatStateUsing(function (?string $state, LoanApplication $record): string {
                                if (! filled($state)) {
                                    return __('No data');
                                }
                                if ($record->adminCanViewFinalContractPdf()) {
                                    return __('admin_contract_pdf_open_link');
                                }

                                return __('admin_contract_pdf_pending_sign');
                            })
                            ->url(fn (LoanApplication $record): ?string => $record->adminCanViewFinalContractPdf() && $record->contract?->pdf_path
                                ? route('staff.files.contract', ['contract' => $record->contract])
                                : null)
                            ->openUrlInNewTab(),
                    ]),
            ]);
    }
}
