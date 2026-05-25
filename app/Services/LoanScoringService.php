<?php

namespace App\Services;

use App\Models\CustomerProfile;
use App\Models\EkycDocument;
use App\Models\LoanApplication;

class LoanScoringService
{
    /**
     * @return array{score: int, approved_amount: int, risk_level: string, monthly_payment: int}
     */
    public function evaluate(LoanApplication $application): array
    {
        $profile = $application->profile ?? new CustomerProfile;
        $ekyc = $application->ekyc ?? new EkycDocument;

        $score = 0;

        $income = (int) ($profile->monthly_income ?? 0);
        if ($income >= 50_000_000) {
            $score += 40;
        } elseif ($income >= 30_000_000) {
            $score += 35;
        } elseif ($income >= 15_000_000) {
            $score += 28;
        } elseif ($income >= 8_000_000) {
            $score += 20;
        } else {
            $score += 12;
        }

        $age = null;
        if ($profile->birthday) {
            $age = $profile->birthday->diffInYears(now());
            if ($age >= 22 && $age <= 45) {
                $score += 20;
            } elseif ($age >= 18 && $age <= 55) {
                $score += 12;
            } else {
                $score += 5;
            }
        }

        $title = strtolower((string) ($profile->job_title ?? ''));
        if (str_contains($title, 'director') || str_contains($title, 'manager') || str_contains($title, 'lead')) {
            $score += 15;
        } elseif (str_contains($title, 'engineer') || str_contains($title, 'developer') || str_contains($title, 'analyst')) {
            $score += 12;
        } elseif ($title !== '') {
            $score += 8;
        }

        $docs = 0;
        if ($ekyc->front_path) {
            $docs++;
        }
        if ($ekyc->back_path) {
            $docs++;
        }
        if ($ekyc->holding_front_path) {
            $docs++;
        }
        if ($ekyc->holding_back_path) {
            $docs++;
        }
        // Giữ điểm nếu hồ sơ cũ chỉ có selfie (3 ảnh cũ).
        if ($docs < 3 && $ekyc->selfie_path) {
            $docs++;
        }
        $score += match ($docs) {
            4 => 25,
            3 => 18,
            2 => 12,
            1 => 6,
            default => 0,
        };

        $score = min(100, $score);

        $approvedAmount = match (true) {
            $score >= 80 => 20_000_000,
            $score >= 60 => 10_000_000,
            default => 3_000_000,
        };

        $riskLevel = match (true) {
            $score >= 80 => 'low',
            $score >= 60 => 'medium',
            default => 'elevated',
        };

        $tenure = max(1, (int) $application->tenure_months);
        $annualRate = 0.18;
        $principal = $approvedAmount;
        $monthlyRate = $annualRate / 12;
        $payment = (int) round(
            ($principal * $monthlyRate * (1 + $monthlyRate) ** $tenure) / (((1 + $monthlyRate) ** $tenure) - 1)
        );

        return [
            'score' => $score,
            'approved_amount' => $approvedAmount,
            'risk_level' => $riskLevel,
            'monthly_payment' => max(1, $payment),
        ];
    }
}
