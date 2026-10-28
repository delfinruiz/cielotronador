<?php

namespace Tests\Feature;

use App\Models\PageTemplate;
use App\Support\PageBlockLibrary;
use App\Support\TemplateSanitizer;
use App\Support\VideoWidget;
use Tests\AdminTestCase;

class VideoBlockTest extends AdminTestCase
{
    public function test_el_kit_incluye_el_bloque_de_video(): void
    {
        $this->assertContains('🎬 Video', PageBlockLibrary::labels());

        $bloque = collect(PageBlockLibrary::all())->firstWhere('id', 'cf-video');

        $this->assertNotNull($bloque);
        $this->assertStringContainsString('data-cf-video="yes"', $bloque['content']);
        $this->assertStringContainsString('data-video-url="'.VideoWidget::DEFAULT_URL.'"', $bloque['content']);
    }

    public function test_el_bloque_no_guarda_iframes_y_conserva_sus_atributos(): void
    {
        $html = app(TemplateSanitizer::class)->html(VideoWidget::block());

        $this->assertStringNotContainsString('<iframe', $html);
        $this->assertStringContainsString('data-cf-video="yes"', $html);
        $this->assertStringContainsString('data-video-provider="auto"', $html);
        $this->assertStringContainsString('data-video-ratio="16/9"', $html);
        $this->assertStringContainsString('data-video-play="yes"', $html);
        $this->assertStringContainsString('data-video-poster="yes"', $html);
        $this->assertStringContainsString('data-video-play-icon="yes"', $html);
    }

    public function test_la_pagina_publica_muestra_la_portada_del_video(): void
    {
        $template = PageTemplate::create([
            'name' => 'Video público',
            'slug' => 'video-publico-test',
            'render_mode' => 'grapesjs',
            'settings' => ['seo_title' => 'Video'],
            'html' => app(TemplateSanitizer::class)->html(VideoWidget::block()),
            'is_active' => false,
            'published_at' => now(),
        ]);

        $this->get(route('page-templates.show', ['template' => $template->slug]))
            ->assertOk()
            ->assertSee('data-cf-video="yes"', false)
            ->assertSee('data-video-url', false)
            ->assertSee('aqz-KE-bpKQ')
            ->assertSee('Reproducir video: Video de presentación')
            ->assertDontSee('<iframe', false);
    }

    public function test_el_css_del_video_cubre_los_formatos_y_la_portada(): void
    {
        $css = VideoWidget::css();

        $this->assertStringContainsString('aspect-ratio: 16 / 9', $css);
        $this->assertStringContainsString('aspect-ratio: 9 / 16', $css);
        $this->assertStringContainsString('[data-video-play-icon]', $css);
        $this->assertStringContainsString('prefers-reduced-motion', $css);
    }
}
