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
            ->assertRedirect('/')
            ->assertSessionHas('contacto-estado', 'enviado');

        Mail::assertSent(ContactMessage::class, function (ContactMessage $mail) {
            $mail->assertTo(config('services.contact.email'))
                ->assertHasReplyTo('juan@example.com');

            return $mail->nombre === 'Juan Pérez'
                && $mail->mensaje === 'Hola, quisiera información sobre las inscripciones.';
        });
    }

    public function test_el_formulario_rechaza_datos_invalidos(): void
    {
        $this->from(route('landing'))
            ->post(route('contacto'), [
                'nombre' => 'a',
                'email' => 'no-es-un-correo',
                'mensaje' => 'corto',
            ])
            ->assertRedirect(route('landing'))
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
            ->assertRedirect('/')
            ->assertSessionHas('contacto-estado', 'error')
            ->assertSessionHasInput('nombre', 'Juan Pérez');
    }
}
