<?php

namespace App\Support;

/**
 * Bloque de cuatro contadores para el constructor.
 *
 * Cada contador guarda sus datos en atributos `data-counter-*` para que el
 * editor pueda cambiarlos desde Settings y la página pública los muestre tal
 * cual, con una animación numérica opcional al entrar en pantalla.
 */
final class StatsWidget
{
    /**
     * CSS que no puede ir en línea: la cuadrícula adaptable y las tarjetas.
     */
    public static function css(): string
    {
        return <<<'CSS'
[data-cf-stats] .cf-stats-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: clamp(16px, 3vw, 28px);
    max-width: 1100px;
    margin: 0 auto;
}
[data-cf-stats] [data-counter] {
    padding: clamp(18px, 3vw, 28px) clamp(14px, 2.5vw, 22px);
    border: 1px solid var(--cf-border, #e4e4e7);
    border-radius: 20px;
    background-color: var(--cf-surface-muted, #f8fafc);
    text-align: center;
}
[data-cf-stats] .cf-counter-value {
    margin: 0 0 8px;
    color: var(--cf-brand, #ff4c00);
    font-size: clamp(32px, 6vw, 48px);
    font-weight: 800;
    line-height: 1;
    letter-spacing: -0.02em;
}
[data-cf-stats] .cf-counter-label {
    margin: 0;
    color: var(--cf-text, #52525b);
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
}
@media (max-width: 900px) {
    [data-cf-stats] .cf-stats-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
}
@media (max-width: 480px) {
    [data-cf-stats] .cf-stats-grid { grid-template-columns: 1fr; }
}
CSS;
    }

    public static function block(): string
    {
        $contadores = [
            ['prefix' => '', 'value' => '120', 'suffix' => '+', 'label' => 'Jugadores'],
            ['prefix' => '', 'value' => '35', 'suffix' => '', 'label' => 'Años de historia'],
            ['prefix' => '', 'value' => '12', 'suffix' => '', 'label' => 'Categorías'],
            ['prefix' => '', 'value' => '8', 'suffix' => '', 'label' => 'Títulos'],
        ];

        $html = '';

        foreach ($contadores as $contador) {
            $prefix = e($contador['prefix']);
            $value = e($contador['value']);
            $suffix = e($contador['suffix']);
            $label = e($contador['label']);

            $html .= <<<HTML
        <div data-counter="yes"
             data-counter-prefix="{$prefix}"
             data-counter-value="{$value}"
             data-counter-suffix="{$suffix}"
             data-counter-label="{$label}">
          <p class="cf-counter-value"><span class="cf-counter-prefix">{$prefix}</span><span class="cf-counter-number">{$value}</span><span class="cf-counter-suffix">{$suffix}</span></p>
          <p class="cf-counter-label">{$label}</p>
        </div>
HTML;
        }

        return <<<HTML
<section data-cf-stats="yes" style="padding:clamp(48px,7vw,80px) 24px;background-color:var(--cf-surface, #ffffff);font-family:var(--cf-font-body, ui-sans-serif, system-ui, sans-serif);">
  <div class="cf-stats-grid">
{$html}
  </div>
</section>
HTML;
    }
}
