<?php

namespace App\Support;

use App\Models\PageTemplate;
use Illuminate\Support\Collection;

class TemplateRenderer
{
    /**
     * Secciones dinámicas que una plantilla GrapesJS puede insertar.
     *
     * @var array<string, string>
     */
    public const SECTIONS = [
        'noticias' => 'Últimas noticias',
        'galeria' => 'Galería fotográfica',
        'ventas' => 'Productos en venta',
        'horarios' => 'Horarios de entrenamiento',
        'contacto' => 'Formulario de contacto',
        'directiva' => 'Nuestra directiva',
        'faq' => 'Preguntas frecuentes',
        'marcas' => 'Marcas',
        'testimonios' => 'Testimonios',
    ];

    public function __construct(private readonly TemplateSanitizer $sanitizer) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function render(PageTemplate $template, array $data = [], ?string $html = null): string
    {
        $html = FormWidget::upgradeLegacyHtml($this->sanitizer->html($html ?? (string) $template->html));

        if ($html === '') {
            return '';
        }

        $html = preg_replace_callback(
            '~<([a-z0-9]+)([^>]*?)data-cf-section=(["\'])([a-z0-9_-]+)\3([^>]*)>.*?</\1>~is',
            function (array $match) use ($data): string {
                $section = strtolower($match[4]);

                if (! array_key_exists($section, self::SECTIONS)) {
                    return '';
                }

                $atributos = ($match[2] ?? '').' '.($match[5] ?? '');
                $opciones = [];

                preg_match_all(
                    '~data-cf-option-([a-z0-9-]+)\s*=\s*(["\'])(.*?)\2~i',
                    $atributos,
                    $encontradas,
                    PREG_SET_ORDER,
                );

                foreach ($encontradas as $opcion) {
                    $opciones[str_replace('-', '_', strtolower($opcion[1]))] = $opcion[3];
                }

                $datosSeccion = $data;
                $datosSeccion['options'] = $opciones;

                return view('landing.sections.'.$section, $datosSeccion)->render();
            },
            $html,
        ) ?? $html;

        /*
         * La capa decorativa se agrega después de resolver las secciones
         * dinámicas: así un envoltorio con data-cf-parallax también decora
         * una sección que viene de la base de datos.
         */
        return Branding::applyToHtml(ParallaxWidget::inject($html));
    }

    /**
     * @return Collection<string, string>
     */
    public static function options(): Collection
    {
        return collect(self::SECTIONS);
    }
}
