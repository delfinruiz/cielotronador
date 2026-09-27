<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_el_login_usa_el_encabezado_ingrese_a_su_cuenta(): void
    {
        $this->get('/admin/login')
            ->assertOk()
            ->assertSee('Ingrese a su cuenta', false)
            ->assertDontSee('Entre a su cuenta', false);
    }

    public function test_el_login_muestra_el_footer_de_la_pagina_principal(): void
    {
        $html = $this->get('/admin/login')->assertOk()->getContent();

        $this->assertStringContainsString('Política de Privacidad', $html);
        $this->assertStringContainsString('Seguridad y Manejo de Datos', $html);
        $this->assertStringContainsString('instagram.com/cielotronadorthno', $html);
        $this->assertStringContainsString('w-full border-t border-zinc-200', $html);
        $this->assertStringContainsString('dark:bg-zinc-950', $html);
        $this->assertStringContainsString('Volver al sitio web', $html);
    }
}
