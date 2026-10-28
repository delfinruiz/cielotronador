<?php

namespace Tests\Feature;

use App\Actions\CreatePageFromPreset;
use App\Models\PageTemplate;
use App\Support\Presets\CreativePreset;
use App\Support\Presets\PresetLibrary;
use App\Support\TemplateSanitizer;
use Tests\AdminTestCase;

class CreativePresetTest extends AdminTestCase
{
    public function test_la_plantilla_creativo_usa_bloques_y_enlaza_sus_secciones(): void
    {
        $html = app(TemplateSanitizer::class)->html((new CreativePreset)->html());

        foreach ([
            'id="inicio"',
            'id="proyectos"',
            'id="estudio"',
            'id="proceso"',
            'id="contacto"',
            '<h1',
            'data-cf-lightbox-gallery="yes"',
            'data-cf-form="yes"',
            'data-cf-footer="yes"',
            'data-top-button="yes"',
            'Estudio Creativo',
        ] as $needle) {
            $this->assertStringContainsString($needle, $html);
        }

        $this->assertStringContainsString('Ideas que<br', $html);
        $this->assertStringContainsString('/img/plantillas/creativo/estudio.jpg', $html);
        $this->assertSame(4, substr_count($html, '/img/plantillas/creativo/proyecto-'));
    }

    public function test_la_plantilla_creativo_no_deja_textos_de_ejemplo(): void
    {
        $html = app(TemplateSanitizer::class)->html((new CreativePreset)->html());

        foreach ([
            'Entrada al cargar',
            'Este bloque anima',
            'Escríbenos',
            'Nuestros momentos',
            'Entrenamientos',
            'Partidos',
            'Celebraciones',
            'Torneos',
            'Cada lado entra por su costado',
            'Conócenos',
            'Primer paso',
            'Segundo paso',
            'Tercer paso',
            'Arrastra aquí',
            'Tu marca',
            'Tu ciudad',
            'picsum.photos',
            'tudominio.com',
            'tucuenta',
        ] as $needle) {
            $this->assertStringNotContainsString($needle, $html);
        }
    }

    public function test_el_tema_creativo_define_marca_tipografia_y_anclas(): void
    {
        $preset = new CreativePreset;
        $css = $preset->settings()['theme_css'];

        $this->assertSame('', $preset->css());
        $this->assertSame(['bebas-neue', 'inter'], $preset->fonts());
        $this->assertStringContainsString('--cf-brand: #e8341f', $css);
        $this->assertStringContainsString('--cf-on-brand: #ffffff', $css);
        $this->assertStringContainsString('scroll-margin-top', $css);
    }

    public function test_crear_una_pagina_creativa_la_deja_como_borrador_con_su_menu(): void
    {
        $template = app(CreatePageFromPreset::class)->execute(
            PresetLibrary::find('creativo'),
            'Portafolio Norte',
            $this->admin()->id,
        );

        $this->assertSame('portafolio-norte', $template->slug);
        $this->assertFalse($template->is_active);
        $this->assertNull($template->published_at);
        $this->assertSame(['bebas-neue', 'inter'], $template->settings['fonts']);
        $this->assertStringContainsString('--cf-brand: #e8341f', $template->settings['theme_css']);
        $this->assertSame(5, $template->mainMenuItems()->count());
        $this->assertStringContainsString('Estudio Creativo', $template->html);
    }

    public function test_la_vista_previa_de_creativo_muestra_el_diseno(): void
    {
        $this->actingAs($this->admin())
            ->get(route('presets.preview', ['preset' => 'creativo']))
            ->assertOk()
            ->assertSee('Ideas que', false)
            ->assertSee('id="proyectos"', false)
            ->assertSee('Nube Café')
            ->assertSee('Bebas Neue', false)
            ->assertSee('--cf-brand: #e8341f', false)
            ->assertSee('data-cf-footer', false);
    }

    public function test_la_galeria_lista_las_dos_plantillas(): void
    {
        $this->actingAs($this->admin())
            ->get('/admin/plantillas')
            ->assertOk()
            ->assertSee('Agencia')
            ->assertSee('Creativo')
            ->assertSee('Portafolio de una página');
    }

    public function test_la_plantilla_creativa_no_crea_paginas_por_si_sola(): void
    {
        $antes = PageTemplate::query()->count();

        $this->assertNotNull(PresetLibrary::find('creativo'));
        $this->assertSame($antes, PageTemplate::query()->count());
    }
}
