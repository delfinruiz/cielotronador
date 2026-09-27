<?php

namespace Tests\Feature;

use App\Models\Galeria;
use Database\Seeders\NoticiaSeeder;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LandingTest extends TestCase
{
    use RefreshDatabase;

    public function test_la_pagina_principal_renderiza_todas_las_secciones(): void
    {
        Galeria::factory()->create();

        $response = $this->get(route('landing'));

        $response->assertOk();

        $html = $response->getContent();

        $this->assertStringContainsString('Más que un club, una familia', $html);
        $this->assertStringContainsString('Quiénes Somos', $html);
        $this->assertStringContainsString('Últimas noticias', $html);
        $this->assertStringContainsString('Galería fotográfica', $html);
        $this->assertStringContainsString('Horarios de entrenamiento', $html);
        $this->assertStringContainsString('Escríbenos', $html);
        $this->assertStringContainsString('contacto@cielotronador.cl', $html);
        $this->assertStringContainsString(url('/admin/login'), $html);
        $this->assertStringContainsString('img/galeria/', $html);
    }

    public function test_la_landing_muestra_el_toggle_de_modo_oscuro(): void
    {
        $this->get(route('landing'))
            ->assertOk()
            ->assertSee('id="theme-toggle"', false);
    }

    public function test_la_galeria_muestra_maximo_8_fotos_por_pagina(): void
    {
        Galeria::factory()->count(10)->create();

        $html = $this->get(route('landing'))->assertOk()->getContent();

        $this->assertSame(8, preg_match_all('#<img[^>]+src="[^"]*img/galeria/#', $html));
    }

    public function test_la_galeria_pagina_las_fotos_restantes(): void
    {
        Galeria::factory()->count(10)->create();

        $html = $this->get(route('landing', ['page' => 2]))->assertOk()->getContent();

        $this->assertSame(2, preg_match_all('#<img[^>]+src="[^"]*img/galeria/#', $html));
    }

    public function test_la_galeria_incluye_el_modal_y_los_enlaces_de_paginacion(): void
    {
        Galeria::factory()->count(10)->create();

        $this->get(route('landing'))
            ->assertOk()
            ->assertSee('id="gallery-modal"', false)
            ->assertSee('data-gallery-full', false)
            ->assertSee('data-gallery-caption', false)
            ->assertSee('aria-label="Paginación de la galería"', false);
    }

    public function test_el_parcial_de_galeria_se_sirve_sin_layout(): void
    {
        Galeria::factory()->count(10)->create();

        $respuesta = $this->get('/?page=2', ['X-Partial' => 'galeria']);

        $respuesta->assertOk();

        $html = $respuesta->getContent();

        $this->assertStringContainsString('data-gallery-root', $html);
        $this->assertStringContainsString('Página 2 de', $html);
        $this->assertStringNotContainsString('<html', $html);
        $this->assertStringNotContainsString('<header', $html);
    }

    public function test_las_noticias_se_muestran_en_modal_y_sin_enlaces_externos(): void
    {
        $this->seed(NoticiaSeeder::class);

        $html = $this->get(route('landing'))->assertOk()->getContent();

        $this->assertStringContainsString('id="noticia-modal"', $html);
        $this->assertStringContainsString('data-noticia', $html);
        $this->assertStringContainsString('id="noticias-data"', $html);
        $this->assertStringContainsString('Jugando Aprendo lleva un año prepararlo', $html);
        $this->assertStringNotContainsString('href="https://cielotronador.cl', $html);
    }

    public function test_la_landing_incluye_animaciones_de_scroll(): void
    {
        $html = $this->get(route('landing'))->assertOk()->getContent();

        $this->assertStringContainsString("document.documentElement.classList.add('js');", $html);
        $this->assertStringContainsString('id="scroll-progress"', $html);
        $this->assertStringContainsString('id="site-header"', $html);
        $this->assertStringContainsString('data-nav-link', $html);
        $this->assertStringContainsString('data-reveal', $html);
        $this->assertGreaterThanOrEqual(10, substr_count($html, 'data-parallax'));
        $this->assertStringContainsString('stroke="currentColor"', $html);
        $this->assertStringContainsString('preserveAspectRatio="xMidYMid slice"', $html);
        $this->assertStringContainsString('[data-reveal]', (new Filesystem)->get(base_path('resources/css/app.css')));
    }

    public function test_el_boton_volver_arriba_es_un_balon(): void
    {
        $html = $this->get(route('landing'))->assertOk()->getContent();

        $this->assertStringContainsString('id="top-button"', $html);
        $this->assertStringContainsString('aria-label="Volver arriba"', $html);
        $this->assertStringContainsString('top-button-rotator', $html);
        $this->assertStringContainsString('radialGradient', $html);
        $this->assertStringContainsString('--rotacion', (new Filesystem)->get(base_path('resources/css/app.css')));
    }

    public function test_la_landing_incluye_el_widget_de_whatsapp(): void
    {
        $html = $this->get(route('landing'))->assertOk()->getContent();

        $this->assertStringContainsString('id="whatsapp-widget"', $html);
        $this->assertStringContainsString('id="whatsapp-panel"', $html);
        $this->assertStringContainsString('data-wa-number="56998970550"', $html);
        $this->assertStringContainsString('Hola, soy la tía Jacqueline Reyes, directora del club CieloTronador.', $html);
    }

    public function test_las_paginas_de_autenticacion_de_filament_estan_disponibles(): void
    {
        $this->get('/admin/login')->assertOk();
        $this->get('/admin/register')->assertNotFound();
        $this->get('/admin/password-reset/request')->assertOk();
    }
}
