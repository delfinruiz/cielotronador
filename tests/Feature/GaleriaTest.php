<?php

namespace Tests\Feature;

use App\Models\Galeria;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GaleriaTest extends TestCase
{
    use RefreshDatabase;

    public function test_la_landing_solo_muestra_fotografias_publicadas(): void
    {
        Galeria::factory()->create(['imagen' => 'pub.jpg', 'published' => true]);
        Galeria::factory()->oculta()->create(['imagen' => 'oculta.jpg', 'published' => false]);

        $html = $this->get(route('landing'))->assertOk()->getContent();

        $this->assertStringContainsString('img/galeria/pub.jpg', $html);
        $this->assertStringNotContainsString('img/galeria/oculta.jpg', $html);
    }

    public function test_la_landing_ordena_la_galeria_por_sort_order(): void
    {
        $primero = Galeria::factory()->create(['imagen' => 'primero.jpg', 'sort_order' => 2]);
        $segundo = Galeria::factory()->create(['imagen' => 'segundo.jpg', 'sort_order' => 1]);

        $html = $this->get(route('landing'))->assertOk()->getContent();

        $this->assertLessThan(
            strpos($html, 'img/galeria/primero.jpg'),
            strpos($html, 'img/galeria/segundo.jpg'),
        );
    }

    public function test_la_landing_muestra_el_titulo_de_la_foto_en_el_modal(): void
    {
        Galeria::factory()->create(['imagen' => 'g01.jpg', 'titulo' => 'Gran final del campeonato']);

        $html = $this->get(route('landing'))->assertOk()->getContent();

        $this->assertStringContainsString('data-gallery-caption="Gran final del campeonato"', $html);
    }
}
