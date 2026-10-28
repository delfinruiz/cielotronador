<?php

namespace App\Http\Controllers;

use App\Models\CategoriaProducto;
use App\Models\Faq;
use App\Models\Marca;
use App\Models\MenuItem;
use App\Models\PageTemplate;
use App\Models\Producto;
use App\Models\Testimonio;
use App\Support\PageTheme;
use App\Support\Presets\PresetLibrary;
use App\Support\SeoData;
use App\Support\TemplateFonts;
use App\Support\TemplateRenderer;
use App\Support\TemplateSanitizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

/**
 * Muestra una plantilla de inicio tal como quedará antes de crearla.
 *
 * No guarda nada: arma un PageTemplate en memoria y reutiliza la vista
 * pública, así la vista previa y la página real se ven igual.
 */
class PresetPreviewController extends Controller
{
    public function __invoke(
        Request $request,
        string $preset,
        TemplateSanitizer $sanitizer,
        TemplateRenderer $renderer,
    ) {
        Gate::authorize('create', PageTemplate::class);

        $preset = PresetLibrary::find($preset);

        abort_unless($preset, 404);

        $settings = $preset->settings();

        $template = new PageTemplate([
            'name' => $preset->name(),
            'slug' => $preset->slug(),
            'description' => $preset->tagline(),
            'render_mode' => 'grapesjs',
            'settings' => $settings,
        ]);

        $menuItems = collect($preset->menu())
            ->values()
            ->map(static fn (array $item): MenuItem => new MenuItem([
                'location' => 'main',
                'label' => $item['label'],
                'link_type' => 'anchor',
                'link_value' => $item['value'],
                'target' => '_self',
                'is_visible' => true,
            ]));

        $html = $sanitizer->html($preset->html());

        $faqs = Faq::query()->activas()->orderBy('sort_order')->orderBy('id')->get();
        $marcas = Marca::query()->activas()->orderBy('sort_order')->orderBy('id')->get();
        $testimonios = Testimonio::query()->activos()->orderBy('sort_order')->orderBy('id')->get();
        $productos = Producto::query()->visibles()->with('categoria')->orderBy('orden')->orderBy('id')->get();
        $categoriasProductos = CategoriaProducto::query()
            ->publicadas()
            ->whereHas('productos', fn ($query) => $query->publicados())
            ->orderBy('orden')
            ->orderBy('id')
            ->get();

        $css = PageTheme::css($settings['theme_css'] ?? null, $settings['fonts'] ?? null)."\n".$preset->css();
        $fonts = $settings['fonts'] ?? null;

        return response()
            ->view('landing.custom', [
                'template' => $template,
                'menuItems' => $menuItems,
                'footerMenuItems' => collect(),
                'noticias' => collect(),
                'galeria' => collect(),
                'productos' => $productos,
                'categoriasProductos' => $categoriasProductos,
                'faqs' => $faqs,
                'marcas' => $marcas,
                'testimonios' => $testimonios,
                'contenido' => $renderer->render($template, [
                    'faqs' => $faqs,
                    'marcas' => $marcas,
                    'testimonios' => $testimonios,
                    'productos' => $productos,
                    'categoriasProductos' => $categoriasProductos,
                ], $html),
                'cssPlantilla' => $sanitizer->scopeCss($css),
                'pageFonts' => TemplateFonts::resolve($fonts),
                'hasCustomFooter' => str_contains($html, 'data-cf-footer'),
                'seo' => SeoData::for($template),
            ])
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }
}
