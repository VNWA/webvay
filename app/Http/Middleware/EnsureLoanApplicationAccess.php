<?php

namespace App\Http\Middleware;

use App\Models\LoanApplication;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureLoanApplicationAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $reference = (string) $request->route('reference', '');
        $application = LoanApplication::query()->where('reference', $reference)->first();
        abort_if(! $application, 404);

        $sessionKey = 'loan_app:'.$application->reference;
        $token = (string) $request->query('t', '');

        if ($application->access_token && $token !== '' && hash_equals($application->access_token, $token)) {
            $request->session()->put($sessionKey, true);
        }

        abort_unless($request->session()->get($sessionKey), 403);

        $request->attributes->set('loan_application', $application);

        return $next($request);
    }
}
