<?php

namespace App\Support;

use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;

class TemplateSanitizer
{
    private HtmlSanitizer $sanitizer;

    public function __construct()
    {
        $config = (new HtmlSanitizerConfig)
            ->allowSafeElements()
            ->allowRelativeLinks()
            ->allowRelativeMedias()
            ->allowLinkSchemes(['http', 'https', 'mailto', 'tel'])
            ->allowMediaSchemes(['http', 'https', 'data'])
            ->allowElement('form', ['method', 'action', 'data-wa-form', 'data-form-form'])
            ->allowElement('input', ['type', 'name', 'id', 'required', 'autocomplete', 'placeholder', 'value', 'tabindex', 'aria-label', 'data-wa-input', 'data-form-token'])
            ->allowElement('textarea', ['name', 'id', 'required', 'rows', 'placeholder', 'aria-label'])
            ->allowAttribute('class', allowedElements: '*')
            ->allowAttribute('id', allowedElements: '*')
            ->allowAttribute('style', allowedElements: '*')
            ->allowAttribute('data-cf-section', allowedElements: '*')
            ->allowAttribute('data-cf-option-columns', allowedElements: '*')
            ->allowAttribute('data-cf-option-open-first', allowedElements: '*')
            ->allowAttribute('data-cf-option-speed', allowedElements: '*')
            ->allowAttribute('data-cf-option-grayscale', allowedElements: '*')
            ->allowAttribute('data-cf-option-pause', allowedElements: '*')
            ->allowAttribute('data-cf-option-invert', allowedElements: '*')
            ->allowAttribute('data-cf-option-layout', allowedElements: '*')
            ->allowAttribute('data-cf-option-autoplay', allowedElements: '*')
            ->allowAttribute('data-cf-option-interval', allowedElements: '*')
            ->allowAttribute('data-cf-option-arrows', allowedElements: '*')
            ->allowAttribute('data-cf-option-dots', allowedElements: '*')
            ->allowAttribute('data-cf-video', allowedElements: '*')
            ->allowAttribute('data-video-url', allowedElements: '*')
            ->allowAttribute('data-video-provider', allowedElements: '*')
            ->allowAttribute('data-video-ratio', allowedElements: '*')
            ->allowAttribute('data-video-title', allowedElements: '*')
            ->allowAttribute('data-video-poster', allowedElements: '*')
            ->allowAttribute('data-video-poster-url', allowedElements: '*')
            ->allowAttribute('data-video-frame', allowedElements: '*')
            ->allowAttribute('data-video-play', allowedElements: '*')
            ->allowAttribute('data-video-play-icon', allowedElements: '*')
            ->allowAttribute('data-video-caption', allowedElements: '*')
            ->allowAttribute('data-cf-stats', allowedElements: '*')
            ->allowAttribute('data-cf-lightbox', allowedElements: '*')
            ->allowAttribute('data-cf-lightbox-grid', allowedElements: '*')
            ->allowAttribute('data-cf-lightbox-gallery', allowedElements: '*')
            ->allowAttribute('data-cf-lightbox-card', allowedElements: '*')
            ->allowAttribute('data-cf-lightbox-body', allowedElements: '*')
            ->allowAttribute('data-lightbox-count', allowedElements: '*')
            ->allowAttribute('data-cf-directiva', allowedElements: '*')
            ->allowAttribute('data-directiva-card', allowedElements: '*')
            ->allowAttribute('data-directiva-avatar', allowedElements: '*')
            ->allowAttribute('data-directiva-avatar-plain', allowedElements: '*')
            ->allowAttribute('data-directiva-name', allowedElements: '*')
            ->allowAttribute('data-directiva-role', allowedElements: '*')
            ->allowAttribute('data-directiva-social', allowedElements: '*')
            ->allowAttribute('data-directiva-link', allowedElements: '*')
            ->allowAttribute('data-directiva-link-off', allowedElements: '*')
            ->allowAttribute('data-counter', allowedElements: '*')
            ->allowAttribute('data-counter-prefix', allowedElements: '*')
            ->allowAttribute('data-counter-value', allowedElements: '*')
            ->allowAttribute('data-counter-suffix', allowedElements: '*')
            ->allowAttribute('data-counter-label', allowedElements: '*')
            ->allowAttribute('data-reveal', allowedElements: '*')
            ->allowAttribute('data-reveal-delay', allowedElements: '*')
            ->allowAttribute('data-reveal-trigger', allowedElements: '*')
            ->allowAttribute('data-cf-parallax', allowedElements: '*')
            ->allowAttribute('data-cf-parallax-layer', allowedElements: '*')
            ->allowAttribute('data-cf-parallax-tone', allowedElements: '*')
            ->allowAttribute('data-parallax', allowedElements: '*')
            ->allowAttribute('data-parallax-x', allowedElements: '*')
            ->allowAttribute('data-cf-loop', allowedElements: '*')
            ->allowAttribute('data-top-button', allowedElements: '*')
            ->allowAttribute('data-top-enabled', allowedElements: '*')
            ->allowAttribute('data-cf-footer', allowedElements: '*')
            ->allowAttribute('data-brand-logo-light', allowedElements: '*')
            ->allowAttribute('data-brand-logo-dark', allowedElements: '*')
            ->allowAttribute('data-cf-social', allowedElements: '*')
            ->allowAttribute('data-cf-social-network', allowedElements: '*')
            ->allowAttribute('data-cf-social-tone', allowedElements: '*')
            ->allowAttribute('data-cf-social-off', allowedElements: '*')
            ->allowAttribute('data-cf-whatsapp', allowedElements: '*')
            ->allowAttribute('data-wa-widget', allowedElements: '*')
            ->allowAttribute('data-wa-number', allowedElements: '*')
            ->allowAttribute('data-wa-title', allowedElements: '*')
            ->allowAttribute('data-wa-message', allowedElements: '*')
            ->allowAttribute('data-wa-panel', allowedElements: '*')
            ->allowAttribute('data-wa-toggle', allowedElements: '*')
            ->allowAttribute('data-wa-close', allowedElements: '*')
            ->allowAttribute('data-wa-form', allowedElements: '*')
            ->allowAttribute('data-wa-input', allowedElements: '*')
            ->allowAttribute('data-wa-title-text', allowedElements: '*')
            ->allowAttribute('data-wa-message-text', allowedElements: '*')
            ->allowAttribute('data-wa-button', allowedElements: '*')
            ->allowAttribute('data-wa-open', allowedElements: '*')
            ->allowAttribute('data-cf-product', allowedElements: '*')
            ->allowAttribute('data-product-layout', allowedElements: '*')
            ->allowAttribute('data-product-grid', allowedElements: '*')
            ->allowAttribute('data-product-name', allowedElements: '*')
            ->allowAttribute('data-product-description', allowedElements: '*')
            ->allowAttribute('data-product-price', allowedElements: '*')
            ->allowAttribute('data-product-currency', allowedElements: '*')
            ->allowAttribute('data-product-price-note', allowedElements: '*')
            ->allowAttribute('data-product-old-price', allowedElements: '*')
            ->allowAttribute('data-product-badge-text', allowedElements: '*')
            ->allowAttribute('data-product-badge-enabled', allowedElements: '*')
            ->allowAttribute('data-product-cta-text', allowedElements: '*')
            ->allowAttribute('data-product-url', allowedElements: '*')
            ->allowAttribute('data-product-image-url', allowedElements: '*')
            ->allowAttribute('data-product-image-alt', allowedElements: '*')
            ->allowAttribute('data-product-badge', allowedElements: '*')
            ->allowAttribute('data-product-badge-off', allowedElements: '*')
            ->allowAttribute('data-product-badge-label', allowedElements: '*')
            ->allowAttribute('data-product-currency-off', allowedElements: '*')
            ->allowAttribute('data-product-price-note-off', allowedElements: '*')
            ->allowAttribute('data-product-old-price-off', allowedElements: '*')
            ->allowAttribute('data-product-name-text', allowedElements: '*')
            ->allowAttribute('data-product-description-text', allowedElements: '*')
            ->allowAttribute('data-product-price-text', allowedElements: '*')
            ->allowAttribute('data-product-currency-text', allowedElements: '*')
            ->allowAttribute('data-product-price-note-text', allowedElements: '*')
            ->allowAttribute('data-product-old-price-text', allowedElements: '*')
            ->allowAttribute('data-product-cta-text', allowedElements: '*')
            ->allowAttribute('data-product-cta', allowedElements: '*')
            ->allowAttribute('data-product-cta-empty', allowedElements: '*')
            ->allowAttribute('data-product-image', allowedElements: '*')
            ->allowAttribute('data-cf-carousel', allowedElements: '*')
            ->allowAttribute('data-carousel-viewport', allowedElements: '*')
            ->allowAttribute('data-carousel-track', allowedElements: '*')
            ->allowAttribute('data-carousel-slide', allowedElements: '*')
            ->allowAttribute('data-carousel-caption', allowedElements: '*')
            ->allowAttribute('data-carousel-image', allowedElements: '*')
            ->allowAttribute('data-carousel-title-text', allowedElements: '*')
            ->allowAttribute('data-carousel-text', allowedElements: '*')
            ->allowAttribute('data-carousel-text-off', allowedElements: '*')
            ->allowAttribute('data-carousel-text-text', allowedElements: '*')
            ->allowAttribute('data-carousel-cta', allowedElements: '*')
            ->allowAttribute('data-carousel-cta-off', allowedElements: '*')
            ->allowAttribute('data-carousel-cta-text', allowedElements: '*')
            ->allowAttribute('data-carousel-prev', allowedElements: '*')
            ->allowAttribute('data-carousel-next', allowedElements: '*')
            ->allowAttribute('data-carousel-dots', allowedElements: '*')
            ->allowAttribute('data-carousel-dot', allowedElements: '*')
            ->allowAttribute('data-carousel-autoplay', allowedElements: '*')
            ->allowAttribute('data-carousel-interval', allowedElements: '*')
            ->allowAttribute('data-carousel-arrows', allowedElements: '*')
            ->allowAttribute('data-slide-image', allowedElements: '*')
            ->allowAttribute('data-slide-title', allowedElements: '*')
            ->allowAttribute('data-slide-text', allowedElements: '*')
            ->allowAttribute('data-slide-cta-text', allowedElements: '*')
            ->allowAttribute('data-slide-cta-url', allowedElements: '*')
            ->allowAttribute('data-cf-form', allowedElements: '*')
            ->allowAttribute('data-form-title', allowedElements: '*')
            ->allowAttribute('data-form-description', allowedElements: '*')
            ->allowAttribute('data-form-label-nombre', allowedElements: '*')
            ->allowAttribute('data-form-label-email', allowedElements: '*')
            ->allowAttribute('data-form-label-telefono', allowedElements: '*')
            ->allowAttribute('data-form-label-mensaje', allowedElements: '*')
            ->allowAttribute('data-form-telefono', allowedElements: '*')
            ->allowAttribute('data-form-button', allowedElements: '*')
            ->allowAttribute('data-form-success', allowedElements: '*')
            ->allowAttribute('data-form-upgraded', allowedElements: '*')
            ->allowAttribute('data-form-title-text', allowedElements: '*')
            ->allowAttribute('data-form-description-text', allowedElements: '*')
            ->allowAttribute('data-form-label-nombre-text', allowedElements: '*')
            ->allowAttribute('data-form-label-email-text', allowedElements: '*')
            ->allowAttribute('data-form-label-telefono-text', allowedElements: '*')
            ->allowAttribute('data-form-label-mensaje-text', allowedElements: '*')
            ->allowAttribute('data-form-button-text', allowedElements: '*')
            ->allowAttribute('data-form-telefono-row', allowedElements: '*')
            ->allowAttribute('data-form-token', allowedElements: '*')
            ->allowAttribute('aria-disabled', allowedElements: '*')
            ->allowAttribute('aria-controls', allowedElements: '*')
            ->allowAttribute('aria-expanded', allowedElements: '*')
            ->allowAttribute('aria-modal', allowedElements: '*')
            ->allowAttribute('role', allowedElements: '*')
            ->allowAttribute('aria-label', allowedElements: '*')
            ->allowAttribute('aria-hidden', allowedElements: '*')
            ->allowAttribute('target', allowedElements: 'a')
            ->allowAttribute('rel', allowedElements: 'a')
            ->withMaxInputLength(500000);

        $this->sanitizer = new HtmlSanitizer($config);
    }

