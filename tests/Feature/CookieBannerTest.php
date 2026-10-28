<?php

namespace Tests\Feature;

use App\Filament\Pages\CookieBannerSettings;
use App\Filament\Resources\PageTemplates\PageTemplateResource;
use App\Models\PageTemplate;
use App\Models\SiteSetting;
use App\Models\User;
use App\Support\CookieBanner;
use App\Support\PageBlockLibrary;
use App\Support\Presets\PresetLibrary;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\AdminTestCase;

class CookieBannerTest extends AdminTestCase
{
    public function test_los_textos_por_defecto_son_los_actuales(): void
    {
        $this->assertSame([
            'message' => 'Usamos cookies necesarias para el funcionamiento del sitio y, con tu consentimiento, para mejorar tu experiencia.',
            'policy_prefix' => 'Consulta nuestra',
            'policy_label' => 'Política de Cookies',
            'necessary_label' => 'Solo necesarias',
            'accept_label' => 'Aceptar todas',
        ], CookieBanner::current());
    }

    public function test_la_configuracion_global_cambia_el_aviso_en_todas_las_plantillas(): void
    {
        SiteSetting::put(CookieBanner::KEY, [
            'message' => 'Texto personalizado para todo el sitio.',
            'policy_prefix' => 'Lee nuestra',
            'policy_label' => 'Política de cookies',
            'necessary_label' => 'Solo lo necesario',
            'accept_label' => 'Acepto todo',
        ]);

        $this->actingAs($this->admin());

        foreach (PresetLibrary::collection() as $preset) {
            $this->get(route('presets.preview', ['preset' => $preset->slug()]))
                ->assertOk()
                ->assertSee('id="cookie-banner"', false)
                ->assertSee('Texto personalizado para todo el sitio.')
                ->assertSee('Lee nuestra')
                ->assertSee('Política de cookies')
                ->assertSee('Solo lo necesario')
                ->assertSee('Acepto todo');
        }
    }

    public function test_el_aviso_aparece_en_la_portada_clasica_y_en_las_paginas_legales(): void
    {
        $this->get(route('landing'))
            ->assertOk()
            ->assertSee('id="cookie-banner"', false)
            ->assertSee(CookieBanner::defaults()['message']);

        $this->get(route('politica.cookies'))
            ->assertOk()
            ->assertSee('id="cookie-banner"', false)
            ->assertSee(CookieBanner::defaults()['necessary_label']);
    }

    public function test_el_aviso_sigue_apareciendo_si_el_contenido_esta_vacio(): void
    {
        $template = PageTemplate::factory()->create([
            'name' => 'Página sin contenido',
            'slug' => 'pagina-sin-contenido',
            'render_mode' => 'grapesjs',
            'html' => '',
            'published_html' => '',
            'is_active' => false,
            'published_at' => now(),
        ]);

        $this->get(route('page-templates.show', ['template' => $template->slug]))
            ->assertOk()
            ->assertSee('id="cookie-banner"', false)
            ->assertSee(CookieBanner::defaults()['message']);
    }

    public function test_los_campos_vacios_o_con_html_vuelven_al_texto_por_defecto(): void
    {
        SiteSetting::put(CookieBanner::KEY, [
            'message' => '<script>alert(1)</script>',
            'policy_prefix' => '',
            'policy_label' => '   ',
            'necessary_label' => null,
            'accept_label' => '<b>Acepto</b>',
        ]);

        $aviso = CookieBanner::current();

        $this->assertSame(CookieBanner::defaults()['message'], $aviso['message']);
        $this->assertSame(CookieBanner::defaults()['policy_prefix'], $aviso['policy_prefix']);
        $this->assertSame(CookieBanner::defaults()['policy_label'], $aviso['policy_label']);
        $this->assertSame(CookieBanner::defaults()['necessary_label'], $aviso['necessary_label']);
        $this->assertSame('Acepto', $aviso['accept_label']);
    }

    public function test_guardar_los_ajustes_actualiza_la_cache(): void
    {
        $this->assertSame(CookieBanner::defaults()['message'], CookieBanner::current()['message']);

        SiteSetting::put(CookieBanner::KEY, ['message' => 'Nuevo mensaje']);

        $this->assertSame('Nuevo mensaje', CookieBanner::current()['message']);
    }

    public function test_no_existe_un_bloque_de_cookies_en_la_biblioteca(): void
    {
        $this->assertNotContains('🍪 Cookies', PageBlockLibrary::labels());
        $this->assertNotContains('Aviso de cookies', PageBlockLibrary::labels());
    }

