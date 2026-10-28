<?php

namespace Tests\Feature;

use App\Actions\CreatePageFromPreset;
use App\Filament\Pages\TemplateGallery;
use App\Filament\Resources\PageTemplates\Pages\EditPageTemplate;
use App\Filament\Resources\PageTemplates\PageTemplateResource;
use App\Models\User;
use App\Support\Presets\AgencyPreset;
use App\Support\Presets\PresetLibrary;
use App\Support\TemplateFonts;
use App\Support\TemplateSanitizer;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Tests\AdminTestCase;

class PresetGalleryTest extends AdminTestCase
{
    public function test_la_plantilla_agencia_se_compone_de_bloques_del_constructor(): void
    {
        $html = app(TemplateSanitizer::class)->html((new AgencyPreset)->html());

        foreach ([
            'id="inicio"',
            'id="servicios"',
            'id="portafolio"',
            'id="nosotros"',
            'id="contacto"',
            'data-cf-lightbox-gallery="yes"',
            'data-cf-stats="yes"',
            'data-cf-form="yes"',
            'data-cf-footer="yes"',
            'data-top-button="yes"',
            'Agencia Nova',
        ] as $needle) {
            $this->assertStringContainsString($needle, $html);
        }
    }

    public function test_la_plantilla_no_deja_textos_de_ejemplo_ni_imagenes_externas(): void
    {
        $html = app(TemplateSanitizer::class)->html((new AgencyPreset)->html());

        foreach ([
            'Tu título principal',
            'Sobre nosotros',
            'Tres razones para elegirnos',
            'Nuestros momentos',
            'Cada lado entra por su costado',
            'Un lema inspirador',
            'Jugadores',
            'Tu ciudad, País',
            'tudominio.com',
            'tucuenta',
            'picsum.photos',
        ] as $needle) {
            $this->assertStringNotContainsString($needle, $html);
        }
    }

    public function test_la_plantilla_usa_imagenes_locales_y_enlaza_sus_secciones(): void
    {
        $html = app(TemplateSanitizer::class)->html((new AgencyPreset)->html());

        $this->assertStringContainsString('/img/plantillas/agencia/hero.jpg', $html);
        $this->assertStringContainsString('/img/plantillas/agencia/nosotros.jpg', $html);
        $this->assertSame(4, substr_count($html, '/img/plantillas/agencia/portafolio-'));

        $menu = collect((new AgencyPreset)->menu())->pluck('value')->all();

        $this->assertSame(['#inicio', '#servicios', '#portafolio', '#nosotros', '#contacto'], $menu);

        foreach ($menu as $anchor) {
            $this->assertStringContainsString('id="'.ltrim($anchor, '#').'"', $html);
        }
    }

    public function test_el_tema_de_la_plantilla_define_la_marca_y_las_tipografias(): void
    {
        $preset = new AgencyPreset;
        $css = $preset->settings()['theme_css'];

        $this->assertSame('', $preset->css());
        $this->assertStringContainsString('--cf-brand: #ffc800', $css);
        $this->assertStringContainsString('--cf-on-brand: #212529', $css);
        $this->assertStringContainsString('text-transform: uppercase', $css);
        $this->assertStringContainsString('scroll-margin-top', $css);
    }

    public function test_el_registro_expone_la_plantilla_agencia(): void
    {
        $preset = PresetLibrary::find('agencia');

        $this->assertInstanceOf(AgencyPreset::class, $preset);
        $this->assertSame('Agencia', $preset->name());
        $this->assertSame(['montserrat', 'roboto-slab'], $preset->fonts());
        $this->assertCount(6, PresetLibrary::collection());
        $this->assertSame('Creativo', PresetLibrary::find('creativo')?->name());
        $this->assertSame('Estudio Visual', PresetLibrary::find('estudio-visual')?->name());
        $this->assertSame('Corporativo', PresetLibrary::find('corporativo')?->name());
        $this->assertSame('Producto', PresetLibrary::find('producto')?->name());
        $this->assertSame('Club', PresetLibrary::find('club')?->name());
    }

    public function test_crear_una_pagina_desde_la_plantilla_la_deja_como_borrador_con_su_menu(): void
    {
        $user = $this->admin();

        $template = app(CreatePageFromPreset::class)->execute(
            PresetLibrary::find('agencia'),
            'Estudio Norte',
            $user->id,
        );

        $this->assertSame('estudio-norte', $template->slug);
        $this->assertFalse($template->is_active);
        $this->assertNull($template->published_at);
        $this->assertNull($template->published_html);
        $this->assertNull($template->gjs_data);
        $this->assertSame('grapesjs', $template->render_mode);
        $this->assertSame('Estudio Norte', $template->name);
        $this->assertSame(['montserrat', 'roboto-slab'], $template->settings['fonts']);
        $this->assertStringContainsString('--cf-brand: #ffc800', $template->settings['theme_css']);
        $this->assertSame(5, $template->mainMenuItems()->count());
        $this->assertStringContainsString('Agencia Nova', $template->html);

        $this->assertDatabaseHas('menu_items', [
            'page_template_id' => $template->id,
            'location' => 'main',
            'label' => 'Portafolio',
            'link_value' => '#portafolio',
            'is_visible' => true,
        ]);
    }

