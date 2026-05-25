<?php

namespace App\Http\Controllers\Apply;

use App\Enums\LoanApplicationStatus;
use App\Enums\WizardStep;
use App\Jobs\ProcessFakeAiVerificationJob;
use App\Models\LoanApplication;
use App\Models\User;
use App\Rules\VietnamesePhone;
use App\Services\AuditLogService;
use App\Services\UploadService;
use App\Support\VnPhone;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WizardController extends ApplicationPortalController
{
    public function personal(Request $request): View|RedirectResponse
    {
        $application = $this->currentApplication($request);
        abort_if(! $application, 404);

        if ($application->wizard_step !== WizardStep::Personal) {
            return redirect()->to($this->routeForStep($application));
        }

        return view('apply.personal', [
            'application' => $application,
            'profile' => $application->profile,
        ]);
    }

    public function savePersonal(Request $request, AuditLogService $audit): RedirectResponse
    {
        $application = $this->currentApplication($request);
        abort_if(! $application, 404);

        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'birthday' => ['required', 'date', 'before:today'],
            'gender' => ['required', 'in:male,female,other'],
            'phone' => ['required', 'string', 'max:32', new VietnamesePhone],
            'cccd_number' => ['required', 'string', 'max:32'],
            'address' => ['required', 'string', 'max:2000'],
            'province' => ['required', 'string', 'max:128'],
            'district' => ['required', 'string', 'max:128'],
            'ward' => ['required', 'string', 'max:128'],
        ]);

        $data['phone'] = VnPhone::normalize($data['phone']);

        $application->profile->fill($data);
        $application->profile->save();

        $application->user->update([
            'name' => $data['full_name'],
            'phone' => $data['phone'],
        ]);

        if ($application->status === LoanApplicationStatus::NeedsRevision) {
            $application->status = LoanApplicationStatus::InProgress;
            $application->revision_message = null;
        }

        $application->wizard_step = WizardStep::Employment;
        $application->save();

        $audit->log(User::find($application->user_id), 'application.personal_saved', LoanApplication::class, $application->id);

        return redirect()->route('apply.ref.employment', ['reference' => $application->reference]);
    }

    public function employment(Request $request): View|RedirectResponse
    {
        $application = $this->currentApplication($request);
        if (! $application) {
            return redirect()->route('apply.start');
        }

        if ($application->wizard_step !== WizardStep::Employment) {
            return redirect()->to($this->routeForStep($application));
        }

        return view('apply.employment', [
            'application' => $application,
            'profile' => $application->profile,
        ]);
    }

    public function saveEmployment(Request $request, AuditLogService $audit): RedirectResponse
    {
        $application = $this->currentApplication($request);
        abort_if(! $application, 404);

        $data = $request->validate([
            'company' => ['required', 'string', 'max:255'],
            'job_title' => ['required', 'string', 'max:255'],
            'monthly_income' => ['required', 'integer', 'min:1'],
            'bank_name' => ['required', 'string', 'max:128'],
            'bank_account' => ['required', 'string', 'max:64'],
        ]);

        $application->profile->fill($data);
        $application->profile->save();

        $application->wizard_step = WizardStep::Documents;
        $application->save();

        $audit->log(User::find($application->user_id), 'application.employment_saved', LoanApplication::class, $application->id);

        return redirect()->route('apply.ref.documents', ['reference' => $application->reference]);
    }

    public function documents(Request $request): View|RedirectResponse
    {
        $application = $this->currentApplication($request);
        if (! $application) {
            return redirect()->route('apply.start');
        }

        if ($application->wizard_step !== WizardStep::Documents) {
            return redirect()->to($this->routeForStep($application));
        }

        return view('apply.documents', [
            'application' => $application,
            'ekyc' => $application->ekyc,
        ]);
    }

    public function saveDocuments(Request $request, UploadService $uploads, AuditLogService $audit): RedirectResponse
    {
        $application = $this->currentApplication($request);
        abort_if(! $application, 404);

        $request->validate([
            'front' => ['required', 'image', 'max:'.UploadService::MAX_KB],
            'back' => ['required', 'image', 'max:'.UploadService::MAX_KB],
            'holding_front' => ['required', 'image', 'max:'.UploadService::MAX_KB],
            'holding_back' => ['required', 'image', 'max:'.UploadService::MAX_KB],
        ]);

        $ekyc = $application->ekyc;
        $ekyc->front_path = $uploads->storeEkycImage($application, $request->file('front'), 'front');
        $ekyc->back_path = $uploads->storeEkycImage($application, $request->file('back'), 'back');
        $ekyc->holding_front_path = $uploads->storeEkycImage($application, $request->file('holding_front'), 'holding_front');
        $ekyc->holding_back_path = $uploads->storeEkycImage($application, $request->file('holding_back'), 'holding_back');
        $ekyc->save();

        $application->status = LoanApplicationStatus::AiProcessing;
        $application->wizard_step = WizardStep::AiProcessing;
        $application->save();

        ProcessFakeAiVerificationJob::dispatch($application->id)->onQueue('default');

        $audit->log(User::find($application->user_id), 'application.documents_uploaded', LoanApplication::class, $application->id);

        return redirect()->route('apply.ref.ai', ['reference' => $application->reference]);
    }
}
