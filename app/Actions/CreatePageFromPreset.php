<?php

namespace App\Actions;

use App\Models\PageTemplate;
use App\Support\Presets\LandingPreset;
use App\Support\TemplateSanitizer;
use Illuminate\Support\Str;

/**
 * Crea una página borrador a partir de una plantilla de inicio.
 *
 * La plantilla solo aporta el contenido inicial: la página nace inactiva y sin
 * publicar, con su propio menú, para que el usuario la edite sin tocar la web.
 */
class CreatePageFromPreset
{
    /**
     * Slugs que el resto de la aplicación ya usa en la raíz.
     *
     * @var list<string>
     */
    private const RESERVED = [
        'admin',
        'api',
        'preview',
        'constructor',
        'grapesjs',
        'up',
        'politica-de-privacidad',
        'politica-de-cookies',
        'politica-de-datos',
    ];

    public function __construct(private readonly TemplateSanitizer $sanitizer) {}

    public function execute(LandingPreset $preset, ?string $name = null, ?int $userId = null): PageTemplate
    {
        $name = trim((string) ($name ?? ''));

        if ($name === '') {
            $name = $preset->name();
        }

        $template = PageTemplate::create([
            'name' => $name,
            'slug' => $this->uniqueSlug($name),
            'description' => $preset->tagline(),
            'render_mode' => 'grapesjs',
            'settings' => $preset->settings(),
            'html' => $this->sanitizer->html($preset->html()),
            'css' => $this->sanitizer->css($preset->css()),
            'gjs_data' => null,
            'is_active' => false,
            'published_at' => null,
            'created_by' => $userId ?? auth()->id(),
            'updated_by' => $userId ?? auth()->id(),
        ]);

        foreach ($preset->menu() as $index => $item) {
            $template->menuItems()->create([
                'location' => 'main',
                'label' => $item['label'],
                'link_type' => 'anchor',
                'link_value' => $item['value'],
                'target' => '_self',
                'is_visible' => true,
                'sort_order' => $index + 1,
            ]);
        }

        return $template;
    }

    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name);

        if ($base === '') {
            $base = 'pagina';
        }

        if (in_array($base, self::RESERVED, true)) {
            $base .= '-pagina';
        }

        $slug = $base;
        $suffix = 2;

        while (PageTemplate::withTrashed()->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }
}
