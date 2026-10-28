<?php

namespace App\Support;

/**
 * Tarjetas con imagen ampliable para el constructor.
 *
 * Cada tarjeta envuelve la foto en un botón con `data-cf-lightbox`. En la
 * página pública, `app.js` reutiliza el modal de la galería para abrir la
 * imagen en grande con flechas y teclado; en el lienzo del editor no se carga
 * ese script, así que el clic solo selecciona el componente.
 */
final class GalleryCardsWidget
{
    /**
     * CSS con la cuadrícula, el marco de las tarjetas y el efecto al pasar el
     * cursor. Se inyecta en el lienzo del editor y en la página pública.
     */
    public static function css(): string
    {
        return <<<'CSS'
[data-cf-lightbox-grid] {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: clamp(16px, 2.5vw, 24px);
}
[data-cf-lightbox-grid][data-lightbox-count="3"] {
    grid-template-columns: repeat(3, minmax(0, 1fr));
}
[data-cf-lightbox-card] {
    display: flex;
    flex-direction: column;
    overflow: hidden;
    border: 1px solid var(--cf-border, #e4e4e7);
    border-radius: 20px;
    background-color: var(--cf-surface, #ffffff);
    box-shadow: 0 12px 28px -20px rgba(0, 0, 0, .45);
}
[data-cf-lightbox-card] [data-cf-lightbox] {
    display: block;
    width: 100%;
    padding: 0;
    border: 0;
    background: none;
    cursor: zoom-in;
    overflow: hidden;
}
[data-cf-lightbox-card] [data-cf-lightbox] img {
    display: block;
    width: 100%;
    height: auto;
    aspect-ratio: 4 / 3;
    object-fit: cover;
    transition: transform .3s ease;
}
[data-cf-lightbox-card] [data-cf-lightbox]:hover img {
    transform: scale(1.05);
}
[data-cf-lightbox-card] [data-cf-lightbox]:focus-visible {
    outline: 2px solid var(--cf-brand, #ff4c00);
    outline-offset: -3px;
}
[data-cf-lightbox-body] {
    padding: 18px 20px 22px;
}
[data-cf-lightbox-body] h3 {
    margin: 0 0 8px;
    color: var(--cf-ink, #18181b);
    font-size: 18px;
    font-weight: 700;
    line-height: 1.3;
}
[data-cf-lightbox-body] p {
    margin: 0;
    color: var(--cf-text, #52525b);
    font-size: 14px;
    line-height: 1.6;
}
@media (max-width: 900px) {
    [data-cf-lightbox-grid],
    [data-cf-lightbox-grid][data-lightbox-count="3"] {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}
@media (max-width: 560px) {
    [data-cf-lightbox-grid],
    [data-cf-lightbox-grid][data-lightbox-count="3"] {
        grid-template-columns: 1fr;
    }
}
@media (prefers-reduced-motion: reduce) {
    [data-cf-lightbox-card] [data-cf-lightbox] img { transition: none; }
    [data-cf-lightbox-card] [data-cf-lightbox]:hover img { transform: none; }
}
CSS;
    }

    /**
     * Bloque completo con tres o cuatro tarjetas.
     */
    public static function block(int $count = 3): string
    {
        $count = $count === 4 ? 4 : 3;

        $tarjetas = [
            [
                'titulo' => 'Entrenamientos',
                'descripcion' => 'Sesiones por categoría en la Tortuga de Talcahuano.',
                'imagen' => 'https://picsum.photos/seed/cielo-entrenamientos/800/600',
                'alt' => 'Jugadores entrenando en la cancha',
            ],
            [
                'titulo' => 'Partidos',
                'descripcion' => 'La energía de cada jornada, dentro y fuera de la cancha.',
                'imagen' => 'https://picsum.photos/seed/cielo-partidos/800/600',
                'alt' => 'Partido del club en la cancha',
            ],
            [
                'titulo' => 'Celebraciones',
                'descripcion' => 'Los momentos que nos unen como familia del básquetbol.',
                'imagen' => 'https://picsum.photos/seed/cielo-celebraciones/800/600',
                'alt' => 'Celebración del equipo',
            ],
            [
                'titulo' => 'Torneos',
                'descripcion' => 'Torneos y actividades abiertas a toda la comunidad.',
                'imagen' => 'https://picsum.photos/seed/cielo-torneos/800/600',
                'alt' => 'Torneo del club',
            ],
        ];

        $html = '';

        foreach (array_slice($tarjetas, 0, $count) as $tarjeta) {
            $titulo = e($tarjeta['titulo']);
            $descripcion = e($tarjeta['descripcion']);
            $imagen = e($tarjeta['imagen']);
            $alt = e($tarjeta['alt']);

            $html .= <<<HTML
      <article data-cf-lightbox-card="yes">
        <button type="button" data-cf-lightbox="yes" aria-label="Ampliar imagen: {$alt}">
          <img src="{$imagen}" alt="{$alt}" loading="lazy">
        </button>
        <div data-cf-lightbox-body="yes">
          <h3>{$titulo}</h3>
          <p>{$descripcion}</p>
        </div>
      </article>
HTML;
        }

        $nombre = $count === 4 ? '4 tarjetas ampliables' : '3 tarjetas ampliables';

        return <<<HTML
<section data-cf-lightbox-gallery="yes" data-gjs-name="{$nombre}" style="padding:clamp(48px,7vw,80px) 24px;background-color:var(--cf-surface, #ffffff);font-family:var(--cf-font-body, ui-sans-serif, system-ui, sans-serif);">
  <div style="max-width:1100px;margin:0 auto;">
    <div style="max-width:640px;margin:0 auto 40px;text-align:center;">
      <p style="margin:0 0 12px;color:var(--cf-brand, #ff4c00);font-size:13px;font-weight:700;letter-spacing:.2em;text-transform:uppercase;">Galería</p>
      <h2 style="margin:0 0 16px;color:var(--cf-ink, #18181b);font-size:clamp(28px,5.5vw,40px);line-height:1.1;font-weight:800;">Nuestros momentos</h2>
      <p style="margin:0;color:var(--cf-text, #52525b);font-size:17px;line-height:1.7;">Haz clic en una imagen para verla en grande.</p>
    </div>
    <div data-cf-lightbox-grid="yes" data-lightbox-count="{$count}">
{$html}
    </div>
  </div>
</section>
HTML;
    }
}
