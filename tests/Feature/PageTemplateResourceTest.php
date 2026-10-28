<?php

namespace Tests\Feature;

use App\Filament\Pages\Auth\Login;
use App\Filament\Pages\ConstructorHelp;
use App\Filament\Resources\PageTemplates\Pages\CreatePageTemplate;
use App\Filament\Resources\PageTemplates\Pages\EditPageTemplate;
use App\Filament\Resources\PageTemplates\Pages\ListPageTemplates;
use App\Filament\Resources\PageTemplates\PageTemplateResource;
use App\Models\PageTemplate;
use App\Models\PageTemplateVersion;
use App\Models\User;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\AdminTestCase;

class PageTemplateResourceTest extends AdminTestCase
{
    public function test_el_login_funciona_sin_segundo_factor(): void
    {
        $user = $this->admin();

        Livewire::test(Login::class)
            ->fillForm([
                'email' => $user->email,
                'password' => 'password',
            ])
            ->call('authenticate')
            ->assertHasNoFormErrors()
            ->assertSet('userUndertakingMultiFactorAuthentication', false);

        $this->assertAuthenticatedAs($user);
    }

    public function test_el_constructor_regresa_a_la_pestana_contenido(): void
    {
        $pagina = PageTemplate::factory()->create();

        $url = $pagina->grapesjsTerugUrl();

        $this->assertNotNull($url);
        $this->assertStringContainsString('tab=contenido', $url);

        $this->actingAs($this->admin())
            ->get($url)
            ->assertOk()
            ->assertSee('data-tab-key="contenido"', false)
            ->assertSee('activeTab: 3', false);
    }

    public function test_el_panel_y_el_recurso_responden_sin_segundo_factor(): void
    {
        $this->actingAs($this->admin())
            ->get('/admin')
            ->assertOk();

        $this->actingAs($this->admin())
            ->get(PageTemplateResource::getUrl('index'))
            ->assertOk()
            ->assertSee('Páginas');
    }

    public function test_se_pueden_listar_las_plantillas(): void
    {
        $classic = PageTemplate::query()->where('slug', 'clasica')->firstOrFail();

        Testable::actingAs($this->admin());

        Livewire::test(ListPageTemplates::class)
            ->assertCanSeeTableRecords([$classic]);
    }

