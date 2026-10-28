<?php

namespace Tests\Feature;

use App\Filament\Pages\SecurityCenter;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\AdminTestCase;

class SecurityTest extends AdminTestCase
{
    public function test_las_cabeceras_de_seguridad_estan_presentes(): void
    {
        $this->get(route('landing'))
            ->assertOk()
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('X-Frame-Options', 'SAMEORIGIN')
            ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin')
            ->assertHeader('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=()');
    }

    public function test_el_centro_de_seguridad_solo_es_accesible_para_super_admin(): void
    {
        Testable::actingAs($this->admin());

        $this->assertTrue(SecurityCenter::canAccess());

        Livewire::test(SecurityCenter::class)->assertOk();

        $panelUser = User::factory()->create();
        $panelUser->assignRole(Role::findOrCreate('panel_user'));

        Testable::actingAs($panelUser);

        $this->assertFalse(SecurityCenter::canAccess());
    }

    public function test_la_descarga_de_respaldos_requiere_firma_y_un_nombre_valido(): void
    {
        $directory = storage_path('app/backups');
        File::ensureDirectoryExists($directory);

        $file = 'backup-2026-01-01_020000.zip';
        File::put($directory.'/'.$file, 'zip');

        $this->actingAs($this->admin());

        $this->get(route('security.backups.download', ['file' => $file]))
            ->assertForbidden();

        $url = URL::temporarySignedRoute(
            'security.backups.download',
            now()->addMinutes(5),
            ['file' => $file],
        );

        $this->get($url)->assertOk();

        $this->get(URL::temporarySignedRoute(
            'security.backups.download',
            now()->addMinutes(5),
            ['file' => '../.env'],
        ))->assertNotFound();

        File::delete($directory.'/'.$file);
    }
}
