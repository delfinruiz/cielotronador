<?php

namespace App\Support;

/**
 * Tema visual de una página creada desde una plantilla.
 *
 * El tema se guarda en `settings.theme_css` (colores, tipografía y reglas
 * base) y se inyecta en la página pública y en el lienzo del editor desde el
 * servidor. No vive en la columna `css` porque GrapesJS reescribe esa columna
 * al guardar y borraría el tema.
 */
final class PageTheme
{
    /**
     * CSS del tema: primero las reglas guardadas y después las tipografías.
     */
    public static function css(mixed $themeCss, mixed $fonts = null): string
    {
        $theme = is_string($themeCss) ? trim($themeCss) : '';

        return trim($theme."\n".TemplateFonts::themeCss($fonts));
    }
}
