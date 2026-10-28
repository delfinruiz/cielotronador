<?php

namespace Tests\Feature;

use App\Models\CategoriaProducto;
use App\Models\Producto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductoTest extends TestCase
{
    use RefreshDatabase;

    public function test_el_precio_se_formatea_segun_la_moneda(): void
    {
        $producto = Producto::factory()->make([
            'moneda' => 'CLP',
            'precio' => 29990,
        ]);

        $this->assertSame('$29.990', $producto->precioFormateado());

        $producto->moneda = 'USD';
        $producto->precio = 25;
        $this->assertSame('US$25,00', $producto->precioFormateado());

        $producto->moneda = 'EUR';
        $producto->precio = 19.9;
        $this->assertSame('€19,90', $producto->precioFormateado());
    }

    public function test_el_precio_anterior_solo_se_muestra_si_representa_un_descuento(): void
    {
        $producto = Producto::factory()->make([
            'precio' => 20000,
            'precio_anterior' => 25000,
        ]);

        $this->assertSame('$25.000', $producto->precioAnteriorFormateado());

        $producto->precio_anterior = 15000;
        $this->assertNull($producto->precioAnteriorFormateado());

        $producto->precio_anterior = null;
        $this->assertNull($producto->precioAnteriorFormateado());
    }

    public function test_la_imagen_usa_el_disco_local_o_un_marcador_estable(): void
    {
        $local = Producto::factory()->create(['imagen' => 'camiseta.jpg']);
        $remota = Producto::factory()->create(['imagen' => 'https://example.com/foto.jpg']);
        $sinImagen = Producto::factory()->create(['imagen' => null]);

        $this->assertSame(asset('img/productos/camiseta.jpg'), $local->imagenUrl());
        $this->assertSame('https://example.com/foto.jpg', $remota->imagenUrl());
        $this->assertStringStartsWith('https://picsum.photos/seed/cielo-producto-', $sinImagen->imagenUrl());
    }

    public function test_las_categorias_generan_un_slug_unico(): void
    {
        $primera = CategoriaProducto::create(['nombre' => 'Indumentaria']);
        $segunda = CategoriaProducto::create(['nombre' => 'Indumentaria']);

        $this->assertSame('indumentaria', $primera->slug);
        $this->assertSame('indumentaria-2', $segunda->slug);
    }

    public function test_solo_se_ven_productos_publicados_de_categorias_publicadas(): void
    {
        $visible = Producto::factory()->create(['nombre' => 'Visible']);
        Producto::factory()->borrador()->create(['nombre' => 'Borrador']);

        $categoriaOculta = CategoriaProducto::factory()->oculta()->create();
        Producto::factory()->for($categoriaOculta, 'categoria')->create(['nombre' => 'Categoría oculta']);

        $nombres = Producto::query()->visibles()->pluck('nombre');

        $this->assertTrue($nombres->contains($visible->nombre));
        $this->assertFalse($nombres->contains('Borrador'));
        $this->assertFalse($nombres->contains('Categoría oculta'));
    }
}
