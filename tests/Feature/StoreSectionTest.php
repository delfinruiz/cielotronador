<?php

namespace Tests\Feature;

use App\Filament\Pages\StoreSection;
use App\Models\CategoriaProducto;
use App\Models\Producto;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Contracts\Support\Htmlable;
use Spatie\Permission\Models\Role;
use Tests\AdminTestCase;

class StoreSectionTest extends AdminTestCase
{
    public function test_la_tienda_aparece_en_el_grupo_contenido(): void
    {
        $this->actingAs($this->admin())
            ->get('/admin')
            ->assertOk();

        $groups = collect(Filament::getNavigation())
            ->mapWithKeys(function ($group): array {
                $label = $group->getLabel() instanceof Htmlable
                    ? $group->getLabel()->toHtml()
                    : $group->getLabel();

                return [$label => collect($group->getItems())
                    ->map(fn ($item) => $item->getLabel())
                    ->all()];
            });

        $this->assertContains('Tienda', $groups->get('Contenido', []));
        $this->assertFalse($groups->has('Tienda'));
        $this->assertSame('Configuración', $groups->keys()->last());
    }

    public function test_la_seccion_tienda_lista_productos_y_categorias(): void
    {
        $categoria = CategoriaProducto::factory()->create(['nombre' => 'Indumentaria']);
        Producto::factory()->create([
            'nombre' => 'Camiseta de prueba',
            'categoria_id' => $categoria->id,
        ]);

        $this->actingAs($this->admin())
            ->get(StoreSection::getUrl())
            ->assertOk()
            ->assertSee('data-tienda="productos"', false)
            ->assertSee('data-tienda="categorias"', false)
            ->assertSee('Camiseta de prueba');

        $this->actingAs($this->admin())
            ->get(StoreSection::getUrl(['tienda' => 'categorias']))
            ->assertOk()
            ->assertSee('Indumentaria');
    }

    public function test_la_tienda_es_accesible_para_quien_administra_productos(): void
    {
        $user = User::factory()->create();
        $user->assignRole(Role::findOrCreate('panel_user'));
        $user->assignRole(Role::findOrCreate('Directora'));

        $this->actingAs($user)
            ->get(StoreSection::getUrl())
            ->assertOk()
            ->assertSee('Productos')
            ->assertSee('Categorías');
    }

    public function test_la_tienda_no_es_accesible_sin_permiso_de_productos(): void
    {
        $user = User::factory()->create();
        $user->assignRole(Role::findOrCreate('panel_user'));

        $this->actingAs($user)
            ->get(StoreSection::getUrl())
            ->assertForbidden();
    }
}
