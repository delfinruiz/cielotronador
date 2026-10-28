<?php

namespace App\Http\Controllers;

use App\Models\PageTemplate;
use App\Models\PageTemplateVersion;
use App\Support\LandingPagePresenter;
use Illuminate\Http\Request;

class PageTemplateVersionPreviewController extends Controller
{
    public function __invoke(
        Request $request,
        PageTemplate $template,
        PageTemplateVersion $version,
        LandingPagePresenter $presenter,
    ) {
        abort_unless($request->hasValidSignature(), 403);
        abort_unless((int) $version->page_template_id === (int) $template->getKey(), 404);

        $data = $presenter->data($template, $request, preview: true, version: $version);

        return response()
            ->view($template->isClassic() ? 'landing' : 'landing.custom', $data)
            ->header('X-Robots-Tag', 'noindex, nofollow');
    }
}
