<?php

namespace App\Support\Presets;

use App\Support\FormWidget;

/**
 * Plantilla "Creativo": portafolio de una página para un estudio pequeño.
 *
 * Usa los mismos bloques del constructor que cualquier usuario puede
 * arrastrar. El carácter visual (rojo coral, crema, Bebas Neue) se guarda en
 * `settings.theme_css` y el cuerpo se compone con los bloques reales.
 */
final class CreativePreset implements LandingPreset
{
    public function slug(): string
    {
        return 'creativo';
    }

    public function name(): string
    {
        return 'Creativo';
    }

    public function tagline(): string
    {
        return 'Portafolio de una página con proyecto, proceso y contacto directo.';
    }

    public function tags(): array
    {
        return ['Una página', 'Portafolio', 'Roja'];
    }

    public function thumbnail(): string
    {
        return '/img/plantillas/miniaturas/creativo.webp';
    }

    public function fonts(): array
    {
        return ['bebas-neue', 'inter'];
    }

    public function settings(): array
    {
        return [
            'seo_title' => 'Estudio Creativo · Ideas que se ven',
            'seo_description' => 'Estudio de diseño de marcas, sitios web y campañas. Proyectos con identidad propia, hechos con pocas reglas y muchas ideas.',
            'fonts' => $this->fonts(),
            'theme_css' => $this->themeCss(),
        ];
    }

    public function menu(): array
    {
        return [
            ['label' => 'Inicio', 'value' => '#inicio'],
            ['label' => 'Proyectos', 'value' => '#proyectos'],
            ['label' => 'Estudio', 'value' => '#estudio'],
            ['label' => 'Proceso', 'value' => '#proceso'],
            ['label' => 'Contacto', 'value' => '#contacto'],
        ];
    }

