<?php

namespace App\Filament\Resources\LoanApplications\Pages;

use App\Enums\LoanApplicationStatus;
use App\Filament\Resources\LoanApplications\LoanApplicationResource;
use App\Services\UploadService;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\File as SymfonyFile;

class EditLoanApplication extends EditRecord
{
    protected static string $resource = LoanApplicationResource::class;

    /**
     * @var array<string, mixed|null>
     */
    protected array $pendingEkycHoldingUploads = [];

    public function getTitle(): string | Htmlable
    {
        return __('admin_edit_dossier_title', ['ref' => $this->record->reference]);
    }

    protected function beforeFill(): void
    {
        $this->record->ensureDossierChildren();
    }

    protected function beforeSave(): void
    {
        $raw = $this->form->getRawState();
        $this->pendingEkycHoldingUploads = [
            'holding_front' => data_get($raw, 'ekyc.admin_replace_holding_front'),
            'holding_back' => data_get($raw, 'ekyc.admin_replace_holding_back'),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (isset($data['status'])) {
            $newStatus = $data['status'] instanceof LoanApplicationStatus
                ? $data['status']
                : LoanApplicationStatus::tryFrom((string) $data['status']);
            $oldStatus = $this->record->status instanceof LoanApplicationStatus
                ? $this->record->status
                : LoanApplicationStatus::tryFrom((string) $this->record->status);
            if ($newStatus && $oldStatus && $newStatus !== $oldStatus) {
                $data['wizard_step'] = $newStatus->suggestedWizardStep()->value;
            }
        }

        if (isset($data['ekyc']) && is_array($data['ekyc'])) {
            unset($data['ekyc']['admin_replace_holding_front'], $data['ekyc']['admin_replace_holding_back']);
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $this->applyEkycHoldingReplacementsFromPending();

        $this->record->loadMissing('profile', 'user');
        $name = $this->record->profile?->full_name;
        if ($name && $this->record->user) {
            $this->record->user->update(['name' => $name]);
        }
    }

    private function applyEkycHoldingReplacementsFromPending(): void
    {
        $this->record->refresh();
        $ekyc = $this->record->ekyc;
        if (! $ekyc) {
            return;
        }

        $uploads = app(UploadService::class);
        $disk = Storage::disk('local');

        foreach (['holding_front' => 'holding_front_path', 'holding_back' => 'holding_back_path'] as $kind => $column) {
            $state = $this->pendingEkycHoldingUploads[$kind] ?? null;
            if ($state === null || $state === '') {
                continue;
            }

            $paths = is_array($state) ? $state : [$state];
            $relativePath = $paths[0] ?? null;
            if (! is_string($relativePath) || $relativePath === '') {
                continue;
            }

            if (! $disk->exists($relativePath)) {
                continue;
            }

            $absolutePath = $disk->path($relativePath);
            $uploadedFile = \Illuminate\Http\UploadedFile::createFromBase(
                new SymfonyFile($absolutePath),
                true
            );

            $oldPath = $ekyc->{$column};
            $newPath = $uploads->storeEkycImage($this->record, $uploadedFile, $kind);

            if ($oldPath && $oldPath !== $newPath && $disk->exists($oldPath)) {
                $disk->delete($oldPath);
            }

            $ekyc->update([$column => $newPath]);

            if ($disk->exists($relativePath)) {
                $disk->delete($relativePath);
            }
        }

        $this->pendingEkycHoldingUploads = [];
    }
}
