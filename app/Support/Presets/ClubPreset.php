<?php

namespace App\Support\Presets;

use App\Support\DirectivaWidget;
use App\Support\FormWidget;

/**
 * Plantilla "Club": página de un club deportivo con equipo y tienda.
 *
 * Usa el bloque "Nuestro equipo" (editable en el constructor) y la sección
 * dinámica de productos, que lee los artículos publicados en Tienda.
 */
final class ClubPreset implements LandingPreset
{
    public function slug(): string
    {
        return 'club';
    }

    public function name(): string
    {
        return 'Club';
    }

    public function tagline(): string
    {
        return 'Club deportivo con equipo, tienda, testimonios y contacto.';
    }

    public function tags(): array
    {
        return ['Una página', 'Equipo + tienda', 'Naranja'];
    }

    public function thumbnail(): string
    {
        return '/img/plantillas/miniaturas/club.webp';
    }

    public function fonts(): array
    {
        return ['oswald', 'inter'];
    }

    public function settings(): array
    {
        return [
            'seo_title' => 'Club CieloTronador · Formamos jugadores',
            'seo_description' => 'Club de básquetbol formativo: categorías desde sub 9 hasta adultos, equipo de trabajo y tienda oficial.',
            'fonts' => $this->fonts(),
            'theme_css' => $this->themeCss(),
        ];
    }

    public function menu(): array
    {
        return [
            ['label' => 'Inicio', 'value' => '#inicio'],
            ['label' => 'Equipo', 'value' => '#equipo'],
            ['label' => 'Tienda', 'value' => '#ventas'],
            ['label' => 'Contacto', 'value' => '#contacto'],
        ];
    }

    public function html(): string
    {
        $blocks = new PresetBlocks;

        $hero = $blocks->fill($blocks->get('cf-hero'), [
            'Bienvenidos' => 'Club deportivo · Talcahuano',
            'Tu título principal' => 'Formamos jugadores,<br>construimos personas',
            'Escribe aquí una frase breve que explique de qué se trata esta página.' => 'Básquetbol formativo desde sub 9 hasta adultos. Un equipo que acompaña dentro y fuera de la cancha.',
            '>Comenzar</a>' => '>Conoce el equipo</a>',
            'href="#contacto"' => 'href="#equipo"',
            'https://picsum.photos/seed/cielo-hero/1200/800' => '/img/plantillas/club/hero.jpg',
            'alt="Imagen principal"' => 'alt="Jugadores del club en la cancha"',
        ]);

        $cifras = $blocks->fill($blocks->get('cf-stats'), [
            'data-counter-value="120"' => 'data-counter-value="140"',
            'data-counter-value="35"' => 'data-counter-value="20"',
            'data-counter-value="12"' => 'data-counter-value="12"',
            'data-counter-value="8"' => 'data-counter-value="8"',
            'data-counter-label="Jugadores"' => 'data-counter-label="Jugadores activos"',
            'data-counter-label="Años de historia"' => 'data-counter-label="Años de historia"',
            'data-counter-label="Categorías"' => 'data-counter-label="Categorías"',
            'data-counter-label="Títulos"' => 'data-counter-label="Títulos"',
            'class="cf-counter-label">Jugadores<' => 'class="cf-counter-label">Jugadores activos<',
            'class="cf-counter-number">120<' => 'class="cf-counter-number">140<',
            'class="cf-counter-number">35<' => 'class="cf-counter-number">20<',
        ]);

        $equipo = $blocks->withId(DirectivaWidget::block(
            kicker: 'Nuestro equipo',
            title: 'Quienes hacen el club',
            intro: 'Un equipo de trabajo que sostiene cada categoría, cada viaje y cada entrenamiento.',
            members: [
                [
                    'nombre' => 'Jacqueline Reyes',
                    'puesto' => 'Directora',
                    'foto' => '/img/plantillas/club/equipo-1.jpg',
                ],
                [
                    'nombre' => 'Angélica Fuente',
                    'puesto' => 'Secretaria',
                    'foto' => '/img/plantillas/club/equipo-2.jpg',
                ],
                [
                    'nombre' => 'Bertita Vergara',
                    'puesto' => 'Tesorera',
                    'foto' => '/img/plantillas/club/equipo-3.jpg',
                ],
            ],
        ), 'equipo');

        $tienda = $blocks->get('cf-ventas');

        $testimonios = $blocks->fill($blocks->get('cf-testimonios'), [
            'data-cf-option-layout="slider"' => 'data-cf-option-layout="slider"',
            'data-cf-option-autoplay="yes"' => 'data-cf-option-autoplay="no"',
        ]);

        $llamada = $blocks->fill($blocks->get('cf-anim-load'), [
            '>Bienvenida<' => '>Inscripciones abiertas<',
            'Entrada al cargar la página' => 'Ven a entrenar con nosotros',
            'Este bloque anima sus elementos apenas se abre la página, sin esperar al scroll. Úsalo en la primera pantalla.' => 'Escríbenos para conocer horarios, categorías y cupos disponibles. La primera semana de prueba es sin costo.',
            '>Escríbenos</a>' => '>Pedir información</a>',
        ]);

        $contacto = FormWidget::block([
            'id' => 'contacto',
            'titulo' => 'Escríbenos',
            'descripcion' => 'Cuéntanos la edad del jugador y la categoría que te interesa. Te respondemos con horarios y cupos.',
            'boton' => 'Enviar mensaje',
            'exito' => '¡Gracias! Te contactaremos con los horarios y cupos disponibles.',
            'email' => 'contacto@cielotronador.cl',
            'instagram' => '@cielotronadorthno',
            'instagramUrl' => 'https://www.instagram.com/cielotronadorthno/',
        ]);

        $anio = now()->year;

        $pie = $blocks->fill($blocks->get('cf-footer-columns'), [
            'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.' => 'Básquetbol formativo en Talcahuano. Más que un club, una familia.',
            '>Nosotros<' => '>El club<',
            '>Recursos<' => '>Tienda<',
            'href="#quienes-somos"' => 'href="#equipo"',
            '>Quiénes somos<' => '>Equipo<',
            'href="#categorias"' => 'href="#ventas"',
            '>Servicios<' => '>Productos<',
            'href="#noticias"' => 'href="#testimonios"',
            '>Noticias<' => '>Testimonios<',
            'href="#galeria"' => 'href="#ventas"',
            '>Galería<' => '>Tienda<',
            'href="#horario"' => 'href="#contacto"',
            '>Horarios<' => '>Contacto<',
            'href="#preguntas"' => 'href="#contacto"',
            '>Preguntas frecuentes<' => '>Inscripciones<',
            'contacto@tudominio.com' => 'contacto@cielotronador.cl',
            'https://www.instagram.com/tucuenta/' => 'https://www.instagram.com/cielotronadorthno/',
            '>📸 @tucuenta<' => '>📸 @cielotronadorthno<',
            '>📍 Tu ciudad, País<' => '>📍 Talcahuano, Chile<',
        ]);

        $pie = preg_replace(
            '~© \d{4} Tu marca · Tu ciudad, País~',
            "© {$anio} Club CieloTronador · Talcahuano, Chile",
            $pie,
        ) ?? $pie;

        return implode("\n\n", array_filter([
            $hero,
            $cifras,
            $equipo,
            $tienda,
            $testimonios,
            $llamada,
            $contacto,
            $pie,
            $blocks->get('cf-top-button'),
        ]));
    }

