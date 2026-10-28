<?php

namespace App\Support;

/**
 * Decoración de fondo con parallax para las secciones del constructor.
 *
 * La sección solo declara `data-cf-parallax="soft|strong"`. Al renderizar la
 * página se inserta una capa decorativa con las líneas de la cancha y un
 * balón; `app.js` mueve esas piezas al hacer scroll reutilizando el mismo
 * mecanismo de la portada clásica (`data-parallax`).
 *
 * La capa se genera fuera del HTML guardado para no duplicarla en cada bloque,
 * no ensuciar el lienzo y poder cambiarla en un solo sitio. `none` deja la
 * sección preparada para activarla después sin insertar nada.
 */
final class ParallaxWidget
{
    public const ATTRIBUTE = 'data-cf-parallax';

    /**
     * Opciones que muestra el ajuste "Fondo parallax" del editor.
     *
     * @return list<array{id: string, label: string}>
     */
    public static function options(): array
    {
        return [
            ['id' => 'none', 'label' => 'Sin efecto'],
            ['id' => 'soft', 'label' => 'Suave'],
            ['id' => 'strong', 'label' => 'Intenso'],
        ];
    }

    /**
     * Posiciona la capa detrás del contenido y recorta la decoración.
     */
    public static function css(): string
    {
        return <<<'CSS'
[data-cf-parallax]:not([data-cf-parallax="none"]) {
    position: relative;
    overflow: hidden;
    isolation: isolate;
}
[data-cf-parallax]:not([data-cf-parallax="none"]) > [data-cf-parallax-layer] {
    position: absolute;
    inset: -12% -6%;
    z-index: 0;
    overflow: hidden;
    pointer-events: none;
    color: var(--cf-brand, #ff4c00);
    opacity: .10;
}
[data-cf-parallax][data-cf-parallax-tone="light"] > [data-cf-parallax-layer] {
    color: #ffffff;
}
[data-cf-parallax]:not([data-cf-parallax="none"]) > [data-cf-parallax-layer] .cf-parallax-court {
    position: absolute;
    inset: 0;
    will-change: transform;
}
[data-cf-parallax]:not([data-cf-parallax="none"]) > [data-cf-parallax-layer] .cf-parallax-court svg {
    display: block;
    width: 100%;
    height: 100%;
}
[data-cf-parallax]:not([data-cf-parallax="none"]) > [data-cf-parallax-layer] .cf-parallax-ball {
    position: absolute;
    right: 8%;
    bottom: 8%;
    width: clamp(72px, 9vw, 128px);
    will-change: transform;
}
[data-cf-parallax]:not([data-cf-parallax="none"]) > [data-cf-parallax-layer] .cf-parallax-ball svg {
    display: block;
    width: 100%;
    height: auto;
}
@media (prefers-reduced-motion: reduce) {
    [data-cf-parallax]:not([data-cf-parallax="none"]) > [data-cf-parallax-layer] {
        opacity: .08;
    }
}
CSS;
    }

    /**
     * Capa decorativa completa. `soft` y `strong` cambian la velocidad.
     */
    public static function layer(string $intensity = 'soft'): string
    {
        $intensity = $intensity === 'strong' ? 'strong' : 'soft';

        [$courtSpeed, $ballSpeed] = $intensity === 'strong'
            ? ['0.18', '-0.12']
            : ['0.08', '-0.06'];

        $courtDirection = $intensity === 'strong' ? '0.05' : '0.03';
        $ballDirection = $intensity === 'strong' ? '-0.05' : '-0.03';

        return <<<HTML
<div data-cf-parallax-layer="yes" aria-hidden="true">
  <div class="cf-parallax-court" data-parallax="{$courtSpeed}" data-parallax-x="{$courtDirection}">
    <svg viewBox="0 0 1440 800" fill="none" preserveAspectRatio="xMidYMid slice">
      <circle cx="1120" cy="400" r="230" stroke="currentColor" stroke-width="2"/>
      <circle cx="1120" cy="400" r="150" stroke="currentColor" stroke-width="1.5"/>
      <line x1="1120" y1="0" x2="1120" y2="800" stroke="currentColor" stroke-width="1.5"/>
      <path d="M 240 800 A 520 520 0 0 1 1320 800" stroke="currentColor" stroke-width="2"/>
      <circle cx="780" cy="800" r="220" stroke="currentColor" stroke-width="1.5"/>
      <g transform="translate(1210, 110)" stroke="currentColor" stroke-width="2.5">
        <circle r="120"/>
        <path d="M0 -120 V 120"/>
        <path d="M-120 0 H 120"/>
        <path d="M-41 -113 c24 37 24 76 0 113"/>
        <path d="M41 -113 c-24 37 -24 76 0 113"/>
      </g>
      <circle cx="330" cy="160" r="5" fill="currentColor" stroke="none"/>
      <circle cx="1400" cy="640" r="5" fill="currentColor" stroke="none"/>
    </svg>
  </div>
  <div class="cf-parallax-ball" data-parallax="{$ballSpeed}" data-parallax-x="{$ballDirection}">
    <svg viewBox="0 0 64 64" fill="none">
      <g stroke="currentColor" stroke-width="2.5">
        <circle cx="32" cy="32" r="30"/>
        <path d="M32 2v60"/>
        <path d="M2 32h60"/>
        <path d="M13 10c7.5 7 7.5 37 0 44"/>
        <path d="M51 10c-7.5 7-7.5 37 0 44"/>
      </g>
    </svg>
  </div>
</div>
HTML;
    }

    /**
     * Inserta la capa después de abrir cada etiqueta marcada con soft/strong.
     *
     * Se ejecuta después de sustituir las secciones dinámicas para que también
     * funcione un envoltorio con `data-cf-parallax` alrededor de
     * `data-cf-section`. No toca `none` ni las secciones sin atributo.
     */
    public static function inject(string $html): string
    {
        if (! str_contains($html, self::ATTRIBUTE)) {
            return $html;
        }

        $resultado = preg_replace_callback(
            '~<([a-z][a-z0-9]*)\b([^>]*\bdata-cf-parallax\s*=\s*(["\'])(soft|strong)\3[^>]*)>(?!\s*<div\b[^>]*data-cf-parallax-layer)~i',
            static function (array $match): string {
                return $match[0]."\n".self::layer($match[4]);
            },
            $html,
        );

        return $resultado ?? $html;
    }
}
