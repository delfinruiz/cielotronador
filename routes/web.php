<?php

use App\Http\Controllers\BackupDownloadController;
use App\Http\Controllers\BrandingAssetController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\PageTemplatePageController;
use App\Http\Controllers\PageTemplatePreviewController;
use App\Http\Controllers\PageTemplateVersionPreviewController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\PresetPreviewController;
use App\Http\Controllers\SitemapController;
use App\Models\PageTemplate;
use HansDeBoeck\Grapesjs\Http\Controllers\EditorController;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;

Route::get('/', LandingController::class)->name('landing');

/*
 * Logos y favicon configurables. La URL es estable para que el HTML de las
 * plantillas guardadas siga mostrando el archivo actual.
 */
Route::get('/branding/{asset}', BrandingAssetController::class)
    ->whereIn('asset', ['logo-light', 'logo-dark', 'favicon'])
    ->name('branding.asset');

Route::get('/constructor/plantilla/{template}', function (PageTemplate $template) {
    Gate::authorize('update', $template);

    if (! request()->has('v')) {
        return redirect()->to(route('page-templates.editor', [
            'template' => $template,
            'v' => @filemtime(resource_path('views/vendor/grapesjs/editor.blade.php')) ?: '1',
        ]));
    }

    $view = app(EditorController::class)->bewerk('plantilla', $template->getKey());

    return response($view->render())
        ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
        ->header('Pragma', 'no-cache')
        ->header('Expires', '0');
})->middleware('auth')->name('page-templates.editor');

Route::post('/constructor/plantilla/{template}/publicar', function (PageTemplate $template) {
    Gate::authorize('update', $template);

    $template->publish();

    return response()->json([
        'publicado' => true,
        'published_at' => $template->published_at?->toIso8601String(),
    ]);
})->middleware('auth')->name('page-templates.publish');

/*
 * La URL antigua del constructor queda redirigida a la nueva para que las
 * pestañas o marcadores viejos no sigan sirviendo una página cacheada.
 */
Route::get('/grapesjs/{soort}/{sleutel}', function (string $soort, string $sleutel) {
    if ($soort !== 'plantilla') {
        abort(404);
    }

    return redirect()->to(route('page-templates.editor', [
        'template' => $sleutel,
        'v' => @filemtime(resource_path('views/vendor/grapesjs/editor.blade.php')) ?: '1',
    ]));
})->middleware('auth')->name('page-templates.editor.legacy');

Route::get('/preview/plantilla/{template:slug}', PageTemplatePreviewController::class)
    ->middleware('signed')
    ->name('page-templates.preview');

Route::get('/admin/plantillas/{preset}/vista-previa', PresetPreviewController::class)
    ->middleware('auth')
    ->name('presets.preview');

Route::get('/preview/plantilla/{template:slug}/version/{version}', PageTemplateVersionPreviewController::class)
    ->middleware('signed')
    ->name('page-templates.preview-version');

Route::post('/contacto', ContactController::class)
    ->middleware('throttle:contacto')
    ->name('contacto');

Route::get('/admin/seguridad/respaldos/{file}', BackupDownloadController::class)
    ->middleware(['auth', 'signed'])
    ->name('security.backups.download');

Route::get('/politica-de-privacidad', [PolicyController::class, 'privacidad'])->name('politica.privacidad');
Route::get('/politica-de-cookies', [PolicyController::class, 'cookies'])->name('politica.cookies');
Route::get('/politica-de-datos', [PolicyController::class, 'datos'])->name('politica.datos');

Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

Route::get('/robots.txt', function () {
    $sitemap = url('/sitemap.xml');

    return response(
        "User-agent: *\n"
        ."Disallow: /admin\n"
        ."Disallow: /constructor\n"
        ."Disallow: /grapesjs\n"
        ."Disallow: /preview\n\n"
        ."Sitemap: {$sitemap}\n",
        200,
        ['Content-Type' => 'text/plain; charset=UTF-8'],
    );
})->name('robots');

/*
 * Página pública por slug. Va al final para no interceptar rutas específicas
 * (panel, preview, constructor, políticas, sitemap o robots).
 */
Route::get('/{template:slug}', PageTemplatePageController::class)
    ->where('template', '^(?!admin$|api$|preview$|constructor$|grapesjs$|up$|politica-de-privacidad$|politica-de-cookies$|politica-de-datos$)[A-Za-z0-9_-]+$')
    ->name('page-templates.show');
