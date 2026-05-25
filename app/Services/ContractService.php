<?php

namespace App\Services;

use App\Models\Contract;
use App\Models\LoanApplication;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ContractService
{
    public function ensureContract(LoanApplication $application): Contract
    {
        $existing = $application->contract;
        if ($existing) {
            return $existing;
        }

        $code = 'CT-'.now()->format('Y').'-'.strtoupper(Str::random(6));

        return Contract::query()->create([
            'loan_application_id' => $application->id,
            'code' => $code,
            'meta' => [
                'generated_at' => now()->toIso8601String(),
            ],
        ]);
    }

    public function generatePdf(LoanApplication $application): string
    {
        $contract = $this->ensureContract($application);
        $application->load(['profile', 'user']);

        $pdf = Pdf::loadView('pdf.loan-contract', [
            'application' => $application,
            'contract' => $contract,
        ])->setPaper('a4');

        $relative = 'contracts/'.$application->id.'-'.Str::uuid().'.pdf';
        Storage::disk('local')->put($relative, $pdf->output());

        $contract->pdf_path = $relative;
        $contract->save();

        return $relative;
    }
}
