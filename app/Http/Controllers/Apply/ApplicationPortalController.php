<?php

namespace App\Http\Controllers\Apply;

use App\Enums\WizardStep;
use App\Http\Controllers\Controller;
use App\Models\LoanApplication;
use App\Repositories\Contracts\LoanApplicationRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ApplicationPortalController extends Controller
{
    public function __construct(
        protected LoanApplicationRepositoryInterface $loans,
    ) {}

    public function entry(Request $request): RedirectResponse
    {
        $application = $this->currentApplication($request);
        abort_if(! $application, 404);

        return redirect()->to($this->routeForStep($application));
    }

    public function routeForStep(LoanApplication $application): string
    {
        $p = ['reference' => $application->reference];

        return match ($application->wizard_step) {
            WizardStep::Personal => route('apply.ref.personal', $p),
            WizardStep::Employment => route('apply.ref.employment', $p),
            WizardStep::Documents => route('apply.ref.documents', $p),
            WizardStep::AiProcessing => route('apply.ref.ai', $p),
            WizardStep::PendingAdminReview => route('apply.ref.pending', $p),
            WizardStep::ApplicationRejected => route('apply.ref.rejected', $p),
            WizardStep::Result => route('apply.ref.result', $p),
            WizardStep::ContractOtp => route('apply.ref.contract', $p),
            WizardStep::Completed => route('apply.ref.success', $p),
        };
    }

    protected function currentApplication(Request $request): ?LoanApplication
    {
        $app = $request->attributes->get('loan_application');

        return $app instanceof LoanApplication ? $app : null;
    }
}
