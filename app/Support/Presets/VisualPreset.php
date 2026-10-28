<?php

namespace App\Support\Presets;

use App\Support\FormWidget;

/**
 * Plantilla "Estudio Visual": fotografía y producción de imagen.
 *
 * El hero es el bloque Carrusel en versión ancha: tres diapositivas con
 * imagen, titular, texto y botón. El resto de la página usa bloques reales del
 * constructor y el carácter visual vive en `settings.theme_css`.
 */
final class VisualPreset implements LandingPreset
{
    public function slug(): string
    {
        return 'estudio-visual';
    }

    public function name(): string
    {
        return 'Estudio Visual';
    }

    public function tagline(): string
    {
        return 'Hero con carrusel a pantalla completa para fotografía y producción de imagen.';
    }

    public function tags(): array
    {
        return ['Una página', 'Carrusel', 'Oscura'];
    }

    public function thumbnail(): string
    {
        return '/img/plantillas/miniaturas/estudio-visual.webp';
    }

    public function fonts(): array
    {
        return ['playfair-display', 'lato'];
    }

    public function settings(): array
    {
        return [
            'seo_title' => 'Estudio Visual · Fotografía y producción de imagen',
            'seo_description' => 'Estudio de fotografía editorial, producto y eventos. Imágenes que cuentan historias y hacen crecer tu marca.',
            'fonts' => $this->fonts(),
            'theme_css' => $this->themeCss(),
        ];
    }

    public function menu(): array
    {
        return [
            ['label' => 'Inicio', 'value' => '#inicio'],
            ['label' => 'Portafolio', 'value' => '#portafolio'],
            ['label' => 'Estudio', 'value' => '#estudio'],
            ['label' => 'Servicios', 'value' => '#servicios'],
            ['label' => 'Contacto', 'value' => '#contacto'],
        ];
    }