    public function test_el_slug_de_la_pagina_creada_no_se_repite(): void
    {
        $action = app(CreatePageFromPreset::class);
        $preset = PresetLibrary::find('agencia');

        $primera = $action->execute($preset, 'Agencia');
        $segunda = $action->execute($preset, 'Agencia');

        $this->assertSame('agencia', $primera->slug);
        $this->assertSame('agencia-2', $segunda->slug);
    }

    public function test_guardar_el_seo_conserva_el_tema_y_las_tipografias(): void
    {
        Testable::actingAs($this->admin());

        $template = app(CreatePageFromPreset::class)->execute(PresetLibrary::find('agencia'), 'Agencia SEO');

        Livewire::test(EditPageTemplate::class, ['record' => $template->getRouteKey()])
            ->fillForm(['settings.seo_title' => 'Nuevo título SEO'])
            ->call('save')
            ->assertHasNoFormErrors();

        $template->refresh();

        $this->assertSame('Nuevo título SEO', $template->settings['seo_title']);
        $this->assertSame(['montserrat', 'roboto-slab'], $template->settings['fonts']);
        $this->assertStringContainsString('--cf-brand: #ffc800', $template->settings['theme_css']);
    }

    public function test_la_vista_previa_de_la_plantilla_muestra_el_diseno_completo(): void
    {
        $this->actingAs($this->admin())
            ->get(route('presets.preview', ['preset' => 'agencia']))
            ->assertOk()
            ->assertSee('Agencia Nova')
            ->assertSee('Diseño que', false)
            ->assertSee('id="portafolio"', false)
            ->assertSee('Proyectos entregados')
            ->assertSee('Roboto Slab', false)
            ->assertSee('--cf-brand: #ffc800', false)
            ->assertSee('data-cf-footer', false);
    }

    public function test_la_vista_previa_requiere_permiso_para_crear_paginas(): void
    {
        $this->assertTrue(
            $this->get(route('presets.preview', ['preset' => 'agencia']))->isRedirect(),
        );

        $usuario = User::factory()->create();

        $this->actingAs($usuario)
            ->get(route('presets.preview', ['preset' => 'agencia']))
            ->assertForbidden();
    }

    public function test_usar_plantilla_crea_el_borrador_y_redirige_a_paginas(): void
    {
        Testable::actingAs($this->admin());

        Livewire::test(TemplateGallery::class)
            ->call('usar', 'agencia')
            ->assertRedirect(PageTemplateResource::getUrl('index'));

        $this->assertDatabaseHas('page_templates', [
            'name' => 'Agencia',
            'is_active' => false,
            'published_at' => null,
        ]);
    }

    public function test_la_galeria_se_abre_desde_paginas_y_no_del_menu(): void
    {
        $this->actingAs($this->admin())
            ->get('/admin/page-templates')
            ->assertOk()
            ->assertSee(TemplateGallery::getUrl(), false)
            ->assertSee('Plantillas');

        $this->assertFalse(TemplateGallery::shouldRegisterNavigation());
        $this->assertTrue(TemplateGallery::canAccess());
    }

    public function test_la_galeria_de_plantillas_esta_en_el_panel(): void
    {
        $this->actingAs($this->admin())
            ->get('/admin/plantillas')
            ->assertOk()
            ->assertSee('Plantillas para empezar')
            ->assertSee('Agencia')
            ->assertSee('Usar esta plantilla')
            ->assertSee('Vista previa');
    }

    public function test_todas_las_plantillas_traen_variante_oscura(): void
    {
        foreach (PresetLibrary::collection() as $preset) {
            $css = $preset->settings()['theme_css'];

            $this->assertStringContainsString('.dark body', $css, $preset->slug());
            $this->assertStringContainsString('--cf-surface', $css, $preset->slug());
        }
    }

    public function test_el_css_oscuro_se_ancla_al_html_y_no_al_contenido(): void
    {
        $css = app(TemplateSanitizer::class)->scopeCss('.dark body{--cf-surface:#18181b}
.dark h1{color:#fff}');

        $this->assertStringContainsString('html.dark #cf-template{--cf-surface:#18181b}', $css);
        $this->assertStringContainsString('html.dark #cf-template h1{color:#fff}', $css);
        $this->assertStringNotContainsString('#cf-template .dark', $css);
    }

    public function test_la_vista_previa_incluye_la_regla_oscura_de_la_plantilla(): void
    {
        $this->actingAs($this->admin())
            ->get(route('presets.preview', ['preset' => 'agencia']))
            ->assertOk()
            ->assertSee('html.dark #cf-template', false);
    }

    public function test_las_tipografias_aceptan_alias_o_nombre_visible(): void
    {
        $this->assertSame(['montserrat', 'roboto-slab'], TemplateFonts::resolve(['Montserrat', 'Roboto Slab']));
        $this->assertSame(['montserrat', 'roboto-slab'], TemplateFonts::resolve(['montserrat', 'roboto-slab']));
        $this->assertSame(TemplateFonts::DEFAULTS, TemplateFonts::resolve(['inexistente']));
        $this->assertSame(['inter'], array_slice(TemplateFonts::resolve(['inter']), 0, 1));
        $this->assertStringContainsString("'Montserrat'", TemplateFonts::cssVariables(['montserrat', 'roboto-slab']));
    }
}
