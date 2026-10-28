<?php

namespace Tests\Feature;

use App\Models\CategoriaProducto;
use App\Models\Galeria;
use App\Models\MenuItem;
use App\Models\PageTemplate;
use App\Models\PageTemplateVersion;
use App\Models\Producto;
use App\Support\BlockTheme;
use App\Support\CarouselWidget;
use App\Support\DirectivaWidget;
use App\Support\EditorLocale;
use App\Support\FormWidget;
use App\Support\GalleryCardsWidget;
use App\Support\PageBlockLibrary;
use App\Support\ParallaxWidget;
use App\Support\SalesWidget;
use App\Support\SocialLinkLibrary;
use App\Support\StatsWidget;
use App\Support\TemplateSanitizer;
use App\Support\WhatsAppWidget;
use Database\Seeders\PageTemplateDemoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tests\TestCase;

class PageTemplateTest extends TestCase
{
    use RefreshDatabase;

    public function test_la_plantilla_clasica_se_muestra_por_defecto(): void
    {
        $html = $this->get(route('landing'))->assertOk()->getContent();

        $this->assertStringContainsString('Quiénes Somos', $html);
        $this->assertStringContainsString('Últimas noticias', $html);
        $this->assertStringContainsString(url('/admin/login'), $html);
    }

    public function test_el_kit_de_bloques_incluye_estructura_y_contenido(): void
    {
        $labels = PageBlockLibrary::labels();

        $this->assertContains('🏠 Hero de bienvenida', $labels);
        $this->assertContains('📦 Sección', $labels);
        $this->assertContains('🔠 Título grande (H1)', $labels);
        $this->assertContains('📋 Lista con viñetas', $labels);
        $this->assertContains('🖼️ Imagen', $labels);
        $this->assertContains('⚪ Botón secundario', $labels);
        $this->assertContains('🧱 2 columnas', $labels);
        $this->assertContains('🧱 4 columnas', $labels);
        $this->assertContains('🧱 1/3 + 2/3', $labels);
        $this->assertContains('🔲 Cuadrícula 2×2', $labels);
        $this->assertContains('🌑 Sección oscura', $labels);
        $this->assertContains('📊 Tabla', $labels);
        $this->assertContains('🗂️ Tarjetas', $labels);
        $this->assertContains('🖼️ 3 tarjetas ampliables', $labels);
        $this->assertContains('🖼️ 4 tarjetas ampliables', $labels);
        $this->assertContains('👥 Nuestro equipo', $labels);
        $this->assertContains('✨ Aparición escalonada', $labels);
        $this->assertContains('⬅️➡️ Columnas deslizantes', $labels);
        $this->assertContains('⚡ Entrada al cargar', $labels);
        $this->assertContains('🌊 Movimiento continuo', $labels);
        $this->assertContains('⬆️ Botón volver arriba', $labels);
        $this->assertContains('💬 Widget de WhatsApp', $labels);
        $this->assertContains('🔗 Redes sociales', $labels);
        $this->assertContains('🌑 Redes sociales (oscuro)', $labels);
        $this->assertContains('🏁 Pie simple centrado', $labels);
        $this->assertContains('🏁 Pie con columnas', $labels);
        $this->assertContains('🌑 Pie oscuro', $labels);
        $this->assertContains('➖ Pie minimalista', $labels);
        $this->assertContains('📰 Noticias', $labels);
        $this->assertContains('🖼️ Galería de fotos', $labels);
        $this->assertContains('📝 Formulario de contacto', $labels);
        $this->assertContains('🛒 Productos en venta', $labels);
        $this->assertContains('🎠 Carrusel de imágenes', $labels);
    }

    public function test_los_bloques_estan_agrupados_en_categorias_amplias(): void
    {
        $categorias = collect(PageBlockLibrary::all())
            ->groupBy('category')
            ->map->count();

        $this->assertSame([
            'Básicos',
            'Estructura',
            'Secciones dinámicas',
            'Contacto y redes',
            'Navegación',
            'Animaciones',
        ], $categorias->keys()->all());

        $this->assertGreaterThanOrEqual(
            3,
            $categorias->min(),
            'Cada categoría debe reunir al menos tres bloques para no crear un grupo por componente.',
        );
    }

    public function test_los_bloques_usan_tokens_para_adaptarse_al_modo_oscuro(): void
    {
        $contents = collect(PageBlockLibrary::all())->pluck('content')->implode("\n");

        $this->assertStringContainsString('var(--cf-surface, #ffffff)', $contents);
        $this->assertStringContainsString('var(--cf-ink, #18181b)', $contents);
        $this->assertStringContainsString('var(--cf-border, #e4e4e7)', $contents);
        $this->assertStringContainsString('var(--cf-brand, #ff4c00)', $contents);
        $this->assertStringContainsString('var(--cf-dark-surface, #0f172a)', $contents);

        foreach ([
            'background-color:#ffffff',
            'color:#18181b',
            'color:#52525b',
            'color:#d4d4d8',
            'border:1px solid #e4e4e7',
        ] as $fixedColor) {
            $this->assertStringNotContainsString($fixedColor, $contents);
        }
    }

    public function test_el_tema_expone_variables_para_claro_y_oscuro(): void
    {
        $css = BlockTheme::css();

        $this->assertStringContainsString(':root{', $css);
        $this->assertStringContainsString('.dark{', $css);
        $this->assertStringContainsString('--cf-surface:#ffffff', $css);
        $this->assertStringContainsString('--cf-surface:#18181b', $css);
        $this->assertStringContainsString('--cf-ink:#fafafa', $css);
    }

    public function test_la_pagina_incluye_los_tokens_de_color(): void
    {
        PageTemplate::query()->update(['is_active' => false]);

        PageTemplate::factory()->active()->create([
            'slug' => 'con-tokens',
            'html' => '<section style="background-color:var(--cf-surface);color:var(--cf-ink)">Contenido</section>',
            'css' => '',
        ]);

        $html = $this->get(route('landing'))->assertOk()->getContent();

        $this->assertStringContainsString('--cf-surface:#ffffff', $html);
        $this->assertStringContainsString('.dark{', $html);
        $this->assertStringContainsString('--cf-surface:#18181b', $html);
        $this->assertStringContainsString('background-color:var(--cf-surface)', $html);
    }

    public function test_el_sanitizador_conserva_las_variables_de_color(): void
    {
        $html = app(TemplateSanitizer::class)->html(
            '<div style="background-color:var(--cf-surface);color:var(--cf-ink);border:1px solid var(--cf-border)">x</div>',
        );

        $this->assertStringContainsString('var(--cf-surface)', $html);
        $this->assertStringContainsString('var(--cf-ink)', $html);
        $this->assertStringContainsString('var(--cf-border)', $html);
    }

    public function test_el_seeder_demo_crea_una_plantilla_editable(): void
    {
        $this->seed(PageTemplateDemoSeeder::class);

        $demo = PageTemplate::query()->where('slug', 'free')->firstOrFail();

        $this->assertSame('grapesjs', $demo->render_mode);
        $this->assertFalse($demo->is_active);
        $this->assertStringContainsString('Tres razones para elegirnos', $demo->html);
        $this->assertStringContainsString('data-reveal="up"', $demo->html);
        $this->assertStringContainsString('data-reveal-delay', $demo->html);
        $this->assertStringContainsString('data-top-button="yes"', $demo->html);
        $this->assertStringContainsString('data-cf-footer="yes"', $demo->html);
        $this->assertStringContainsString('data-cf-section="noticias"', $demo->html);
        $this->assertStringContainsString('data-cf-section="galeria"', $demo->html);
        $this->assertStringContainsString('data-cf-section="ventas"', $demo->html);
        $this->assertStringContainsString('data-cf-form="yes"', $demo->html);
        $this->assertSame(5, $demo->menuItems()->count());
    }

    public function test_la_plantilla_demo_se_renderiza_con_secciones_dinamicas(): void
    {
        $this->seed(PageTemplateDemoSeeder::class);

        PageTemplate::query()->update(['is_active' => false]);
        PageTemplate::query()->where('slug', 'free')->update(['is_active' => true]);
        cache()->forget('page_templates.active_id');

        $html = $this->get(route('landing'))->assertOk()->getContent();

        $this->assertStringContainsString('Tres razones para elegirnos', $html);
        $this->assertStringContainsString('Últimas noticias', $html);
        $this->assertStringContainsString('Escríbenos', $html);
        $this->assertStringContainsString(url('/admin/login'), $html);
    }

