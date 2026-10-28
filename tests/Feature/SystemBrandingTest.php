<?php

namespace Tests\Feature;

use App\Filament\Pages\SystemSettings;
use App\Models\PageTemplate;
use App\Models\User;
use App\Support\Branding;
use App\Support\TemplateRenderer;
use Database\Seeders\SystemOwnerSeeder;
use Filament\Facades\Filament;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\AdminTestCase;

class SystemBrandingTest extends AdminTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('branding');
    }

    public function test_solo_super_admin_puede_abrir_la_configuracion_del_sistema(): void
    {
        $this->actingAs($this->admin())
            ->get(SystemSettings::getUrl())
            ->assertOk()
            ->assertSee('Configuración del sistema')
            ->assertSee('Guardar cambios');
    }

    public function test_los_demas_roles_no_pueden_abrir_ni_ver_el_modulo(): void
    {
        $user = User::factory()->create();
        $user->assignRole(Role::findOrCreate('panel_user'));
        $user->assignRole(Role::findOrCreate('Directora'));

        $this->actingAs($user);

        $this->assertFalse(SystemSettings::canAccess());

        $this->get(SystemSettings::getUrl())->assertForbidden();

        $this->get('/admin')
            ->assertOk()
            ->assertDontSee('Configuración del sistema');
    }

    public function test_el_grupo_configuracion_queda_al_final_del_menu(): void
    {
        $this->actingAs($this->admin())
            ->get('/admin')
            ->assertOk();

        $groups = collect(Filament::getNavigation())
            ->map(fn ($group): ?string => $group->getLabel() instanceof Htmlable
                ? $group->getLabel()->toHtml()
                : $group->getLabel())
            ->filter()
            ->values()
            ->all();

        $this->assertSame('Configuración', end($groups));
    }

    public function test_se_puede_guardar_la_identidad_y_los_archivos(): void
    {
        $this->actingAs($this->admin());

        Livewire::test(SystemSettings::class)
            ->fillForm([
                'company_name' => 'Acme',
                'tagline' => 'Creamos soluciones',
                'city' => 'Concepción, Chile',
                'contact_email' => 'hola@acme.cl',
                'instagram_url' => 'https://www.instagram.com/acme/',
                'logo_light' => [UploadedFile::fake()->image('claro.png')],
                'logo_dark' => [UploadedFile::fake()->image('oscuro.png')],
                'favicon' => [UploadedFile::fake()->image('icono.png')],
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $branding = Branding::current();

        $this->assertSame('Acme', $branding['company_name']);
        $this->assertSame('Creamos soluciones', $branding['tagline']);
        $this->assertSame('Concepción, Chile', $branding['city']);
        $this->assertSame('hola@acme.cl', $branding['contact_email']);
        $this->assertNotNull($branding['logo_light']);
        $this->assertNotNull($branding['logo_dark']);
        $this->assertNotNull($branding['favicon']);

        Storage::disk('branding')->assertExists($branding['logo_light']);
        Storage::disk('branding')->assertExists($branding['logo_dark']);
        Storage::disk('branding')->assertExists($branding['favicon']);
    }

    public function test_la_portada_el_login_y_el_favicon_leen_el_branding(): void
    {
        $this->guardarBranding();

        $this->get(route('landing'))
            ->assertOk()
            ->assertSee('Acme')
            ->assertSee('Creamos soluciones')
            ->assertSee('Concepción, Chile')
            ->assertSee('/branding/logo-light')
            ->assertSee('/branding/logo-dark')
            ->assertSee('/branding/favicon');

        $this->get('/admin/login')
            ->assertOk()
            ->assertSee('Acme')
            ->assertSee('/branding/logo-light');

        $this->get(route('politica.cookies'))
            ->assertOk()
            ->assertSee('/branding/favicon');
    }

    public function test_la_ruta_de_marca_sirve_el_archivo_subido(): void
    {
        $this->guardarBranding();

        $this->get('/branding/logo-light')
            ->assertOk()
            ->assertHeader('Content-Type', 'image/png');
    }

    public function test_la_ruta_de_marca_usa_el_logo_original_si_no_hay_uno_personalizado(): void
    {
        $this->get('/branding/logo-light')
            ->assertOk()
            ->assertHeader('Content-Type', 'image/png');
    }

    public function test_al_reemplazar_un_logo_se_borra_el_archivo_anterior(): void
    {
        $anterior = UploadedFile::fake()->image('anterior.png')->store('', 'branding');

        Branding::save(['logo_light' => $anterior]);

        $nuevo = UploadedFile::fake()->image('nuevo.png')->store('', 'branding');

        Branding::save(['logo_light' => $nuevo]);

        Storage::disk('branding')->assertMissing($anterior);
        Storage::disk('branding')->assertExists($nuevo);
        $this->assertSame($nuevo, Branding::current()['logo_light']);
    }

    public function test_restaurar_la_identidad_borra_los_archivos_personalizados(): void
    {
        $logo = UploadedFile::fake()->image('logo.png')->store('', 'branding');

        Branding::save([
            'company_name' => 'Acme',
            'logo_light' => $logo,
        ]);

        Branding::restoreDefaults();

        $this->assertSame(Branding::defaults()['company_name'], Branding::name());
        $this->assertNull(Branding::current()['logo_light']);
        Storage::disk('branding')->assertMissing($logo);
    }

    public function test_el_nombre_se_limpia_y_nunca_queda_vacio(): void
    {
        Branding::save(['company_name' => '<b>Acme</b>']);
        $this->assertSame('Acme', Branding::name());

        Branding::save(['company_name' => '   ']);
        $this->assertSame('CieloTronador', Branding::name());
    }

    public function test_el_formulario_valida_el_nombre_y_el_correo(): void
    {
        $this->actingAs($this->admin());

        Livewire::test(SystemSettings::class)
            ->fillForm(['company_name' => ''])
            ->call('save')
            ->assertHasFormErrors(['company_name' => 'required']);

        Livewire::test(SystemSettings::class)
            ->fillForm([
                'company_name' => 'Acme',
                'contact_email' => 'esto-no-es-un-correo',
            ])
            ->call('save')
            ->assertHasFormErrors(['contact_email']);
    }

    public function test_las_plantillas_guardadas_usan_el_logo_configurado(): void
    {
        $template = PageTemplate::factory()->create([
            'html' => '<section><img src="/img/logo.png" alt="Logotipo"></section>',
        ]);

        $html = app(TemplateRenderer::class)->render($template);

        $this->assertStringContainsString('src="/branding/logo-light"', $html);
        $this->assertStringContainsString('data-brand-logo-light="/branding/logo-light"', $html);
        $this->assertStringContainsString('data-brand-logo-dark="/branding/logo-dark"', $html);
        $this->assertStringContainsString('alt="CieloTronador"', $html);
    }

    public function test_el_pie_global_usa_el_branding(): void
    {
        $this->guardarBranding();

        PageTemplate::query()->update(['is_active' => false]);

        PageTemplate::factory()->active()->create([
            'slug' => 'sin-pie-branding',
            'html' => '<section>Contenido sin pie propio</section>',
        ]);

        $this->get(route('landing'))
            ->assertOk()
            ->assertSee('data-global-footer', false)
            ->assertSee('Acme')
            ->assertSee('Concepción, Chile')
            ->assertSee('/branding/logo-light');
    }

    public function test_el_seeder_asigna_super_admin_al_correo_configurado(): void
    {
        config(['system.owner_email' => 'duena@example.com']);

        $user = User::factory()->create(['email' => 'duena@example.com']);

        $this->seed(SystemOwnerSeeder::class);

        $this->assertTrue($user->fresh()->hasRole('super_admin'));
    }

    private function guardarBranding(): void
    {
        Branding::save([
            'company_name' => 'Acme',
            'tagline' => 'Creamos soluciones',
            'city' => 'Concepción, Chile',
            'contact_email' => 'hola@acme.cl',
            'instagram_url' => 'https://www.instagram.com/acme/',
            'logo_light' => UploadedFile::fake()->image('claro.png')->store('', 'branding'),
            'logo_dark' => UploadedFile::fake()->image('oscuro.png')->store('', 'branding'),
            'favicon' => UploadedFile::fake()->image('icono.png')->store('', 'branding'),
        ]);
    }
}
