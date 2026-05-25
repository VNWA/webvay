<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\Contract;
use App\Models\LoanApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminPrivateFileController extends Controller
{
    public function ekyc(Request $request, LoanApplication $loanApplication, string $field): StreamedResponse
    {
        abort_unless($request->user()?->role === UserRole::Admin, 403);

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

    public function contractPdf(Request $request, Contract $contract): StreamedResponse
    {
        abort_unless($request->user()?->role === UserRole::Admin, 403);

        $path = $contract->pdf_path;
        abort_unless($path && Storage::disk('local')->exists($path), 404);

        return Storage::disk('local')->response($path, 'contract-'.$contract->code.'.pdf', [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