    public function test_una_plantilla_grapesjs_activa_reemplaza_la_portada(): void
    {
        PageTemplate::query()->update(['is_active' => false]);

        $template = PageTemplate::factory()->active()->create([
            'name' => 'Portada nueva',
            'slug' => 'portada-nueva',
            'html' => '<section id="inicio"><h1>Nueva portada</h1></section><section data-cf-section="noticias"></section>',
        ]);

        MenuItem::factory()->create([
            'page_template_id' => $template->id,
            'label' => 'Novedades',
            'link_type' => 'anchor',
            'link_value' => '#noticias',
        ]);

        $html = $this->get(route('landing'))->assertOk()->getContent();

        $this->assertStringContainsString('Nueva portada', $html);
        $this->assertStringContainsString('Novedades', $html);
        $this->assertStringContainsString('Últimas noticias', $html);
        $this->assertStringContainsString(url('/admin/login'), $html);
        $this->assertStringNotContainsString('data-parallax', $html);
    }

    public function test_el_html_de_la_plantilla_se_sanitiza(): void
    {
        PageTemplate::query()->update(['is_active' => false]);

        PageTemplate::factory()->active()->create([
            'slug' => 'con-script',
            'html' => '<div><script>alert(1)</script><img src="x" onerror="alert(2)"><a href="javascript:alert(3)">mal</a><p style="color:red">contenido seguro</p></div>',
        ]);

        $html = $this->get(route('landing'))->assertOk()->getContent();

        $this->assertStringNotContainsString('<script>alert(1)</script>', $html);
        $this->assertStringNotContainsString('onerror=', $html);
        $this->assertStringNotContainsString('javascript:alert', $html);
        $this->assertStringContainsString('contenido seguro', $html);
    }

    public function test_las_plantillas_grapesjs_conservan_las_animaciones(): void
    {
        PageTemplate::query()->update(['is_active' => false]);

        PageTemplate::factory()->active()->create([
            'slug' => 'con-animaciones',
            'html' => '<section data-reveal="up" data-reveal-delay="150"><h1 data-reveal="zoom" data-reveal-trigger="load" data-cf-loop="float">Animado</h1></section>',
        ]);

        $html = $this->get(route('landing'))->assertOk()->getContent();

        $this->assertStringContainsString('data-reveal="up"', $html);
        $this->assertStringContainsString('data-reveal-delay="150"', $html);
        $this->assertStringContainsString('data-reveal-trigger="load"', $html);
        $this->assertStringContainsString('data-cf-loop="float"', $html);
        $this->assertStringContainsString('@keyframes cf-float', $html);
    }

    public function test_las_plantillas_grapesjs_conservan_el_ajuste_de_parallax(): void
    {
        $html = app(TemplateSanitizer::class)->html(
            '<section data-cf-parallax="soft"><div data-parallax="0.08" data-parallax-x="0.03"></div></section>'
        );

        $this->assertStringContainsString('data-cf-parallax="soft"', $html);
        $this->assertStringContainsString('data-parallax="0.08"', $html);
        $this->assertStringContainsString('data-parallax-x="0.03"', $html);
    }

    public function test_el_parallax_se_inserta_solo_en_las_secciones_que_lo_activan(): void
    {
        PageTemplate::query()->update(['is_active' => false]);

        PageTemplate::factory()->active()->create([
            'slug' => 'con-parallax',
            'html' => '<section data-cf-parallax="soft"><h1>Con parallax</h1></section>'
                .'<section data-cf-parallax="none"><h1>Sin parallax</h1></section>',
        ]);

        $html = $this->get(route('landing'))->assertOk()->getContent();

        $this->assertStringContainsString('data-cf-parallax="soft"', $html);
        $this->assertStringContainsString('data-cf-parallax="none"', $html);
        $this->assertSame(1, substr_count($html, 'data-cf-parallax-layer="yes"'));
        $this->assertStringContainsString('data-parallax="0.08"', $html);
        $this->assertStringContainsString('data-parallax="-0.06"', $html);
    }

    public function test_el_parallax_funciona_alrededor_de_una_seccion_dinamica(): void
    {
        PageTemplate::query()->update(['is_active' => false]);

        PageTemplate::factory()->active()->create([
            'slug' => 'parallax-dinamico',
            'html' => '<div data-cf-parallax="strong"><section data-cf-section="horarios"></section></div>',
        ]);

        $html = $this->get(route('landing'))->assertOk()->getContent();

        $this->assertStringContainsString('data-cf-parallax-layer="yes"', $html);
        $this->assertStringContainsString('Horarios de entrenamiento', $html);
        $this->assertStringContainsString('data-parallax="0.18"', $html);
        $this->assertStringContainsString('data-parallax="-0.12"', $html);
    }

    public function test_el_css_del_parallax_contiene_la_decoracion_y_no_bloquea_clics(): void
    {
        $css = ParallaxWidget::css();

        $this->assertStringContainsString('[data-cf-parallax]', $css);
        $this->assertStringContainsString(':not([data-cf-parallax="none"])', $css);
        $this->assertStringContainsString('isolation: isolate', $css);
        $this->assertStringContainsString('z-index: 0', $css);
        $this->assertStringContainsString('pointer-events: none', $css);
        $this->assertStringContainsString('.cf-parallax-ball', $css);
        $this->assertStringContainsString('prefers-reduced-motion', $css);
    }

    public function test_el_boton_volver_arriba_se_puede_desactivar_desde_el_componente(): void
    {
        PageTemplate::query()->update(['is_active' => false]);

        PageTemplate::factory()->active()->create([
            'slug' => 'con-boton-configurable',
            'html' => '<section>Contenido</section><button id="top-button" type="button" aria-label="Volver arriba" data-top-button data-top-enabled="no"><img src="/img/balon.svg" alt="Volver arriba"></button>',
        ]);

        $html = $this->get(route('landing'))->assertOk()->getContent();

        $this->assertSame(1, substr_count($html, 'id="top-button"'));
        $this->assertStringContainsString('data-top-button', $html);
        $this->assertStringContainsString('data-top-enabled="no"', $html);
        $this->assertStringContainsString('/img/balon.svg', $html);
        $this->assertStringContainsString("[data-top-button][data-top-enabled='no']", file_get_contents(base_path('resources/css/app.css')));
    }

    public function test_el_boton_volver_arriba_aparece_por_defecto_si_no_hay_bloque(): void
    {
        PageTemplate::query()->update(['is_active' => false]);

        PageTemplate::factory()->active()->create([
            'slug' => 'sin-boton-personalizado',
            'html' => '<section>Contenido sin botón propio</section>',
        ]);

        $html = $this->get(route('landing'))->assertOk()->getContent();

        $this->assertSame(1, substr_count($html, 'id="top-button"'));
        $this->assertStringContainsString('data-top-button', $html);
    }

    public function test_un_boton_arriba_con_id_duplicado_no_hace_aparecer_el_boton_clasico(): void
    {
        PageTemplate::query()->update(['is_active' => false]);

        PageTemplate::factory()->active()->create([
            'slug' => 'boton-arriba-duplicado',
            'html' => '<section>Contenido</section><button id="top-button-2" type="button" aria-label="Volver arriba"><img src="/img/balon.svg" alt="Volver arriba"></button>',
        ]);

        $html = $this->get(route('landing'))->assertOk()->getContent();

        $this->assertSame(1, substr_count($html, 'id="top-button'));
        $this->assertStringContainsString('id="top-button-2"', $html);
        $this->assertStringNotContainsString('data-top-button inert', $html);
    }

    public function test_el_bloque_de_redes_sociales_incluye_las_cinco_redes(): void
    {
        $block = collect(PageBlockLibrary::all())->firstWhere('id', 'cf-social');
        $html = app(TemplateSanitizer::class)->html($block['content']);

        $this->assertSame(5, substr_count($html, 'data-cf-social-network='));
        $this->assertSame(5, substr_count($html, '/img/social/'));
        $this->assertStringContainsString('data-cf-social="yes"', $html);
        $this->assertStringContainsString('data-cf-social-tone="light"', $html);

        foreach (['linkedin', 'youtube', 'x', 'instagram', 'facebook'] as $red) {
            $this->assertStringContainsString('data-cf-social-network="'.$red.'"', $html);
            $this->assertStringContainsString('/img/social/'.$red.'.svg', $html);
        }
    }

