<?php

namespace Database\Factories;

use App\Models\Noticia;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Noticia>
 */
class NoticiaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'titulo' => $this->faker->sentence(6),
            'fecha' => $this->faker->date(),
            'extracto' => $this->faker->sentence(20),
            'imagen' => 'jugando-aprendo.jpg',
            'cuerpo' => [['parrafo' => $this->faker->paragraph()]],
            'published' => true,
        ];
    }

    /**
     * Noticia sin publicar (borrador).
     */
    public function borrador(): static
    {
        return $this->state(fn (array $attributes) => [
            'published' => false,
        ]);
    }
}
