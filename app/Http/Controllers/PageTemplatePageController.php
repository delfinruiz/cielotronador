<?php

namespace App\Http\Controllers;

use App\Models\PageTemplate;
use App\Support\LandingPagePresenter;
use Illuminate\Http\Request;

class PageTemplatePageController extends Controller
{
    public function __invoke(Request $request, PageTemplate $template, LandingPagePresenter $presenter)
    {
        abort_unless($template->isGrapesjs(), 404);
        abort_unless($template->isPublished(), 404);

        /*
         * La portada activa ya se sirve en `/`; evitamos contenido duplicado
         * redirigiendo su slug a la URL principal.
         */
        if ($template->is_active) {
            return redirect()->route('landing', [], 301);
        }

        $data = $presenter->data($template, $request);

        return view('landing.custom', $data);
    }
}
