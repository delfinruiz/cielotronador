<?php

namespace Tests\Feature;

use App\Models\PageTemplate;
use App\Support\ClassicHomeWidget;
use App\Support\DirectivaWidget;
use App\Support\PageBlockLibrary;
use App\Support\TemplateSanitizer;
use Database\Seeders\ClassicHomeBuilderSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class ClassicHomeBuilderTest extends TestCase
{
    use RefreshDatabase;

    public function test_la_portada_del_constructor_replica_el_contenido_clasico(): void
    {
        $html = app(TemplateSanitizer::class)->html(ClassicHomeWidget::page());

        $this->assertStringContainsString('id="inicio"', $html);
        $this->assertStringContainsString('Cielo<br', $html);
        $this->assertStringContainsString('<span>Tronador</span>', $html);
        $this->assertStringContainsString('«Más que un club, una familia»', $html);
        $this->assertStringContainsString('/img/hero.png', $html);
        $this->assertStringContainsString('id="quienes-somos"', $html);
        $this->assertStringContainsString('/img/bienvenida.jpg', $html);
        $this->assertStringContainsString('Bienvenidos a<br', $html);
        $this->assertStringContainsString('nuestro sitio web', $html);
        $this->assertStringContainsString('Jacqueline Reyes', $html);
        $this->assertStringContainsString('/img/balon.svg', $html);
        $this->assertStringContainsString('data-directiva-avatar-plain="yes"', $html);
        $this->assertStringContainsString('data-cf-section="noticias"', $html);
        $this->assertStringContainsString('data-cf-section="galeria"', $html);
        $this->assertStringContainsString('data-cf-section="horarios"', $html);
        $this->assertStringContainsString('data-cf-section="contacto"', $html);
        $this->assertStringContainsString('data-cf-parallax="soft"', $html);
        $this->assertStringContainsString('data-cf-parallax-tone="light"', $html);
        $this->assertStringContainsString('data-cf-footer="yes"', $html);
        $this->assertStringContainsString('/branding/logo-light', $html);
        $this->assertStringContainsString('Política de Privacidad', $html);
        $this->assertStringContainsString('data-top-button="yes"', $html);
        $this->assertStringContainsString('data-wa-title="Tía Jacqueline Reyes"', $html);
        $this->assertStringContainsString('data-wa-number="56998970550"', $html);
    }

    public function test_la_directiva_clasica_usa_el_balon_y_no_abre_visor(): void
    {
        $html = app(TemplateSanitizer::class)->html(DirectivaWidget::block(
            kicker: 'Nuestra directiva',
            title: 'Quienes llevan el club',
            intro: '',
            members: [
                ['nombre' => 'Jacqueline Reyes', 'puesto' => 'Directora', 'foto' => '/img/balon.svg'],
                ['nombre' => 'Angélica Fuente', 'puesto' => 'Secretaria', 'foto' => '/img/balon.svg'],
                ['nombre' => 'Bertita Vergara', 'puesto' => 'Tesorera', 'foto' => '/img/balon.svg'],
            ],
            classic: true,
        ));

        $this->assertStringContainsString('Nuestra directiva', $html);
        $this->assertStringContainsString('Quienes llevan el club', $html);
        $this->assertStringContainsString('/img/balon.svg', $html);
        $this->assertStringContainsString('data-directiva-avatar-plain="yes"', $html);
        $this->assertStringNotContainsString('data-cf-lightbox', $html);
        $this->assertStringNotContainsString('Conoce a quienes', $html);
    }

    public function test_la_portada_del_constructor_queda_como_borrador_y_se_puede_previsualizar(): void
    {
        PageTemplate::query()->update(['is_active' => false]);

        $template = PageTemplate::create([
            'name' => 'Portada clásica (borrador)',
            'slug' => 'portada-clasica-borrador-test',
            'render_mode' => 'grapesjs',
            'settings' => ['seo_title' => 'Club CieloTronador'],
            'html' => app(TemplateSanitizer::class)->html(ClassicHomeWidget::page()),
            'is_active' => false,
            'published_at' => null,
        ]);

        $this->assertFalse($template->is_active);
        $this->assertNull($template->published_at);
        $this->assertNull($template->published_html);

        $this->get(route('page-templates.show', $template->slug))->assertNotFound();

        $url = URL::temporarySignedRoute(
            'page-templates.preview',
            now()->addMinutes(5),
            ['template' => $template->slug],
        );

        $html = $this->get($url)->assertOk()->getContent();

        $this->assertStringContainsString('Cielo', $html);
        $this->assertStringContainsString('Bienvenidos a', $html);
        $this->assertStringContainsString('Jacqueline Reyes', $html);
        $this->assertStringContainsString('Horarios de entrenamiento', $html);
        $this->assertStringContainsString('Escríbenos', $html);
        $this->assertStringContainsString('Copyright', $html);
        $this->assertStringContainsString('Tía Jacqueline Reyes', $html);
        $this->assertStringContainsString('data-cf-parallax-layer', $html);
    }

    public function test_el_kit_incluye_los_bloques_de_la_portada_clasica(): void
    {
        $labels = PageBlockLibrary::labels();

        $this->assertContains('🏀 Hero del club', $labels);
        $this->assertContains('🏀 Quiénes somos', $labels);
        $this->assertContains('🕒 Horarios', $labels);
        $this->assertContains('✉️ Contacto completo', $labels);
        $this->assertContains('🏁 Pie del club', $labels);
        $this->assertContains('🏀 Botón volver arriba (balón)', $labels);
        $this->assertContains('💬 WhatsApp del club', $labels);
    }

    public function test_el_css_de_la_portada_clasica_es_adaptable(): void
    {
        $css = ClassicHomeWidget::css();

        $this->assertStringContainsString('.cf-club-hero', $css);
        $this->assertStringContainsString('min-height: calc(100vh - 5rem)', $css);
        $this->assertStringContainsString('.cf-club-about', $css);
        $this->assertStringContainsString('.cf-club-footer', $css);
        $this->assertStringContainsString('@media (min-width: 1024px)', $css);
        $this->assertStringContainsString('prefers-reduced-motion', $css);
    }

    public function test_el_seeder_crea_la_portada_como_borrador(): void
    {
        $this->seed(ClassicHomeBuilderSeeder::class);

        $template = PageTemplate::query()
            ->where('slug', 'portada-clasica-constructor')
            ->first();

        $this->assertNotNull($template);
        $this->assertFalse($template->is_active);
        $this->assertNull($template->published_at);
        $this->assertNull($template->published_html);
        $this->assertStringContainsString('cf-club-hero', $template->html);
        $this->assertSame(5, $template->mainMenuItems()->count());
        $this->assertDatabaseHas('menu_items', [
            'page_template_id' => $template->id,
            'label' => 'Quiénes Somos',
            'link_value' => '#quienes-somos',
        ]);
    }
}
