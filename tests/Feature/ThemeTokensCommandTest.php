<?php

namespace Tests\Feature;

use App\Models\PageTemplate;
use App\Support\BlockTheme;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ThemeTokensCommandTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, mixed>
     */
    private function templateAttributes(): array
    {
        return [
            'slug' => 'plantilla-con-colores-fijos',
            'html' => '<section style="background-color:#ffffff;color:#18181b;"><a style="color:#FF4C00">Contenido</a></section>',
            'css' => '#cf-template .tarjeta { background-color: #ffffff; color: #18181b; border: 1px solid #e4e4e7; }',
            'gjs_data' => [
                'styles' => [
                    [
                        'selectors' => ['#cf-template .tarjeta'],
                        'style' => [
                            'background-color' => '#ffffff',
                            'color' => '#18181b',
                            'border' => '1px solid #e4e4e7',
                        ],
                    ],
                ],
            ],
        ];
    }

    public function test_el_modo_dry_run_no_guarda_cambios(): void
    {
        $template = PageTemplate::factory()->create($this->templateAttributes());

        $this->artisan('landing:theme-tokens', ['--dry-run' => true])->assertSuccessful();

        $template->refresh();

        $this->assertStringContainsString('#ffffff', $template->html);
        $this->assertStringContainsString('#ffffff', $template->css);
        $this->assertSame('#ffffff', $template->gjs_data['styles'][0]['style']['background-color']);
    }

    public function test_el_comando_convierte_html_css_y_proyecto(): void
    {
        $template = PageTemplate::factory()->create($this->templateAttributes());

        $this->artisan('landing:theme-tokens')->assertSuccessful();

        $template->refresh();

        $this->assertStringContainsString('background-color:var(--cf-surface, #ffffff)', $template->html);
        $this->assertStringContainsString('color:var(--cf-ink, #18181b)', $template->html);
        $this->assertStringContainsString('color:var(--cf-brand, #ff4c00)', $template->html);

        $this->assertStringContainsString('background-color:var(--cf-surface, #ffffff)', $template->css);
        $this->assertStringContainsString('color:var(--cf-ink, #18181b)', $template->css);
        $this->assertStringContainsString('border:1px solid var(--cf-border, #e4e4e7)', $template->css);

        $this->assertSame('var(--cf-surface, #ffffff)', $template->gjs_data['styles'][0]['style']['background-color']);
        $this->assertSame('var(--cf-ink, #18181b)', $template->gjs_data['styles'][0]['style']['color']);
        $this->assertSame('1px solid var(--cf-border, #e4e4e7)', $template->gjs_data['styles'][0]['style']['border']);
    }

    public function test_el_comando_es_idempotente(): void
    {
        $template = PageTemplate::factory()->create($this->templateAttributes());

        $this->artisan('landing:theme-tokens')->assertSuccessful();
        $this->artisan('landing:theme-tokens')->assertSuccessful();

        $template->refresh();

        $this->assertSame(1, substr_count($template->html, 'var(--cf-surface, #ffffff)'));
        $this->assertSame(1, substr_count($template->css, 'var(--cf-surface, #ffffff)'));
    }

    public function test_los_tokens_de_color_estan_definidos_para_los_dos_temas(): void
    {
        $light = BlockTheme::tokens();
        $dark = BlockTheme::tokens(true);

        $this->assertSame(array_keys($light), array_keys($dark));

        foreach (['--cf-surface', '--cf-ink', '--cf-text', '--cf-border', '--cf-brand'] as $token) {
            $this->assertArrayHasKey($token, $light);
            $this->assertArrayHasKey($token, $dark);
        }

        $this->assertNotSame($light['--cf-surface'], $dark['--cf-surface']);
    }
}
