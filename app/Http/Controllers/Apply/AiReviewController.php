<?php

namespace App\Http\Controllers\Apply;

use App\Enums\LoanApplicationStatus;
use App\Enums\WizardStep;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AiReviewController extends ApplicationPortalController
{
    public function show(Request $request): View|RedirectResponse
    {
        $application = $this->currentApplication($request);
        if (! $application) {
            return redirect()->route('apply.start');
        }

        if ($application->wizard_step === WizardStep::Result && $application->status === LoanApplicationStatus::Approved) {
            return redirect()->route('apply.ref.result', ['reference' => $application->reference]);
        }

        if ($application->wizard_step === WizardStep::PendingAdminReview) {
            return redirect()->route('apply.ref.pending', ['reference' => $application->reference]);
        }

        if ($application->status !== LoanApplicationStatus::AiProcessing && $application->wizard_step !== WizardStep::AiProcessing) {
            return redirect()->to($this->routeForStep($application));
        }

        return view('apply.ai', [
            'application' => $application,
            'aiStatusUrl' => route('apply.ref.ai.status', ['reference' => $application->reference]),
        ]);
    }

    public function status(Request $request): JsonResponse
    {
        $application = $this->currentApplication($request);
        abort_unless($application, 404);

        $redirect = null;
        if ($application->wizard_step === WizardStep::PendingAdminReview) {
            $redirect = route('apply.ref.pending', ['reference' => $application->reference]);
        } elseif ($application->wizard_step === WizardStep::Result && $application->status === LoanApplicationStatus::Approved) {
            $redirect = route('apply.ref.result', ['reference' => $application->reference]);
        }

        return response()->json([
            'status' => $application->status->value,
            'wizard_step' => $application->wizard_step->value,
            'ai_progress' => $application->ai_progress,
            'redirect' => $redirect,
        ]);
    }
}
