<?php

namespace Database\Factories;

use App\Models\MenuItem;
use App\Models\PageTemplate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MenuItem>
 */
class MenuItemFactory extends Factory
{
    protected $model = MenuItem::class;

    public function definition(): array
    {
        return [
            'page_template_id' => PageTemplate::factory(),
            'location' => 'main',
            'label' => fake()->words(2, true),
            'link_type' => 'anchor',
            'link_value' => '#'.fake()->slug(2),
            'target' => '_self',
            'is_visible' => true,
            'sort_order' => fake()->numberBetween(1, 20),
        ];
    }
}
