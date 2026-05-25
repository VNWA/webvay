<?php

namespace App\Http\Controllers;

use App\Models\LoanApplication;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ApplicationLookupController extends Controller
{
    public function create(): View
    {
        return view('apply.lookup');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'reference' => ['required', 'string', 'max:64'],
            'email' => ['required', 'email'],
        ]);

        $user = User::query()->where('email', $validated['email'])->first();
        if (! $user) {
            return redirect()
                ->route('application.lookup.create')
                ->withInput($request->only('reference', 'email'))
                ->withErrors(['email' => __('lookup_no_match')]);
        }

        $application = LoanApplication::query()
            ->where('user_id', $user->id)
            ->where('reference', $validated['reference'])
            ->first();

        if (! $application) {
            return redirect()
                ->route('application.lookup.create')
                ->withInput($request->only('reference', 'email'))
                ->withErrors(['reference' => __('lookup_no_match')]);
        }

        $request->session()->put('lookup_dossier_application_id', $application->id);
        $request->session()->put('lookup_dossier_expires_at', now()->addHour()->timestamp);

        return redirect()->route('application.lookup.dossier');
    }

    public function dossier(Request $request): View
    {
        $id = (int) $request->session()->get('lookup_dossier_application_id', 0);
        $exp = (int) $request->session()->get('lookup_dossier_expires_at', 0);
        abort_unless($id > 0 && $exp > 0 && now()->timestamp <= $exp, 403);

        $application = LoanApplication::query()
            ->with(['user', 'contract', 'profile', 'ekyc'])
            ->findOrFail($id);

        return view('apply.lookup-dossier', [
            'application' => $application,
        ]);
    }

    public function lookupImage(Request $request, LoanApplication $loanApplication, string $field): StreamedResponse
    {
        $id = (int) $request->session()->get('lookup_dossier_application_id', 0);
        $exp = (int) $request->session()->get('lookup_dossier_expires_at', 0);
        abort_unless($id === (int) $loanApplication->id && $exp > 0 && now()->timestamp <= $exp, 403);

        $loanApplication->load('ekyc');
        $ekyc = $loanApplication->ekyc;
        abort_unless($ekyc, 404);

        $path = match ($field) {
            'front' => $ekyc->front_path,
            'back' => $ekyc->back_path,
            'holding_front' => $ekyc->holding_front_path,
            'holding_back' => $ekyc->holding_back_path,
            'selfie' => $ekyc->selfie_path,
            default => null,
        };

        abort_unless($path && Storage::disk('local')->exists($path), 404);

        return Storage::disk('local')->response($path);
    }
}
