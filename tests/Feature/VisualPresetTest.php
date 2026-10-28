<?php

namespace Tests\Feature;

use App\Actions\CreatePageFromPreset;
use App\Models\PageTemplate;
use App\Support\Presets\PresetLibrary;
use App\Support\Presets\VisualPreset;
use App\Support\TemplateSanitizer;
use Tests\AdminTestCase;

class VisualPresetTest extends AdminTestCase
{
    public function test_el_hero_es_un_carrusel_de_tres_diapositivas(): void
    {
        $html = app(TemplateSanitizer::class)->html((new VisualPreset)->html());

        $this->assertSame(3, substr_count($html, 'data-carousel-slide="yes"'));
        $this->assertSame(3, substr_count($html, 'data-slide-cta-url="#portafolio"'));
        $this->assertSame(1, substr_count($html, '<h1'));
        $this->assertStringContainsString('<h1 data-carousel-title-text="yes"', $html);
        $this->assertStringContainsString('Luz que cuenta historias', $html);
        $this->assertStringContainsString('Cada detalle importa', $html);
        $this->assertStringContainsString('Tu marca, bien vista', $html);
        $this->assertStringContainsString('height:clamp(440px,80vh,760px)', $html);
        $this->assertStringContainsString('width:100%;max-width:none;margin:0;padding:0;', $html);
        $this->assertStringContainsString('/img/plantillas/estudio-visual/hero-1.jpg', $html);
        $this->assertStringContainsString('/img/plantillas/estudio-visual/hero-3.jpg', $html);
    }

    public function test_la_pagina_usa_las_secciones_y_anclas_del_menu(): void
    {
        $html = app(TemplateSanitizer::class)->html((new VisualPreset)->html());

        foreach ([
            'id="inicio"',
            'id="portafolio"',
            'id="estudio"',
            'id="servicios"',
            'id="contacto"',
            'data-cf-lightbox-gallery="yes"',
            'data-cf-stats="yes"',
            'data-cf-form="yes"',
            'data-cf-footer="yes"',
            'data-top-button="yes"',
            'Estudio Visual',
        ] as $needle) {
            $this->assertStringContainsString($needle, $html);
        }

        $this->assertSame(4, substr_count($html, '/img/plantillas/estudio-visual/serie-'));
        $this->assertSame(
            ['#inicio', '#portafolio', '#estudio', '#servicios', '#contacto'],
            collect((new VisualPreset)->menu())->pluck('value')->all(),
        );
    }

    public function test_la_plantilla_no_deja_textos_de_ejemplo(): void
    {
        $html = app(TemplateSanitizer::class)->html((new VisualPreset)->html());

        foreach ([
            'Título principal',
            'Segundo titular',
            'Tercer titular',
            'Lorem ipsum',
            'Ver más',
            'Saber más',
            'Contáctanos',
            'Nuestros momentos',
            'Entrenamientos',
            'Partidos',
            'Celebraciones',
            'Torneos',
            'Cada lado entra por su costado',
            'Conócenos',
            'Primer paso',
            'Arrastra aquí',
            'Tu ciudad',
            'picsum.photos',
            'tudominio.com',
            'tucuenta',
        ] as $needle) {
            $this->assertStringNotContainsString($needle, $html);
        }
    }

    public function test_el_tema_visual_define_marca_tipografia_y_anclas(): void
    {
        $preset = new VisualPreset;
        $css = $preset->settings()['theme_css'];

        $this->assertSame('', $preset->css());
        $this->assertSame(['playfair-display', 'lato'], $preset->fonts());
        $this->assertStringContainsString('--cf-brand: #c8a24a', $css);
        $this->assertStringContainsString('--cf-on-brand: #141414', $css);
        $this->assertStringContainsString('--cf-dark-surface: #111111', $css);
        $this->assertStringContainsString('scroll-margin-top', $css);
    }

    public function test_crear_una_pagina_visual_la_deja_como_borrador_con_su_menu(): void
    {
        $template = app(CreatePageFromPreset::class)->execute(
            PresetLibrary::find('estudio-visual'),
            'Fotografía Sur',
            $this->admin()->id,
        );

        $this->assertSame('fotografia-sur', $template->slug);
        $this->assertFalse($template->is_active);
        $this->assertNull($template->published_at);
        $this->assertSame(['playfair-display', 'lato'], $template->settings['fonts']);
        $this->assertStringContainsString('--cf-brand: #c8a24a', $template->settings['theme_css']);
        $this->assertSame(5, $template->mainMenuItems()->count());
        $this->assertStringContainsString('data-cf-carousel="yes"', $template->html);
    }

    public function test_la_vista_previa_del_estudio_visual_muestra_el_carrusel(): void
    {
        $this->actingAs($this->admin())
            ->get(route('presets.preview', ['preset' => 'estudio-visual']))
            ->assertOk()
            ->assertSee('Luz que cuenta historias')
            ->assertSee('data-cf-carousel="yes"', false)
            ->assertSee('data-carousel-slide="yes"', false)
            ->assertSee('Playfair Display', false)
            ->assertSee('--cf-brand: #c8a24a', false)
            ->assertSee('data-cf-footer', false);
    }

    public function test_la_galeria_lista_las_tres_plantillas(): void
    {
        $this->actingAs($this->admin())
            ->get('/admin/plantillas')
            ->assertOk()
            ->assertSee('Agencia')
            ->assertSee('Creativo')
            ->assertSee('Estudio Visual')
            ->assertSee('Hero con carrusel');
    }

    public function test_el_estudio_visual_no_crea_paginas_por_si_solo(): void
    {
        $antes = PageTemplate::query()->count();

        $this->assertNotNull(PresetLibrary::find('estudio-visual'));
        $this->assertSame($antes, PageTemplate::query()->count());
    }
}