    public function html(): string
    {
        $blocks = new PresetBlocks;

        $hero = $blocks->withId($blocks->fill($blocks->get('cf-anim-load'), [
            '>Bienvenida<' => '>Estudio creativo · Portafolio 2026<',
            '<h2' => '<h1',
            '</h2>' => '</h1>',
            'Entrada al cargar la página' => 'Ideas que<br>se ven',
            'font-size:clamp(32px,7vw,46px);line-height:1.08' => 'font-size:clamp(56px,12vw,120px);line-height:.92',
            'Este bloque anima sus elementos apenas se abre la página, sin esperar al scroll. Úsalo en la primera pantalla.' => 'Diseño de marcas, sitios y campañas para proyectos que no quieren pasar desapercibidos.',
            'href="#contacto"' => 'href="#proyectos"',
            '>Escríbenos</a>' => '>Ver proyectos</a>',
        ]), 'inicio');

        $proyectos = $blocks->withId($blocks->fill($blocks->get('cf-galeria-4'), [
            '>Galería<' => '>Portafolio<',
            'Nuestros momentos' => 'Trabajos recientes',
            'Haz clic en una imagen para verla en grande.' => 'Cuatro proyectos de identidad, web y contenido. Haz clic en una imagen para verla en grande.',
            'Entrenamientos' => 'Nube Café',
            'Sesiones por categoría en la Tortuga de Talcahuano.' => 'Identidad y sitio web para una cafetería de barrio.',
            'Partidos' => 'Raíz Estudio',
            'La energía de cada jornada, dentro y fuera de la cancha.' => 'Fotografía, marca y portafolio digital para un estudio de imagen.',
            'Celebraciones' => 'Faro Tienda',
            'Los momentos que nos unen como familia del básquetbol.' => 'Catálogo, pagos y envíos para una tienda independiente.',
            'Torneos' => 'Lumen Festival',
            'Torneos y actividades abiertas a toda la comunidad.' => 'Campaña visual y venta de entradas para un festival de música.',
            'https://picsum.photos/seed/cielo-entrenamientos/800/600' => '/img/plantillas/creativo/proyecto-1.jpg',
            'https://picsum.photos/seed/cielo-partidos/800/600' => '/img/plantillas/creativo/proyecto-2.jpg',
            'https://picsum.photos/seed/cielo-celebraciones/800/600' => '/img/plantillas/creativo/proyecto-3.jpg',
            'https://picsum.photos/seed/cielo-torneos/800/600' => '/img/plantillas/creativo/proyecto-4.jpg',
            'alt="Jugadores entrenando en la cancha"' => 'alt="Identidad y sitio web de Nube Café"',
            'alt="Partido del club en la cancha"' => 'alt="Portafolio de Raíz Estudio"',
            'alt="Celebración del equipo"' => 'alt="Catálogo de Faro Tienda"',
            'alt="Torneo del club"' => 'alt="Campaña visual de Lumen Festival"',
            'aria-label="Ampliar imagen: Jugadores entrenando en la cancha"' => 'aria-label="Ampliar imagen: Identidad y sitio web de Nube Café"',
            'aria-label="Ampliar imagen: Partido del club en la cancha"' => 'aria-label="Ampliar imagen: Portafolio de Raíz Estudio"',
            'aria-label="Ampliar imagen: Celebración del equipo"' => 'aria-label="Ampliar imagen: Catálogo de Faro Tienda"',
            'aria-label="Ampliar imagen: Torneo del club"' => 'aria-label="Ampliar imagen: Campaña visual de Lumen Festival"',
        ]), 'proyectos');

        $estudio = $blocks->withId($blocks->fill($blocks->get('cf-anim-split'), [
            'https://picsum.photos/seed/cielo-columna/1200/800' => '/img/plantillas/creativo/estudio.jpg',
            'alt="Imagen de la sección"' => 'alt="Mesa de trabajo del Estudio Creativo"',
            '>Columnas<' => '>El estudio<',
            'Cada lado entra por su costado' => 'Pocas reglas,<br>muchas ideas',
            'La imagen se desliza desde la izquierda y el texto desde la derecha. En móvil se apilan sin perder la animación.' => 'Somos un equipo de tres personas que trabaja con pocos proyectos a la vez. Preferimos entender bien un negocio antes de dibujar la primera pantalla, y cuidar cada detalle hasta el lanzamiento.',
            '>Conócenos</a>' => '>Hablemos</a>',
        ]), 'estudio');

        $proceso = $blocks->withId($blocks->fill($blocks->get('cf-steps'), [
            'Primer paso' => 'Descubrir',
            'Explica aquí el primer paso del proceso.' => 'Escuchamos, investigamos y definimos el problema antes de abrir el editor.',
            'Segundo paso' => 'Diseñar',
            'Explica aquí el segundo paso del proceso.' => 'Exploramos direcciones, prototipamos y afinamos hasta que todo tiene sentido.',
            'Tercer paso' => 'Lanzar',
            'Explica aquí el tercer paso del proceso.' => 'Publicamos, medimos y dejamos todo listo para que el proyecto siga creciendo.',
        ]), 'proceso');

        $testimonio = $blocks->fill($blocks->get('cf-section-dark'), [
            'Título de la sección' => 'Palabras de clientes',
        ]);

        $testimonio = preg_replace(
            '~<p style="[^"]*">Arrastra aquí tus textos.*?</p>~s',
            '<blockquote style="margin:0;padding:8px 0 8px 24px;border-left:4px solid var(--cf-brand, #e8341f);color:var(--cf-on-dark-muted, #d4d4d8);font-size:20px;line-height:1.6;">'
            .'«Llegamos con una idea suelta y salimos con una marca que se entiende en cualquier parte. El proceso fue claro, sin vueltas y con entregas a tiempo.»'
            .'<footer style="margin-top:12px;color:var(--cf-on-dark-subtle, #a1a1aa);font-size:14px;font-style:normal;">— Camila Rojas, Nube Café</footer>'
            .'</blockquote>',
            $testimonio,
            1,
        ) ?? $testimonio;

        $contacto = FormWidget::block([
            'id' => 'contacto',
            'titulo' => 'Hablemos de tu proyecto',
            'descripcion' => 'Cuéntanos qué tienes en mente. Respondemos con una propuesta clara y un calendario realista en menos de 48 horas.',
            'boton' => 'Enviar mensaje',
            'exito' => '¡Gracias! Recibimos tu mensaje y te responderemos en menos de 48 horas.',
            'email' => 'hola@estudiocreativo.cl',
            'instagram' => '@estudio.creativo',
            'instagramUrl' => 'https://www.instagram.com/estudio.creativo/',
        ]);

        $anio = now()->year;

        $pie = $blocks->fill($blocks->get('cf-footer-minimal'), [
            '<a href="#inicio" style="display:flex;align-items:center;gap:10px;text-decoration:none;">
      <img src="/branding/logo-light" data-brand-logo-light="/branding/logo-light" data-brand-logo-dark="/branding/logo-dark" alt="Logotipo" style="height:36px;width:auto;">
      <span style="color:var(--cf-ink, #18181b);font-size:15px;font-weight:800;">Tu marca</span>
    </a>' => '<a href="#inicio" style="display:flex;align-items:center;gap:10px;text-decoration:none;color:var(--cf-ink, #171412);font-size:17px;font-weight:800;letter-spacing:.04em;text-transform:uppercase;">Estudio Creativo</a>',
            '>Noticias<' => '>Proyectos<',
            'href="#noticias"' => 'href="#proyectos"',
            '© 2026 · Tu ciudad, País' => "© {$anio} · Estudio Creativo",
        ]);

        $pie = preg_replace(
            '~© \d{4} · Tu ciudad, País~',
            "© {$anio} · Estudio Creativo",
            $pie,
        ) ?? $pie;

        return implode("\n\n", array_filter([
            $hero,
            $proyectos,
            $estudio,
            $proceso,
            $testimonio,
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
    --cf-brand: #e8341f;
    --cf-brand-light: #ff6a52;
    --cf-brand-ink: #a81f0f;
    --cf-brand-soft: #ffeae6;
    --cf-brand-border: #ffc7bf;
    --cf-on-brand: #ffffff;
    --cf-dark-surface: #171412;
    --cf-ink: #171412;
    --cf-ink-soft: #3b332d;
    --cf-text: #6b625a;
    --cf-muted: #a39a90;
    --cf-surface: #faf7f2;
    --cf-surface-muted: #f3ede5;
    --cf-surface-gray: #ece4d9;
    --cf-border: #e3d9cc;
    --cf-border-strong: #cdbfae;
    background-color: #faf7f2;
    color: var(--cf-text);
}
.dark body {
    --cf-brand: #ff6a52;
    --cf-brand-light: #ff9a88;
    --cf-brand-ink: #ffb3a6;
    --cf-brand-soft: rgba(255, 106, 82, .14);
    --cf-brand-border: rgba(255, 106, 82, .40);
    --cf-on-brand: #1a1512;
    --cf-dark-surface: #0f0d0b;
    --cf-ink: #f6f1ea;
    --cf-ink-soft: #e2d9cf;
    --cf-text: #c9beb2;
    --cf-muted: #9c9186;
    --cf-surface: #181411;
    --cf-surface-muted: #13100e;
    --cf-surface-gray: #201a16;
    --cf-border: #37302a;
    --cf-border-strong: #4a4138;
    background-color: #100e0c;
    color: var(--cf-text);
}
h1,
h2,
h3,
h4,
h5,
h6 {
    font-family: var(--cf-font-heading);
    text-transform: uppercase;
    letter-spacing: .04em;
}
h1 {
    line-height: .95;
}
section[id],
footer[id] {
    scroll-margin-top: 6rem;
}
CSS;
    }
}
