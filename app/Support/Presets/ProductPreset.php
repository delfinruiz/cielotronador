<?php

namespace App\Support\Presets;

use App\Support\FormWidget;

/**
 * Plantilla "Producto": landing de una aplicación SaaS.
 *
 * Incluye el bloque de video (carga diferida de YouTube, Vimeo o Bunny.net),
 * la franja de marcas, los testimonios y las preguntas frecuentes dinámicas.
 */
final class ProductPreset implements LandingPreset
{
    public function slug(): string
    {
        return 'producto';
    }

    public function name(): string
    {
        return 'Producto';
    }

    public function tagline(): string
    {
        return 'Landing SaaS con video de producto, marcas, testimonios y preguntas frecuentes.';
    }

    public function tags(): array
    {
        return ['Una página', 'Video', 'Violeta'];
    }

    public function thumbnail(): string
    {
        return '/img/plantillas/miniaturas/producto.webp';
    }

    public function fonts(): array
    {
        return ['poppins', 'inter'];
    }

    public function settings(): array
    {
        return [
            'seo_title' => 'Nexo · Todo tu equipo en un solo lugar',
            'seo_description' => 'Proyectos, tareas y conversaciones en una plataforma simple. Prueba Nexo gratis y ordena el trabajo de tu equipo.',
            'fonts' => $this->fonts(),
            'theme_css' => $this->themeCss(),
        ];
    }

    public function menu(): array
    {
        return [
            ['label' => 'Inicio', 'value' => '#inicio'],
            ['label' => 'Producto', 'value' => '#producto'],
            ['label' => 'Video', 'value' => '#video'],
            ['label' => 'Clientes', 'value' => '#testimonios'],
            ['label' => 'Contacto', 'value' => '#contacto'],
        ];
    }

    public function html(): string
    {
        $blocks = new PresetBlocks;

        $hero = $blocks->fill($blocks->get('cf-hero'), [
            'Bienvenidos' => 'Nexo · Plataforma de equipos',
            'Tu título principal' => 'Todo tu equipo<br>en un solo lugar',
            'Escribe aquí una frase breve que explique de qué se trata esta página.' => 'Proyectos, tareas y conversaciones sin saltar entre herramientas. Una vista clara para decidir y avanzar.',
            '>Comenzar</a>' => '>Probar gratis</a>',
            'https://picsum.photos/seed/cielo-hero/1200/800' => '/img/plantillas/producto/hero.jpg',
            'alt="Imagen principal"' => 'alt="Panel de trabajo de Nexo en un computador portátil"',
        ]);

        $marcas = $blocks->fill($blocks->get('cf-marcas'), [
            'data-cf-option-speed="40"' => 'data-cf-option-speed="52"',
            'data-cf-option-grayscale="yes"' => 'data-cf-option-grayscale="no"',
        ]);

        $producto = $blocks->withId($blocks->fill($blocks->get('cf-cards'), [
            'Sobre nosotros' => 'Producto',
            'Tres razones para elegirnos' => 'Menos ruido, más avance',
            '>✨<' => '>📊<',
            '>🏆<' => '>⚡<',
            '>🤝<' => '>💬<',
            '>Calidad<' => '>Paneles claros<',
            '>Comunidad<' => '>Automatizaciones<',
            '>Valores<' => '>Colaboración<',
        ]), 'producto');

        $producto = $blocks->nth($producto, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', [
            'Cada proyecto con su estado, responsables y próximos pasos en una sola vista.',
            'Reglas simples que mueven tareas y avisan al equipo sin perseguir a nadie.',
            'Comentarios, archivos y decisiones junto al trabajo, no en otro chat.',
        ]);

        $video = $blocks->withId($blocks->fill($blocks->get('cf-video'), [
            'data-video-title="Video de presentación"' => 'data-video-title="Mira Nexo en acción"',
            'Reproducir video: Video de presentación' => 'Reproducir video: Mira Nexo en acción',
            '>Video de presentación<' => '>Mira Nexo en acción<',
        ]), 'video');

        $cifras = $blocks->fill($blocks->get('cf-stats'), [
            'data-counter-value="120"' => 'data-counter-value="12"',
            'data-counter-value="35"' => 'data-counter-value="40"',
            'data-counter-value="12"' => 'data-counter-value="98"',
            'data-counter-value="8"' => 'data-counter-value="24"',
            'data-counter-suffix="+"' => 'data-counter-suffix="k+"',
            'data-counter-label="Jugadores"' => 'data-counter-label="Equipos activos"',
            'data-counter-label="Años de historia"' => 'data-counter-label="Países"',
            'data-counter-label="Categorías"' => 'data-counter-label="Renovación anual"',
            'data-counter-label="Títulos"' => 'data-counter-label="Integraciones"',
            'class="cf-counter-label">Jugadores<' => 'class="cf-counter-label">Equipos activos<',
            'class="cf-counter-label">Años de historia<' => 'class="cf-counter-label">Países<',
            'class="cf-counter-label">Categorías<' => 'class="cf-counter-label">Renovación anual<',
            'class="cf-counter-label">Títulos<' => 'class="cf-counter-label">Integraciones<',
            'class="cf-counter-number">120<' => 'class="cf-counter-number">12<',
            'class="cf-counter-number">35<' => 'class="cf-counter-number">40<',
            'class="cf-counter-number">12<' => 'class="cf-counter-number">98<',
            'class="cf-counter-number">8<' => 'class="cf-counter-number">24<',
            '<span class="cf-counter-suffix">+</span>' => '<span class="cf-counter-suffix">k+</span>',
        ]);

