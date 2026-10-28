<?php

namespace Database\Factories;

use App\Models\Testimonio;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Testimonio>
 */
class TestimonioFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'role' => $this->faker->boolean(75) ? $this->faker->jobTitle() : null,
            'quote' => $this->faker->paragraph(2),
            'avatar' => null,
            'rating' => $this->faker->numberBetween(4, 5),
            'sort_order' => $this->faker->numberBetween(1, 100),
            'is_active' => true,
        ];
    }

    public function inactivo(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_active' => false,
        ]);
    }
}
