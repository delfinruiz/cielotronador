<?php

namespace Database\Factories;

use App\Models\PageTemplate;
use App\Models\PageTemplateVersion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PageTemplateVersion>
 */
class PageTemplateVersionFactory extends Factory
{
    protected $model = PageTemplateVersion::class;

    public function definition(): array
    {
        return [
            'page_template_id' => PageTemplate::factory(),
            'html' => '<section><h1>Versión publicada</h1></section>',
            'css' => '',
            'gjs_data' => null,
            'published_at' => now(),
            'created_by' => null,
        ];
    }
}
