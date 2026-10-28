<?php

namespace Tests\Feature;

use App\Actions\CreatePageFromPreset;
use App\Support\Presets\PresetLibrary;
use App\Support\Presets\ProductPreset;
use App\Support\TemplateSanitizer;
use Database\Seeders\ContenidoDinamicoSeeder;
use Tests\AdminTestCase;

class ProductPresetTest extends AdminTestCase
{
    public function test_la_plantilla_producto_incluye_video_y_modulos_dinamicos(): void
    {
        $html = app(TemplateSanitizer::class)->html((new ProductPreset)->html());

        foreach ([
            'id="inicio"',
            'id="marcas"',
            'id="producto"',
            'id="video"',
            'id="testimonios"',
            'id="faq"',
            'id="contacto"',
            'data-cf-video="yes"',
            'data-video-title="Mira Nexo en acción"',
            'data-cf-section="marcas"',
            'data-cf-section="testimonios"',
            'data-cf-section="faq"',
            'data-cf-option-layout="grid"',
            'data-cf-option-columns="2"',
            'Nexo',
        ] as $needle) {
            $this->assertStringContainsString($needle, $html);
        }

        $this->assertStringNotContainsString('<iframe', $html);
    }

    public function test_la_plantilla_producto_no_deja_textos_de_ejemplo(): void
    {
        $html = app(TemplateSanitizer::class)->html((new ProductPreset)->html());

        foreach ([
            'Tu título principal',
            'Sobre nosotros',
            'Tres razones para elegirnos',
            'Calidad',
            'Comunidad',
            'Valores',
            'Lorem ipsum',
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
            'Video de presentación',
        ] as $needle) {
            $this->assertStringNotContainsString($needle, $html);
        }
    }

    public function test_el_tema_producto_tiene_variante_clara_y_oscura(): void
    {
        $preset = new ProductPreset;
        $css = $preset->settings()['theme_css'];

        $this->assertSame('', $preset->css());
        $this->assertSame(['poppins', 'inter'], $preset->fonts());
        $this->assertStringContainsString('--cf-brand: #5b4bff', $css);
        $this->assertStringContainsString('.dark body', $css);
        $this->assertStringContainsString('--cf-brand: #8b7cff', $css);
    }

    public function test_crear_la_pagina_producto_la_deja_como_borrador(): void
    {
        $template = app(CreatePageFromPreset::class)->execute(
            PresetLibrary::find('producto'),
            'Nexo Demo',
            $this->admin()->id,
        );

        $this->assertSame('nexo-demo', $template->slug);
        $this->assertFalse($template->is_active);
        $this->assertNull($template->published_at);
        $this->assertSame(5, $template->mainMenuItems()->count());
        $this->assertStringContainsString('data-cf-video="yes"', $template->html);
    }

    public function test_la_vista_previa_muestra_video_y_contenido_dinamico(): void
    {
        $this->seed(ContenidoDinamicoSeeder::class);

        $this->actingAs($this->admin())
            ->get(route('presets.preview', ['preset' => 'producto']))
            ->assertOk()
            ->assertSee('Todo tu equipo', false)
            ->assertSee('Mira Nexo en acción')
            ->assertSee('data-cf-video="yes"', false)
            ->assertSee('¿Cómo empieza un proyecto?')
            ->assertSee('Nube')
            ->assertSee('Camila Rojas')
            ->assertSee('--cf-brand: #5b4bff', false);
    }

    public function test_la_galeria_lista_las_cinco_plantillas(): void
    {
        $this->actingAs($this->admin())
            ->get('/admin/plantillas')
            ->assertOk()
            ->assertSee('Agencia')
            ->assertSee('Creativo')
            ->assertSee('Estudio Visual')
            ->assertSee('Corporativo')
            ->assertSee('Producto')
            ->assertSee('video de producto');
    }
}
