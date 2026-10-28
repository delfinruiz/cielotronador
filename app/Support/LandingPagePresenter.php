<?php

namespace App\Support;

use App\Models\CategoriaProducto;
use App\Models\Faq;
use App\Models\Galeria;
use App\Models\Marca;
use App\Models\Noticia;
use App\Models\PageTemplate;
use App\Models\PageTemplateVersion;
use App\Models\Producto;
use App\Models\Testimonio;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class LandingPagePresenter
{
    public function __construct(
        private readonly TemplateRenderer $renderer,
        private readonly TemplateSanitizer $sanitizer,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function data(PageTemplate $template, Request $request, bool $preview = false, ?PageTemplateVersion $version = null): array
    {
        $noticias = $this->noticias();
        $galeria = $this->galeria($request);

        /*
         * La vista previa muestra el borrador; la portada pública muestra la
         * última versión publicada. El fallback conserva las plantillas que ya
         * existían antes de separar borrador y publicado.
         */
        if ($version) {
            $html = (string) $version->html;
            $css = (string) $version->css;
            $project = $version->gjs_data;
        } else {
            $html = $preview
                ? (string) $template->html
                : ($template->published_html !== null ? (string) $template->published_html : (string) $template->html);

            $css = $preview
                ? (string) $template->css
                : ($template->published_css !== null ? (string) $template->published_css : (string) $template->css);

            $css = $this->withTheme(
                $css,
                $template->settings['theme_css'] ?? null,
                $template->settings['fonts'] ?? null,
            );

            $project = $preview
                ? $template->gjs_data
                : ($template->published_gjs_data ?? $template->gjs_data);
        }

        /*
         * El menú es global por defecto: si una página no tiene enlaces
         * propios, reutiliza los de la portada activa. Así no hay que copiar
         * el mismo menú en cada página.
         */
        $menuItems = $template->mainMenuItems()->get();

        if ($menuItems->isEmpty()) {
            $portada = PageTemplate::query()->active()->first();

            if ($portada && ! $portada->is($template)) {
                $menuItems = $portada->mainMenuItems()->get();
            }
        }

        $data = [
            'template' => $template,
            'menuItems' => $menuItems,
            'footerMenuItems' => $template->footerMenuItems()->get(),
            'noticias' => $noticias,
            'galeria' => $galeria,
            'productos' => $this->productos(),
            'categoriasProductos' => $this->categoriasProductos(),
            'faqs' => $this->faqs(),
            'marcas' => $this->marcas(),
            'testimonios' => $this->testimonios(),
            'cssPlantilla' => $this->sanitizer->scopeCss($css),
            'pageFonts' => TemplateFonts::resolve($template->settings['fonts'] ?? null),
            'hasCustomFooter' => $this->hasCustomFooter($template, $html, $project),
            'seo' => SeoData::for($template, $preview),
        ];

        if ($template->isGrapesjs()) {
            $data['contenido'] = $this->renderer->render($template, $data, $html);
        }

        return $data;
    }

    /**
     * Antepone el tema de la plantilla (colores y tipografías) al CSS propio
     * de la página. El tema vive en `settings`, no en la columna `css`, porque
     * GrapesJS reescribe esa columna cada vez que se guarda el diseño.
     */
    private function withTheme(string $css, mixed $themeCss, mixed $fonts): string
    {
        $theme = PageTheme::css($themeCss, $fonts);

        return $theme === '' ? $css : $theme."\n".$css;
    }

    /**
     * Detecta un pie propio aunque GrapesJS haya descartado el atributo vacío
     * al guardar. En ese caso el marcador solo queda en el proyecto.
     *
     * @param  array<string, mixed>|null  $project
     */
    private function hasCustomFooter(PageTemplate $template, ?string $html = null, ?array $project = null): bool
    {
        $html = $html ?? (string) $template->html;

        if (str_contains($html, 'data-cf-footer')) {
            return true;
        }

        $project = $project ?? $template->gjs_data;

        return is_array($project) && $this->treeHasAttribute($project, 'data-cf-footer');
    }

    /**
     * @param  array<string, mixed>  $node
     */
    private function treeHasAttribute(array $node, string $attribute): bool
    {
        $attributes = $node['attributes'] ?? null;

        if (is_array($attributes) && array_key_exists($attribute, $attributes)) {
            return true;
        }

        foreach ($node as $value) {
            if (is_array($value) && $this->treeHasAttribute($value, $attribute)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public function noticias(): Collection
    {
        return Noticia::query()
            ->publicadas()
            ->orderByDesc('fecha')
            ->get()
            ->map(fn (Noticia $noticia) => [
                'titulo' => $noticia->titulo,
                'fecha' => $noticia->fecha->format('d/m/Y'),
                'extracto' => $noticia->extracto,
                'imagen' => 'img/noticias/'.$noticia->imagen,
                'cuerpo' => array_column($noticia->cuerpo, 'parrafo'),
            ]);
    }

    public function galeria(Request $request): LengthAwarePaginator
    {
        $porPagina = 8;
        $pagina = max(1, (int) $request->query('page', 1));

        $paginator = Galeria::query()
            ->publicadas()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate($porPagina, ['*'], 'page', $pagina);

        $paginator->getCollection()->transform(
            fn (Galeria $galeria) => [
                'imagen' => 'img/galeria/'.$galeria->imagen,
                'titulo' => $galeria->titulo,
            ],
        );

        return $paginator;
    }

    /**
     * Preguntas frecuentes visibles, en el orden definido en el panel.
     *
     * @return Collection<int, Faq>
     */
    private function faqs(): Collection
    {
        return Faq::query()->activas()->orderBy('sort_order')->orderBy('id')->get();
    }

    /**
     * Marcas visibles para la franja de logos.
     *
     * @return Collection<int, Marca>
     */
    private function marcas(): Collection
    {
        return Marca::query()->activas()->orderBy('sort_order')->orderBy('id')->get();
    }

    /**
     * Testimonios visibles.
     *
     * @return Collection<int, Testimonio>
     */
    private function testimonios(): Collection
    {
        return Testimonio::query()->activos()->orderBy('sort_order')->orderBy('id')->get();
    }

    /**
     * Productos publicados que se muestran en la sección de ventas.
     *
     * @return Collection<int, Producto>
     */
    public function productos(): Collection
    {
        return Producto::query()
            ->visibles()
            ->with('categoria')
            ->orderBy('orden')
            ->orderBy('id')
            ->get();
    }

    /**
     * Categorías publicadas que agrupan al menos un producto visible.
     *
     * @return Collection<int, CategoriaProducto>
     */
    public function categoriasProductos(): Collection
    {
        return CategoriaProducto::query()
            ->publicadas()
            ->whereHas('productos', fn (Builder $query): Builder => $query->publicados())
            ->orderBy('orden')
            ->orderBy('id')
            ->get();
    }
}
