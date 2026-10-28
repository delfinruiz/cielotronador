<?php

namespace Database\Seeders;

use App\Models\CategoriaProducto;
use App\Models\Producto;
use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    /**
     * Artículos de ejemplo para que la sección de ventas no quede vacía.
     */
    public function run(): void
    {
        $categorias = CategoriaProducto::query()->pluck('id', 'slug');

        $productos = [
            [
                'categoria' => 'indumentaria',
                'nombre' => 'Camiseta oficial 2026',
                'descripcion' => 'Camiseta roja del club con el escudo bordado y tela deportiva de secado rápido.',
                'imagen' => null,
                'precio' => 29990,
                'precio_anterior' => 34990,
                'nota_precio' => 'Tallas S a XXL',
                'etiqueta' => 'Nuevo',
                'enlace_pago' => 'https://www.mercadopago.cl',
                'orden' => 1,
            ],
            [
                'categoria' => 'indumentaria',
                'nombre' => 'Polera de entrenamiento',
                'descripcion' => 'Polera liviana para los entrenamientos de la semana, con el sello del club.',
                'imagen' => null,
                'precio' => 19990,
                'precio_anterior' => null,
                'nota_precio' => null,
                'etiqueta' => null,
                'enlace_pago' => 'https://www.mercadopago.cl',
                'orden' => 2,
            ],
            [
                'categoria' => 'accesorios',
                'nombre' => 'Balón oficial de juego',
                'descripcion' => 'Balón de básquetbol tamaño 7, ideal para partidos y entrenamientos.',
                'imagen' => null,
                'precio' => 24990,
                'precio_anterior' => 29990,
                'nota_precio' => 'Incluye bomba',
                'etiqueta' => 'Oferta',
                'enlace_pago' => 'https://www.mercadopago.cl',
                'orden' => 3,
            ],
            [
                'categoria' => 'accesorios',
                'nombre' => 'Botella térmica del club',
                'descripcion' => 'Botella de acero de 750 ml para mantener el agua fría durante todo el partido.',
                'imagen' => null,
                'precio' => 12990,
                'precio_anterior' => null,
                'nota_precio' => 'Retiro en el club',
                'etiqueta' => null,
                'enlace_pago' => 'https://www.mercadopago.cl',
                'orden' => 4,
            ],
            [
                'categoria' => 'entradas-y-eventos',
                'nombre' => 'Entrada Torneo Jugando Aprendo',
                'descripcion' => 'Entrada general para acompañar una jornada del torneo de minibásquet del club.',
                'imagen' => null,
                'precio' => 5000,
                'precio_anterior' => null,
                'nota_precio' => 'Valor por jornada',
                'etiqueta' => 'Evento',
                'enlace_pago' => 'https://www.mercadopago.cl',
                'orden' => 5,
            ],
        ];

        foreach ($productos as $producto) {
            $categoriaSlug = $producto['categoria'];
            unset($producto['categoria']);

            Producto::updateOrCreate(
                ['nombre' => $producto['nombre']],
                [
                    ...$producto,
                    'categoria_id' => $categorias[$categoriaSlug] ?? null,
                    'moneda' => 'CLP',
                    'boton_texto' => 'Comprar ahora',
                    'publicado' => true,
                ],
            );
        }
    }
}