    public function html(?string $html): string
    {
        if (blank($html)) {
            return '';
        }

        return $this->sanitizer->sanitize($html);
    }

    public function css(?string $css): string
    {
        if (blank($css)) {
            return '';
        }

        $css = preg_replace('~/\*.*?\*/~s', '', $css) ?? $css;
        $css = preg_replace('/<\/?style[^>]*>/i', '', $css) ?? $css;
        $css = preg_replace('/@import\b[^;]*;?/i', '', $css) ?? $css;
        $css = preg_replace('/expression\s*\(/i', '', $css) ?? $css;
        $css = preg_replace('/javascript\s*:/i', '', $css) ?? $css;
        $css = preg_replace('/behavior\s*:/i', '', $css) ?? $css;
        $css = preg_replace('/-moz-binding\s*:/i', '', $css) ?? $css;

        return mb_substr($css, 0, 500000);
    }

    /**
     * Aísla el CSS de una plantilla para que no altere el shell del sitio.
     */
    public function scopeCss(?string $css, string $scope = '#cf-template'): string
    {
        $css = $this->css($css);

        if ($css === '') {
            return '';
        }

        return trim($this->scopeBlock($css, $scope));
    }

    private function scopeBlock(string $css, string $scope): string
    {
        $out = '';
        $buffer = '';
        $length = strlen($css);
        $index = 0;

        while ($index < $length) {
            $character = $css[$index];

            if ($character !== '{') {
                if ($character !== '}') {
                    $buffer .= $character;
                }

                $index++;

                continue;
            }

            $selector = trim($buffer);
            $buffer = '';
            $depth = 1;
            $cursor = $index + 1;

            while ($cursor < $length && $depth > 0) {
                if ($css[$cursor] === '{') {
                    $depth++;
                } elseif ($css[$cursor] === '}') {
                    $depth--;
                }

                $cursor++;
            }

            $body = substr($css, $index + 1, $cursor - $index - 2);

            if (preg_match('/^@(media|supports|layer|container)\b/i', $selector)) {
                $out .= $selector.'{'.$this->scopeBlock($body, $scope).'}';
            } elseif (preg_match('/^@(keyframes|-webkit-keyframes|font-face|page|charset|namespace)\b/i', $selector)) {
                $out .= $selector.'{'.$body.'}';
            } else {
                $out .= $this->prefixSelectors($selector, $scope).'{'.$body.'}';
            }

            $index = $cursor;
        }

        return $out.$buffer;
    }

    private function prefixSelectors(string $selector, string $scope): string
    {
        return collect(explode(',', $selector))
            ->map(function (string $part) use ($scope): string {
                $part = trim($part);

                if ($part === '') {
                    return '';
                }

                if ($part === '*' || strtolower($part) === 'html') {
                    return $scope.' *';
                }

                if (in_array(strtolower($part), ['body', ':root'], true)) {
                    return $scope;
                }

                /*
                 * El modo oscuro vive en `<html class="dark">`, fuera del
                 * contenedor de la plantilla. Sin este caso, `.dark body`
                 * quedaría como `#cf-template .dark body` y nunca aplicaría.
                 */
                if (preg_match('/^(?:html)?\.dark\b(.*)$/i', $part, $coincidencia) === 1) {
                    $resto = trim($coincidencia[1]);

                    if (in_array(strtolower($resto), ['', 'body', ':root', 'html'], true)) {
                        return 'html.dark '.$scope;
                    }

                    return 'html.dark '.$scope.' '.$resto;
                }

                if (str_starts_with($part, $scope)) {
                    return $part;
                }

                return $scope.' '.$part;
            })
            ->filter()
            ->implode(', ');
    }
}
