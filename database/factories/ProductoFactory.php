<?php

namespace Database\Factories;

use App\Models\CategoriaProducto;
use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Producto>
 */
class ProductoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'categoria_id' => CategoriaProducto::factory(),
            'nombre' => $this->faker->unique()->words(3, true),
            'descripcion' => $this->faker->sentence(14),
            'imagen' => null,
            'moneda' => 'CLP',
            'precio' => $this->faker->numberBetween(5000, 50000),
            'precio_anterior' => null,
            'nota_precio' => null,
            'etiqueta' => null,
            'boton_texto' => 'Comprar ahora',
            'enlace_pago' => 'https://www.mercadopago.cl',
            'publicado' => true,
            'orden' => $this->faker->numberBetween(1, 50),
        ];
    }

    /**
     * Artículo sin publicar (borrador).
     */
    public function borrador(): static
    {
        return $this->state(fn (array $attributes) => [
            'publicado' => false,
        ]);
    }

    /**
     * Artículo con precio anterior y distintivo de oferta.
     */
    public function enOferta(): static
    {
        return $this->state(fn (array $attributes) => [
            'precio' => 19990,
            'precio_anterior' => 24990,
            'etiqueta' => 'Oferta',
        ]);
    }
}
