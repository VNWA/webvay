<?php

namespace App\Http\Controllers\Apply;

use App\Enums\LoanApplicationStatus;
use App\Enums\OtpPurpose;
use App\Enums\WizardStep;
use App\Jobs\GenerateContractPdfJob;
use App\Jobs\SendApplicationCompletedEmailJob;
use App\Models\LoanApplication;
use App\Models\User;
use App\Services\AuditLogService;
use App\Services\ContractService;
use App\Services\OtpService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LoanOutcomeController extends ApplicationPortalController
{
    public function pendingAdmin(Request $request): View|RedirectResponse
    {
        $application = $this->currentApplication($request);
        if (! $application) {
            return redirect()->route('apply.start');
        }

        if ($application->wizard_step !== WizardStep::PendingAdminReview) {
            return redirect()->to($this->routeForStep($application));
        }

        return view('apply.pending-admin', [
            'application' => $application,
        ]);
    }

    public function rejected(Request $request): View|RedirectResponse
    {
        $application = $this->currentApplication($request);
        if (! $application) {
            return redirect()->route('apply.start');
        }

        if ($application->wizard_step !== WizardStep::ApplicationRejected) {
            return redirect()->to($this->routeForStep($application));
        }

        return view('apply.application-rejected', [
            'application' => $application,
        ]);
    }

    public function result(Request $request): View|RedirectResponse
    {
        $application = $this->currentApplication($request);
        if (! $application) {
            return redirect()->route('apply.start');
        }

        if ($application->wizard_step === WizardStep::AiProcessing) {
            return redirect()->route('apply.ref.ai', ['reference' => $application->reference]);
        }

        if ($application->status !== LoanApplicationStatus::Approved) {
            return redirect()->to($this->routeForStep($application));
        }

        if ($application->wizard_step !== WizardStep::Result) {
            return redirect()->to($this->routeForStep($application));
        }

        return view('apply.result', [
            'application' => $application,
        ]);
    }

    public function continueToContract(Request $request, AuditLogService $audit): RedirectResponse
    {
        $application = $this->currentApplication($request);
        abort_unless($application, 404);
        abort_unless($application->wizard_step === WizardStep::Result, 403);

        $application->wizard_step = WizardStep::ContractOtp;
        $application->status = LoanApplicationStatus::ContractPending;
        $application->save();

        GenerateContractPdfJob::dispatch($application->id)->onQueue('default');

        $audit->log(User::find($application->user_id), 'application.contract_flow_started', LoanApplication::class, $application->id);

        return redirect()->route('apply.ref.contract', ['reference' => $application->reference]);
    }

    public function contract(Request $request, ContractService $contracts): View|RedirectResponse
    {
        $application = $this->currentApplication($request);
        if (! $application) {
            return redirect()->route('apply.start');
        }

        if ($application->wizard_step === WizardStep::Completed) {
            return redirect()->route('apply.ref.success', ['reference' => $application->reference]);
        }

        if ($application->wizard_step !== WizardStep::ContractOtp) {
            return redirect()->to($this->routeForStep($application));
        }

        $contract = $contracts->ensureContract($application);

        $ref = ['reference' => $application->reference];

        return view('apply.contract', [
            'application' => $application,
            'contract' => $contract,
            'contractPdfStatusUrl' => route('apply.ref.contract.pdf-status', $ref),
            'contractDownloadUrl' => route('apply.ref.contract.download', $ref),
            'contractOtpUrl' => route('apply.ref.contract.otp', $ref),
            'contractConfirmUrl' => route('apply.ref.contract.confirm', $ref),
        ]);
    }

    public function contractPdfStatus(Request $request): JsonResponse
    {
        $application = $this->currentApplication($request);
        abort_unless($application, 404);

        $application->load('contract');

        return response()->json([
            'ready' => (bool) ($application->contract?->pdf_path && Storage::disk('local')->exists($application->contract->pdf_path)),
            'download_url' => $application->contract?->pdf_path
                ? route('apply.ref.contract.download', ['reference' => $application->reference])
                : null,
        ]);
    }

    public function downloadContract(Request $request): StreamedResponse|RedirectResponse
    {
        $application = $this->currentApplication($request);
        abort_unless($application, 404);

        $application->load('contract');
        $path = $application->contract?->pdf_path;
        abort_unless($path && Storage::disk('local')->exists($path), 404);

        return Storage::disk('local')->download($path, 'loan-contract-'.$application->reference.'.pdf');
    }

    public function sendContractOtp(Request $request, OtpService $otp, AuditLogService $audit): RedirectResponse
    {
        $application = $this->currentApplication($request);
        abort_unless($application, 404);

        $user = $application->user;
        abort_unless($user, 404);

        $otp->send(
            $user->email,
            OtpPurpose::ContractSigning,
            $user,
            $request->ip(),
        );

        $audit->log($user, 'contract.otp_sent', LoanApplication::class, $application->id);

        return back()->with('status', __('We emailed you a signing code.'));
    }

    public function confirmContract(Request $request, OtpService $otp, AuditLogService $audit): RedirectResponse
    {
        $application = $this->currentApplication($request);
        abort_unless($application, 404);

        $user = $application->user;
        abort_unless($user, 404);

        $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $otp->verify(
            $user->email,
            $request->string('code')->toString(),
            OtpPurpose::ContractSigning,
            $request->ip(),
        );

        $application->load('contract');
        $contract = $application->contract ?? app(ContractService::class)->ensureContract($application);
        $contract->signed_at = now();
        $contract->signing_ip = $request->ip();
        $contract->save();

        $application->wizard_step = WizardStep::Completed;
        $application->status = LoanApplicationStatus::Completed;
        $application->save();

        $audit->log($user, 'contract.signed', LoanApplication::class, $application->id);

        SendApplicationCompletedEmailJob::dispatch($application->id)->onQueue('mail');

        return redirect()->route('apply.ref.success', ['reference' => $application->reference]);
    }

    public function success(Request $request): View|RedirectResponse
    {
        $application = $this->currentApplication($request);
        if (! $application || $application->wizard_step !== WizardStep::Completed) {
            return redirect()->route('apply.start');
        }

        return view('apply.success', [
            'application' => $application,
        ]);
    }
}
