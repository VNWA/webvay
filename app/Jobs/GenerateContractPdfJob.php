<?php

namespace App\Jobs;

use App\Models\LoanApplication;
use App\Services\ContractService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GenerateContractPdfJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $loanApplicationId,
    ) {}

    public function handle(ContractService $contracts): void
    {
        $application = LoanApplication::query()->find($this->loanApplicationId);
        if (! $application) {
            return;
        }

        $contracts->generatePdf($application);
    }
}