    public function test_los_iconos_sociales_se_invierten_en_el_modo_oscuro(): void
    {
        $css = SocialLinkLibrary::css();

        $this->assertStringContainsString('.dark [data-cf-social] img', $css);
        $this->assertStringContainsString('.dark [data-cf-social-network] img', $css);
        $this->assertStringContainsString('filter: invert(1)', $css);
        $this->assertStringContainsString('data-cf-social-tone="dark"', SocialLinkLibrary::block(true));
    }

    public function test_las_redes_sociales_se_renderizan_con_sus_iconos(): void
    {
        PageTemplate::query()->update(['is_active' => false]);

        $block = collect(PageBlockLibrary::all())->firstWhere('id', 'cf-social')['content'];
        $html = str_replace(
            'data-cf-social-network="youtube"',
            'data-cf-social-network="youtube" data-cf-social-off="yes"',
            $block,
        );

        PageTemplate::factory()->active()->create([
            'slug' => 'con-redes-sociales',
            'html' => $html,
        ]);

        $response = $this->get(route('landing'))->assertOk()->getContent();

        $this->assertSame(5, substr_count($response, 'data-cf-social-network='));
        $this->assertStringContainsString('data-cf-social-off="yes"', $response);
        $this->assertStringContainsString('/img/social/instagram.svg', $response);
        $this->assertStringContainsString('.dark [data-cf-social] img', $response);
    }

    public function test_el_bloque_de_whatsapp_incluye_numero_titulo_y_mensaje(): void
    {
        $block = collect(PageBlockLibrary::all())->firstWhere('id', 'cf-whatsapp');
        $html = app(TemplateSanitizer::class)->html($block['content']);

        $this->assertStringContainsString('data-cf-whatsapp="yes"', $html);
        $this->assertStringContainsString('data-wa-widget="yes"', $html);
        $this->assertStringContainsString('data-gjs-name="Widget de WhatsApp"', $block['content']);
        $this->assertStringContainsString('data-reveal="up"', $html);
        $this->assertStringContainsString('data-reveal-trigger="load"', $html);
        $this->assertStringContainsString('data-reveal-delay="150"', $html);
        $this->assertStringContainsString('data-wa-number="56998970550"', $html);
        $this->assertStringContainsString('data-wa-title="Atención al cliente"', $html);
        $this->assertStringContainsString('data-wa-message="Hola, ¿en qué podemos ayudarte?"', $html);
        $this->assertStringContainsString('data-wa-title-text', $html);
        $this->assertStringContainsString('data-wa-message-text', $html);
        $this->assertStringContainsString('data-wa-button="yes"', $html);
        $this->assertStringContainsString('data-wa-button="yes"', $block['content']);
        $this->assertStringContainsString('/img/whatsapp.svg', $html);
        $this->assertStringContainsString('/img/whatsapp-send.svg', $html);
        $this->assertStringContainsString('/img/whatsapp-close.svg', $html);
    }

    public function test_la_animacion_continua_del_widget_se_pausa_al_abrir_el_chat(): void
    {
        $css = WhatsAppWidget::css();

        $this->assertStringContainsString('[data-cf-whatsapp][data-cf-loop="beat"] [data-wa-button]', $css);
        $this->assertStringContainsString('[data-cf-whatsapp][data-wa-open="yes"] [data-wa-button]', $css);
        $this->assertStringContainsString('animation-play-state: paused', $css);
    }

    public function test_un_componente_de_whatsapp_reemplaza_el_widget_global(): void
    {
        PageTemplate::query()->update(['is_active' => false]);

        $block = collect(PageBlockLibrary::all())->firstWhere('id', 'cf-whatsapp')['content'];
        $html = str_replace(
            [
                'data-wa-number="56998970550"',
                'data-wa-title="Atención al cliente"',
                'data-wa-message="Hola, ¿en qué podemos ayudarte?"',
            ],
            [
                'data-wa-number="56911112222"',
                'data-wa-title="Contacto del club"',
                'data-wa-message="Hola, quiero información sobre las inscripciones."',
            ],
            $block,
        );

        PageTemplate::factory()->active()->create([
            'slug' => 'con-whatsapp',
            'html' => $html,
        ]);

        $response = $this->get(route('landing'))->assertOk()->getContent();

        $this->assertStringContainsString('data-wa-number="56911112222"', $response);
        $this->assertStringContainsString('data-wa-title="Contacto del club"', $response);
        $this->assertStringContainsString('data-wa-message="Hola, quiero información sobre las inscripciones."', $response);
        $this->assertStringContainsString('data-wa-widget="yes"', $response);
        $this->assertStringNotContainsString('id="whatsapp-widget"', $response);
    }

    public function test_el_bloque_de_ventas_es_una_seccion_dinamica(): void
    {
        $block = collect(PageBlockLibrary::all())->firstWhere('id', 'cf-ventas');

        $this->assertNotNull($block);
        $this->assertSame('🛒 Productos en venta', $block['label']);
        $this->assertSame('Secciones dinámicas', $block['category']);
        $this->assertStringContainsString('data-cf-section="ventas"', $block['content']);
        $this->assertStringContainsString('Productos en venta (se genera automáticamente)', $block['content']);
    }

    public function test_la_seccion_de_ventas_muestra_los_productos_publicados(): void
    {
        PageTemplate::query()->update(['is_active' => false]);

        $indumentaria = CategoriaProducto::factory()->create([
            'nombre' => 'Indumentaria',
            'slug' => 'indumentaria',
        ]);
        $accesorios = CategoriaProducto::factory()->create([
            'nombre' => 'Accesorios',
            'slug' => 'accesorios',
        ]);
        $sinProductos = CategoriaProducto::factory()->create([
            'nombre' => 'Sin productos',
            'slug' => 'sin-productos',
        ]);
        $oculta = CategoriaProducto::factory()->oculta()->create([
            'nombre' => 'Categoría oculta',
            'slug' => 'oculta',
        ]);

        Producto::factory()->for($indumentaria, 'categoria')->create([
            'nombre' => 'Camiseta oficial',
            'precio' => 29990,
            'precio_anterior' => 34990,
            'etiqueta' => 'Nuevo',
            'nota_precio' => 'Tallas S a XXL',
            'publicado' => true,
            'orden' => 0,
        ]);

        Producto::factory()->for($accesorios, 'categoria')->create([
            'nombre' => 'Balón oficial',
            'precio' => 24990,
            'publicado' => true,
        ]);

        Producto::factory()->for($indumentaria, 'categoria')->create([
            'nombre' => 'Producto en borrador',
            'publicado' => false,
        ]);

        Producto::factory()->for($oculta, 'categoria')->create([
            'nombre' => 'Producto de categoría oculta',
            'publicado' => true,
        ]);

        PageTemplate::factory()->active()->create([
            'slug' => 'con-ventas',
            'html' => '<section data-cf-section="ventas">Productos en venta</section>',
        ]);

        $html = $this->get(route('landing'))->assertOk()->getContent();

        $this->assertStringContainsString('Artículos en venta', $html);
        $this->assertStringContainsString('Camiseta oficial', $html);
        $this->assertStringContainsString('Balón oficial', $html);
        $this->assertStringNotContainsString('Producto en borrador', $html);
        $this->assertStringNotContainsString('Producto de categoría oculta', $html);

        $this->assertStringContainsString('data-product-price="$29.990"', $html);
        $this->assertStringContainsString('data-product-currency="CLP"', $html);
        $this->assertStringContainsString('data-product-old-price="$34.990"', $html);
        $this->assertStringContainsString('data-product-badge-text="Nuevo"', $html);
        $this->assertStringContainsString('data-product-category="indumentaria"', $html);
        $this->assertStringContainsString('data-sales-filter="indumentaria"', $html);
        $this->assertStringNotContainsString('data-sales-filter="sin-productos"', $html);
        $this->assertStringNotContainsString('data-sales-filter="oculta"', $html);
        $this->assertStringContainsString('data-product-grid="yes"', $html);
        $this->assertSame(2, substr_count($html, 'data-cf-product="yes"'));
    }

    public function test_la_seccion_de_ventas_muestra_un_mensaje_si_no_hay_productos(): void
    {
        PageTemplate::query()->update(['is_active' => false]);

        PageTemplate::factory()->active()->create([
            'slug' => 'tienda-vacia',
            'html' => '<section data-cf-section="ventas">Productos en venta</section>',
        ]);

        $this->get(route('landing'))
            ->assertOk()
            ->assertSee('Estamos preparando nuevos artículos');
    }