    public function html(): string
    {
        $blocks = new PresetBlocks;
        $lorem = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';

        $hero = $blocks->withId($blocks->get('cf-carrusel'), 'inicio');

        $hero = $blocks->fill($hero, [
            'max-width:1100px;margin:0 auto;padding:0 clamp(12px,3vw,24px);' => 'width:100%;max-width:none;margin:0;padding:0;',
            'border-radius:20px;box-shadow:0 15px 35px -15px rgba(0,0,0,0.28);' => 'border-radius:0;box-shadow:none;',
            'flex:0 0 100%;scroll-snap-align:center;margin:0;position:relative;overflow:hidden;' => 'flex:0 0 100%;scroll-snap-align:center;margin:0;position:relative;overflow:hidden;height:clamp(440px,80vh,760px);aspect-ratio:auto;',
            'padding:clamp(18px,4vw,28px) clamp(16px,4vw,24px);background-image:linear-gradient(to top, rgba(0,0,0,0.72), rgba(0,0,0,0));' => 'padding:clamp(28px,6vw,64px) clamp(16px,4vw,24px);background-image:linear-gradient(to top, rgba(0,0,0,0.86), rgba(0,0,0,0.28) 55%, rgba(0,0,0,0));text-align:center;',
            'font-size:clamp(20px,5vw,28px)' => 'font-size:clamp(30px,6.5vw,58px)',
            'margin:0 0 8px;font-size' => 'margin:0 0 12px;font-size',
            'margin:0 0 16px;max-width:44ch;' => 'margin:0 auto 18px;max-width:52ch;',
            'Título principal' => 'Luz que cuenta historias',
            'Segundo titular' => 'Cada detalle importa',
            'Tercer titular' => 'Tu marca, bien vista',
            'Ver más' => 'Ver portafolio',
            'Saber más' => 'Ver portafolio',
            'Contáctanos' => 'Ver portafolio',
            'https://picsum.photos/seed/cielo-slide-1/1600/900' => '/img/plantillas/estudio-visual/hero-1.jpg',
            'https://picsum.photos/seed/cielo-slide-2/1600/900' => '/img/plantillas/estudio-visual/hero-2.jpg',
            'https://picsum.photos/seed/cielo-slide-3/1600/900' => '/img/plantillas/estudio-visual/hero-3.jpg',
            'alt="Imagen principal"' => 'alt="Sesión de fotografía con cámara analógica"',
            'href="#contacto"' => 'href="#portafolio"',
            'data-slide-cta-url="#contacto"' => 'data-slide-cta-url="#portafolio"',
        ]);

        // La primera diapositiva lleva el H1 de la página; el resto mantiene H3.
        $hero = $blocks->nth($hero, '<h3 data-carousel-title-text="yes"', ['<h1 data-carousel-title-text="yes"']);
        $hero = $blocks->nth($hero, '</h3>', ['</h1>']);

        $hero = $blocks->nth($hero, 'alt="Imagen secundaria"', [
            'alt="Interior cálido de una cafetería"',
            'alt="Concierto con luces de escenario"',
        ]);

        $hero = $blocks->nth($hero, $lorem, [
            'Fotografía editorial, producto y eventos con una mirada cuidada de principio a fin.',
            'Fotografía editorial, producto y eventos con una mirada cuidada de principio a fin.',
            'Espacios, marcas y personas. Producimos la imagen completa, no solo la foto.',
            'Espacios, marcas y personas. Producimos la imagen completa, no solo la foto.',
            'Cobertura de eventos y campañas con entrega rápida y archivo ordenado.',
            'Cobertura de eventos y campañas con entrega rápida y archivo ordenado.',
        ]);

        $portafolio = $blocks->withId($blocks->fill($blocks->get('cf-galeria-4'), [
            '>Galería<' => '>Portafolio<',
            'Nuestros momentos' => 'Series recientes',
            'Haz clic en una imagen para verla en grande.' => 'Editorial, producto y eventos. Haz clic en una imagen para verla en grande.',
            'Entrenamientos' => 'Objetos cotidianos',
            'Sesiones por categoría en la Tortuga de Talcahuano.' => 'Bodegón de producto para un catálogo de diseño.',
            'Partidos' => 'Café de barrio',
            'La energía de cada jornada, dentro y fuera de la cancha.' => 'Serie documental sobre el oficio de una cafetería.',
            'Celebraciones' => 'Mesa de trabajo',
            'Los momentos que nos unen como familia del básquetbol.' => 'Fotografía de espacios y herramientas para un estudio creativo.',
            'Torneos' => 'Noche de escenario',
            'Torneos y actividades abiertas a toda la comunidad.' => 'Cobertura de un festival de música en vivo.',
            'https://picsum.photos/seed/cielo-entrenamientos/800/600' => '/img/plantillas/estudio-visual/serie-1.jpg',
            'https://picsum.photos/seed/cielo-partidos/800/600' => '/img/plantillas/estudio-visual/serie-2.jpg',
            'https://picsum.photos/seed/cielo-celebraciones/800/600' => '/img/plantillas/estudio-visual/serie-3.jpg',
            'https://picsum.photos/seed/cielo-torneos/800/600' => '/img/plantillas/estudio-visual/serie-4.jpg',
            'alt="Jugadores entrenando en la cancha"' => 'alt="Bodegón de producto para catálogo"',
            'alt="Partido del club en la cancha"' => 'alt="Serie documental en una cafetería"',
            'alt="Celebración del equipo"' => 'alt="Fotografía de espacios de trabajo"',
            'alt="Torneo del club"' => 'alt="Cobertura de un festival de música"',
            'aria-label="Ampliar imagen: Jugadores entrenando en la cancha"' => 'aria-label="Ampliar imagen: Bodegón de producto para catálogo"',
            'aria-label="Ampliar imagen: Partido del club en la cancha"' => 'aria-label="Ampliar imagen: Serie documental en una cafetería"',
            'aria-label="Ampliar imagen: Celebración del equipo"' => 'aria-label="Ampliar imagen: Fotografía de espacios de trabajo"',
            'aria-label="Ampliar imagen: Torneo del club"' => 'aria-label="Ampliar imagen: Cobertura de un festival de música"',
        ]), 'portafolio');

        $estudio = $blocks->withId($blocks->fill($blocks->get('cf-anim-split'), [
            'https://picsum.photos/seed/cielo-columna/1200/800' => '/img/plantillas/estudio-visual/estudio.jpg',
            'alt="Imagen de la sección"' => 'alt="Escritorio del Estudio Visual"',
            '>Columnas<' => '>El estudio<',
            'Cada lado entra por su costado' => 'Miramos antes de disparar',
            'La imagen se desliza desde la izquierda y el texto desde la derecha. En móvil se apilan sin perder la animación.' => 'Trabajamos con luz natural cuando se puede y con un equipo mínimo cuando no. Cada sesión empieza con una conversación: qué quieres mostrar y a quién. Después viene la producción, la selección y una entrega ordenada para que uses las fotos sin perder tiempo.',
            '>Conócenos</a>' => '>Hablemos</a>',
        ]), 'estudio');

        $cifras = $blocks->fill($blocks->get('cf-stats'), [
            'data-counter-value="120"' => 'data-counter-value="340"',
            'data-counter-value="35"' => 'data-counter-value="12"',
            'data-counter-value="12"' => 'data-counter-value="48"',
            'data-counter-value="8"' => 'data-counter-value="9"',
            'data-counter-label="Jugadores"' => 'data-counter-label="Sesiones realizadas"',
            'data-counter-label="Años de historia"' => 'data-counter-label="Años de oficio"',
            'data-counter-label="Categorías"' => 'data-counter-label="Marcas acompañadas"',
            'data-counter-label="Títulos"' => 'data-counter-label="Premios y menciones"',
            'class="cf-counter-label">Jugadores<' => 'class="cf-counter-label">Sesiones realizadas<',
            'class="cf-counter-label">Años de historia<' => 'class="cf-counter-label">Años de oficio<',
            'class="cf-counter-label">Categorías<' => 'class="cf-counter-label">Marcas acompañadas<',
            'class="cf-counter-label">Títulos<' => 'class="cf-counter-label">Premios y menciones<',
            'class="cf-counter-number">120<' => 'class="cf-counter-number">340<',
            'class="cf-counter-number">35<' => 'class="cf-counter-number">12<',
            'class="cf-counter-number">12<' => 'class="cf-counter-number">48<',
            'class="cf-counter-number">8<' => 'class="cf-counter-number">9<',
        ]);

        $servicios = $blocks->withId($blocks->fill($blocks->get('cf-cards'), [
            'Sobre nosotros' => 'Servicios',
            'Tres razones para elegirnos' => 'Lo que podemos fotografiar',
            '>✨<' => '>📷<',
            '>🏆<' => '>🛍️<',
            '>🤝<' => '>🎤<',
            '>Calidad<' => '>Editorial<',
            '>Comunidad<' => '>Producto<',
            '>Valores<' => '>Eventos<',
        ]), 'servicios');

        $servicios = $blocks->nth($servicios, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', [
            'Retratos, campañas y series documentales con dirección de arte incluida.',
            'Catálogos, bodegones y fotografía de e-commerce lista para publicar.',
            'Cobertura de conciertos, lanzamientos y actividades corporativas.',
        ]);

