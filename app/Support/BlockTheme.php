<?php

namespace App\Support;

/**
 * Tokens de color que comparten los bloques del constructor y la página pública.
 *
 * Los bloques se guardan con estilos en línea, así que un color fijo no puede
 * reaccionar al modo oscuro. Al usar `var(--cf-*)`, el mismo HTML cambia de
 * tema sin necesidad de volver a editarlo.
 */
final class BlockTheme
{
    /**
     * @var array<string, string>
     */
    private const LIGHT = [
        '--cf-surface' => '#ffffff',
        '--cf-surface-muted' => '#f8fafc',
        '--cf-surface-gray' => '#f4f4f5',
        '--cf-ink' => '#18181b',
        '--cf-ink-soft' => '#3f3f46',
        '--cf-text' => '#52525b',
        '--cf-muted' => '#71717a',
        '--cf-border' => '#e4e4e7',
        '--cf-border-strong' => '#d4d4d8',
        '--cf-brand' => '#ff4c00',
        '--cf-brand-light' => '#fb923c',
        '--cf-brand-ink' => '#c2410c',
        '--cf-brand-soft' => '#fff7ed',
        '--cf-brand-border' => '#fed7aa',
        '--cf-on-brand' => '#ffffff',
        '--cf-on-dark' => '#ffffff',
        '--cf-on-dark-muted' => '#d4d4d8',
        '--cf-on-dark-subtle' => '#a1a1aa',
        '--cf-dark-surface' => '#0f172a',
        '--cf-dark-border' => 'rgba(255,255,255,0.18)',
        '--cf-info-bg' => '#eff6ff',
        '--cf-info-border' => '#bfdbfe',
        '--cf-info-ink' => '#1e3a8a',
        '--cf-info-accent' => '#3b82f6',
        '--cf-chat-bg' => '#ece5dd',
        '--cf-chat-bubble' => '#ffffff',
        '--cf-chat-footer' => '#f0f2f5',
        '--cf-chat-input' => '#ffffff',
    ];

    /**
     * @var array<string, string>
     */
    private const DARK = [
        '--cf-surface' => '#18181b',
        '--cf-surface-muted' => '#131316',
        '--cf-surface-gray' => '#1f1f23',
        '--cf-ink' => '#fafafa',
        '--cf-ink-soft' => '#e4e4e7',
        '--cf-text' => '#d4d4d8',
        '--cf-muted' => '#a1a1aa',
        '--cf-border' => '#3f3f46',
        '--cf-border-strong' => '#52525b',
        '--cf-brand' => '#ff4c00',
        '--cf-brand-light' => '#fb923c',
        '--cf-brand-ink' => '#ffa874',
        '--cf-brand-soft' => 'rgba(255,76,0,0.14)',
        '--cf-brand-border' => 'rgba(255,76,0,0.45)',
        '--cf-on-brand' => '#ffffff',
        '--cf-on-dark' => '#ffffff',
        '--cf-on-dark-muted' => '#d4d4d8',
        '--cf-on-dark-subtle' => '#a1a1aa',
        '--cf-dark-surface' => '#0f172a',
        '--cf-dark-border' => 'rgba(255,255,255,0.18)',
        '--cf-info-bg' => 'rgba(59,130,246,0.12)',
        '--cf-info-border' => 'rgba(59,130,246,0.40)',
        '--cf-info-ink' => '#bfdbfe',
        '--cf-info-accent' => '#60a5fa',
        '--cf-chat-bg' => '#0b141a',
        '--cf-chat-bubble' => '#202c33',
        '--cf-chat-footer' => '#1f2c34',
        '--cf-chat-input' => '#2a3942',
    ];

    /**
     * Colores neutros de fondo que deben seguir el tema.
     *
     * @var array<string, string>
     */
    private const BACKGROUND_MAP = [
        '#ffffff' => 'var(--cf-surface)',
        '#f8fafc' => 'var(--cf-surface-muted)',
        '#f4f4f5' => 'var(--cf-surface-gray)',
        '#0f172a' => 'var(--cf-dark-surface)',
        '#ff4c00' => 'var(--cf-brand)',
        '#fff7ed' => 'var(--cf-brand-soft)',
        '#eff6ff' => 'var(--cf-info-bg)',
    ];

    /**
     * Colores de texto. Los que viven sobre fondos oscuros fijos no cambian.
     *
     * @var array<string, string>
     */
    private const COLOR_MAP = [
        '#ffffff' => 'var(--cf-on-dark)',
        '#f8fafc' => 'var(--cf-on-dark)',
        '#fff7ed' => 'var(--cf-on-dark)',
        '#d4d4d8' => 'var(--cf-on-dark-muted)',
        '#e4e4e7' => 'var(--cf-on-dark-muted)',
        '#a1a1aa' => 'var(--cf-on-dark-subtle)',
        '#18181b' => 'var(--cf-ink)',
        '#3f3f46' => 'var(--cf-ink-soft)',
        '#52525b' => 'var(--cf-text)',
        '#71717a' => 'var(--cf-muted)',
        '#ff4c00' => 'var(--cf-brand)',
        '#fb923c' => 'var(--cf-brand-light)',
        '#c2410c' => 'var(--cf-brand-ink)',
        '#1e3a8a' => 'var(--cf-info-ink)',
    ];

