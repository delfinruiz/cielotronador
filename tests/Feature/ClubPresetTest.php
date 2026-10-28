<?php

namespace Tests\Feature;

use App\Actions\CreatePageFromPreset;
use App\Models\Producto;
use App\Support\Presets\ClubPreset;
use App\Support\Presets\PresetLibrary;
use App\Support\TemplateSanitizer;
use Tests\AdminTestCase;

class ClubPresetTest extends AdminTestCase
{
    public function test_la_plantilla_club_incluye_equipo_y_ventas(): void
    {
        $html = app(TemplateSanitizer::class)->html((new ClubPreset)->html());

        foreach ([
            'id="inicio"',
            'id="equipo"',
            'data-cf-directiva="yes"',
            'id="ventas"',
            'data-cf-section="ventas"',
            'Jacqueline Reyes',
            'Angélica Fuente',
            'Bertita Vergara',
            '/img/plantillas/club/equipo-1.jpg',
            'Club CieloTronador',
        ] as $needle) {
            $this->assertStringContainsString($needle, $html);
        }

        $this->assertSame(['#inicio', '#equipo', '#ventas', '#contacto'], collect((new ClubPreset)->menu())->pluck('value')->all());
    }

    public function test_la_plantilla_club_no_deja_textos_de_ejemplo(): void
    {
        $html = app(TemplateSanitizer::class)->html((new ClubPreset)->html());

        foreach ([
            'Tu título principal',
            'Escribe aquí',
            'Comenzar',
            'Lorem ipsum',
            'Entrada al cargar',
            'Este bloque anima',
            'Escríbenos a',
            'Tu ciudad',
            'picsum.photos',
            'tudominio.com',
            'tucuenta',
        ] as $needle) {
            $this->assertStringNotContainsString($needle, $html);
        }
    }

    public function test_el_tema_club_tiene_variante_clara_y_oscura(): void
    {
        $preset = new ClubPreset;
        $css = $preset->settings()['theme_css'];

        $this->assertSame('', $preset->css());
        $this->assertSame(['oswald', 'inter'], $preset->fonts());
        $this->assertStringContainsString('--cf-brand: #ff4c00', $css);
        $this->assertStringContainsString('.dark body', $css);
        $this->assertStringContainsString('--cf-brand: #fb923c', $css);
    }

    public function test_crear_la_pagina_club_la_deja_como_borrador(): void
    {
        $template = app(CreatePageFromPreset::class)->execute(
            PresetLibrary::find('club'),
            'Club Demo',
            $this->admin()->id,
        );

        $this->assertSame('club-demo', $template->slug);
        $this->assertFalse($template->is_active);
        $this->assertNull($template->published_at);
        $this->assertSame(4, $template->mainMenuItems()->count());
        $this->assertStringContainsString('data-cf-directiva="yes"', $template->html);
    }

    public function test_la_vista_previa_muestra_el_equipo_y_los_productos(): void
    {
        $producto = Producto::factory()->create(['nombre' => 'Camiseta de prueba']);

        $this->actingAs($this->admin())
            ->get(route('presets.preview', ['preset' => 'club']))
            ->assertOk()
            ->assertSee('Formamos jugadores', false)
            ->assertSee('Jacqueline Reyes')
            ->assertSee('data-cf-directiva="yes"', false)
            ->assertSee('Camiseta de prueba')
            ->assertSee('--cf-brand: #ff4c00', false);
    }

    public function test_la_galeria_lista_las_seis_plantillas(): void
    {
        $this->actingAs($this->admin())
            ->get('/admin/plantillas')
            ->assertOk()
            ->assertSee('Agencia')
            ->assertSee('Creativo')
            ->assertSee('Estudio Visual')
            ->assertSee('Corporativo')
            ->assertSee('Producto')
            ->assertSee('Club');
    }
}
