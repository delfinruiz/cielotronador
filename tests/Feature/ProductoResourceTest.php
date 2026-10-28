<?php

namespace Tests\Feature;

use App\Filament\Resources\Productos\Pages\CreateProducto;
use App\Filament\Resources\Productos\Pages\EditProducto;
use App\Filament\Resources\Productos\Pages\ListProductos;
use App\Models\CategoriaProducto;
use App\Models\Producto;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Tests\AdminTestCase;

class ProductoResourceTest extends AdminTestCase
{
    public function test_se_pueden_listar_los_productos(): void
    {
        $producto = Producto::factory()->create(['nombre' => 'Camiseta oficial']);

        Testable::actingAs($this->admin());

        Livewire::test(ListProductos::class)
            ->assertCanSeeTableRecords([$producto])
            ->assertSee('$'.number_format((float) $producto->precio, 0, ',', '.'));
    }

    public function test_se_puede_crear_un_producto(): void
    {
        Storage::fake('productos');
        $categoria = CategoriaProducto::factory()->create(['nombre' => 'Indumentaria']);

        Testable::actingAs($this->admin());

        Livewire::test(CreateProducto::class)
            ->fillForm([
                'nombre' => 'Camiseta oficial 2026',
                'categoria_id' => $categoria->id,
                'descripcion' => 'Camiseta roja con el escudo del club.',
                'imagen' => [UploadedFile::fake()->image('camiseta.jpg')],
                'moneda' => 'CLP',
                'precio' => 29990,
                'precio_anterior' => 34990,
                'nota_precio' => 'Tallas S a XXL',
                'etiqueta' => 'Nuevo',
                'boton_texto' => 'Comprar ahora',
                'enlace_pago' => 'https://www.mercadopago.cl',
                'publicado' => true,
                'orden' => 1,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('productos', [
            'nombre' => 'Camiseta oficial 2026',
            'categoria_id' => $categoria->id,
            'precio' => 29990,
            'moneda' => 'CLP',
        ]);
    }

    public function test_el_producto_puede_crearse_sin_imagen(): void
    {
        Storage::fake('productos');
        $categoria = CategoriaProducto::factory()->create(['nombre' => 'Accesorios']);

        Testable::actingAs($this->admin());

        Livewire::test(CreateProducto::class)
            ->fillForm([
                'nombre' => 'Producto sin foto',
                'categoria_id' => $categoria->id,
                'descripcion' => 'Se mostrará con una imagen de ejemplo.',
                'imagen' => [],
                'moneda' => 'CLP',
                'precio' => 9990,
                'boton_texto' => 'Comprar ahora',
                'publicado' => true,
                'orden' => 3,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('productos', [
            'nombre' => 'Producto sin foto',
            'imagen' => null,
        ]);
    }

    public function test_el_producto_requiere_nombre_categoria_y_precio(): void
    {
        Storage::fake('productos');
        Testable::actingAs($this->admin());

        Livewire::test(CreateProducto::class)
            ->fillForm([
                'nombre' => '',
                'categoria_id' => null,
                'precio' => null,
                'imagen' => [UploadedFile::fake()->image('foto.jpg')],
                'descripcion' => 'Descripción.',
                'boton_texto' => 'Comprar ahora',
            ])
            ->call('create')
            ->assertHasFormErrors([
                'nombre' => 'required',
                'categoria_id' => 'required',
                'precio' => 'required',
            ]);
    }

    public function test_se_puede_editar_un_producto(): void
    {
        Storage::fake('productos');

        $producto = Producto::factory()->create([
            'nombre' => 'Nombre original',
            'imagen' => 'camiseta.jpg',
        ]);

        Testable::actingAs($this->admin());

        Livewire::test(EditProducto::class, ['record' => $producto->getRouteKey()])
            ->fillForm([
                'nombre' => 'Nombre editado',
                'precio' => 31990,
                'imagen' => [UploadedFile::fake()->image('camiseta.jpg')],
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame('Nombre editado', $producto->fresh()->nombre);
        $this->assertSame('31990.00', $producto->fresh()->precio);
    }

    public function test_se_puede_publicar_o_ocultar_desde_la_tabla(): void
    {
        $producto = Producto::factory()->create(['publicado' => true]);

        Testable::actingAs($this->admin());

        Livewire::test(ListProductos::class)
            ->call('updateTableColumnState', 'publicado', $producto->getKey(), false);

        $this->assertFalse($producto->fresh()->publicado);
    }
}
