<?php

namespace App\Support;

/**
 * Tarjetas de "Nuestro equipo" para el constructor.
 *
 * Cada miembro muestra una fotografía circular, su nombre, su puesto y enlaces
 * opcionales a Instagram, LinkedIn, X y su web o portafolio. La foto es una
 * tarjeta de presentación, no una galería: no abre el visor de imágenes.
 *
 * Los campos se editan desde Settings → Miembro del equipo. Las redes vacías
 * se ocultan con `data-directiva-link-off` y no dejan huecos en la tarjeta.
 */
final class DirectivaWidget
{
    /**
     * Redes y enlaces que puede tener cada miembro.
     *
     * @var list<array{id: string, label: string, icon: string}>
     */
    private const LINKS = [
        ['id' => 'instagram', 'label' => 'Instagram', 'icon' => '/img/social/instagram.svg'],
        ['id' => 'facebook', 'label' => 'Facebook', 'icon' => '/img/social/facebook.svg'],
        ['id' => 'linkedin', 'label' => 'LinkedIn', 'icon' => '/img/social/linkedin.svg'],
        ['id' => 'x', 'label' => 'X', 'icon' => '/img/social/x.svg'],
        ['id' => 'web', 'label' => 'Sitio web', 'icon' => '/img/social/web.svg'],
    ];

