<?php

namespace Tests\Feature;

use App\Actions\CreatePageFromPreset;
use App\Models\Faq;
use App\Models\Marca;
use App\Models\PageTemplate;
use App\Models\Testimonio;
use App\Support\PageBlockLibrary;
use App\Support\Presets\PresetLibrary;
use App\Support\TemplateRenderer;
use App\Support\TemplateSanitizer;
use Database\Seeders\ContenidoDinamicoSeeder;
use Tests\AdminTestCase;

class DynamicSectionsTest extends AdminTestCase
{
    public function test_el_kit_incluye_las_tres_secciones_dinamicas(): void
    {
        $labels = PageBlockLibrary::labels();

        $this->assertContains('❓ Preguntas frecuentes', $labels);
        $this->assertContains('🏷️ Franja de marcas', $labels);
        $this->assertContains('💬 Testimonios', $labels);
    }

    public function test_el_sanitizador_conserva_las_opciones_de_las_secciones(): void
    {
        $html = '<section data-cf-section="faq" data-cf-option-columns="2" data-cf-option-open-first="no"></section>';

        $limpio = app(TemplateSanitizer::class)->html($html);

        $this->assertStringContainsString('data-cf-option-columns="2"', $limpio);
        $this->assertStringContainsString('data-cf-option-open-first="no"', $limpio);
    }

    public function test_el_renderizador_pasa_las_opciones_y_arma_las_secciones(): void
    {
        $faqs = Faq::factory()->count(3)->create();
        $marcas = Marca::factory()->count(2)->create();
        $testimonios = Testimonio::factory()->count(2)->create();

        $html = <<<'HTML'
<section data-cf-section="faq" data-cf-option-columns="2" data-cf-option-open-first="no"></section>
<section data-cf-section="marcas" data-cf-option-speed="25" data-cf-option-grayscale="no"></section>
<section data-cf-section="testimonios" data-cf-option-layout="grid" data-cf-option-autoplay="no"></section>
HTML;

        $template = new PageTemplate(['html' => $html, 'render_mode' => 'grapesjs']);

        $salida = app(TemplateRenderer::class)->render($template, [
            'faqs' => $faqs,
            'marcas' => $marcas,
            'testimonios' => $testimonios,
        ], $html);

        $this->assertStringContainsString('data-columns="2"', $salida);
        $this->assertSame(3, substr_count($salida, 'data-faq-item'));
        $this->assertStringContainsString('--cf-marquesa: 25s', $salida);
        $this->assertStringContainsString('data-invert="yes"', $salida);
        $this->assertStringContainsString('cf-testimonios__grid', $salida);
        $this->assertSame(2, substr_count($salida, 'cf-testimonios__card'));
    }

    public function test_la_pagina_publica_muestra_solo_el_contenido_activo(): void
    {
        $this->seed(ContenidoDinamicoSeeder::class);

        Faq::factory()->inactiva()->create(['question' => 'Pregunta oculta para la web']);
        Marca::factory()->inactiva()->create(['name' => 'Marca oculta']);
        Testimonio::factory()->inactivo()->create(['name' => 'Testimonio oculto']);

        $template = app(CreatePageFromPreset::class)->execute(
            PresetLibrary::find('corporativo'),
            'Corporativa pública',
            $this->admin()->id,
        );

        $template->forceFill(['published_at' => now()])->save();

        $this->get(route('page-templates.show', ['template' => $template->slug]))
            ->assertOk()
            ->assertSee('¿Cómo empieza un proyecto?')
            ->assertSee('Nube')
            ->assertSee('Camila Rojas')
            ->assertDontSee('Pregunta oculta para la web')
            ->assertDontSee('Marca oculta')
            ->assertDontSee('Testimonio oculto');
    }

    public function test_el_menu_contenido_agrupa_los_modulos_y_oculta_la_guia(): void
    {
        $this->actingAs($this->admin())
            ->get('/admin')
            ->assertOk()
            ->assertSee('Contenido')
            ->assertSee('Noticias')
            ->assertSee('Galería')
            ->assertSee('Preguntas frecuentes')
            ->assertSee('Marcas')
            ->assertSee('Testimonios')
            ->assertDontSee('Guía del constructor');
    }

    public function test_los_recursos_del_panel_responden(): void
    {
        $this->actingAs($this->admin());

        foreach (['/admin/faqs', '/admin/marcas', '/admin/testimonios'] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_los_tres_modulos_se_pueden_configurar_desde_el_bloque(): void
    {
        $bloques = collect(PageBlockLibrary::all())->keyBy('id');

        $html = app(TemplateSanitizer::class)->html(
            $bloques['cf-faq']['content'].$bloques['cf-marcas']['content'].$bloques['cf-testimonios']['content'],
        );

        $this->assertStringContainsString('data-cf-option-columns="1"', $html);
        $this->assertStringContainsString('data-cf-option-speed="40"', $html);
        $this->assertStringContainsString('data-cf-option-layout="slider"', $html);
    }
}