    public function test_el_sanitizador_elimina_enlaces_de_pago_peligrosos(): void
    {
        $producto = Producto::factory()->create([
            'enlace_pago' => 'javascript:alert(1)',
        ]);

        $sanitized = app(TemplateSanitizer::class)->html(SalesWidget::cardFor($producto));

        $this->assertStringNotContainsString('href="javascript:', $sanitized);
        $this->assertStringNotContainsString("href='javascript:", $sanitized);
    }

    public function test_el_css_de_ventas_se_inyecta_en_la_pagina(): void
    {
        $css = SalesWidget::css();

        $this->assertStringContainsString('[data-product-grid]', $css);
        $this->assertStringContainsString('[data-product-cta][data-product-cta-empty]', $css);
        $this->assertStringContainsString('pointer-events: none', $css);
        $this->assertStringContainsString('[data-cf-sales] [data-sales-filter]', $css);

        PageTemplate::query()->update(['is_active' => false]);

        Producto::factory()->create(['nombre' => 'Producto visible']);

        PageTemplate::factory()->active()->create([
            'slug' => 'con-ventas',
            'html' => '<section data-cf-section="ventas">Productos en venta</section>',
        ]);

        $html = $this->get(route('landing'))->assertOk()->getContent();

        $this->assertStringContainsString('data-cf-product="yes"', $html);
        $this->assertStringContainsString('[data-product-grid]', $html);
        $this->assertStringContainsString('Producto visible', $html);
    }

    public function test_el_bloque_de_carrusel_incluye_diapositivas_y_controles(): void
    {
        $block = collect(PageBlockLibrary::all())->firstWhere('id', 'cf-carrusel');
        $html = app(TemplateSanitizer::class)->html($block['content']);

        $this->assertStringContainsString('data-cf-carousel="yes"', $html);
        $this->assertStringContainsString('data-carousel-track="yes"', $html);
        $this->assertStringContainsString('data-carousel-autoplay="yes"', $html);
        $this->assertStringContainsString('data-carousel-arrows="yes"', $html);
        $this->assertStringContainsString('data-carousel-dots="yes"', $html);
        $this->assertStringContainsString('data-carousel-prev="yes"', $html);
        $this->assertStringContainsString('data-carousel-next="yes"', $html);
        $this->assertStringContainsString('data-carousel-slide="yes"', $html);
        $this->assertStringContainsString('data-slide-image="https://picsum.photos/seed/cielo-slide-1/1600/900"', $html);
        $this->assertStringContainsString('data-slide-title="Título principal"', $html);
        $this->assertStringContainsString('data-slide-text', $html);
        $this->assertStringContainsString('data-slide-cta-text="Ver más"', $html);
        $this->assertStringContainsString('data-slide-cta-url="#contacto"', $html);
        $this->assertStringContainsString('data-carousel-image="yes"', $html);
        $this->assertStringContainsString('data-carousel-caption="yes"', $html);
        $this->assertStringContainsString('var(--cf-on-dark, #ffffff)', $html);
        $this->assertSame(3, substr_count($block['content'], 'data-carousel-slide="yes"'));
        $this->assertSame(3, substr_count($block['content'], 'data-carousel-dot="yes"'));
        $this->assertSame(5, CarouselWidget::MAX_SLIDES);
    }

    public function test_el_css_del_carrusel_se_inyecta_en_la_pagina(): void
    {
        $css = CarouselWidget::css();

        $this->assertStringContainsString('[data-carousel-track]', $css);
        $this->assertStringContainsString('[data-carousel-dot].active', $css);
        $this->assertStringContainsString('data-carousel-arrows="no"', $css);
        $this->assertStringContainsString('aspect-ratio: 16 / 9', $css);
        $this->assertStringContainsString('@media (max-width: 640px)', $css);
        $this->assertStringContainsString('aspect-ratio: 4 / 5', $css);
        $this->assertStringContainsString('font-size:clamp(20px,5vw,28px)', CarouselWidget::block());

        PageTemplate::query()->update(['is_active' => false]);

        PageTemplate::factory()->active()->create([
            'slug' => 'con-carrusel',
            'html' => CarouselWidget::block(),
        ]);

        $html = $this->get(route('landing'))->assertOk()->getContent();

        $this->assertStringContainsString('data-cf-carousel="yes"', $html);
        $this->assertStringContainsString('[data-carousel-track]', $html);
    }

    public function test_el_bloque_de_formulario_incluye_campos_configurables(): void
    {
        $block = collect(PageBlockLibrary::all())->firstWhere('id', 'cf-formulario');
        $html = app(TemplateSanitizer::class)->html($block['content']);

        $this->assertStringContainsString('data-cf-form="yes"', $html);
        $this->assertStringContainsString('data-form-title="Escríbenos"', $html);
        $this->assertStringContainsString('data-form-description', $html);
        $this->assertStringContainsString('data-form-label-nombre="Nombre"', $html);
        $this->assertStringContainsString('data-form-label-email', $html);
        $this->assertStringContainsString('data-form-label-telefono="Teléfono"', $html);
        $this->assertStringContainsString('data-form-label-mensaje="Mensaje"', $html);
        $this->assertStringContainsString('data-form-telefono="yes"', $html);
        $this->assertStringContainsString('data-form-button="Enviar mensaje"', $html);
        $this->assertStringContainsString('data-form-success', $html);
        $this->assertStringContainsString('data-form-token="yes"', $html);
        $this->assertStringContainsString('action="/contacto"', $html);
        $this->assertStringContainsString('name="telefono"', $html);
        $this->assertStringContainsString('name="mensaje"', $html);
        $this->assertStringContainsString('<textarea', $html);
        $this->assertStringContainsString('class="cf-form-grid"', $html);
        $this->assertStringContainsString('class="cf-form-card"', $html);
        $this->assertStringContainsString('id="contacto-form"', $html);
        $this->assertStringContainsString('id="mensaje-contador"', $html);
        $this->assertStringContainsString('O escríbenos a', $html);
        $this->assertStringContainsString('Síguenos en Instagram', $html);
        $this->assertStringContainsString('cf-form-icon-mail', $html);
        $this->assertStringContainsString('cf-form-icon-instagram', $html);
        $this->assertStringContainsString('aria-hidden="true">*</span>', $html);
    }

    public function test_el_css_del_formulario_define_el_diseno_en_dos_columnas(): void
    {
        $css = FormWidget::css();

        $this->assertStringContainsString('[data-cf-form] .cf-form-card', $css);
        $this->assertStringContainsString('grid-template-columns: minmax(0, 1fr) minmax(0, 1fr)', $css);
        $this->assertStringContainsString('@media (min-width: 1024px)', $css);
        $this->assertStringContainsString('padding: 56px 24px', $css);
        $this->assertStringContainsString('padding: clamp(22px, 4vw, 32px)', $css);
        $this->assertStringContainsString('data-form-telefono="no"', $css);
        $this->assertStringContainsString('font-size:clamp(30px,5vw,48px)', FormWidget::block());

        PageTemplate::query()->update(['is_active' => false]);

        PageTemplate::factory()->active()->create([
            'slug' => 'con-formulario',
            'html' => FormWidget::block(),
        ]);

        cache()->forget('page_templates.active_id');

        $html = $this->get(route('landing'))->assertOk()->getContent();

        $this->assertStringContainsString('data-cf-form="yes"', $html);
        $this->assertStringContainsString('[data-cf-form] .cf-form-card', $html);
    }

