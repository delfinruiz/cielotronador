<?php

namespace App\Support;

/**
 * Tipografías disponibles para las plantillas del constructor.
 *
 * Las familias se compilan con Vite (`laravel-vite-plugin/fonts`) y se cargan
 * por página con `@fonts($aliases)`, así que una plantilla solo descarga las
 * fuentes que realmente usa. La clave es el alias del manifiesto de Vite
 * (`playfair-display`); el nombre visible y la pila CSS se resuelven aquí.
 */
final class TemplateFonts
{
    /**
     * Fuentes que ya usaba el sitio antes de las plantillas.
     *
     * @var list<string>
     */
    public const DEFAULTS = ['poppins', 'oswald'];

    /**
     * Alias de Vite => nombre visible y pila de respaldo.
     *
     * @var array<string, array{label: string, stack: string}>
     */
    private const FAMILIES = [
        'poppins' => ['label' => 'Poppins', 'stack' => "'Poppins', ui-sans-serif, system-ui, sans-serif"],
        'oswald' => ['label' => 'Oswald', 'stack' => "'Oswald', ui-sans-serif, system-ui, sans-serif"],
        'inter' => ['label' => 'Inter', 'stack' => "'Inter', ui-sans-serif, system-ui, sans-serif"],
        'montserrat' => ['label' => 'Montserrat', 'stack' => "'Montserrat', ui-sans-serif, system-ui, sans-serif"],
        'lato' => ['label' => 'Lato', 'stack' => "'Lato', ui-sans-serif, system-ui, sans-serif"],
        'raleway' => ['label' => 'Raleway', 'stack' => "'Raleway', ui-sans-serif, system-ui, sans-serif"],
        'playfair-display' => ['label' => 'Playfair Display', 'stack' => "'Playfair Display', ui-serif, Georgia, serif"],
        'roboto-slab' => ['label' => 'Roboto Slab', 'stack' => "'Roboto Slab', ui-serif, Georgia, serif"],
        'bebas-neue' => ['label' => 'Bebas Neue', 'stack' => "'Bebas Neue', ui-sans-serif, system-ui, sans-serif"],
    ];

    /**
     * @return array<string, string> alias => nombre visible
     */
    public static function families(): array
    {
        return array_map(
            static fn (array $family): string => $family['label'],
            self::FAMILIES,
        );
    }

    /**
     * @return list<string>
     */
    public static function aliases(): array
    {
        return array_keys(self::FAMILIES);
    }

    /**
     * Pila CSS de una familia concreta.
     */
    public static function stack(string $alias): string
    {
        return self::FAMILIES[$alias]['stack'] ?? self::FAMILIES['poppins']['stack'];
    }

    /**
     * Nombre visible de una familia.
     */
    public static function label(string $alias): string
    {
        return self::FAMILIES[$alias]['label'] ?? self::FAMILIES['poppins']['label'];
    }

    /**
     * Normaliza la lista guardada en `settings.fonts`.
     *
     * Acepta el alias (`roboto-slab`) o el nombre visible (`Roboto Slab`).
     * Descarta nombres desconocidos y, si no queda ninguno, devuelve las
     * fuentes históricas para que las páginas antiguas no cambien.
     *
     * @return list<string>
     */
    public static function resolve(mixed $requested): array
    {
        if (! is_array($requested)) {
            return self::DEFAULTS;
        }

        $byLabel = [];

        foreach (self::FAMILIES as $alias => $family) {
            $byLabel[mb_strtolower($family['label'])] = $alias;
        }

        $valid = [];

        foreach ($requested as $family) {
            $key = mb_strtolower(trim((string) $family));
            $alias = array_key_exists($key, self::FAMILIES) ? $key : ($byLabel[$key] ?? null);

            if ($alias !== null) {
                $valid[] = $alias;
            }
        }

        return $valid === [] ? self::DEFAULTS : array_values(array_unique($valid));
    }

    /**
     * Variables CSS que usan los bloques del constructor.
     */
    public static function cssVariables(mixed $fonts): string
    {
        $fonts = self::resolve($fonts);

        $heading = $fonts[0] ?? 'poppins';
        $body = $fonts[1] ?? $heading;

        return '--cf-font-heading: '.self::stack($heading).'; --cf-font-body: '.self::stack($body).';';
    }

    /**
     * Bloque CSS con las variables tipográficas de una página.
     *
     * Devuelve una cadena vacía cuando la página no eligió tipografías, para no
     * alterar el aspecto de las páginas que ya existían.
     */
    public static function themeCss(mixed $fonts): string
    {
        if (! is_array($fonts) || $fonts === []) {
            return '';
        }

        return 'body { '.self::cssVariables($fonts).' }';
    }
}
