<?php

namespace Database\Factories;

use App\Models\PageTemplate;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<PageTemplate>
 */
class PageTemplateFactory extends Factory
{
    protected $model = PageTemplate::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);

        return [
            'name' => Str::title($name),
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(100, 999),
            'description' => fake()->sentence(),
            'render_mode' => 'grapesjs',
            'settings' => ['seo_title' => 'Club CieloTronador'],
            'gjs_data' => null,
            'html' => '<section id="inicio"><h1>Bienvenidos</h1></section>',
            'css' => '#cf-template h1 { color: #FF4C00; }',
            'is_active' => false,
            'published_at' => now(),
        ];
    }

    public function active(): static
    {
        return $this->state(fn (): array => [
            'is_active' => true,
            'published_at' => now(),
        ]);
    }

    /**
     * Las plantillas de prueba nacen con una versión pública igual al borrador,
     * para que la portada y la vista previa se comporten como en producción.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (PageTemplate $template): void {
            if ($template->published_at === null) {
                return;
            }

            if (
                $template->published_html !== null
                || $template->published_css !== null
                || $template->published_gjs_data !== null
            ) {
                return;
            }

            $template->forceFill([
                'published_html' => $template->html,
                'published_css' => $template->css,
                'published_gjs_data' => $template->gjs_data,
            ])->saveQuietly();
        });
    }
}
