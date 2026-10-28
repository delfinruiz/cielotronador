<?php

namespace Database\Seeders;

use App\Models\PageTemplate;
use App\Support\PageBlockLibrary;
use App\Support\TemplateSanitizer;
use Illuminate\Database\Seeder;

class PageTemplateDemoSeeder extends Seeder
{
    public function run(): void
    {
        $template = PageTemplate::query()
            ->where('slug', 'free')
            ->orWhere('name', 'Comercial')
            ->first();

        if (! $template) {
            $template = new PageTemplate([
                'name' => 'Comercial',
                'slug' => 'free',
                'description' => 'Página de ejemplo editable con el constructor visual.',
                'render_mode' => 'grapesjs',
            ]);
        }

        if (blank($template->html)) {
            $template->forceFill([
                'name' => $template->name ?: 'Comercial',
                'slug' => $template->slug ?: 'free',
                'description' => $template->description ?: 'Página de ejemplo editable con el constructor visual.',
                'render_mode' => 'grapesjs',
                'html' => app(TemplateSanitizer::class)->html(PageBlockLibrary::demoPage()),
                'css' => null,
                'gjs_data' => null,
                'settings' => [
                    'seo_title' => 'Comercial | Tu marca',
                    'seo_description' => 'Página de ejemplo creada con el constructor visual.',
                ],
                'published_at' => now(),
            ])->save();
        }

        if (! $template->hasPublishedDesign()) {
            $template->publish();
        }

        $items = [
            ['label' => 'Inicio', 'value' => '#inicio'],
            ['label' => 'Noticias', 'value' => '#noticias'],
            ['label' => 'Galería', 'value' => '#galeria'],
            ['label' => 'Tienda', 'value' => '#ventas'],
            ['label' => 'Contacto', 'value' => '#contacto'],
        ];

        $existing = $template->menuItems()->get();
        $sortOrder = (int) ($existing->max('sort_order') ?? 0);

        foreach ($items as $item) {
            $alreadyExists = $existing->contains(
                fn ($menuItem): bool => $menuItem->link_value === $item['value']
                    && $menuItem->location === 'main',
            );

            if ($alreadyExists) {
                continue;
            }

            $template->menuItems()->create([
                'location' => 'main',
                'label' => $item['label'],
                'link_type' => 'anchor',
                'link_value' => $item['value'],
                'target' => '_self',
                'is_visible' => true,
                'sort_order' => ++$sortOrder,
            ]);
        }
    }
}
