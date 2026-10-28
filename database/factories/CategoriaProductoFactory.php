<?php

namespace Database\Factories;

use App\Models\CategoriaProducto;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<CategoriaProducto>
 */
class CategoriaProductoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $nombre = $this->faker->unique()->words(2, true);

        return [
            'nombre' => Str::title($nombre),
            'slug' => null,
            'descripcion' => $this->faker->sentence(12),
            'orden' => $this->faker->numberBetween(1, 50),
            'publicada' => true,
        ];
    }

    /**
     * Categoría oculta de la tienda.
     */
    public function oculta(): static
    {
        return $this->state(fn (array $attributes) => [
            'publicada' => false,
        ]);
    }
}
