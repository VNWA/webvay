<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function __invoke(Request $request): View
    {
        $desired = (int) $request->query('amount', 10_000_000);
        $desired = max(3_000_000, min(50_000_000, $desired));

        return view('landing', [
            'desiredAmount' => $desired,
        ]);
    }
}
