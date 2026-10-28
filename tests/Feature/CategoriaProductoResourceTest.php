<?php

namespace Tests\Feature;

use App\Filament\Resources\CategoriaProductos\Pages\CreateCategoriaProducto;
use App\Filament\Resources\CategoriaProductos\Pages\EditCategoriaProducto;
use App\Filament\Resources\CategoriaProductos\Pages\ListCategoriaProductos;
use App\Models\CategoriaProducto;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Tests\AdminTestCase;

class CategoriaProductoResourceTest extends AdminTestCase
{
    public function test_se_pueden_listar_las_categorias(): void
    {
        $categoria = CategoriaProducto::factory()->create(['nombre' => 'Indumentaria']);

        Testable::actingAs($this->admin());

        Livewire::test(ListCategoriaProductos::class)
            ->assertCanSeeTableRecords([$categoria]);
    }

    public function test_se_puede_crear_una_categoria(): void
    {
        Testable::actingAs($this->admin());

        Livewire::test(CreateCategoriaProducto::class)
            ->fillForm([
                'nombre' => 'Accesorios',
                'descripcion' => 'Balones y botellas del club.',
                'publicada' => true,
                'orden' => 2,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('categoria_productos', [
            'nombre' => 'Accesorios',
            'slug' => 'accesorios',
            'publicada' => true,
        ]);
    }

    public function test_la_categoria_requiere_nombre(): void
    {
        Testable::actingAs($this->admin());

        Livewire::test(CreateCategoriaProducto::class)
            ->fillForm([
                'nombre' => '',
            ])
            ->call('create')
            ->assertHasFormErrors(['nombre' => 'required']);
    }

    public function test_se_puede_editar_una_categoria(): void
    {
        $categoria = CategoriaProducto::factory()->create(['nombre' => 'Nombre original']);

        Testable::actingAs($this->admin());

        Livewire::test(EditCategoriaProducto::class, ['record' => $categoria->getRouteKey()])
            ->fillForm([
                'nombre' => 'Nombre editado',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame('Nombre editado', $categoria->fresh()->nombre);
    }
}
