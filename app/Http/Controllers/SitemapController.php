<?php

namespace App\Http\Controllers;

use App\Models\PageTemplate;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Sitemap de la portada pública y las páginas legales.
     *
     * Las noticias y la galería viven en la portada, por eso no tienen una URL
     * propia todavía. Cuando existan páginas independientes se agregan aquí.
     */
    public function __invoke(): Response
    {
        $template = PageTemplate::activeOrClassic();

        $lastmod = $template->updated_at?->toAtomString() ?? now()->toAtomString();

        $urls = [
            [
                'loc' => route('landing'),
                'lastmod' => $lastmod,
                'changefreq' => 'weekly',
                'priority' => '1.0',
            ],
            [
                'loc' => route('politica.privacidad'),
                'lastmod' => $lastmod,
                'changefreq' => 'yearly',
                'priority' => '0.3',
            ],
            [
                'loc' => route('politica.cookies'),
                'lastmod' => $lastmod,
                'changefreq' => 'yearly',
                'priority' => '0.3',
            ],
            [
                'loc' => route('politica.datos'),
                'lastmod' => $lastmod,
                'changefreq' => 'yearly',
                'priority' => '0.3',
            ],
        ];

        /*
         * Páginas publicadas que no son la portada. La portada activa ya está
         * incluida arriba con la URL `/`; así no duplicamos su slug.
         */
        PageTemplate::query()
            ->grapesjs()
            ->whereNotNull('published_at')
            ->where('is_active', false)
            ->orderBy('slug')
            ->get(['slug', 'published_at', 'updated_at'])
            ->each(function (PageTemplate $pagina) use (&$urls, $lastmod): void {
                $urls[] = [
                    'loc' => route('page-templates.show', ['template' => $pagina->slug]),
                    'lastmod' => ($pagina->published_at ?? $pagina->updated_at)?->toAtomString() ?? $lastmod,
                    'changefreq' => 'monthly',
                    'priority' => '0.7',
                ];
            });

        return response()
            ->view('sitemap', ['urls' => $urls], 200, [
                'Content-Type' => 'application/xml; charset=UTF-8',
            ]);
    }
}
