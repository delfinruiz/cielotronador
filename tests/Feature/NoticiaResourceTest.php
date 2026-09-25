<?php

namespace Tests\Feature;

use App\Filament\Resources\Noticias\Pages\CreateNoticia;
use App\Filament\Resources\Noticias\Pages\EditNoticia;
use App\Filament\Resources\Noticias\Pages\ListNoticias;
use App\Models\Noticia;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Tests\AdminTestCase;

class NoticiaResourceTest extends AdminTestCase
{
    public function test_se_pueden_listar_las_noticias(): void
    {
        $noticia = Noticia::factory()->create(['titulo' => 'Noticia de prueba']);

        Testable::actingAs($this->admin());

        Livewire::test(ListNoticias::class)
            ->assertCanSeeTableRecords([$noticia]);
    }

    public function test_se_puede_crear_una_noticia(): void
    {
        Storage::fake('noticias');
        Testable::actingAs($this->admin());

        Livewire::test(CreateNoticia::class)
            ->fillForm([
                'titulo' => 'Nueva noticia',
                'fecha' => '2025-11-01',
                'extracto' => 'Extracto de la nueva noticia.',
                'imagen' => [UploadedFile::fake()->image('foto.jpg')],
                'cuerpo' => [['parrafo' => 'Primer párrafo.']],
                'published' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('noticias', ['titulo' => 'Nueva noticia']);
    }

    public function test_la_noticia_requiere_titulo(): void
    {
        Testable::actingAs($this->admin());

        Livewire::test(CreateNoticia::class)
            ->fillForm([
                'titulo' => '',
                'fecha' => '2025-11-01',
                'extracto' => 'Extracto.',
            ])
            ->call('create')
            ->assertHasFormErrors(['titulo' => 'required']);
    }

    public function test_se_puede_editar_una_noticia(): void
    {
        $noticia = Noticia::factory()->create(['titulo' => 'Título original']);

        Testable::actingAs($this->admin());

        Livewire::test(EditNoticia::class, ['record' => $noticia->getRouteKey()])
            ->fillForm([
                'titulo' => 'Título editado',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame('Título editado', $noticia->fresh()->titulo);
    }
}
