<?php

namespace Tests\Feature;

use App\Models\Noticia;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NoticiaTest extends TestCase
{
    use RefreshDatabase;

    public function test_la_landing_solo_muestra_noticias_publicadas(): void
    {
        Noticia::factory()->create(['titulo' => 'Noticia publicada', 'published' => true]);
        Noticia::factory()->borrador()->create(['titulo' => 'Noticia borrador', 'published' => false]);

        $html = $this->get(route('landing'))->assertOk()->getContent();

        $this->assertStringContainsString('Noticia publicada', $html);
        $this->assertStringNotContainsString('Noticia borrador', $html);
    }

    public function test_la_landing_ordena_las_noticias_por_fecha_descendente(): void
    {
        $antigua = Noticia::factory()->create(['titulo' => 'Noticia antigua', 'fecha' => '2023-01-01']);
        $reciente = Noticia::factory()->create(['titulo' => 'Noticia reciente', 'fecha' => '2025-01-01']);

        $html = $this->get(route('landing'))->assertOk()->getContent();

        $this->assertStringContainsString('Noticia reciente', $html);
        $this->assertStringContainsString('Noticia antigua', $html);

        $this->assertLessThan(
            strpos($html, 'Noticia antigua'),
            strpos($html, 'Noticia reciente'),
        );
    }

    public function test_el_modelo_convierte_el_cuerpo_a_array(): void
    {
        $noticia = Noticia::factory()->create(['cuerpo' => [['parrafo' => 'Primer párrafo'], ['parrafo' => 'Segundo párrafo']]]);

        $this->assertIsArray($noticia->cuerpo);
        $this->assertSame('Primer párrafo', $noticia->cuerpo[0]['parrafo']);
    }

    public function test_la_landing_aplana_el_cuerpo_a_parrafos(): void
    {
        Noticia::factory()->create([
            'titulo' => 'Con cuerpo',
            'cuerpo' => [['parrafo' => 'Primer párrafo'], ['parrafo' => 'Segundo párrafo']],
        ]);

        $html = $this->get(route('landing'))->assertOk()->getContent();

        $this->assertStringContainsString('Primer párrafo', $html);
        $this->assertStringContainsString('Segundo párrafo', $html);
    }
}