    /**
     * Cuadrícula, tarjetas y enlaces. Se inyecta en el lienzo y en la página.
     */
    public static function css(): string
    {
        return <<<'CSS'
[data-cf-directiva] .cf-directiva-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: clamp(16px, 2.5vw, 28px);
    max-width: 1100px;
    margin: 0 auto;
}
[data-cf-directiva] [data-directiva-card] {
    padding: clamp(24px, 4vw, 32px);
    border: 1px solid var(--cf-border, #e4e4e7);
    border-radius: 20px;
    background-color: var(--cf-surface-muted, #f8fafc);
    text-align: center;
    transition: transform .2s ease, border-color .2s ease, box-shadow .2s ease;
}
[data-cf-directiva] [data-directiva-card]:hover {
    transform: translateY(-4px);
    border-color: var(--cf-brand-border, #fed7aa);
    box-shadow: 0 18px 35px -25px rgba(0, 0, 0, .45);
}
[data-cf-directiva] [data-directiva-avatar] {
    display: block;
    width: clamp(96px, 12vw, 120px);
    height: clamp(96px, 12vw, 120px);
    margin: 0 auto 20px;
    padding: 0;
    border: 4px solid var(--cf-brand-soft, #fff7ed);
    border-radius: 999px;
    background-color: var(--cf-brand-soft, #fff7ed);
    overflow: hidden;
}
[data-cf-directiva] [data-directiva-avatar] img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
}
[data-cf-directiva] [data-directiva-avatar][data-directiva-avatar-plain] {
    border: 0;
    border-radius: 0;
    background-color: transparent;
    overflow: visible;
    filter: drop-shadow(0 10px 15px rgba(0, 0, 0, .15));
}
[data-cf-directiva] [data-directiva-avatar][data-directiva-avatar-plain] img {
    border-radius: 0;
    transition: transform .25s ease;
}
[data-cf-directiva] [data-directiva-card]:hover [data-directiva-avatar-plain] img {
    transform: rotate(12deg) scale(1.1);
}
[data-cf-directiva] [data-directiva-card] h3 {
    margin: 0 0 6px;
    color: var(--cf-ink, #18181b);
    font-size: 18px;
    font-weight: 700;
    line-height: 1.3;
}
[data-cf-directiva] [data-directiva-card] p {
    margin: 0;
    color: var(--cf-brand-ink, #c2410c);
    font-size: 12px;
    font-weight: 700;
    letter-spacing: .14em;
    text-transform: uppercase;
}
[data-cf-directiva] [data-directiva-social] {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 8px;
    margin-top: 18px;
}
[data-cf-directiva] [data-directiva-social]:not(:has([data-directiva-link]:not([data-directiva-link-off]))) {
    display: none;
}
[data-cf-directiva] [data-directiva-link] {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 34px;
    height: 34px;
    border: 1px solid var(--cf-border, #e4e4e7);
    border-radius: 999px;
    background-color: var(--cf-surface, #ffffff);
    text-decoration: none;
    transition: transform .2s ease, border-color .2s ease, background-color .2s ease;
}
[data-cf-directiva] [data-directiva-link]:hover {
    transform: translateY(-2px);
    border-color: var(--cf-brand-border, #fed7aa);
    background-color: var(--cf-brand-soft, #fff7ed);
}
[data-cf-directiva] [data-directiva-link]:focus-visible {
    outline: 2px solid var(--cf-brand, #ff4c00);
    outline-offset: 2px;
}
[data-cf-directiva] [data-directiva-link] img {
    display: block;
    width: 16px;
    height: 16px;
}
[data-cf-directiva] [data-directiva-link-off] {
    display: none !important;
}
.dark [data-cf-directiva] [data-directiva-link] img {
    filter: invert(1);
}
@media (max-width: 820px) {
    [data-cf-directiva] .cf-directiva-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
}
@media (max-width: 520px) {
    [data-cf-directiva] .cf-directiva-grid { grid-template-columns: 1fr; }
}
@media (prefers-reduced-motion: reduce) {
    [data-cf-directiva] [data-directiva-card],
    [data-cf-directiva] [data-directiva-link],
    [data-cf-directiva] [data-directiva-avatar] img { transition: none; }
    [data-cf-directiva] [data-directiva-card]:hover,
    [data-cf-directiva] [data-directiva-link]:hover,
    [data-cf-directiva] [data-directiva-card]:hover [data-directiva-avatar-plain] img { transform: none; }
}
CSS;
    }

    /**
     * Bloque completo con el encabezado y los miembros.
     *
     * @param  list<array{nombre: string, puesto: string, foto?: string}>|null  $members
     */
    public static function block(
        string $kicker = 'Nuestro equipo',
        string $title = 'Las personas que hacen el club',
        string $intro = 'Conoce a quienes trabajan cada día para que el básquetbol siga creciendo.',
        ?array $members = null,
        bool $classic = false,
    ): string {
        $members ??= [
            ['nombre' => 'Jacqueline Reyes', 'puesto' => 'Directora'],
            ['nombre' => 'Angélica Fuente', 'puesto' => 'Secretaria'],
            ['nombre' => 'Bertita Vergara', 'puesto' => 'Tesorera'],
        ];

        $kicker = e($kicker);
        $title = e($title);
        $introHtml = $intro !== ''
            ? '<p style="margin:0;color:var(--cf-text, #52525b);font-size:17px;line-height:1.7;">'.e($intro).'</p>'
            : '';
        $sectionPadding = $classic
            ? 'padding:0 24px clamp(48px,7vw,80px);'
            : 'padding:clamp(48px,7vw,80px) 24px;';
        $titleStyle = $classic
            ? "font-family:'Oswald',var(--font-heading, ui-sans-serif), system-ui, sans-serif;font-size:clamp(24px,4.5vw,30px);letter-spacing:.02em;text-transform:uppercase;"
            : '';

        $enlaces = '';

        foreach (self::LINKS as $link) {
            $id = e($link['id']);
            $label = e($link['label']);
            $icon = e($link['icon']);

            $enlaces .= <<<HTML
          <a href="" data-directiva-link="{$id}" data-directiva-link-off="yes" target="_blank" rel="noopener" aria-label="{$label}">
            <img src="{$icon}" alt="{$label}" width="16" height="16">
          </a>
HTML;
        }

        $html = '';

        foreach ($members as $index => $miembro) {
            $nombre = e($miembro['nombre']);
            $puesto = e($miembro['puesto']);
            $foto = e($miembro['foto'] ?? '/img/equipo/avatar.svg');
            $plain = $classic ? ' data-directiva-avatar-plain="yes"' : '';
            $delay = $index * 150;

            $html .= <<<HTML
      <article data-directiva-card="yes" data-reveal data-reveal-delay="{$delay}">
        <div data-directiva-avatar="yes"{$plain}>
          <img src="{$foto}" alt="{$nombre}, {$puesto}" loading="lazy">
        </div>
        <h3 data-directiva-name="yes">{$nombre}</h3>
        <p data-directiva-role="yes">{$puesto}</p>
        <div data-directiva-social="yes">
{$enlaces}
        </div>
      </article>
HTML;
        }

        return <<<HTML
<section data-cf-directiva="yes" data-gjs-name="Nuestro equipo" style="{$sectionPadding}background-color:var(--cf-surface, #ffffff);font-family:var(--cf-font-body, ui-sans-serif, system-ui, sans-serif);">
  <div style="max-width:1100px;margin:0 auto;">
    <div style="max-width:640px;margin:0 auto 40px;text-align:center;">
      <p style="margin:0 0 12px;color:var(--cf-brand, #ff4c00);font-size:13px;font-weight:700;letter-spacing:.2em;text-transform:uppercase;">{$kicker}</p>
      <h2 style="margin:0 0 16px;color:var(--cf-ink, #18181b);font-size:clamp(28px,5.5vw,40px);line-height:1.1;font-weight:800;{$titleStyle}">{$title}</h2>
      {$introHtml}
    </div>
    <div class="cf-directiva-grid">
{$html}
    </div>
  </div>
</section>
HTML;
    }
}
