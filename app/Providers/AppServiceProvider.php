<?php

namespace App\Providers;

use App\Repositories\Contracts\LoanApplicationRepositoryInterface;
use App\Repositories\Contracts\OtpCodeRepositoryInterface;
use App\Repositories\Eloquent\LoanApplicationRepository;
use App\Repositories\Eloquent\OtpCodeRepository;
use Carbon\Carbon;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(LoanApplicationRepositoryInterface::class, LoanApplicationRepository::class);
        $this->app->bind(OtpCodeRepositoryInterface::class, OtpCodeRepository::class);
    }

    public function boot(): void
    {
        Carbon::setLocale(config('app.locale'));

        if (str_starts_with((string) config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }

        RateLimiter::for('otp-send', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        RateLimiter::for('otp-verify', function (Request $request) {
            $key = (string) ($request->session()->get('otp_email') ?: $request->ip());

            return Limit::perMinute(10)->by($key);
        });
    }
}