    public function test_la_pagina_de_ajustes_carga_y_guarda(): void
    {
        $this->actingAs($this->admin());

        Livewire::test(CookieBannerSettings::class)
            ->assertOk()
            ->assertFormSet([
                'message' => CookieBanner::defaults()['message'],
            ])
            ->fillForm([
                'message' => 'Nuevo mensaje desde el panel.',
                'policy_prefix' => 'Lee',
                'policy_label' => 'la política',
                'necessary_label' => 'Solo necesarias',
                'accept_label' => 'Aceptar',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame('Nuevo mensaje desde el panel.', CookieBanner::current()['message']);
        $this->assertDatabaseHas('site_settings', ['key' => CookieBanner::KEY]);
    }

    public function test_la_pagina_de_ajustes_rechaza_campos_vacios(): void
    {
        $this->actingAs($this->admin());

        Livewire::test(CookieBannerSettings::class)
            ->fillForm(['message' => ''])
            ->call('save')
            ->assertHasFormErrors(['message' => 'required']);

        $this->assertSame(CookieBanner::defaults()['message'], CookieBanner::current()['message']);
    }

    public function test_restaurar_texto_por_defecto(): void
    {
        SiteSetting::put(CookieBanner::KEY, [
            'message' => 'Texto cambiado',
            'accept_label' => 'Acepto todo',
        ]);

        $this->actingAs($this->admin());

        Livewire::test(CookieBannerSettings::class)
            ->callAction('restore');

        $this->assertSame(CookieBanner::defaults()['message'], CookieBanner::current()['message']);
        $this->assertSame(CookieBanner::defaults()['accept_label'], CookieBanner::current()['accept_label']);
    }

    public function test_la_pagina_de_ajustes_requiere_permiso_para_editar_paginas(): void
    {
        $this->actingAs($this->admin())
            ->get(CookieBannerSettings::getUrl())
            ->assertOk();

        $user = User::factory()->create();
        $user->assignRole('Jugador');

        $this->actingAs($user)
            ->get(CookieBannerSettings::getUrl())
            ->assertForbidden();
    }

    public function test_la_politica_usa_los_mismos_botones_que_el_aviso(): void
    {
        SiteSetting::put(CookieBanner::KEY, [
            'necessary_label' => 'Solo lo necesario',
            'accept_label' => 'Acepto todo',
        ]);

        $this->get(route('politica.cookies'))
            ->assertOk()
            ->assertSee('Solo lo necesario')
            ->assertSee('Acepto todo');
    }

    public function test_el_editor_enlaza_a_la_configuracion_del_aviso(): void
    {
        $this->actingAs($this->admin());

        $template = PageTemplate::factory()->create([
            'render_mode' => 'grapesjs',
            'slug' => 'editor-cookies',
        ]);

        $this->get(route('page-templates.editor', ['template' => $template, 'v' => '1']))
            ->assertOk()
            ->assertSee(PageTemplateResource::getUrl('index', ['pestana' => 'aviso']))
            ->assertSee('Cookies');
    }

    public function test_la_seccion_paginas_incluye_solo_paginas_y_aviso(): void
    {
        $this->actingAs($this->admin())
            ->get(PageTemplateResource::getUrl('index'))
            ->assertOk()
            ->assertSee('data-pestana="paginas"', false)
            ->assertSee('data-pestana="aviso"', false)
            ->assertDontSee('data-pestana="tienda"', false);
    }

    public function test_la_pestana_aviso_muestra_el_formulario_de_cookies(): void
    {
        $this->actingAs($this->admin())
            ->get(PageTemplateResource::getUrl('index', ['pestana' => 'aviso']))
            ->assertOk()
            ->assertSee('Aviso de cookies')
            ->assertSee('Texto del aviso')
            ->assertSee('Guardar aviso');
    }

    public function test_las_pestanas_respetan_los_permisos(): void
    {
        $user = User::factory()->create();
        $user->assignRole(Role::findOrCreate('panel_user'));
        $user->givePermissionTo('ViewAny:PageTemplate');

        $this->actingAs($user)
            ->get(PageTemplateResource::getUrl('index'))
            ->assertOk()
            ->assertSee('data-pestana="paginas"', false)
            ->assertDontSee('data-pestana="tienda"', false)
            ->assertDontSee('data-pestana="aviso"', false);

        $this->actingAs($user)
            ->get(PageTemplateResource::getUrl('index', ['pestana' => 'tienda']))
            ->assertOk()
            ->assertSee('data-pestana="paginas"', false)
            ->assertDontSee('data-pestana="tienda"', false)
            ->assertDontSee('data-tienda="productos"', false);
    }
}