    public function css(): string
    {
        return '';
    }

    /**
     * Variables y reglas que dan el carácter visual a la plantilla.
     */
    public function themeCss(): string
    {
        return <<<'CSS'
body {
    --cf-brand: #ff4c00;
    --cf-brand-light: #fb923c;
    --cf-brand-ink: #c2410c;
    --cf-brand-soft: #fff7ed;
    --cf-brand-border: #fed7aa;
    --cf-on-brand: #ffffff;
    --cf-dark-surface: #111827;
    --cf-ink: #111827;
    --cf-ink-soft: #374151;
    --cf-text: #4b5563;
    --cf-muted: #6b7280;
    --cf-surface: #ffffff;
    --cf-surface-muted: #f9fafb;
    --cf-surface-gray: #f3f4f6;
    --cf-border: #e5e7eb;
    --cf-border-strong: #d1d5db;
    background-color: #ffffff;
    color: var(--cf-text);
}
.dark body {
    --cf-brand: #fb923c;
    --cf-brand-light: #fdba74;
    --cf-brand-ink: #fed7aa;
    --cf-brand-soft: rgba(251, 146, 60, .14);
    --cf-brand-border: rgba(251, 146, 60, .40);
    --cf-on-brand: #1a1207;
    --cf-dark-surface: #0b0f17;
    --cf-ink: #f9fafb;
    --cf-ink-soft: #e5e7eb;
    --cf-text: #d1d5db;
    --cf-muted: #9ca3af;
    --cf-surface: #111827;
    --cf-surface-muted: #0f172a;
    --cf-surface-gray: #1f2937;
    --cf-border: #1f2937;
    --cf-border-strong: #374151;
    background-color: #0f172a;
    color: var(--cf-text);
}
h1,
h2,
h3,
h4,
h5,
h6 {
    font-family: var(--cf-font-heading);
    letter-spacing: .01em;
    text-transform: uppercase;
}
section[id],
footer[id] {
    scroll-margin-top: 6rem;
}
CSS;
    }
}
