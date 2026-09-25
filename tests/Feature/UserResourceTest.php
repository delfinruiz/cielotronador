<?php

namespace Tests\Feature;

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\AdminTestCase;

class UserResourceTest extends AdminTestCase
{
    public function test_se_pueden_listar_los_usuarios(): void
    {
        $usuario = User::factory()->create(['name' => 'Usuario de prueba']);

        Testable::actingAs($this->admin());

        Livewire::test(ListUsers::class)
            ->assertCanSeeTableRecords([$usuario]);
    }

    public function test_se_puede_crear_un_usuario_con_contrasena_hasheada(): void
    {
        Testable::actingAs($this->admin());

        Livewire::test(CreateUser::class)
            ->fillForm([
                'name' => 'Nuevo usuario',
                'email' => 'nuevo@example.com',
                'password' => 'secreto123',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $usuario = User::where('email', 'nuevo@example.com')->firstOrFail();

        $this->assertTrue(Hash::check('secreto123', $usuario->password));
    }

    public function test_se_puede_asignar_un_rol_al_crear_un_usuario(): void
    {
        $rol = Role::findByName('panel_user');

        Testable::actingAs($this->admin());

        Livewire::test(CreateUser::class)
            ->fillForm([
                'name' => 'Usuario con rol',
                'email' => 'conrol@example.com',
                'password' => 'secreto123',
                'roles' => [$rol->getKey()],
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $usuario = User::where('email', 'conrol@example.com')->firstOrFail();

        $this->assertTrue($usuario->hasRole('panel_user'));
    }

    public function test_el_usuario_requiere_nombre_y_correo(): void
    {
        Testable::actingAs($this->admin());

        Livewire::test(CreateUser::class)
            ->fillForm([
                'name' => '',
                'email' => '',
                'password' => 'secreto123',
            ])
            ->call('create')
            ->assertHasFormErrors([
                'name' => 'required',
                'email' => 'required',
            ]);
    }
}