    public function test_una_plantilla_antigua_muestra_el_diseno_nuevo_del_formulario(): void
    {
        $legacy = <<<'HTML'
<section id="contacto-2" data-cf-form="yes" data-reveal="up" data-reveal-trigger="scroll" data-reveal-delay="150" data-form-title="Hablemos" data-form-description="Cuéntanos tu idea." data-form-label-nombre="Tu nombre" data-form-label-email="Tu correo" data-form-label-telefono="Tu fono" data-form-label-mensaje="Tu mensaje" data-form-telefono="no" data-form-button="Enviar ahora" data-form-success="Listo, te contactamos.">
    <div><h2 data-form-title-text="yes">Hablemos</h2><p data-form-description-text="yes">Cuéntanos tu idea.</p></div>
    <form method="POST" action="/contacto"><input type="text" name="nombre"><button type="submit">Enviar ahora</button></form>
</section>
HTML;

        PageTemplate::query()->update(['is_active' => false]);

        PageTemplate::factory()->active()->create([
            'slug' => 'formulario-antiguo',
            'html' => $legacy,
        ]);

        cache()->forget('page_templates.active_id');

        $html = $this->get(route('landing'))->assertOk()->getContent();

        $this->assertSame(1, substr_count($html, 'data-cf-form="yes"'));
        $this->assertStringContainsString('data-form-upgraded="yes"', $html);
        $this->assertStringContainsString('id="contacto"', $html);
        $this->assertStringNotContainsString('id="contacto-2"', $html);
        $this->assertStringContainsString('class="cf-form-grid"', $html);
        $this->assertStringContainsString('class="cf-form-card"', $html);
        $this->assertStringContainsString('cf-form-icon-mail', $html);
        $this->assertStringContainsString('data-form-title="Hablemos"', $html);
        $this->assertStringContainsString('data-form-description="Cuéntanos tu idea."', $html);
        $this->assertStringContainsString('data-form-label-nombre="Tu nombre"', $html);
        $this->assertStringContainsString('data-form-label-email="Tu correo"', $html);
        $this->assertStringContainsString('data-form-label-telefono="Tu fono"', $html);
        $this->assertStringContainsString('data-form-label-mensaje="Tu mensaje"', $html);
        $this->assertStringContainsString('data-form-telefono="no"', $html);
        $this->assertStringContainsString('data-form-button="Enviar ahora"', $html);
        $this->assertStringContainsString('data-form-success="Listo, te contactamos."', $html);
        $this->assertStringContainsString('data-reveal-delay="150"', $html);
    }

    public function test_la_actualizacion_del_formulario_es_idempotente_y_respeta_el_ancla_existente(): void
    {
        $legacy = '<section id="contacto-2" data-cf-form="yes" data-form-title="Hola">'
            .'<h2 data-form-title-text="yes">Hola</h2><form><input name="nombre"></form></section>';

        $upgraded = FormWidget::upgradeLegacyHtml($legacy);

        $this->assertStringContainsString('data-form-upgraded="yes"', $upgraded);
        $this->assertStringContainsString('class="cf-form-grid"', $upgraded);
        $this->assertStringContainsString(
            'data-form-upgraded="yes"',
            app(TemplateSanitizer::class)->html($upgraded),
        );

        $this->assertSame($upgraded, FormWidget::upgradeLegacyHtml($upgraded));
        $this->assertSame(FormWidget::block(), FormWidget::upgradeLegacyHtml(FormWidget::block()));

        $conAncla = FormWidget::upgradeLegacyHtml('<div id="contacto"></div>'.$legacy);

        $this->assertSame(1, substr_count($conAncla, 'id="contacto"'));
        $this->assertStringContainsString('id="contacto-2"', $conAncla);
    }

    public function test_el_formulario_usa_ids_unicos_cuando_se_repite_en_la_pagina(): void
    {
        $this->assertStringContainsString('id="contacto-form"', FormWidget::block());
        $this->assertStringContainsString('id="nombre"', FormWidget::block());

        $segundo = FormWidget::block(['id' => 'contacto-2']);

        $this->assertStringContainsString('id="contacto-2"', $segundo);
        $this->assertStringContainsString('id="contacto-form-2"', $segundo);
        $this->assertStringContainsString('for="website-2"', $segundo);
        $this->assertStringContainsString('for="nombre-2"', $segundo);
        $this->assertStringContainsString('id="nombre-2"', $segundo);
        $this->assertStringContainsString('id="mensaje-contador-2"', $segundo);
        $this->assertStringContainsString('id="contacto-submit-2"', $segundo);
    }

    public function test_dos_formularios_en_la_misma_pagina_no_repiten_ids(): void
    {
        PageTemplate::query()->update(['is_active' => false]);

        PageTemplate::factory()->active()->create([
            'slug' => 'dos-formularios',
            'html' => FormWidget::block().FormWidget::block(['id' => 'contacto-2']),
        ]);

        $html = $this->get(route('landing'))->assertOk()->getContent();

        $this->assertSame(1, substr_count($html, 'id="contacto-form"'));
        $this->assertSame(1, substr_count($html, 'id="contacto-form-2"'));
        $this->assertSame(1, substr_count($html, 'id="nombre"'));
        $this->assertSame(1, substr_count($html, 'id="nombre-2"'));
        $this->assertSame(1, substr_count($html, 'id="mensaje-contador"'));
        $this->assertSame(1, substr_count($html, 'id="mensaje-contador-2"'));
        $this->assertStringContainsString('for="nombre-2"', $html);
        $this->assertStringContainsString('for="email-2"', $html);
        $this->assertStringContainsString('for="mensaje-2"', $html);
    }

    public function test_la_paleta_y_el_idioma_del_editor_estan_disponibles(): void
    {
        $paleta = BlockTheme::palette();

        $this->assertCount(12, $paleta);
        $this->assertContains('#ff4c00', $paleta);
        $this->assertSame($paleta, array_values(array_unique($paleta)));

        $mensajes = EditorLocale::spanish();

        $this->assertArrayHasKey('panels', $mensajes);
        $this->assertArrayHasKey('styleManager', $mensajes);
        $this->assertSame('Tipografía', $mensajes['styleManager']['sectors']['typography']);
        $this->assertSame('Móvil vertical', $mensajes['deviceManager']['devices']['mobilePortrait']);
        $this->assertNotSame('', $mensajes['panels']['buttons']['titles']['open-sm']);
    }

    public function test_los_bloques_usan_tipografia_y_espaciado_adaptables_al_movil(): void
    {
        $blocks = collect(PageBlockLibrary::all())->keyBy('id');

        $this->assertStringContainsString('padding:clamp(56px,8vw,96px) 24px', $blocks['cf-hero']['content']);
        $this->assertStringContainsString('font-size:clamp(36px,8vw,56px)', $blocks['cf-hero']['content']);
        $this->assertStringContainsString('gap:clamp(28px,5vw,48px)', $blocks['cf-hero']['content']);
        $this->assertStringContainsString('font-size:clamp(36px,8vw,56px)', $blocks['cf-heading-1']['content']);
        $this->assertStringContainsString('font-size:clamp(28px,5.5vw,40px)', $blocks['cf-section']['content']);
        $this->assertStringContainsString('padding:clamp(44px,6vw,64px) 24px', $blocks['cf-columns-2']['content']);
        $this->assertStringContainsString('flex:1 1 260px', $blocks['cf-columns-4']['content']);
        $this->assertStringContainsString('flex-wrap:wrap', $blocks['cf-columns-4']['content']);
        $this->assertStringContainsString('repeat(auto-fit,minmax(240px,1fr))', $blocks['cf-grid-2x2']['content']);
        $this->assertStringContainsString('overflow-x:auto', $blocks['cf-table']['content']);
        $this->assertStringContainsString('padding:clamp(22px,4vw,32px)', $blocks['cf-columns-2']['content']);
    }

    public function test_el_formulario_configurable_muestra_los_errores_de_validacion(): void
    {
        PageTemplate::query()->update(['is_active' => false]);

        PageTemplate::factory()->active()->create([
            'slug' => 'formulario-errores',
            'html' => collect(PageBlockLibrary::all())->firstWhere('id', 'cf-formulario')['content'],
        ]);

        cache()->forget('page_templates.active_id');

        $response = $this->followingRedirects()
            ->from(route('landing'))
            ->post(route('contacto'), [
                'nombre' => 'a',
                'email' => 'no-es-un-correo',
                'mensaje' => 'corto',
            ]);

        $response->assertOk();
        $this->assertMatchesRegularExpression('/data-contacto-errores="[^"]+"/', $response->getContent());
    }

    public function test_los_bloques_dinamicos_de_noticias_y_galeria_usan_data_cf_section(): void
    {
        $noticias = collect(PageBlockLibrary::all())->firstWhere('id', 'cf-noticias')['content'];
        $galeria = collect(PageBlockLibrary::all())->firstWhere('id', 'cf-galeria')['content'];

        $this->assertStringContainsString('data-cf-section="noticias"', $noticias);
        $this->assertStringContainsString('data-cf-section="galeria"', $galeria);
        $this->assertStringContainsString('id="noticias"', $noticias);
        $this->assertStringContainsString('id="galeria"', $galeria);
        $this->assertStringContainsString('data-gjs-name="Noticias"', $noticias);
    }

