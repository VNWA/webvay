<?php

namespace App\Http\Controllers;

use App\Models\LoanApplication;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Storage;

class ApplicationPublicSummaryController extends Controller
{
    public function show(LoanApplication $application): View
    {
        $application->load(['user', 'contract', 'profile', 'ekyc']);

        $imageUrls = $this->signedImageUrls($application);

        return view('apply.public-summary', [
            'application' => $application,
            'publicImageUrls' => $imageUrls,
        ]);
    }

    public function image(LoanApplication $application, string $field): StreamedResponse
    {
        $application->load('ekyc');
        $ekyc = $application->ekyc;
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

    /**
     * @return array<string, string|null>
     */
    private function signedImageUrls(LoanApplication $application): array
    {
        $application->loadMissing('ekyc');
        $ekyc = $application->ekyc;

        $map = [
            'front' => 'front_path',
            'back' => 'back_path',
            'holding_front' => 'holding_front_path',
            'holding_back' => 'holding_back_path',
            'selfie' => 'selfie_path',
        ];

        $out = [];
        foreach ($map as $field => $prop) {
            $path = $ekyc?->{$prop};
            $out[$field] = $path
                ? URL::temporarySignedRoute(
                    'application.public.image',
                    now()->addHours(48),
                    ['application' => $application->id, 'field' => $field],
                )
                : null;
        }

        return $out;
    }
}
