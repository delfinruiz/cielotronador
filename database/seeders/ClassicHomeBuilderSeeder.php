<?php

namespace Database\Seeders;

use App\Models\PageTemplate;
use App\Support\ClassicHomeWidget;
use App\Support\TemplateSanitizer;
use Illuminate\Database\Seeder;

/**
 * Crea la réplica de la portada clásica como borrador editable.
 *
 * No la publica ni la activa: la portada pública sigue siendo la clásica hasta
 * que alguien la revise y pulse Publicar desde el constructor.
 */
class ClassicHomeBuilderSeeder extends Seeder
{
    public function run(): void
    {
        $template = PageTemplate::query()
            ->where('slug', 'portada-clasica-constructor')
            ->first();

        if (! $template) {
            $template = PageTemplate::create([
                'name' => 'Portada clásica (borrador)',
                'slug' => 'portada-clasica-constructor',
                'description' => 'Réplica de la portada clásica con módulos del constructor. Pendiente de publicar.',
                'render_mode' => 'grapesjs',
                'settings' => [
                    'seo_title' => 'Club CieloTronador',
                ],
                'html' => app(TemplateSanitizer::class)->html(ClassicHomeWidget::page()),
                'is_active' => false,
                'published_at' => null,
            ]);
        }

        $items = [
            ['label' => 'Quiénes Somos', 'value' => '#quienes-somos'],
            ['label' => 'Noticias', 'value' => '#noticias'],
            ['label' => 'Actividades', 'value' => '#actividades'],
            ['label' => 'Horarios', 'value' => '#horarios'],
            ['label' => 'Contáctenos', 'value' => '#contacto'],
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
