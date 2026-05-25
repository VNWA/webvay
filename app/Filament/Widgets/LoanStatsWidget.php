<?php

namespace App\Filament\Widgets;

use App\Enums\LoanApplicationStatus;
use App\Models\LoanApplication;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LoanStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalUsers = User::query()->count();
        $totalLoans = LoanApplication::query()->count();
        $adminApproved = LoanApplication::query()->where('status', LoanApplicationStatus::Approved)->count();
        $adminRejected = LoanApplication::query()->where('status', LoanApplicationStatus::Rejected)->count();
        $pendingAdmin = LoanApplication::query()->where('status', LoanApplicationStatus::PendingAdmin)->count();
        $pipeline = LoanApplication::query()->whereIn('status', [
            LoanApplicationStatus::InProgress,
            LoanApplicationStatus::AiProcessing,
            LoanApplicationStatus::NeedsRevision,
            LoanApplicationStatus::ContractPending,
        ])->count();

        return [
            Stat::make(__('Total users'), (string) $totalUsers)
                ->description(__('All registered accounts'))
                ->color('primary'),
            Stat::make(__('Total applications'), (string) $totalLoans)
                ->description(__('Including completed journeys'))
                ->color('info'),
            Stat::make(__('loan_status_pending_admin'), (string) $pendingAdmin)
                ->description(__('widget_pending_admin_desc'))
                ->color('warning'),
            Stat::make(__('loan_status_admin_approved'), (string) $adminApproved)
                ->description(__('widget_admin_approved_desc'))
                ->color('success'),
            Stat::make(__('loan_status_admin_rejected'), (string) $adminRejected)
                ->description(__('widget_admin_rejected_desc'))
                ->color('danger'),
            Stat::make(__('In pipeline'), (string) $pipeline)
                ->description(__('Active onboarding / review'))
                ->color('gray'),
        ];
    }
}
