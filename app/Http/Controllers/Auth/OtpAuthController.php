<?php

namespace App\Http\Controllers\Auth;

use App\Enums\OtpPurpose;
use App\Http\Controllers\Controller;
use App\Rules\GmailAddress;
use App\Rules\VietnamesePhone;
use App\Services\AuditLogService;
use App\Services\OtpService;
use App\Support\VnPhone;
use App\Repositories\Contracts\LoanApplicationRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OtpAuthController extends Controller
{
    public function __construct(
        protected OtpService $otp,
        protected AuditLogService $audit,
        protected LoanApplicationRepositoryInterface $loans,
    ) {}

    public function create(Request $request): View
    {
        if ($request->has('amount')) {
            $amt = (int) $request->query('amount');
            $request->session()->put('desired_amount', max(3_000_000, min(50_000_000, $amt)));
        }

        return view('auth.otp-start', [
            'desiredAmount' => (int) $request->session()->get('desired_amount', 10_000_000),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'string', 'max:255', new GmailAddress],
            'phone' => ['required', 'string', 'max:32', new VietnamesePhone],
            'desired_amount' => ['nullable', 'integer', 'min:3000000', 'max:50000000'],
        ]);

        $request->session()->put('otp_email', $data['email']);
        $request->session()->put('otp_phone', VnPhone::normalize($data['phone']));
        if (! empty($data['desired_amount'])) {
            $request->session()->put('desired_amount', (int) $data['desired_amount']);
        }

        $this->otp->send(
            $data['email'],
            OtpPurpose::Login,
            Auth::user(),
            $request->ip(),
        );

        return redirect()->route('apply.verify.show');
    }

    public function verifyForm(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('otp_email')) {
            return redirect()->route('apply.start');
        }

        return view('auth.otp-verify', [
            'email' => $request->session()->get('otp_email'),
        ]);
    }

    public function verify(Request $request): RedirectResponse
    {
        $email = (string) $request->session()->get('otp_email');
        if ($email === '') {
            return redirect()->route('apply.start');
        }

        $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $result = $this->otp->verify($email, $request->string('code')->toString(), OtpPurpose::Login, $request->ip());
        $user = $result['user'];

        $phone = (string) $request->session()->get('otp_phone', '');
        $user->forceFill([
            'phone' => $phone !== '' ? $phone : $user->phone,
            'last_login_ip' => $request->ip(),
            'last_login_at' => now(),
        ])->save();

        $this->audit->log($user, 'auth.otp_verified_start_application', null, null, ['method' => 'otp']);

        $desired = (int) $request->session()->get('desired_amount', 10_000_000);
        $application = $this->loans->findActiveForUser($user);
        if (! $application) {
            $application = $this->loans->createDraft($user, $desired);
        } else {
            $application->access_token = \Illuminate\Support\Str::random(64);
            $application->save();
        }

        if (! $application->access_token) {
            $application->access_token = \Illuminate\Support\Str::random(64);
            $application->save();
        }

        $request->session()->forget('otp_email');
        $request->session()->put('loan_app:'.$application->reference, true);

        return redirect()->route('apply.ref.personal', [
            'reference' => $application->reference,
            't' => $application->access_token,
        ]);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
