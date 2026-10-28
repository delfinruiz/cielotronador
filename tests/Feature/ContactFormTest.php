<?php

namespace Tests\Feature;

use App\Mail\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_el_formulario_de_contacto_envia_un_correo(): void
    {
        Mail::fake();

        $this->post(route('contacto'), [
            'nombre' => 'Juan Pérez',
            'email' => 'juan@example.com',
            'mensaje' => 'Hola, quisiera información sobre las inscripciones.',
        ])
            ->assertRedirect(route('landing').'#contacto')
            ->assertSessionHas('contacto-estado', 'enviado');

        Mail::assertSent(ContactMessage::class, function (ContactMessage $mail) {
            $mail->assertTo(config('services.contact.email'))
                ->assertHasReplyTo('juan@example.com');

            return $mail->nombre === 'Juan Pérez'
                && $mail->mensaje === 'Hola, quisiera información sobre las inscripciones.';
        });
    }

    public function test_el_formulario_acepta_telefono_opcional(): void
    {
        Mail::fake();

        $this->post(route('contacto'), [
            'nombre' => 'Juan Pérez',
            'email' => 'juan@example.com',
            'telefono' => '+56 9 1234 5678',
            'mensaje' => 'Hola, quisiera información sobre las inscripciones.',
        ])
            ->assertRedirect(route('landing').'#contacto')
            ->assertSessionHas('contacto-estado', 'enviado');

        Mail::assertSent(ContactMessage::class, function (ContactMessage $mail) {
            return $mail->telefono === '+56 9 1234 5678';
        });
    }

    public function test_el_formulario_vuelve_a_la_pagina_donde_se_envio(): void
    {
        Mail::fake();

        $this->post(route('contacto'), [
            'nombre' => 'Juan Pérez',
            'email' => 'juan@example.com',
            'mensaje' => 'Hola, quisiera información sobre las inscripciones.',
            '_return' => '/nosotros',
        ])
            ->assertRedirect(url('/nosotros').'#contacto')
            ->assertSessionHas('contacto-estado', 'enviado');
    }

    public function test_el_formulario_ignora_un_retorno_externo(): void
    {
        Mail::fake();

        $this->post(route('contacto'), [
            'nombre' => 'Juan Pérez',
            'email' => 'juan@example.com',
            'mensaje' => 'Hola, quisiera información sobre las inscripciones.',
            '_return' => '//evil.example.com',
        ])
            ->assertRedirect(route('landing').'#contacto')
            ->assertSessionHas('contacto-estado', 'enviado');
    }

    public function test_la_validacion_vuelve_a_la_pagina_de_origen(): void
    {
        $this->post(route('contacto'), [
            'nombre' => 'a',
            'email' => 'no-es-un-correo',
            'mensaje' => 'corto',
            '_return' => '/nosotros',
        ])
            ->assertRedirect(url('/nosotros').'#contacto')
            ->assertSessionHasErrors(['nombre', 'email', 'mensaje']);
    }

    public function test_el_formulario_rechaza_datos_invalidos(): void
    {
        $this->from(route('landing'))
            ->post(route('contacto'), [
                'nombre' => 'a',
                'email' => 'no-es-un-correo',
                'mensaje' => 'corto',
            ])
            ->assertRedirect(route('landing').'#contacto')
            ->assertSessionHasErrors(['nombre', 'email', 'mensaje']);
    }

    public function test_el_formulario_muestra_error_cuando_falla_el_envio(): void
    {
        Mail::shouldReceive('to')
            ->once()
            ->andThrow(new \RuntimeException('SMTP no disponible'));

        $this->post(route('contacto'), [
            'nombre' => 'Juan Pérez',
            'email' => 'juan@example.com',
            'mensaje' => 'Hola, quisiera información sobre las inscripciones.',
        ])
            ->assertRedirect(route('landing').'#contacto')
            ->assertSessionHas('contacto-estado', 'error')
            ->assertSessionHasInput('nombre', 'Juan Pérez');
    }

    public function test_el_honeypot_simula_un_envio_sin_mandar_correo(): void
    {
        Mail::fake();

        $this->post(route('contacto'), [
            'nombre' => 'Bot',
            'email' => 'bot@example.com',
            'mensaje' => 'Este mensaje lo escribe un robot automático.',
            'website' => 'https://spam.example.com',
        ])
            ->assertRedirect(route('landing').'#contacto')
            ->assertSessionHas('contacto-estado', 'enviado');

        Mail::assertNothingSent();
    }

    public function test_el_formulario_tiene_limite_de_intentos_por_minuto(): void
    {
        Mail::fake();

        $payload = [
            'nombre' => 'Juan Pérez',
            'email' => 'juan@example.com',
            'mensaje' => 'Hola, quisiera información sobre las inscripciones.',
        ];

        for ($intento = 1; $intento <= 5; $intento++) {
            $this->post(route('contacto'), $payload)->assertRedirect();
        }

        $this->post(route('contacto'), $payload)->assertStatus(429);
    }
}