    public function test_una_plantilla_grapesjs_sin_bloque_no_muestra_el_widget_global(): void
    {
        PageTemplate::query()->update(['is_active' => false]);

        PageTemplate::factory()->active()->create([
            'slug' => 'sin-widget-whatsapp',
            'html' => '<section>Contenido sin WhatsApp</section>',
            'css' => '',
        ]);

        $html = $this->get(route('landing'))->assertOk()->getContent();

        $this->assertStringNotContainsString('id="whatsapp-widget"', $html);
        $this->assertStringNotContainsString('data-wa-widget', $html);
    }

    public function test_un_pie_personalizado_reemplaza_el_pie_global(): void
    {
        PageTemplate::query()->update(['is_active' => false]);

        PageTemplate::factory()->active()->create([
            'slug' => 'con-pie',
            'html' => '<section>Contenido</section><footer data-cf-footer>Pie propio</footer>',
        ]);

        $html = $this->get(route('landing'))->assertOk()->getContent();

        $this->assertStringContainsString('Pie propio', $html);
        $this->assertStringNotContainsString('data-global-footer', $html);
    }

    public function test_un_pie_antiguo_sin_marcador_en_el_html_no_duplica_el_pie_global(): void
    {
        PageTemplate::query()->update(['is_active' => false]);

        PageTemplate::factory()->active()->create([
            'slug' => 'pie-antiguo',
            'html' => '<section>Contenido</section><footer>Pie propio</footer>',
            'gjs_data' => [
                'pages' => [
                    [
                        'attributes' => ['data-cf-footer' => null],
                        'components' => [],
                    ],
                ],
            ],
        ]);

        $html = $this->get(route('landing'))->assertOk()->getContent();

        $this->assertStringContainsString('Pie propio', $html);
        $this->assertStringNotContainsString('data-global-footer', $html);
    }

    public function test_sin_pie_personalizado_se_muestra_el_pie_global(): void
    {
        PageTemplate::query()->update(['is_active' => false]);

        PageTemplate::factory()->active()->create([
            'slug' => 'sin-pie',
            'html' => '<section>Contenido sin pie propio</section>',
        ]);

        $html = $this->get(route('landing'))->assertOk()->getContent();

        $this->assertStringContainsString('data-global-footer', $html);
        $this->assertStringContainsString('CieloTronador', $html);
        $this->assertStringContainsString('Más que un club, una familia', $html);
    }

    public function test_la_vista_previa_requiere_firma_y_no_se_indexa(): void
    {
        $template = PageTemplate::factory()->create(['slug' => 'previa']);

        $this->get(route('page-templates.preview', ['template' => $template->slug]))
            ->assertForbidden();

        $url = URL::temporarySignedRoute(
            'page-templates.preview',
            now()->addMinutes(30),
            ['template' => $template->slug],
        );

        $this->get($url)
            ->assertOk()
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow')
            ->assertSee('Bienvenidos');
    }

    public function test_la_vista_previa_fuerza_noindex_en_los_metadatos(): void
    {
        $template = PageTemplate::factory()->create([
            'slug' => 'previa-meta',
            'settings' => ['robots' => 'index, follow'],
        ]);

        $url = URL::temporarySignedRoute(
            'page-templates.preview',
            now()->addMinutes(30),
            ['template' => $template->slug],
        );

        $this->get($url)
            ->assertOk()
            ->assertSee('<meta name="robots" content="noindex, nofollow">', false);
    }

    public function test_la_portada_incluye_metadatos_para_redes_y_google(): void
    {
        PageTemplate::query()->update(['is_active' => false]);

        PageTemplate::factory()->active()->create([
            'slug' => 'seo',
            'name' => 'SEO',
            'settings' => [
                'seo_title' => 'Título SEO',
                'seo_description' => 'Descripción SEO',
                'og_title' => 'Título social',
                'og_description' => 'Descripción social',
                'og_image' => 'https://example.com/og.jpg',
                'canonical_url' => 'https://example.com/pagina',
                'robots' => 'noindex, nofollow',
                'schema_type' => 'LocalBusiness',
                'schema_name' => 'Club de Prueba',
                'schema_phone' => '+56 9 1234 5678',
                'schema_address' => 'Talcahuano, Chile',
            ],
            'html' => '<section><h1>Hola</h1></section>',
        ]);

        $html = $this->get(route('landing'))->assertOk()->getContent();

        $this->assertStringContainsString('<title>Título SEO</title>', $html);
        $this->assertStringContainsString('<meta name="description" content="Descripción SEO">', $html);
        $this->assertStringContainsString('<link rel="canonical" href="https://example.com/pagina">', $html);
        $this->assertStringContainsString('<meta name="robots" content="noindex, nofollow">', $html);
        $this->assertStringContainsString('<meta property="og:title" content="Título social">', $html);
        $this->assertStringContainsString('<meta property="og:description" content="Descripción social">', $html);
        $this->assertStringContainsString('<meta property="og:image" content="https://example.com/og.jpg">', $html);
        $this->assertStringContainsString('<meta name="twitter:card" content="summary_large_image">', $html);
        $this->assertStringContainsString('application/ld+json', $html);
        $this->assertStringContainsString('"@type":"LocalBusiness"', $html);
        $this->assertStringContainsString('"name":"Club de Prueba"', $html);
        $this->assertStringContainsString('"telephone":"+56 9 1234 5678"', $html);
        $this->assertStringContainsString('"streetAddress":"Talcahuano, Chile"', $html);
    }

    public function test_la_portada_clasica_conserva_sus_metadatos(): void
    {
        PageTemplate::query()->update(['is_active' => false]);

        $html = $this->get(route('landing'))->assertOk()->getContent();

        $this->assertStringContainsString('<title>Club CieloTronador | Cielo Tronador</title>', $html);
        $this->assertStringContainsString('Club de Básquetbol Cielo Tronador de Talcahuano', $html);
        $this->assertStringContainsString('<meta property="og:title" content="Club CieloTronador | Cielo Tronador">', $html);
        $this->assertStringContainsString('"@type":"SportsOrganization"', $html);
    }

    public function test_el_sitemap_incluye_la_portada_y_las_politicas(): void
    {
        PageTemplate::query()->update(['is_active' => false]);

        PageTemplate::factory()->active()->create(['slug' => 'sitemap']);

        $response = $this->get(route('sitemap'))->assertOk();

        $response->assertHeader('Content-Type', 'application/xml; charset=UTF-8');

        $xml = $response->getContent();

        $this->assertStringContainsString('<urlset', $xml);
        $this->assertStringContainsString('<loc>'.route('landing').'</loc>', $xml);
        $this->assertStringContainsString('<loc>'.route('politica.privacidad').'</loc>', $xml);
        $this->assertStringContainsString('<loc>'.route('politica.cookies').'</loc>', $xml);
        $this->assertStringContainsString('<loc>'.route('politica.datos').'</loc>', $xml);
        $this->assertStringContainsString('<lastmod>', $xml);
    }

    public function test_robots_bloquea_el_panel_y_declara_el_sitemap(): void
    {
        $response = $this->get(route('robots'))->assertOk();

        $response->assertHeader('Content-Type', 'text/plain; charset=UTF-8');

        $texto = $response->getContent();

        $this->assertStringContainsString('Disallow: /admin', $texto);
        $this->assertStringContainsString('Disallow: /constructor', $texto);
        $this->assertStringContainsString('Sitemap: '.url('/sitemap.xml'), $texto);
    }

    public function test_la_galeria_paginada_sigue_funcionando_con_plantilla_grapesjs(): void
    {
        PageTemplate::query()->update(['is_active' => false]);

        PageTemplate::factory()->active()->create([
            'slug' => 'con-galeria',
            'html' => '<section data-cf-section="galeria"></section>',
        ]);

        Galeria::factory()->count(10)->create();

        $response = $this->get('/?page=2', ['X-Partial' => 'galeria']);

        $response->assertOk();

        $html = $response->getContent();

        $this->assertStringContainsString('data-gallery-root', $html);
        $this->assertStringContainsString('Página 2 de', $html);
        $this->assertStringNotContainsString('<html', $html);
    }

    public function test_un_enlace_peligroso_del_menu_no_se_renderiza(): void
    {
        $item = MenuItem::factory()->create([
            'link_type' => 'url',
            'link_value' => 'javascript:alert(1)',
        ]);

        $this->assertSame('#', $item->resolvedUrl());
    }

