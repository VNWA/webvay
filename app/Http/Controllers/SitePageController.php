<?php

namespace App\Http\Controllers;

use App\Models\SitePage;
use Illuminate\Contracts\View\View;

class SitePageController extends Controller
{
    public function about(): View
    {
        return $this->render('gioi-thieu');
    }

    public function services(): View
    {
        return $this->render('dich-vu');
    }

    protected function render(string $slug): View
    {
        $page = SitePage::query()->published()->where('slug', $slug)->firstOrFail();

        return view('pages.site', [
            'page' => $page,
        ]);
    }
}