    public function test_se_puede_crear_una_plantilla(): void
    {
        Testable::actingAs($this->admin());

        Livewire::test(CreatePageTemplate::class)
            ->fillForm([
                'name' => 'Portada de verano',
                'slug' => 'portada-verano',
                'settings.seo_title' => 'Portada de verano',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $template = PageTemplate::query()->where('slug', 'portada-verano')->firstOrFail();

        $this->assertSame('grapesjs', $template->render_mode);
        $this->assertFalse($template->is_active);
        $this->assertSame('Portada de verano', $template->settings['seo_title']);
    }

    public function test_al_crear_una_plantilla_grapesjs_se_abre_el_constructor(): void
    {
        Testable::actingAs($this->admin());

        $component = Livewire::test(CreatePageTemplate::class)
            ->fillForm([
                'name' => 'Portada directa',
                'slug' => 'portada-directa',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $template = PageTemplate::query()->where('slug', 'portada-directa')->firstOrFail();

        $component->assertRedirect($template->grapesjsEditorUrl());
    }

    public function test_la_pestana_contenido_muestra_el_acceso_al_constructor(): void
    {
        $grapesjs = PageTemplate::factory()->create(['render_mode' => 'grapesjs']);
        $classic = PageTemplate::query()->where('slug', 'clasica')->firstOrFail();

        Testable::actingAs($this->admin());

        $component = Livewire::test(EditPageTemplate::class, ['record' => $grapesjs->getRouteKey()])
            ->assertSee('Abrir constructor visual')
            ->assertSee('¿Cómo usar el editor?')
            ->assertSee('Publicación')
            ->assertSee('Publicar cambios');

        $this->assertDoesNotMatchRegularExpression(
            '/<a[^>]*grapesjs[^>]*target="_blank"/',
            $component->html(),
        );

        Livewire::test(EditPageTemplate::class, ['record' => $classic->getRouteKey()])
            ->assertSee('Crear versión editable');
    }

    public function test_la_plantilla_clasica_se_puede_duplicar_como_grapesjs(): void
    {
        $classic = PageTemplate::query()->where('slug', 'clasica')->firstOrFail();

        Testable::actingAs($this->admin());

        Livewire::test(EditPageTemplate::class, ['record' => $classic->getRouteKey()])
            ->call('duplicateAsGrapesjs')
            ->assertRedirect();

        $copy = PageTemplate::query()
            ->where('slug', 'like', 'clasica-grapesjs-%')
            ->firstOrFail();

        $this->assertSame('grapesjs', $copy->render_mode);
        $this->assertFalse($copy->is_active);
        $this->assertSame(5, $copy->menuItems()->count());
    }

    public function test_se_puede_crear_una_plantilla_con_menu(): void
    {
        Testable::actingAs($this->admin());

        Livewire::test(CreatePageTemplate::class)
            ->fillForm([
                'name' => 'Portada con menú',
                'slug' => 'portada-con-menu',
                'mainMenuItems' => [
                    [
                        'label' => 'Inicio',
                        'link_type' => 'anchor',
                        'link_value' => '#inicio',
                        'target' => '_self',
                        'is_visible' => true,
                    ],
                ],
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $template = PageTemplate::query()->where('slug', 'portada-con-menu')->firstOrFail();

        $this->assertDatabaseHas('menu_items', [
            'page_template_id' => $template->id,
            'location' => 'main',
            'label' => 'Inicio',
            'link_value' => '#inicio',
        ]);
    }

    public function test_el_acceso_al_constructor_se_abre_en_la_misma_pestana(): void
    {
        $template = PageTemplate::factory()->create(['render_mode' => 'grapesjs']);

        Testable::actingAs($this->admin());

        Livewire::test(ListPageTemplates::class)
            ->assertTableActionShouldNotOpenUrlInNewTab('design', $template);
    }

    public function test_el_formulario_ya_no_tiene_pestana_de_enlaces_del_pie(): void
    {
        $template = PageTemplate::factory()->create();

        Testable::actingAs($this->admin());

        Livewire::test(EditPageTemplate::class, ['record' => $template->getRouteKey()])
            ->assertOk()
            ->assertDontSee('Enlaces del pie de página');
    }

    public function test_el_editor_guarda_css_y_sanitiza_el_html(): void
    {
        $template = PageTemplate::factory()->create(['render_mode' => 'grapesjs']);

        $this->actingAs($this->admin())
            ->put(route('grapesjs.bewaar', ['plantilla', $template->getKey()]), [
                'project' => ['pages' => []],
                'html' => '<h1>Hola</h1><script>alert(1)</script><img src="x" onerror="alert(2)">',
                'css' => '#cf-template h1 { color: #FF4C00; } body { background: #fff; }',
            ])
            ->assertOk();

        $template->refresh();

        $this->assertSame('#cf-template h1 { color: #FF4C00; } body { background: #fff; }', $template->css);
        $this->assertStringContainsString('<h1>Hola</h1>', $template->html);
        $this->assertStringNotContainsString('<script>', $template->html);
        $this->assertStringNotContainsString('onerror', $template->html);
        $this->assertSame(['pages' => []], $template->gjs_data);
    }

    public function test_publicar_copia_el_borrador_a_la_version_publica(): void
    {
        $template = PageTemplate::factory()->create([
            'render_mode' => 'grapesjs',
            'html' => '<h1>Borrador anterior</h1>',
            'css' => '#cf-template h1 { color: red; }',
            'gjs_data' => ['pages' => []],
        ]);

        $template->forceFill([
            'html' => '<h1>Borrador nuevo</h1>',
            'css' => '#cf-template h1 { color: blue; }',
            'gjs_data' => ['pages' => [['id' => 'nuevo']]],
            'published_html' => '<h1>Publicado anterior</h1>',
            'published_css' => '#cf-template h1 { color: red; }',
            'published_gjs_data' => ['pages' => []],
        ])->saveQuietly();

        $this->actingAs($this->admin())
            ->post(route('page-templates.publish', ['template' => $template]))
            ->assertOk()
            ->assertJson(['publicado' => true]);

        $template->refresh();

        $this->assertSame('<h1>Borrador nuevo</h1>', $template->published_html);
        $this->assertSame('#cf-template h1 { color: blue; }', $template->published_css);
        $this->assertSame(['pages' => [['id' => 'nuevo']]], $template->published_gjs_data);
        $this->assertNotNull($template->published_at);
        $this->assertSame(1, $template->versions()->count());
        $this->assertSame('<h1>Borrador nuevo</h1>', $template->versions()->firstOrFail()->html);
    }

    public function test_la_url_antigua_del_constructor_redirige_a_la_nueva(): void
    {
        $template = PageTemplate::factory()->create(['render_mode' => 'grapesjs']);

        $this->actingAs($this->admin())
            ->get('/grapesjs/plantilla/'.$template->getKey())
            ->assertRedirect($template->grapesjsEditorUrl());
    }

    public function test_el_constructor_usa_una_url_nueva_versionada(): void
    {
        $template = PageTemplate::factory()->create(['render_mode' => 'grapesjs']);
        $url = $template->grapesjsEditorUrl();

        $this->assertStringContainsString('/constructor/plantilla/'.$template->getKey(), $url);
        $this->assertStringContainsString('?v=', $url);

        $this->actingAs($this->admin())->get($url)->assertOk();
    }

    public function test_el_editor_incluye_los_bloques_del_kit(): void
    {
        $template = PageTemplate::factory()->create(['render_mode' => 'grapesjs']);

        $this->actingAs($this->admin())
            ->get($template->grapesjsEditorUrl())
            ->assertOk()
            ->assertSee('Hero de bienvenida')
            ->assertSee('2 columnas')
            ->assertSee('4 columnas')
            ->assertSee('Tarjetas')
            ->assertSee('3 tarjetas ampliables')
            ->assertSee('4 tarjetas ampliables')
            ->assertSee('Nuestro equipo')
            ->assertSee('data-cf-lightbox', false)
            ->assertSee('[data-cf-lightbox-grid]', false)
            ->assertSee('[data-cf-directiva]', false)
            ->assertSee('4 contadores')
            ->assertSee('Más bloques')
            ->assertSee('Animaciones')
            ->assertSee('Entrada al cargar')
            ->assertSee('Contacto y redes')
            ->assertSee('Botón volver arriba')
            ->assertSee('Widget de WhatsApp')
            ->assertSee('data-wa-number', false)
            ->assertSee('data-wa-title', false)
            ->assertSee('data-wa-message', false)
            ->assertSee('Número con código de país')
            ->assertSee('Título del chat')
            ->assertSee('buscarWhatsapp', false)
            ->assertSee('cf-counter-field', false)
            ->assertSee('buscarContador', false)
            ->assertSee('ajustesContador', false)
            ->assertSee('data-counter-value', false)
            ->assertSee("editor.on('component:selected'", false)
            ->assertSee('Widget de WhatsApp', false)
            ->assertSee('stopImmediatePropagation', false)
            ->assertSee('data-top-enabled', false)
            ->assertSee('cf-top-enabled', false)
            ->assertSee("'data-top-button': 'yes'", false)
            ->assertSee("['data-reveal', 'up']", false)
            ->assertSee("['data-cf-footer', 'yes']", false)
            ->assertSee("'data-reveal': 'up'", false)
            ->assertSee("'data-reveal-delay': '150'", false)
            ->assertSee('Pies de página')
            ->assertSee('Pie con columnas')
            ->assertSee('Redes sociales')
            ->assertSee('LinkedIn')
            ->assertSee('YouTube')
            ->assertSee('Facebook')
            ->assertSee('cf-social', false)
            ->assertSee('data-cf-social-tone', false)
            ->assertSee('--cf-surface:#ffffff', false)
            ->assertSee('.dark{', false)
            ->assertSee('Secciones dinámicas')
            ->assertSee('data-reveal', false)
            ->assertSee('data-cf-loop', false)
            ->assertSee('__cielotronadorGjs', false)
            ->assertSee('keyframes cf-float', false)
            ->assertSee('data-gjs-help', false)
            ->assertSee('Guía rápida del constructor')
            ->assertSee('editor.js?v=', false)
            ->assertSee('no-store', false)
            ->assertSee('Productos en venta')
            ->assertSee('cf-ventas', false)
            ->assertSee('data-product-name', false)
            ->assertSee('data-product-description', false)
            ->assertSee('data-product-price', false)
            ->assertSee('data-product-currency', false)
            ->assertSee('data-product-price-note', false)
            ->assertSee('data-product-old-price', false)
            ->assertSee('data-product-badge-text', false)
            ->assertSee('data-product-url', false)
            ->assertSee('data-product-image-url', false)
            ->assertSee('Nombre del producto')
            ->assertSee('Enlace de pago')
            ->assertSee('URL de la imagen')
            ->assertSee('Mostrar etiqueta')
            ->assertSee('buscarProducto', false)
            ->assertSee('actualizarProducto', false)
            ->assertSee("'data-cf-product': 'yes'", false)
            ->assertSee('data-product-grid', false)
            ->assertSee('[data-product-grid]', false)
            ->assertSee('Carrusel')
            ->assertSee('Carrusel de imágenes')
            ->assertSee('data-cf-carousel', false)
            ->assertSee('data-carousel-track', false)
            ->assertSee('data-carousel-autoplay', false)
            ->assertSee('data-carousel-interval', false)
            ->assertSee('data-carousel-arrows', false)
            ->assertSee('data-carousel-dots', false)
            ->assertSee('data-carousel-slide', false)
            ->assertSee('data-slide-image', false)
            ->assertSee('data-slide-title', false)
            ->assertSee('data-slide-text', false)
            ->assertSee('data-slide-cta-text', false)
            ->assertSee('data-slide-cta-url', false)
            ->assertSee('Reproducción automática')
            ->assertSee('Mostrar flechas')
            ->assertSee('Mostrar puntos')
            ->assertSee('Texto del botón')
            ->assertSee('buscarCarrusel', false)
            ->assertSee('buscarDiapositiva', false)
            ->assertSee('actualizarDiapositiva', false)
            ->assertSee('[data-carousel-dot].active', false)
            ->assertSee('Imágenes del carrusel')
            ->assertSee('cf-carousel-slides', false)
            ->assertSee('Añadir imagen')
            ->assertSee('Pulsa Editar')
            ->assertSee('data-carousel-slides-list', false)
            ->assertSee('MAX_DIAPOSITIVAS', false)
            ->assertSee('Contacto y redes')
            ->assertSee('Formulario de contacto')
            ->assertSee('data-cf-form', false)
            ->assertSee('data-form-label-nombre', false)
            ->assertSee('data-form-label-telefono', false)
            ->assertSee('data-form-label-mensaje', false)
            ->assertSee('data-form-telefono', false)
            ->assertSee('data-form-success', false)
            ->assertSee('buscarFormulario', false)
            ->assertSee('actualizarFormulario', false)
            ->assertSee('actualizarFormulariosAntiguos', false)
            ->assertSee('data-form-upgraded', false)
            ->assertSee('inyectarCapasParallax', false)
            ->assertSee('Secciones dinámicas')
            ->assertSee('cf-noticias', false)
            ->assertSee('cf-galeria', false)
            ->assertSee('Navegación')
            ->assertSee('Noticias')
            ->assertSee("['link-block', 'quote', 'text-basic']", false)
            ->assertSee('quitarBloquesDelPreset', false);
    }

    public function test_el_editor_permite_activar_el_parallax_por_seccion(): void
    {
        $template = PageTemplate::factory()->create(['render_mode' => 'grapesjs']);

        $this->actingAs($this->admin())
            ->get($template->grapesjsEditorUrl())
            ->assertOk()
            ->assertSee('Fondo parallax')
            ->assertSee('Sin efecto')
            ->assertSee('Suave')
            ->assertSee('Intenso')
            ->assertSee('cf-parallax', false)
            ->assertSee('[data-cf-parallax]', false)
            ->assertSee('data-cf-parallax-layer', false)
            ->assertSee('inyectarCapasParallax', false);
    }

    public function test_el_editor_permite_editar_los_miembros_del_equipo(): void
    {
        $template = PageTemplate::factory()->create(['render_mode' => 'grapesjs']);

        $this->actingAs($this->admin())
            ->get($template->grapesjsEditorUrl())
            ->assertOk()
            ->assertSee('Miembro del equipo')
            ->assertSee('Nombre y apellido')
            ->assertSee('Entrenador, directora, etc.')
            ->assertSee('Fotografía (URL)')
            ->assertSee('https://facebook.com/...')
            ->assertSee('Web o portafolio')
            ->assertSee('cf-directiva-text', false)
            ->assertSee('cf-directiva-photo', false)
            ->assertSee('cf-directiva-url', false)
            ->assertSee('buscarTarjetaDirectiva', false)
            ->assertSee('data-directiva-link-off', false);
    }

    public function test_el_bundle_del_editor_expone_la_instancia_y_los_mensajes_en_espanol(): void
    {
        $bundle = (new Filesystem)->get(public_path('vendor/grapesjs/editor.js'));

        $this->assertStringContainsString('window.__cielotronadorGjs', $bundle);
        $this->assertStringContainsString('Guardando...', $bundle);
        $this->assertStringContainsString('Guardado', $bundle);
        $this->assertStringContainsString('Sin guardar', $bundle);
        $this->assertStringContainsString('No guardado: ', $bundle);
        $this->assertStringNotContainsString('Bewaard', $bundle);
        $this->assertStringNotContainsString('Bewaren', $bundle);
    }

    public function test_el_editor_incluye_las_mejoras_de_la_fase_1(): void
    {
        $template = PageTemplate::factory()->create(['render_mode' => 'grapesjs']);

        $response = $this->actingAs($this->admin())
            ->get($template->grapesjsEditorUrl())
            ->assertOk();

        $response
            ->assertSee('data-gjs-tema', false)
            ->assertSee('cf-borrador-editor', false)
            ->assertSee('beforeunload', false)
            ->assertSee('cf-borrador-', false)
            ->assertSee('data-cf-buscador', false)
            ->assertSee('Buscar bloques', false)
            ->assertSee('I18n.addMessages', false)
            ->assertSee('StyleManager.render', false)
            ->assertSee('normalizarFormularios', false)
            ->assertSee('alinearEtiquetas', false)
            ->assertSee('Restaurar', false)
            ->assertSee('Descartar', false)
            ->assertSee('Seleccionado:', false)
            ->assertSee('Añadir clase', false)
            ->assertSee('Tienes un borrador sin guardar')
            ->assertSee('Vista previa en modo oscuro')
            ->assertSee('COMANDOS_DISPOSITIVO', false)
            ->assertSee("'set-device-mobile': 'mobilePortrait'", false)
            ->assertSee('devicePreviewMode = true', false)
            ->assertSee('data-gjs-publicar', false)
            ->assertSee('guardarEnServidor', false)
            ->assertSee('asegurarLienzo', false)
            ->assertSee('vigilanteLienzo', false)
            ->assertSee('vigilanteTema', false)
            ->assertSee('type="search"', false);
    }

    public function test_el_formulario_de_plantilla_incluye_los_campos_seo(): void
    {
        $template = PageTemplate::factory()->create(['render_mode' => 'grapesjs']);

        Testable::actingAs($this->admin());

        Livewire::test(EditPageTemplate::class, ['record' => $template->getRouteKey()])
            ->assertOk()
            ->assertSee('Título para redes')
            ->assertSee('Imagen para redes')
            ->assertSee('Descripción para redes')
            ->assertSee('URL canónica')
            ->assertSee('Indexación')
            ->assertSee('Tipo de datos estructurados')
            ->assertSee('Teléfono para Google')
            ->assertSee('Dirección para Google');
    }

    public function test_la_ficha_de_la_plantilla_muestra_las_versiones_publicadas(): void
    {
        $template = PageTemplate::factory()->create(['render_mode' => 'grapesjs']);

        PageTemplateVersion::factory()->for($template)->create();

        Testable::actingAs($this->admin());

        Livewire::test(EditPageTemplate::class, ['record' => $template->getRouteKey()])
            ->assertOk()
            ->assertSee('Versiones publicadas');
    }

    public function test_el_slug_reservado_no_se_puede_usar(): void
    {
        Testable::actingAs($this->admin());

        Livewire::test(CreatePageTemplate::class)
            ->fillForm([
                'name' => 'Panel',
                'slug' => 'admin',
            ])
            ->call('create')
            ->assertHasFormErrors(['slug']);
    }

    public function test_los_campos_seo_respetan_los_largos_estandar(): void
    {
        $template = PageTemplate::factory()->create(['render_mode' => 'grapesjs']);

        Testable::actingAs($this->admin());

        Livewire::test(EditPageTemplate::class, ['record' => $template->getRouteKey()])
            ->fillForm([
                'settings.seo_title' => str_repeat('a', 61),
                'settings.seo_description' => str_repeat('b', 161),
                'settings.og_title' => str_repeat('c', 71),
                'settings.og_description' => str_repeat('d', 201),
            ])
            ->call('save')
            ->assertHasFormErrors([
                'settings.seo_title' => 'max',
                'settings.seo_description' => 'max',
                'settings.og_title' => 'max',
                'settings.og_description' => 'max',
            ]);
    }

    public function test_la_guia_del_constructor_se_abre_en_el_panel(): void
    {
        Testable::actingAs($this->admin());

        Livewire::test(ConstructorHelp::class)
            ->assertOk()
            ->assertSee('Qué significa cada botón')
            ->assertSee('Crear una página en 6 pasos')
            ->assertSee('Animación de entrada')
            ->assertSee('Movimiento continuo')
            ->assertSee('Botón volver arriba')
            ->assertSee('Pies de página')
            ->assertSee('Pie con columnas')
            ->assertSee('Redes sociales')
            ->assertSee('LinkedIn')
            ->assertSee('Widget de WhatsApp')
            ->assertSee('Número con código de país')
            ->assertSee('Título del chat')
            ->assertSee('Modo claro y oscuro')
            ->assertSee('Problemas comunes');
    }

    public function test_el_editor_sube_imagenes_al_disco_de_plantillas(): void
    {
        Storage::fake('plantillas');

        $template = PageTemplate::factory()->create(['render_mode' => 'grapesjs']);

        $response = $this->actingAs($this->admin())
            ->postJson(route('grapesjs.upload', ['plantilla', $template->getKey()]), [
                'bestanden' => [UploadedFile::fake()->image('foto.jpg')],
            ])
            ->assertOk()
            ->assertJsonStructure(['data' => [['type', 'src', 'name']]]);

        $files = Storage::disk('plantillas')->allFiles();

        $this->assertCount(1, $files);
        $this->assertStringNotContainsString('/', $files[0]);
        $this->assertSame('/img/plantillas', config('filesystems.disks.plantillas.url'));
        $this->assertSame('', config('grapesjs.uploads.map'));
        $this->assertSame('image', $response->json('data.0.type'));
    }

    public function test_el_editor_rechaza_archivos_que_no_son_imagenes(): void
    {
        Storage::fake('plantillas');

        $template = PageTemplate::factory()->create(['render_mode' => 'grapesjs']);

        $this->actingAs($this->admin())
            ->postJson(route('grapesjs.upload', ['plantilla', $template->getKey()]), [
                'bestanden' => [UploadedFile::fake()->create('documento.pdf', 10, 'application/pdf')],
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('bestanden.0');
    }

    public function test_la_plantilla_requiere_nombre_y_slug(): void
    {
        Testable::actingAs($this->admin());

        Livewire::test(CreatePageTemplate::class)
            ->fillForm([
                'name' => '',
                'slug' => '',
            ])
            ->call('create')
            ->assertHasFormErrors(['name' => 'required', 'slug' => 'required']);
    }

    public function test_se_puede_activar_una_plantilla_desde_la_tabla(): void
    {
        PageTemplate::query()->update(['is_active' => false]);

        $template = PageTemplate::factory()->create(['slug' => 'para-activar']);

        Testable::actingAs($this->admin());

        Livewire::test(ListPageTemplates::class)
            ->callTableAction('activate', $template)
            ->assertNotified('Página activada');

        $this->assertTrue($template->fresh()->is_active);
        $this->assertSame(1, PageTemplate::query()->where('is_active', true)->count());
    }

    public function test_se_puede_activar_una_plantilla_desde_el_editor(): void
    {
        PageTemplate::query()->update(['is_active' => false]);

        $template = PageTemplate::factory()->create(['slug' => 'desde-editor']);

        Testable::actingAs($this->admin());

        Livewire::test(EditPageTemplate::class, ['record' => $template->getRouteKey()])
            ->call('activate')
            ->assertRedirect(PageTemplateResource::getUrl('index'));

        $this->assertTrue($template->fresh()->is_active);
    }

    public function test_las_acciones_de_fila_estan_agrupadas(): void
    {
        Testable::actingAs($this->admin());

        $html = Livewire::test(ListPageTemplates::class)->html();

        $this->assertStringContainsString('Acciones', $html);
        $this->assertStringContainsString('Diseñar, publicar, duplicar o borrar', $html);
    }

    public function test_una_plantilla_activa_no_se_puede_borrar(): void
    {
        $classic = PageTemplate::query()->where('slug', 'clasica')->firstOrFail();

        Testable::actingAs($this->admin());

        Livewire::test(ListPageTemplates::class)
            ->assertTableActionHidden('delete', $classic);
    }

    public function test_un_usuario_sin_permisos_no_puede_acceder_al_recurso(): void
    {
        $user = User::factory()->create();
        $user->assignRole(Role::findOrCreate('panel_user'));

        Testable::actingAs($user);

        $this->assertFalse(PageTemplateResource::canViewAny());
    }

    public function test_un_usuario_sin_permisos_no_puede_abrir_el_editor(): void
    {
        $user = User::factory()->create();
        $user->assignRole(Role::findOrCreate('panel_user'));

        $template = PageTemplate::factory()->create();

        $this->actingAs($user)
            ->get($template->grapesjsEditorUrl())
            ->assertForbidden();
    }

    public function test_un_super_admin_puede_abrir_el_editor(): void
    {
        $template = PageTemplate::factory()->create();

        $this->actingAs($this->admin())
            ->get($template->grapesjsEditorUrl())
            ->assertOk()
            ->assertSee('CieloTronador');
    }
}
