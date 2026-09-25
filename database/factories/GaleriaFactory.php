<?php

namespace Database\Factories;

use App\Models\Galeria;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Galeria>
 */
class GaleriaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'imagen' => 'g'.str_pad((string) $this->faker->unique()->numberBetween(1, 999), 2, '0', STR_PAD_LEFT).'.jpeg',
            'titulo' => null,
            'sort_order' => $this->faker->numberBetween(1, 100),
            'published' => true,
        ];
    }

    /**
     * Fotografía oculta.
     */
    public function oculta(): static
    {
        return $this->state(fn (array $attributes) => [
            'published' => false,
        ]);
    }
}
