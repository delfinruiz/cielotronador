<?php

namespace App\Support;

/**
 * Redes sociales disponibles en el constructor.
 *
 * Las cinco redes son fijas; el bloque permite activarlas o desactivarlas sin
 * borrar los iconos, de modo que se puedan volver a mostrar desde los ajustes.
 */
final class SocialLinkLibrary
{
    /**
     * @return list<array{id: string, label: string, icon: string, url: string}>
     */
    public static function networks(): array
    {
        return [
            [
                'id' => 'linkedin',
                'label' => 'LinkedIn',
                'icon' => '/img/social/linkedin.svg',
                'url' => 'https://www.linkedin.com/',
            ],
            [
                'id' => 'youtube',
                'label' => 'YouTube',
                'icon' => '/img/social/youtube.svg',
                'url' => 'https://www.youtube.com/',
            ],
            [
                'id' => 'x',
                'label' => 'X',
                'icon' => '/img/social/x.svg',
                'url' => 'https://x.com/',
            ],
            [
                'id' => 'instagram',
                'label' => 'Instagram',
                'icon' => '/img/social/instagram.svg',
                'url' => 'https://www.instagram.com/cielotronadorthno/',
            ],
            [
                'id' => 'facebook',
                'label' => 'Facebook',
                'icon' => '/img/social/facebook.svg',
                'url' => 'https://www.facebook.com/',
            ],
        ];
    }

    /**
     * Oculta las redes desactivadas sin borrarlas del componente.
     */
    public static function css(): string
    {
        return <<<'CSS'
[data-cf-social-off] { display: none !important; }
[data-cf-social] img,
[data-cf-social-network] img { transition: filter 150ms ease; }
.dark [data-cf-social] img,
.dark [data-cf-social-network] img { filter: invert(1); }
CSS;
    }

    public static function block(bool $dark = false): string
    {
        $anchorStyle = $dark
            ? 'display:inline-flex;align-items:center;justify-content:center;width:42px;height:42px;border:1px solid var(--cf-dark-border, rgba(255,255,255,0.18));border-radius:999px;background-color:rgba(255,255,255,0.08);text-decoration:none;'
            : 'display:inline-flex;align-items:center;justify-content:center;width:42px;height:42px;border:1px solid var(--cf-border, #e4e4e7);border-radius:999px;background-color:var(--cf-surface-muted, #f8fafc);text-decoration:none;';

        $imageStyle = $dark
            ? 'display:block;width:20px;height:20px;filter:brightness(0) invert(1);'
            : 'display:block;width:20px;height:20px;';

        $links = collect(self::networks())
            ->map(fn (array $network): string => <<<HTML
                <a href="{$network['url']}" target="_blank" rel="noopener" aria-label="{$network['label']}" data-cf-social-network="{$network['id']}" style="{$anchorStyle}">
                  <img src="{$network['icon']}" alt="{$network['label']}" width="20" height="20" style="{$imageStyle}">
                </a>
                HTML)
            ->implode("\n  ");

        $tone = $dark ? 'dark' : 'light';

        return <<<HTML
        <div data-cf-social="yes" data-cf-social-tone="{$tone}" style="display:flex;flex-wrap:wrap;align-items:center;gap:10px;font-family:var(--cf-font-body, ui-sans-serif, system-ui, sans-serif);">
          {$links}
        </div>
        HTML;
    }
}
