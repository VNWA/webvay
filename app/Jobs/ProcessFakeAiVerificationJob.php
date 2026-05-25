<?php

namespace App\Jobs;

use App\Enums\LoanApplicationStatus;
use App\Enums\WizardStep;
use App\Models\LoanApplication;
use App\Services\LoanScoringService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;

class ProcessFakeAiVerificationJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $loanApplicationId,
    ) {}

    public function handle(LoanScoringService $scoring): void
    {
        $application = LoanApplication::query()->with(['profile', 'ekyc'])->find($this->loanApplicationId);
        if (! $application) {
            return;
        }

        $steps = [
            ['label' => __('ai_step_scan_id'), 'pct' => 15],
            ['label' => __('ai_step_verify_docs'), 'pct' => 32],
            ['label' => __('ai_step_credit'), 'pct' => 52],
            ['label' => __('ai_step_profile'), 'pct' => 68],
            ['label' => __('ai_step_scoring'), 'pct' => 84],
            ['label' => __('ai_step_finalize_validity'), 'pct' => 96],
        ];

        $totalSleep = random_int(8, 15);
        $per = (int) max(1, floor($totalSleep / count($steps)));

        foreach ($steps as $step) {
            DB::transaction(function () use ($application, $step): void {
                $application->ai_progress = [
                    'label' => $step['label'],
                    'percent' => $step['pct'],
                    'updated_at' => now()->toIso8601String(),
                ];
                $application->save();
            });
            sleep($per);
        }

        if ($application->ekyc) {
            $application->ekyc->ocr_json = $this->fakeOcrPayload($application);
            $application->ekyc->save();
        }

        $result = $scoring->evaluate($application);

        $application->score = $result['score'];
        $application->approved_amount = $result['approved_amount'];
        $application->risk_level = $result['risk_level'];
        $application->monthly_payment = $result['monthly_payment'];
        $application->status = LoanApplicationStatus::PendingAdmin;
        $application->wizard_step = WizardStep::PendingAdminReview;
        $application->ai_progress = [
            'label' => __('ai_progress_dossier_valid'),
            'percent' => 100,
            'updated_at' => now()->toIso8601String(),
        ];
        $application->submitted_at = $application->submitted_at ?? now();
        $application->save();

        SendApplicationPendingReviewEmailJob::dispatch($application->id)->onQueue('mail');
    }

    /**
     * @return array<string, mixed>
     */
    private function fakeOcrPayload(LoanApplication $application): array
    {
        $p = $application->profile;

        return [
            'engine' => 'findvay-ocr-v1',
            'confidence' => round(random_int(910, 995) / 10, 1),
            'fields' => [
                'id_number' => $p?->cccd_number ?? 'N/A',
                'full_name' => $p?->full_name ?? 'N/A',
                'phone' => $p?->phone ?? 'N/A',
                'dob' => $p?->birthday?->format('Y-m-d'),
            ],
            'liveness' => [
                'score' => random_int(88, 99),
                'status' => 'pass',
            ],
        ];
    }
}
