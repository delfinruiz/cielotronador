<?php

namespace Database\Seeders;

use App\Models\CategoriaProducto;
use Illuminate\Database\Seeder;

class CategoriaProductoSeeder extends Seeder
{
    /**
     * Categorías iniciales de la tienda del club.
     */
    public function run(): void
    {
        $categorias = [
            [
                'nombre' => 'Indumentaria',
                'slug' => 'indumentaria',
                'descripcion' => 'Camisetas, poleras y ropa oficial del club.',
                'orden' => 1,
            ],
            [
                'nombre' => 'Accesorios',
                'slug' => 'accesorios',
                'descripcion' => 'Balones, botellas y artículos para entrenar.',
                'orden' => 2,
            ],
            [
                'nombre' => 'Entradas y eventos',
                'slug' => 'entradas-y-eventos',
                'descripcion' => 'Torneos, jornadas y actividades del club.',
                'orden' => 3,
            ],
        ];

        foreach ($categorias as $categoria) {
            CategoriaProducto::updateOrCreate(
                ['slug' => $categoria['slug']],
                [...$categoria, 'publicada' => true],
            );
        }
    }
}
