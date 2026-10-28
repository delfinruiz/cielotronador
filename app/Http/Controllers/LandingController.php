<?php

namespace App\Http\Controllers;

use App\Models\PageTemplate;
use App\Support\LandingPagePresenter;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function __invoke(Request $request, LandingPagePresenter $presenter)
    {
        $template = PageTemplate::activeOrClassic();

        if ($request->header('X-Partial') === 'galeria') {
            return view('landing.partials.galeria', [
                'galeria' => $presenter->galeria($request),
            ]);
        }

        $data = $presenter->data($template, $request);

        return view($template->isClassic() ? 'landing' : 'landing.custom', $data);
    }
}
