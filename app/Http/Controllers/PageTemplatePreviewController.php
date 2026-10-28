<?php

namespace App\Http\Controllers;

use App\Models\PageTemplate;
use App\Support\LandingPagePresenter;
use Illuminate\Http\Request;

class PageTemplatePreviewController extends Controller
{
    public function __invoke(Request $request, PageTemplate $template, LandingPagePresenter $presenter)
    {
        abort_unless($request->hasValidSignature(), 403);

        $data = $presenter->data($template, $request, preview: true);

        return response()
            ->view($template->isClassic() ? 'landing' : 'landing.custom', $data)
            ->header('X-Robots-Tag', 'noindex, nofollow');
    }
}