        $testimonios = $blocks->fill($blocks->get('cf-testimonios'), [
            'data-cf-option-layout="slider"' => 'data-cf-option-layout="grid"',
            'data-cf-option-autoplay="yes"' => 'data-cf-option-autoplay="no"',
        ]);

        $faq = $blocks->fill($blocks->get('cf-faq'), [
            'data-cf-option-columns="1"' => 'data-cf-option-columns="2"',
        ]);

        $llamada = $blocks->fill($blocks->get('cf-anim-load'), [
            '>Bienvenida<' => '>Empieza hoy<',
            'Entrada al cargar la página' => 'Ordena el trabajo de tu equipo esta semana',
            'Este bloque anima sus elementos apenas se abre la página, sin esperar al scroll. Úsalo en la primera pantalla.' => 'Crea tu espacio, invita al equipo y prueba Nexo gratis por 14 días. Sin tarjeta y sin instalar nada.',
            '>Escríbenos</a>' => '>Crear cuenta</a>',
        ]);

        $contacto = FormWidget::block([
            'id' => 'contacto',
            'titulo' => 'Pide una demo',
            'descripcion' => 'Cuéntanos cómo trabaja tu equipo y te mostramos Nexo con tus propios procesos. También puedes escribirnos a hola@nexo.app.',
            'boton' => 'Solicitar demo',
            'exito' => '¡Gracias! Te escribiremos para coordinar una demo en los próximos días.',
            'email' => 'hola@nexo.app',
            'instagram' => '@nexoapp',
            'instagramUrl' => 'https://www.instagram.com/nexoapp/',
        ]);

        $anio = now()->year;

        $pie = $blocks->fill($blocks->get('cf-footer-columns'), [
            '<a href="#inicio" style="display:inline-block;margin-bottom:14px;text-decoration:none;">
          <img src="/branding/logo-light" data-brand-logo-light="/branding/logo-light" data-brand-logo-dark="/branding/logo-dark" alt="Logotipo" style="height:52px;width:auto;">
        </a>' => '<a href="#inicio" style="display:inline-block;margin-bottom:14px;color:var(--cf-ink, #0f172a);font-family:var(--cf-font-heading);font-size:23px;font-weight:700;text-decoration:none;">Nexo</a>',
            'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.' => 'La plataforma simple para ordenar proyectos, tareas y conversaciones de tu equipo.',
            '>Nosotros<' => '>Producto<',
            '>Recursos<' => '>Recursos<',
            '>Quiénes somos<' => '>Producto<',
            'href="#quienes-somos"' => 'href="#producto"',
            'href="#categorias"' => 'href="#video"',
            '>Servicios<' => '>Video<',
            'href="#noticias"' => 'href="#testimonios"',
            '>Noticias<' => '>Clientes<',
            'href="#galeria"' => 'href="#marcas"',
            '>Galería<' => '>Marcas<',
            'href="#horario"' => 'href="#faq"',
            '>Horarios<' => '>Preguntas frecuentes<',
            'href="#preguntas"' => 'href="#contacto"',
            '>Preguntas frecuentes<' => '>Contacto<',
            'contacto@tudominio.com' => 'hola@nexo.app',
            'https://www.instagram.com/tucuenta/' => 'https://www.instagram.com/nexoapp/',
            '>📸 @tucuenta<' => '>📸 @nexoapp<',
            '>📍 Tu ciudad, País<' => '>📍 Santiago, Chile<',
        ]);

        $pie = preg_replace(
            '~© \d{4} Tu marca · Tu ciudad, País~',
            "© {$anio} Nexo · Santiago, Chile",
            $pie,
        ) ?? $pie;

        return implode("\n\n", array_filter([
            $hero,
            $marcas,
            $producto,
            $video,
            $cifras,
            $testimonios,
            $faq,
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
    --cf-brand: #5b4bff;
    --cf-brand-light: #8b7cff;
    --cf-brand-ink: #4338ca;
    --cf-brand-soft: #eef0ff;
    --cf-brand-border: #c7cbff;
    --cf-on-brand: #ffffff;
    --cf-dark-surface: #0b1020;
    --cf-ink: #0f172a;
    --cf-ink-soft: #334155;
    --cf-text: #475569;
    --cf-muted: #64748b;
    --cf-surface: #ffffff;
    --cf-surface-muted: #f7f8ff;
    --cf-surface-gray: #eef0fa;
    --cf-border: #e2e8f0;
    --cf-border-strong: #cbd5e1;
    background-color: #ffffff;
    color: var(--cf-text);
}
.dark body {
    --cf-brand: #8b7cff;
    --cf-brand-light: #b3a9ff;
    --cf-brand-ink: #c7c0ff;
    --cf-brand-soft: rgba(139, 124, 255, .16);
    --cf-brand-border: rgba(139, 124, 255, .40);
    --cf-on-brand: #0b1020;
    --cf-dark-surface: #070a14;
    --cf-ink: #f8fafc;
    --cf-ink-soft: #e2e8f0;
    --cf-text: #cbd5e1;
    --cf-muted: #94a3b8;
    --cf-surface: #0f1526;
    --cf-surface-muted: #0b1020;
    --cf-surface-gray: #141b30;
    --cf-border: #1e293b;
    --cf-border-strong: #334155;
    background-color: #0b1020;
    color: var(--cf-text);
}
h1,
h2,
h3,
h4,
h5,
h6 {
    font-family: var(--cf-font-heading);
    letter-spacing: -.02em;
}
section[id],
footer[id] {
    scroll-margin-top: 6rem;
}
.cf-testimonios__grid {
    grid-template-columns: repeat(2, minmax(0, 1fr));
}
@media (max-width: 720px) {
    .cf-testimonios__grid { grid-template-columns: 1fr; }
}
CSS;
    }
}
