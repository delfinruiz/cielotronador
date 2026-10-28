<?php

namespace App\Support;

/**
 * Bloque "Carrusel" para el constructor.
 *
 * Carrusel de imágenes con pie (título, texto y botón) por diapositiva.
 * Las diapositivas se configuran con `data-slide-*` y el carrusel con
 * `data-carousel-*` (autoplay, intervalo, flechas y puntos). El giro, las
 * flechas y los puntos los mueve el JavaScript de la página pública; sin JS
 * el carrusel sigue siendo desplazable por scroll-snap.
 */
final class CarouselWidget
{
    /**
     * Máximo de imágenes (diapositivas) que admite un carrusel.
     */
    public const MAX_SLIDES = 5;

    public static function css(): string
    {
        return <<<'CSS'
[data-carousel-track] { scrollbar-width: none; }
[data-carousel-track]::-webkit-scrollbar { display: none; }

[data-cf-carousel][data-carousel-arrows="no"] [data-carousel-prev],
[data-cf-carousel][data-carousel-arrows="no"] [data-carousel-next] { display: none; }

[data-cf-carousel][data-carousel-dots="no"] [data-carousel-dots] { display: none; }

[data-cf-carousel] [data-carousel-slide] [data-carousel-text][data-carousel-text-off],
[data-cf-carousel] [data-carousel-slide] [data-carousel-cta][data-carousel-cta-off] { display: none; }

[data-cf-carousel] [data-carousel-dot] {
    width: .5rem; height: .5rem; padding: 0; border: 0; border-radius: 9999px;
    background-color: var(--cf-border-strong, #d4d4d8);
    cursor: pointer; transition: background-color .2s ease, width .2s ease;
}
[data-cf-carousel] [data-carousel-dot].active {
    background-color: var(--cf-brand, #ff4c00);
    width: 1.75rem;
}

[data-cf-carousel] [data-carousel-slide] { aspect-ratio: 16 / 9; }

@media (max-width: 640px) {
    [data-cf-carousel] [data-carousel-slide] { aspect-ratio: 4 / 5; }
}
CSS;
    }

    public static function block(): string
    {
        $slides = collect(self::slides())
            ->map(fn (array $datos): string => self::slide($datos))
            ->implode("\n");

        $dots = self::dots(count(self::slides()));

        return <<<HTML
<section data-cf-carousel="yes" data-gjs-name="Carrusel de imágenes"
     data-carousel-autoplay="yes" data-carousel-interval="5000"
     data-carousel-arrows="yes" data-carousel-dots="yes"
     data-reveal="up" data-reveal-trigger="scroll" data-reveal-delay="0"
     style="max-width:1100px;margin:0 auto;padding:0 clamp(12px,3vw,24px);font-family:var(--cf-font-body, ui-sans-serif, system-ui, sans-serif);">
  <div data-carousel-viewport="yes" style="position:relative;">
    <div data-carousel-track="yes" style="display:flex;overflow-x:auto;scroll-snap-type:x mandatory;border-radius:20px;box-shadow:0 15px 35px -15px rgba(0,0,0,0.28);">
{$slides}    </div>
    <button type="button" data-carousel-prev="yes" aria-label="Anterior"
            style="position:absolute;top:50%;left:12px;transform:translateY(-50%);z-index:2;width:2.5rem;height:2.5rem;display:inline-flex;align-items:center;justify-content:center;border:0;border-radius:9999px;background-color:rgba(15,23,42,0.55);color:var(--cf-on-dark, #ffffff);font-size:1.5rem;line-height:1;cursor:pointer;">‹</button>
    <button type="button" data-carousel-next="yes" aria-label="Siguiente"
            style="position:absolute;top:50%;right:12px;transform:translateY(-50%);z-index:2;width:2.5rem;height:2.5rem;display:inline-flex;align-items:center;justify-content:center;border:0;border-radius:9999px;background-color:rgba(15,23,42,0.55);color:var(--cf-on-dark, #ffffff);font-size:1.5rem;line-height:1;cursor:pointer;">›</button>
  </div>
  <div data-carousel-dots="yes" style="display:flex;justify-content:center;gap:.5rem;padding:.75rem 0;">
{$dots}  </div>
</section>
HTML;
    }

    /**
     * @return list<array{imagen: string, alt: string, titulo: string, texto: string, boton: string, enlace: string}>
     */
    private static function slides(): array
    {
        $lorem = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';

        return [
            [
                'imagen' => 'https://picsum.photos/seed/cielo-slide-1/1600/900',
                'alt' => 'Imagen principal',
                'titulo' => 'Título principal',
                'texto' => $lorem,
                'boton' => 'Ver más',
                'enlace' => '#contacto',
            ],
            [
                'imagen' => 'https://picsum.photos/seed/cielo-slide-2/1600/900',
                'alt' => 'Imagen secundaria',
                'titulo' => 'Segundo titular',
                'texto' => $lorem,
                'boton' => 'Saber más',
                'enlace' => '#contacto',
            ],
            [
                'imagen' => 'https://picsum.photos/seed/cielo-slide-3/1600/900',
                'alt' => 'Imagen secundaria',
                'titulo' => 'Tercer titular',
                'texto' => $lorem,
                'boton' => 'Contáctanos',
                'enlace' => '#contacto',
            ],
        ];
    }

    /**
     * @param  array{imagen: string, alt: string, titulo: string, texto: string, boton: string, enlace: string}  $datos
     */
    private static function slide(array $datos): string
    {
        $imagen = e($datos['imagen']);
        $alt = e($datos['alt']);
        $titulo = e($datos['titulo']);
        $texto = e($datos['texto']);
        $boton = e($datos['boton']);
        $enlace = e($datos['enlace']);

        return <<<HTML
    <figure data-carousel-slide="yes" data-gjs-name="Diapositiva"
         data-slide-image="{$imagen}"
         data-slide-title="{$titulo}"
         data-slide-text="{$texto}"
         data-slide-cta-text="{$boton}"
         data-slide-cta-url="{$enlace}"
         style="flex:0 0 100%;scroll-snap-align:center;margin:0;position:relative;overflow:hidden;background-color:var(--cf-dark-surface, #0f172a);">
      <img data-carousel-image="yes" src="{$imagen}" alt="{$alt}" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;display:block;">
      <figcaption data-carousel-caption="yes" style="position:absolute;left:0;right:0;bottom:0;padding:clamp(18px,4vw,28px) clamp(16px,4vw,24px);background-image:linear-gradient(to top, rgba(0,0,0,0.72), rgba(0,0,0,0));">
        <h3 data-carousel-title-text="yes" style="margin:0 0 8px;font-size:clamp(20px,5vw,28px);font-weight:800;line-height:1.2;color:var(--cf-on-dark, #ffffff);">{$titulo}</h3>
        <p data-carousel-text="yes" data-carousel-text-text="yes" style="margin:0 0 16px;max-width:44ch;font-size:clamp(13px,3.5vw,15px);line-height:1.6;color:var(--cf-on-dark-muted, #d4d4d8);">{$texto}</p>
        <a data-carousel-cta="yes" href="{$enlace}" style="display:inline-flex;background-color:var(--cf-brand, #ff4c00);color:var(--cf-on-brand, #ffffff);padding:12px 22px;border-radius:999px;font-weight:700;font-size:14px;text-decoration:none;"><span data-carousel-cta-text="yes">{$boton}</span></a>
      </figcaption>
    </figure>
HTML;
    }

    private static function dots(int $total): string
    {
        $html = '';

        for ($index = 0; $index < $total; $index++) {
            $activa = $index === 0 ? ' active' : '';
            $html .= '    <button type="button" data-carousel-dot="yes" class="cf-carousel-dot'.$activa.'" aria-label="Ir a la diapositiva '.($index + 1).'"></button>'."\n";
        }

        return rtrim($html, "\n");
    }
}
