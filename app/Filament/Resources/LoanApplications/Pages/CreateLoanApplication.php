<?php

namespace App\Filament\Resources\LoanApplications\Pages;

use App\Enums\LoanApplicationStatus;
use App\Enums\WizardStep;
use App\Filament\Resources\LoanApplications\LoanApplicationResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateLoanApplication extends CreateRecord
{
    protected static string $resource = LoanApplicationResource::class;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['reference'] ??= 'LA-'.strtoupper(Str::random(10));
        $data['access_token'] ??= Str::random(64);
        $data['status'] ??= LoanApplicationStatus::InProgress->value;
        $data['wizard_step'] ??= WizardStep::Personal->value;

        return $data;
    }

    protected function afterCreate(): void
    {
        $this->record->profile()->firstOrCreate([]);
        $this->record->ekyc()->firstOrCreate([]);
    }
}