        $testimonio = $blocks->fill($blocks->get('cf-section-gray'), [
            'Título de la sección' => 'Lo que dicen las marcas',
        ]);

        $testimonio = preg_replace(
            '~<p style="[^"]*">Arrastra aquí tus textos.*?</p>~s',
            '<blockquote style="margin:0;padding:8px 0 8px 24px;border-left:4px solid var(--cf-brand, #c8a24a);color:var(--cf-ink-soft, #2b2b2b);font-size:20px;line-height:1.6;">'
            .'«La sesión fue rápida y muy bien producida. Las fotos del catálogo nos sirvieron para la web, las redes y la feria del mes siguiente.»'
            .'<footer style="margin-top:12px;color:var(--cf-muted, #9a948a);font-size:14px;font-style:normal;">— Ignacio Fuentes, tienda de diseño</footer>'
            .'</blockquote>',
            $testimonio,
            1,
        ) ?? $testimonio;

        $contacto = FormWidget::block([
            'id' => 'contacto',
            'titulo' => 'Cuéntanos tu sesión',
            'descripcion' => 'Dinos qué necesitas fotografiar, para cuándo y dónde. Te respondemos con una propuesta y un presupuesto claro.',
            'boton' => 'Pedir presupuesto',
            'exito' => '¡Gracias! Recibimos tu mensaje y te responderemos dentro del próximo día hábil.',
            'email' => 'hola@estudiovisual.cl',
            'instagram' => '@estudio.visual',
            'instagramUrl' => 'https://www.instagram.com/estudio.visual/',
        ]);

