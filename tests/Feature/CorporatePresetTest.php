<?php

namespace Tests\Feature;

use App\Actions\CreatePageFromPreset;
use App\Support\Presets\CorporatePreset;
use App\Support\Presets\PresetLibrary;
use App\Support\TemplateSanitizer;
use Database\Seeders\ContenidoDinamicoSeeder;
use Tests\AdminTestCase;

class CorporatePresetTest extends AdminTestCase
{
    public function test_la_plantilla_corporativa_incluye_los_tres_modulos_dinamicos(): void
    {
        $html = app(TemplateSanitizer::class)->html((new CorporatePreset)->html());

        foreach ([
            'id="inicio"',
            'id="marcas"',
            'id="servicios"',
            'id="nosotros"',
            'id="testimonios"',
            'id="faq"',
            'id="contacto"',
            'data-cf-section="marcas"',
            'data-cf-section="testimonios"',
            'data-cf-section="faq"',
            'data-cf-option-speed="48"',
            'data-cf-option-columns="2"',
            'data-cf-option-layout="slider"',
            'data-cf-option-autoplay="no"',
            'Consultora Norte',
        ] as $needle) {
            $this->assertStringContainsString($needle, $html);
        }
    }

    public function test_la_plantilla_corporativa_no_deja_textos_de_ejemplo(): void
    {
        $html = app(TemplateSanitizer::class)->html((new CorporatePreset)->html());

        foreach ([
            'Tu título principal',
            'Sobre nosotros',
            'Tres razones para elegirnos',
            'Calidad',
            'Comunidad',
            'Valores',
            'Lorem ipsum',
            'Cada lado entra por su costado',
            'Conócenos',
            'Entrada al cargar',
            'Este bloque anima',
            'Escríbenos',
            'Arrastra aquí',
            'Tu marca',
            'Tu ciudad',
            'picsum.photos',
            'tudominio.com',
            'tucuenta',
            'Jugadores',
        ] as $needle) {
            $this->assertStringNotContainsString($needle, $html);
        }
    }

    public function test_el_tema_corporativo_tiene_variante_clara_y_oscura(): void
    {
        $preset = new CorporatePreset;
        $css = $preset->settings()['theme_css'];

        $this->assertSame('', $preset->css());
        $this->assertSame(['raleway', 'inter'], $preset->fonts());
        $this->assertStringContainsString('--cf-brand: #0e7490', $css);
        $this->assertStringContainsString('.dark body', $css);
        $this->assertStringContainsString('--cf-brand: #22b8cf', $css);
        $this->assertStringContainsString('scroll-margin-top', $css);
    }

    public function test_crear_la_pagina_corporativa_la_deja_como_borrador(): void
    {
        $template = app(CreatePageFromPreset::class)->execute(
            PresetLibrary::find('corporativo'),
            'Consultora Sur',
            $this->admin()->id,
        );

        $this->assertSame('consultora-sur', $template->slug);
        $this->assertFalse($template->is_active);
        $this->assertNull($template->published_at);
        $this->assertSame(['raleway', 'inter'], $template->settings['fonts']);
        $this->assertSame(5, $template->mainMenuItems()->count());
        $this->assertStringContainsString('data-cf-section="testimonios"', $template->html);
    }

    public function test_la_vista_previa_muestra_las_secciones_dinamicas(): void
    {
        $this->seed(ContenidoDinamicoSeeder::class);

        $this->actingAs($this->admin())
            ->get(route('presets.preview', ['preset' => 'corporativo']))
            ->assertOk()
            ->assertSee('Crecemos', false)
            ->assertSee('¿Cómo empieza un proyecto?')
            ->assertSee('Nube')
            ->assertSee('Camila Rojas')
            ->assertSee('--cf-brand: #0e7490', false);
    }

    public function test_la_galeria_lista_las_cuatro_plantillas(): void
    {
        $this->actingAs($this->admin())
            ->get('/admin/plantillas')
            ->assertOk()
            ->assertSee('Agencia')
            ->assertSee('Creativo')
            ->assertSee('Estudio Visual')
            ->assertSee('Corporativo')
            ->assertSee('marcas, testimonios y preguntas frecuentes');
    }
}