    public function test_el_login_no_puede_quedarse_fuera_del_menu(): void
    {
        PageTemplate::query()->update(['is_active' => false]);

        $template = PageTemplate::factory()->active()->create([
            'slug' => 'sin-login',
            'html' => '<div>Contenido</div>',
        ]);

        MenuItem::factory()->create([
            'page_template_id' => $template->id,
            'label' => 'Solo contenido',
            'link_type' => 'anchor',
            'link_value' => '#contenido',
        ]);

        $html = $this->get(route('landing'))->assertOk()->getContent();

        $this->assertGreaterThanOrEqual(2, substr_count($html, url('/admin/login')));
    }

    public function test_guardar_el_borrador_no_cambia_la_web_hasta_publicar(): void
    {
        PageTemplate::query()->update(['is_active' => false]);

        $template = PageTemplate::factory()->active()->create([
            'slug' => 'borrador-publicado',
            'html' => '<h1>Borrador v1</h1>',
            'css' => '',
        ]);

        $template->forceFill([
            'published_html' => '<h1>Publicado v1</h1>',
            'published_css' => '',
            'published_gjs_data' => null,
        ])->saveQuietly();

        $this->get(route('landing'))
            ->assertOk()
            ->assertSee('Publicado v1')
            ->assertDontSee('Borrador v1');

        $template->bewaarOntwerp([
            'project' => ['pages' => []],
            'html' => '<h1>Borrador v2</h1>',
            'css' => '',
        ]);

        $this->get(route('landing'))
            ->assertOk()
            ->assertSee('Publicado v1')
            ->assertDontSee('Borrador v2');

        $preview = URL::temporarySignedRoute(
            'page-templates.preview',
            now()->addMinutes(30),
            ['template' => $template->slug],
        );

        $this->get($preview)
            ->assertOk()
            ->assertSee('Borrador v2')
            ->assertDontSee('Publicado v1');

        $template->publish();

        $this->get(route('landing'))
            ->assertOk()
            ->assertSee('Borrador v2')
            ->assertDontSee('Publicado v1');

        $this->assertSame('<h1>Borrador v2</h1>', $template->fresh()->published_html);
        $this->assertSame(['pages' => []], $template->fresh()->published_gjs_data);
    }

    public function test_publicar_guarda_una_version_y_permite_restaurarla(): void
    {
        PageTemplate::query()->update(['is_active' => false]);

        $template = PageTemplate::factory()->create([
            'slug' => 'versiones',
            'html' => '<h1>Versión 1</h1>',
            'css' => '',
            'gjs_data' => null,
        ]);

        $template->publish();

        $this->assertSame(1, $template->versions()->count());

        $primera = $template->versions()->firstOrFail();

        $this->assertSame('<h1>Versión 1</h1>', $primera->html);
        $this->assertSame($template->published_at?->toDateTimeString(), $primera->published_at?->toDateTimeString());

        $template->forceFill(['html' => '<h1>Versión 2</h1>'])->saveQuietly();
        $template->publish();

        $this->assertSame(2, $template->versions()->count());
        $this->assertSame('<h1>Versión 2</h1>', $template->versions()->latest('id')->firstOrFail()->html);

        $template->restoreVersion($primera);

        $this->assertSame('<h1>Versión 1</h1>', $template->fresh()->html);
        $this->assertSame('<h1>Versión 1</h1>', $template->fresh()->published_html);
        $this->assertSame(3, $template->versions()->count());
    }

    public function test_no_se_puede_restaurar_una_version_de_otra_plantilla(): void
    {
        $plantilla = PageTemplate::factory()->create();
        $otra = PageTemplate::factory()->create();

        $versionAjena = PageTemplateVersion::factory()->for($otra)->create();

        $this->expectException(NotFoundHttpException::class);

        $plantilla->restoreVersion($versionAjena);
    }

    public function test_la_vista_previa_de_una_version_es_firmada_y_noindex(): void
    {
        $template = PageTemplate::factory()->create(['slug' => 'version-preview']);

        $version = PageTemplateVersion::factory()->for($template)->create([
            'html' => '<h1>Versión histórica</h1>',
        ]);

        $url = URL::temporarySignedRoute(
            'page-templates.preview-version',
            now()->addMinutes(30),
            ['template' => $template->slug, 'version' => $version->getKey()],
        );

        $this->get($url)
            ->assertOk()
            ->assertSee('Versión histórica')
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow');

        $this->get(route('page-templates.preview-version', [
            'template' => $template->slug,
            'version' => $version->getKey(),
        ]))->assertForbidden();
    }

    public function test_una_pagina_publicada_se_muestra_en_su_slug_con_su_canonical(): void
    {
        PageTemplate::query()->update(['is_active' => false]);

        PageTemplate::factory()->active()->create([
            'slug' => 'portada',
            'html' => '<h1>Portada publicada</h1>',
        ]);

        PageTemplate::factory()->create([
            'slug' => 'nosotros',
            'html' => '<h1>Borrador de nosotros</h1>',
            'published_at' => now(),
            'published_html' => '<h1>Nosotros publicado</h1>',
            'published_css' => '',
            'published_gjs_data' => null,
        ]);

        $this->get('/nosotros')
            ->assertOk()
            ->assertSee('Nosotros publicado')
            ->assertDontSee('Borrador de nosotros')
            ->assertSee('<link rel="canonical" href="'.url('/nosotros').'">', false);

        $this->get('/')->assertOk()->assertSee('Portada publicada');
    }

    public function test_una_pagina_no_publicada_da_404(): void
    {
        PageTemplate::query()->update(['is_active' => false]);

        PageTemplate::factory()->create([
            'slug' => 'borrador',
            'published_at' => null,
            'published_html' => null,
            'published_css' => null,
            'published_gjs_data' => null,
        ]);

        $this->get('/borrador')->assertNotFound();
    }

    public function test_la_portada_activa_redirige_su_slug_a_la_raiz(): void
    {
        PageTemplate::query()->update(['is_active' => false]);

        PageTemplate::factory()->active()->create(['slug' => 'inicio']);

        $this->get('/inicio')->assertRedirect(route('landing'), 301);
    }

    public function test_el_menu_puede_enlazar_a_una_pagina_y_se_comparte_en_las_paginas(): void
    {
        PageTemplate::query()->update(['is_active' => false]);

        $portada = PageTemplate::factory()->active()->create([
            'slug' => 'portada-menu',
            'html' => '<h1>Portada</h1>',
        ]);

        $pagina = PageTemplate::factory()->create([
            'slug' => 'nosotros-menu',
            'html' => '<h1>Nosotros</h1>',
            'published_at' => now(),
            'published_html' => '<h1>Nosotros página</h1>',
            'published_css' => '',
            'published_gjs_data' => null,
        ]);

        MenuItem::factory()->create([
            'page_template_id' => $portada->id,
            'label' => 'Nosotros',
            'link_type' => 'page',
            'link_value' => 'nosotros-menu',
        ]);

        $url = route('page-templates.show', ['template' => 'nosotros-menu']);

        $this->assertSame($url, $pagina->publicUrl());

        $this->get('/')
            ->assertOk()
            ->assertSee('href="'.$url.'"', false);

        $this->get('/nosotros-menu')
            ->assertOk()
            ->assertSee('Nosotros página')
            ->assertSee('href="'.$url.'"', false);
    }

    public function test_el_sitemap_incluye_las_paginas_publicadas(): void
    {
        PageTemplate::query()->update(['is_active' => false]);

        PageTemplate::factory()->active()->create(['slug' => 'portada-sitemap']);

        $pagina = PageTemplate::factory()->create([
            'slug' => 'nosotros-sitemap',
            'published_at' => now(),
            'published_html' => '<h1>Nosotros</h1>',
            'published_css' => '',
        ]);

        $borrador = PageTemplate::factory()->create([
            'slug' => 'borrador-sitemap',
            'published_at' => null,
            'published_html' => null,
            'published_css' => null,
            'published_gjs_data' => null,
        ]);

        $xml = $this->get(route('sitemap'))->assertOk()->getContent();

        $this->assertStringContainsString('<loc>'.route('page-templates.show', ['template' => $pagina->slug]).'</loc>', $xml);
        $this->assertStringNotContainsString($borrador->slug, $xml);
    }

