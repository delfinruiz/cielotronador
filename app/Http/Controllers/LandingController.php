<?php

namespace App\Http\Controllers;

use App\Models\Galeria;
use App\Models\Noticia;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class LandingController extends Controller
{
    public function __invoke(Request $request)
    {
        $galeria = $this->galeriaPaginada($request);

        if ($request->header('X-Partial') === 'galeria') {
            return view('landing.partials.galeria', [
                'galeria' => $galeria,
            ]);
        }

        return view('landing', [
            'galeria' => $galeria,
            'noticias' => $this->noticiasPublicadas(),
        ]);
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    private function noticiasPublicadas(): Collection
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

    private function galeriaPaginada(Request $request): LengthAwarePaginator
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
}