    /**
     * @var array<string, string>
     */
    private const BORDER_MAP = [
        '#e4e4e7' => 'var(--cf-border)',
        '#d4d4d8' => 'var(--cf-border-strong)',
        '#fed7aa' => 'var(--cf-brand-border)',
        '#bfdbfe' => 'var(--cf-info-border)',
        '#3b82f6' => 'var(--cf-info-accent)',
        '#ff4c00' => 'var(--cf-brand)',
    ];

    /**
     * CSS con los valores para el tema claro y el oscuro.
     */
    public static function css(): string
    {
        return self::declarations(':root', self::LIGHT)
            ."\n"
            .self::declarations('.dark', self::DARK);
    }

    /**
     * @return array<string, string>
     */
    public static function tokens(bool $dark = false): array
    {
        return $dark ? self::DARK : self::LIGHT;
    }

    /**
     * Colores que se ofrecen como muestras en el selector de color del editor.
     *
     * @return list<string>
     */
    public static function palette(): array
    {
        $claves = [
            '--cf-brand',
            '--cf-brand-light',
            '--cf-brand-ink',
            '--cf-brand-soft',
            '--cf-ink',
            '--cf-ink-soft',
            '--cf-text',
            '--cf-muted',
            '--cf-surface',
            '--cf-surface-muted',
            '--cf-surface-gray',
            '--cf-dark-surface',
        ];

        return array_values(array_unique(array_map(
            static fn (string $clave): string => self::LIGHT[$clave],
            $claves,
        )));
    }

    /**
     * Convierte los colores fijos de una hoja de estilos en tokens.
     */
    public static function transformCss(string $css): string
    {
        if (trim($css) === '') {
            return $css;
        }

        // El prefijo permite encontrar también la primera declaración.
        $result = preg_replace_callback(
            '/([;{])(\s*)([-a-z]+)\s*:\s*([^;}{]+)/i',
            function (array $match): string {
                $value = trim($match[4]);
                $transformed = self::transformValue(strtolower($match[3]), $value);

                if ($transformed === $value) {
                    return $match[0];
                }

                return $match[1].$match[2].$match[3].':'.$transformed;
            },
            ';'.$css,
        ) ?? $css;

        return substr($result, 1);
    }

    /**
     * Convierte los `style` en línea del HTML guardado.
     */
    public static function transformHtml(string $html): string
    {
        if ($html === '') {
            return $html;
        }

        return preg_replace_callback(
            '/\bstyle\s*=\s*(["\'])(.*?)\1/is',
            fn (array $match): string => 'style='.$match[1].self::transformCss($match[2]).$match[1],
            $html,
        ) ?? $html;
    }

    /**
     * Convierte los estilos del proyecto de GrapesJS.
     *
     * @param  array<int, array<string, mixed>>  $styles
     * @return array<int, array<string, mixed>>
     */
    public static function transformStyles(array $styles): array
    {
        foreach ($styles as $index => $rule) {
            if (! is_array($rule['style'] ?? null)) {
                continue;
            }

            foreach ($rule['style'] as $property => $value) {
                if (is_string($value)) {
                    $styles[$index]['style'][$property] = self::transformValue(strtolower($property), $value);
                }
            }
        }

        return $styles;
    }

    /**
     * @param  array<string, string>  $tokens
     */
    private static function declarations(string $selector, array $tokens): string
    {
        $body = collect($tokens)
            ->map(fn (string $value, string $name): string => $name.':'.$value)
            ->implode(';');

        return $selector.'{'.$body.'}';
    }

    private static function transformValue(string $property, string $value): string
    {
        $map = match (true) {
            in_array($property, ['background', 'background-color'], true) => self::BACKGROUND_MAP,
            in_array($property, ['color', 'fill', 'stroke'], true) => self::COLOR_MAP,
            str_starts_with($property, 'border'), str_starts_with($property, 'outline') => self::BORDER_MAP,
            default => [],
        };

        if ($map === []) {
            return $value;
        }

        $value = preg_replace_callback(
            '/#[0-9a-f]{3,8}\b/i',
            fn (array $match): string => $map[strtolower($match[0])] ?? $match[0],
            $value,
        ) ?? $value;

        // Añade el valor claro como respaldo para que el diseño siga viéndose
        // incluso si el CSS de tokens no llegara a cargarse.
        return preg_replace_callback(
            '/var\((--cf-[a-z0-9-]+)\)/i',
            function (array $match): string {
                $token = strtolower($match[1]);
                $fallback = self::LIGHT[$token] ?? null;

                return $fallback === null ? $match[0] : 'var('.$token.', '.$fallback.')';
            },
            $value,
        ) ?? $value;
    }
}
