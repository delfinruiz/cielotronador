<?php

namespace App\Support;

/**
 * Bloque "Video" para el constructor.
 *
 * Guarda solo la URL y los ajustes en atributos `data-video-*`. La página
 * pública muestra una portada con botón de reproducción y, al pulsar, el
 * JavaScript inserta el iframe del proveedor. Así el HTML guardado no contiene
 * iframes (el saneador los elimina) y el video no carga hasta que se pide.
 */
final class VideoWidget
{
    public const DEFAULT_URL = 'https://www.youtube.com/watch?v=aqz-KE-bpKQ';

    public const DEFAULT_TITLE = 'Video de presentación';

    public static function css(): string
    {
        return <<<'CSS'
[data-cf-video] [data-video-frame] {
    position: relative;
    margin: 0;
    overflow: hidden;
    border: 1px solid var(--cf-border, #e4e4e7);
    border-radius: 24px;
    background-color: var(--cf-surface-gray, #f4f4f5);
    box-shadow: 0 25px 60px -45px rgba(15, 23, 42, .65);
}
[data-cf-video][data-video-ratio="4/3"] [data-video-frame] { aspect-ratio: 4 / 3; }
[data-cf-video][data-video-ratio="1/1"] [data-video-frame] { aspect-ratio: 1 / 1; }
[data-cf-video][data-video-ratio="9/16"] [data-video-frame] { aspect-ratio: 9 / 16; }
[data-cf-video][data-video-ratio="9/16"] [data-video-frame] { max-width: 420px; margin: 0 auto; }
[data-cf-video][data-video-ratio="16/9"] [data-video-frame],
[data-cf-video]:not([data-video-ratio]) [data-video-frame] { aspect-ratio: 16 / 9; }
[data-cf-video] [data-video-play] {
    position: absolute;
    inset: 0;
    z-index: 1;
    width: 100%;
    height: 100%;
    border: 0;
    padding: 0;
    background: transparent;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}
[data-cf-video] [data-video-play]:focus-visible {
    outline: 3px solid var(--cf-brand, #ff4c00);
    outline-offset: -3px;
}
[data-cf-video] [data-video-poster] {
    position: absolute;
    inset: 0;
    background-image: linear-gradient(135deg, rgba(15, 23, 42, .85), rgba(15, 23, 42, .35));
    background-size: cover;
    background-position: center;
    transition: transform .35s ease;
}
[data-cf-video] [data-video-play]:hover [data-video-poster] { transform: scale(1.03); }
[data-cf-video] [data-video-play-icon] {
    position: relative;
    z-index: 2;
    display: inline-flex;
    width: clamp(56px, 8vw, 84px);
    height: clamp(56px, 8vw, 84px);
    align-items: center;
    justify-content: center;
    border-radius: 9999px;
    background-color: var(--cf-brand, #ff4c00);
    color: var(--cf-on-brand, #ffffff);
    font-size: clamp(20px, 3vw, 30px);
    line-height: 1;
    box-shadow: 0 18px 40px -18px rgba(0, 0, 0, .65);
    transition: transform .2s ease, filter .2s ease;
}
[data-cf-video] [data-video-play]:hover [data-video-play-icon] { transform: scale(1.08); filter: brightness(1.05); }
[data-cf-video] [data-video-frame] iframe {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    border: 0;
}
[data-cf-video] [data-video-caption] {
    margin: 14px 0 0;
    color: var(--cf-muted, #71717a);
    font-size: 14px;
    text-align: center;
}
@media (prefers-reduced-motion: reduce) {
    [data-cf-video] [data-video-poster],
    [data-cf-video] [data-video-play-icon] { transition: none; }
}
CSS;
    }

    public static function block(): string
    {
        $url = e(self::DEFAULT_URL);
        $title = e(self::DEFAULT_TITLE);

        return <<<HTML
<section data-cf-video="yes" data-gjs-name="Video"
         data-video-url="{$url}" data-video-provider="auto" data-video-ratio="16/9"
         data-video-title="{$title}" data-video-poster-url=""
         style="padding:clamp(40px,6vw,72px) 24px;background-color:var(--cf-surface, #ffffff);font-family:var(--cf-font-body, ui-sans-serif, system-ui, sans-serif);">
  <div style="max-width:960px;margin:0 auto;">
    <figure data-video-frame="yes" style="margin:0;">
      <button type="button" data-video-play="yes" aria-label="Reproducir video: {$title}">
        <span data-video-poster="yes" aria-hidden="true"></span>
        <span data-video-play-icon="yes" aria-hidden="true">▶</span>
      </button>
      <figcaption data-video-caption="yes">{$title}</figcaption>
    </figure>
  </div>
</section>
HTML;
    }
}