    public function test_despublicar_oculta_la_pagina_y_la_quita_del_sitemap(): void
    {
        PageTemplate::query()->update(['is_active' => false]);

        PageTemplate::factory()->active()->create(['slug' => 'portada-unpublish']);

        $pagina = PageTemplate::factory()->create([
            'slug' => 'noticias-unpublish',
            'published_at' => now(),
            'published_html' => '<h1>Noticias</h1>',
            'published_css' => '',
        ]);

        $this->get('/noticias-unpublish')->assertOk();

        $pagina->unpublish();

        $this->assertNull($pagina->fresh()->published_at);
        $this->get('/noticias-unpublish')->assertNotFound();

        $xml = $this->get(route('sitemap'))->assertOk()->getContent();

        $this->assertStringNotContainsString('noticias-unpublish', $xml);
    }

    public function test_el_bloque_de_contadores_tiene_cuatro_cifras_editables(): void
    {
        $block = collect(PageBlockLibrary::all())->firstWhere('id', 'cf-stats');

        $this->assertNotNull($block);
        $this->assertSame('📊 4 contadores', $block['label']);
        $this->assertSame('Estructura', $block['category']);

        $html = app(TemplateSanitizer::class)->html($block['content']);

        $this->assertStringContainsString('data-cf-stats="yes"', $html);
        $this->assertSame(4, substr_count($html, 'data-counter="yes"'));
        $this->assertStringContainsString('data-counter-value="120"', $html);
        $this->assertStringContainsString('data-counter-prefix', $html);
        $this->assertStringContainsString('data-counter-suffix', $html);
        $this->assertStringContainsString('data-counter-label="Jugadores"', $html);
        $this->assertSame(4, substr_count($html, 'cf-counter-number'));
    }

    public function test_el_css_de_los_contadores_es_adaptable(): void
    {
        $css = StatsWidget::css();

        $this->assertStringContainsString('grid-template-columns: repeat(4, minmax(0, 1fr))', $css);
        $this->assertStringContainsString('@media (max-width: 900px)', $css);
        $this->assertStringContainsString('grid-template-columns: repeat(2, minmax(0, 1fr))', $css);
        $this->assertStringContainsString('@media (max-width: 480px)', $css);
        $this->assertStringContainsString('font-size: clamp(32px, 6vw, 48px)', $css);
    }

    public function test_la_pagina_publica_incluye_el_css_de_contadores(): void
    {
        PageTemplate::query()->update(['is_active' => false]);

        PageTemplate::factory()->active()->create([
            'slug' => 'con-contadores',
            'html' => StatsWidget::block(),
        ]);

        $html = $this->get(route('landing'))->assertOk()->getContent();

        $this->assertStringContainsString('[data-cf-stats] .cf-stats-grid', $html);
        $this->assertStringContainsString('data-counter-value="120"', $html);
    }

    public function test_las_tarjetas_ampliables_generan_tres_y_cuatro_tarjetas(): void
    {
        $tres = collect(PageBlockLibrary::all())->firstWhere('id', 'cf-galeria-3');
        $cuatro = collect(PageBlockLibrary::all())->firstWhere('id', 'cf-galeria-4');

        $this->assertNotNull($tres);
        $this->assertNotNull($cuatro);
        $this->assertSame('🖼️ 3 tarjetas ampliables', $tres['label']);
        $this->assertSame('🖼️ 4 tarjetas ampliables', $cuatro['label']);
        $this->assertSame('Estructura', $tres['category']);

        $htmlTres = app(TemplateSanitizer::class)->html($tres['content']);
        $htmlCuatro = app(TemplateSanitizer::class)->html($cuatro['content']);

        $this->assertStringContainsString('data-cf-lightbox-gallery="yes"', $htmlTres);
        $this->assertStringContainsString('data-cf-lightbox-grid="yes"', $htmlTres);
        $this->assertStringContainsString('data-lightbox-count="3"', $htmlTres);
        $this->assertSame(3, substr_count($htmlTres, 'data-cf-lightbox-card="yes"'));
        $this->assertSame(3, substr_count($htmlTres, 'data-cf-lightbox="yes"'));
        $this->assertSame(4, substr_count($htmlCuatro, 'data-cf-lightbox-card="yes"'));
        $this->assertSame(4, substr_count($htmlCuatro, 'data-cf-lightbox="yes"'));
        $this->assertStringContainsString('data-lightbox-count="4"', $htmlCuatro);
        $this->assertStringContainsString('loading="lazy"', $htmlTres);
        $this->assertStringContainsString('Ampliar imagen', $htmlTres);
    }

    public function test_nuestro_equipo_incluye_tres_miembros_con_foto_puesto_y_redes_opcionales(): void
    {
        $block = collect(PageBlockLibrary::all())->firstWhere('id', 'cf-directiva');

        $this->assertNotNull($block);
        $this->assertSame('👥 Nuestro equipo', $block['label']);
        $this->assertSame('Estructura', $block['category']);

        $html = app(TemplateSanitizer::class)->html($block['content']);

        $this->assertStringContainsString('data-cf-directiva="yes"', $html);
        $this->assertSame(3, substr_count($html, 'data-directiva-card="yes"'));
        $this->assertSame(3, substr_count($html, 'data-directiva-avatar="yes"'));
        $this->assertSame(3, substr_count($html, 'data-directiva-name="yes"'));
        $this->assertSame(3, substr_count($html, 'data-directiva-role="yes"'));
        $this->assertSame(15, substr_count($html, 'data-directiva-link="'));
        $this->assertSame(15, substr_count($html, 'data-directiva-link-off="yes"'));
        $this->assertStringNotContainsString('data-cf-lightbox', $html);
        $this->assertStringNotContainsString('Ampliar foto', $html);
        $this->assertStringContainsString('Jacqueline Reyes', $html);
        $this->assertStringContainsString('Directora', $html);
        $this->assertStringContainsString('/img/equipo/avatar.svg', $html);
        $this->assertStringContainsString('/img/social/instagram.svg', $html);
        $this->assertStringContainsString('/img/social/facebook.svg', $html);
        $this->assertStringContainsString('/img/social/linkedin.svg', $html);
        $this->assertStringContainsString('/img/social/x.svg', $html);
        $this->assertStringContainsString('/img/social/web.svg', $html);
    }

    public function test_el_css_de_tarjetas_ampliables_y_equipo_es_adaptable(): void
    {
        $tarjetas = GalleryCardsWidget::css();

        $this->assertStringContainsString('[data-cf-lightbox-grid]', $tarjetas);
        $this->assertStringContainsString('[data-lightbox-count="3"]', $tarjetas);
        $this->assertStringContainsString('grid-template-columns: repeat(2, minmax(0, 1fr))', $tarjetas);
        $this->assertStringContainsString('object-fit: cover', $tarjetas);
        $this->assertStringContainsString('prefers-reduced-motion', $tarjetas);

        $directiva = DirectivaWidget::css();

        $this->assertStringContainsString('[data-cf-directiva] .cf-directiva-grid', $directiva);
        $this->assertStringContainsString('grid-template-columns: repeat(3, minmax(0, 1fr))', $directiva);
        $this->assertStringContainsString('[data-directiva-avatar]', $directiva);
        $this->assertStringContainsString('[data-directiva-social]', $directiva);
        $this->assertStringContainsString('[data-directiva-link-off]', $directiva);
        $this->assertStringContainsString('prefers-reduced-motion', $directiva);
        $this->assertStringNotContainsString('cursor: zoom-in', $directiva);
    }

    public function test_la_pagina_publica_incluye_tarjetas_ampliables_y_equipo(): void
    {
        PageTemplate::query()->update(['is_active' => false]);

        PageTemplate::factory()->active()->create([
            'slug' => 'con-galeria-ampliable',
            'html' => GalleryCardsWidget::block(4)."\n".DirectivaWidget::block(),
        ]);

        $html = $this->get(route('landing'))->assertOk()->getContent();

        $this->assertStringContainsString('[data-cf-lightbox-grid]', $html);
        $this->assertStringContainsString('data-cf-lightbox-grid="yes"', $html);
        $this->assertStringContainsString('data-cf-lightbox="yes"', $html);
        $this->assertStringContainsString('data-cf-directiva="yes"', $html);
        $this->assertStringContainsString('data-directiva-social="yes"', $html);
        $this->assertStringContainsString('data-directiva-link-off="yes"', $html);
        $this->assertStringContainsString('[data-cf-directiva] .cf-directiva-grid', $html);
        $this->assertStringContainsString('Jacqueline Reyes', $html);
    }
}
