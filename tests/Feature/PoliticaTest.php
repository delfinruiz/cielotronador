<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PoliticaTest extends TestCase
{
    use RefreshDatabase;

    public function test_la_politica_de_privacidad_renderiza(): void
    {
        $response = $this->get(route('politica.privacidad'));

        $response->assertOk()
            ->assertSee('Política de Privacidad', false)
            ->assertSee('Ley N.º 19.628', false);
    }

    public function test_la_politica_de_cookies_renderiza(): void
    {
        $response = $this->get(route('politica.cookies'));

        $response->assertOk()
            ->assertSee('Política de Cookies', false)
            ->assertSee('laravel_session', false);
    }

    public function test_la_politica_de_datos_renderiza(): void
    {
        $response = $this->get(route('politica.datos'));

        $response->assertOk()
            ->assertSee('Política de Seguridad y Manejo de Datos', false)
            ->assertSee('Medidas de seguridad', false);
    }

    public function test_la_landing_incluye_el_banner_de_cookies(): void
    {
        $this->get(route('landing'))
            ->assertOk()
            ->assertSee('id="cookie-banner"', false)
            ->assertSee('Aceptar todas', false);
    }

    public function test_las_politicas_incluyen_el_widget_de_whatsapp(): void
    {
        $this->get(route('politica.privacidad'))
            ->assertOk()
            ->assertSee('id="whatsapp-widget"', false)
            ->assertSee('data-wa-number="56998970550"', false);
    }
}
