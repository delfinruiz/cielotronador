<?php

namespace Tests\Feature;

use App\Filament\Resources\Galerias\Pages\ManageGalerias;
use App\Models\Galeria;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Tests\AdminTestCase;

class GaleriaResourceTest extends AdminTestCase
{
    public function test_se_pueden_listar_las_fotografias(): void
    {
        $foto = Galeria::factory()->create(['imagen' => 'g01.jpeg']);

        Testable::actingAs($this->admin());

        Livewire::test(ManageGalerias::class)
            ->assertCanSeeTableRecords([$foto]);
    }

    public function test_se_puede_crear_una_fotografia(): void
    {
        Storage::fake('galeria');
        Testable::actingAs($this->admin());

        Livewire::test(ManageGalerias::class)
            ->callAction('create', data: [
                'imagen' => [UploadedFile::fake()->image('foto.jpg')],
                'titulo' => 'Partido del campeonato',
                'published' => true,
            ])
            ->assertHasNoActionErrors();

        $this->assertDatabaseHas('galerias', ['titulo' => 'Partido del campeonato']);
    }

    public function test_la_fotografia_requiere_imagen(): void
    {
        Testable::actingAs($this->admin());

        Livewire::test(ManageGalerias::class)
            ->callAction('create', data: [
                'imagen' => [],
                'titulo' => 'Sin imagen',
            ])
            ->assertHasActionErrors(['imagen' => 'required']);
    }

    public function test_la_fotografia_requiere_titulo(): void
    {
        Storage::fake('galeria');
        Testable::actingAs($this->admin());

        Livewire::test(ManageGalerias::class)
            ->callAction('create', data: [
                'imagen' => [UploadedFile::fake()->image('foto.jpg')],
                'titulo' => '',
            ])
            ->assertHasActionErrors(['titulo' => 'required']);
    }
}