        $anio = now()->year;

        $pie = $blocks->fill($blocks->get('cf-footer-dark'), [
            '<a href="#inicio" style="display:inline-block;margin-bottom:14px;text-decoration:none;">
          <img src="/branding/logo-light" data-brand-logo-light="/branding/logo-light" data-brand-logo-dark="/branding/logo-dark" alt="Logotipo" style="height:52px;width:auto;">
        </a>' => '<a href="#inicio" style="display:inline-block;margin-bottom:14px;color:var(--cf-on-dark, #ffffff);font-family:var(--cf-font-heading);font-size:24px;text-decoration:none;">Estudio Visual</a>',
            'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.' => 'Fotografía y producción de imagen para marcas que quieren verse bien y contar algo.',
            '>Recursos<' => '>Servicios<',
            '>Quiénes somos<' => '>Estudio<',
            'href="#quienes-somos"' => 'href="#estudio"',
            'href="#categorias"' => 'href="#servicios"',
            'href="#noticias"' => 'href="#portafolio"',
            '>Noticias<' => '>Portafolio<',
            'href="#galeria"' => 'href="#portafolio"',
            '>Galería<' => '>Series<',
            'href="#horario"' => 'href="#contacto"',
            '>Horarios<' => '>Presupuesto<',
            'href="#preguntas"' => 'href="#contacto"',
            '>Preguntas frecuentes<' => '>Contacto<',
            'contacto@tudominio.com' => 'hola@estudiovisual.cl',
            'https://www.instagram.com/tucuenta/' => 'https://www.instagram.com/estudio.visual/',
            '>📸 @tucuenta<' => '>📸 @estudio.visual<',
            '>📍 Tu ciudad, País<' => '>📍 Santiago, Chile<',
        ]);

        $pie = preg_replace(
            '~© \d{4} Tu marca · Tu ciudad, País~',
            "© {$anio} Estudio Visual · Santiago, Chile",
            $pie,
        ) ?? $pie;

        return implode("\n\n", array_filter([
            $hero,
            $portafolio,
            $estudio,
            $cifras,
            $servicios,
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
    --cf-brand: #c8a24a;
    --cf-brand-light: #e0c684;
    --cf-brand-ink: #8a6a24;
    --cf-brand-soft: #f7f0df;
    --cf-brand-border: #e6d5ac;
    --cf-on-brand: #141414;
    --cf-dark-surface: #111111;
    --cf-ink: #111111;
    --cf-ink-soft: #2b2b2b;
    --cf-text: #5f5a52;
    --cf-muted: #9a948a;
    --cf-surface: #faf8f4;
    --cf-surface-muted: #f2eee6;
    --cf-surface-gray: #e9e3d8;
    --cf-border: #e2dbcf;
    --cf-border-strong: #c9bda9;
    background-color: #faf8f4;
    color: var(--cf-text);
}
.dark body {
    --cf-brand: #c8a24a;
    --cf-brand-light: #e0c684;
    --cf-brand-ink: #e0c684;
    --cf-brand-soft: rgba(200, 162, 74, .16);
    --cf-brand-border: rgba(200, 162, 74, .42);
    --cf-on-brand: #141414;
    --cf-dark-surface: #0b0b0b;
    --cf-ink: #f3efe7;
    --cf-ink-soft: #e0d9cd;
    --cf-text: #c8c1b6;
    --cf-muted: #9a948a;
    --cf-surface: #161513;
    --cf-surface-muted: #111110;
    --cf-surface-gray: #1d1b18;
    --cf-border: #35312b;
    --cf-border-strong: #4a453d;
    background-color: #0d0d0c;
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
}
section[id],
footer[id],
[data-cf-carousel] {
    scroll-margin-top: 6rem;
}
CSS;
    }
}
