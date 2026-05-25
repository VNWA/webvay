<?php

namespace App\Filament\Resources\LoanApplications\Pages;

use App\Enums\LoanApplicationStatus;
use App\Enums\WizardStep;
use App\Filament\Resources\LoanApplications\LoanApplicationResource;
use App\Jobs\SendApplicationAdminDecisionEmailJob;
use App\Services\AuditLogService;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Str;

class ViewLoanApplication extends ViewRecord
{
    protected static string $resource = LoanApplicationResource::class;

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);

        $this->authorizeAccess();

        $this->record->ensureDossierChildren();

        if (! $this->hasInfolist()) {
            $this->fillForm();
        }
    }

    public function getTitle(): string | Htmlable
    {
        return __('admin_view_dossier_title', ['ref' => $this->record->reference]);
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->label(__('admin_action_edit_dossier'))
                ->color('primary'),
            Action::make('viewPdf')
                ->label(__('Contract PDF'))
                ->url(fn (): string => $this->record->contract
                    ? route('staff.files.contract', ['contract' => $this->record->contract])
                    : '#')
                ->visible(fn (): bool => $this->record->adminCanViewFinalContractPdf())
                ->openUrlInNewTab(),
            Action::make('approve')
                ->label(__('admin_action_approve'))
                ->color('success')
                ->visible(fn (): bool => $this->record->status === LoanApplicationStatus::PendingAdmin
                    && $this->record->wizard_step === WizardStep::PendingAdminReview)
                ->requiresConfirmation()
                ->action(function (AuditLogService $audit): void {
                    $app = $this->record;
                    if (! $app->access_token) {
                        $app->access_token = Str::random(64);
                    }
                    $app->update([
                        'admin_decision' => 'approved',
                        'status' => LoanApplicationStatus::Approved,
                        'wizard_step' => WizardStep::Result,
                        'revision_message' => null,
                    ]);
                    $audit->log(auth()->user(), 'admin.application_approved', \App\Models\LoanApplication::class, $app->id);
                    SendApplicationAdminDecisionEmailJob::dispatch($app->id, 'approved', null)->onQueue('mail');
                    Notification::make()->title(__('Marked approved'))->success()->send();
                }),
            Action::make('reject')
                ->label(__('admin_action_reject'))
                ->color('danger')
                ->visible(fn (): bool => $this->record->status === LoanApplicationStatus::PendingAdmin
                    && $this->record->wizard_step === WizardStep::PendingAdminReview)
                ->schema([
                    Textarea::make('reason')->label(__('admin_rejection_reason'))->required()->rows(4),
                ])
                ->action(function (array $data, AuditLogService $audit): void {
                    $app = $this->record;
                    $app->update([
                        'admin_decision' => 'rejected',
                        'status' => LoanApplicationStatus::Rejected,
                        'wizard_step' => WizardStep::ApplicationRejected,
                        'admin_notes' => $data['reason'],
                    ]);
                    $audit->log(auth()->user(), 'admin.application_rejected', \App\Models\LoanApplication::class, $app->id);
                    SendApplicationAdminDecisionEmailJob::dispatch($app->id, 'rejected', $data['reason'])->onQueue('mail');
                    Notification::make()->title(__('Marked rejected'))->success()->send();
                }),
            Action::make('requestRevision')
                ->label(__('admin_action_request_revision'))
                ->color('warning')
                ->visible(fn (): bool => $this->record->status === LoanApplicationStatus::PendingAdmin
                    && $this->record->wizard_step === WizardStep::PendingAdminReview)
                ->schema([
                    Textarea::make('message')->label(__('admin_revision_instructions'))->required()->rows(5),
                ])
                ->action(function (array $data, AuditLogService $audit): void {
                    $app = $this->record;
                    $app->access_token = Str::random(64);
                    $app->update([
                        'admin_decision' => 'needs_revision',
                        'status' => LoanApplicationStatus::NeedsRevision,
                        'wizard_step' => WizardStep::Personal,
                        'revision_message' => $data['message'],
                    ]);
                    $audit->log(auth()->user(), 'admin.application_revision_requested', \App\Models\LoanApplication::class, $app->id);
                    SendApplicationAdminDecisionEmailJob::dispatch($app->id, 'revision', $data['message'])->onQueue('mail');
                    Notification::make()->title(__('admin_revision_sent'))->success()->send();
                }),
        ];
    }
}
