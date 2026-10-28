<?php

namespace App\Support\Presets;

use App\Support\FormWidget;

/**
 * Plantilla "Agencia": una página de estudio creativo con hero, servicios,
 * portafolio, sobre nosotros, cifras, testimonio, llamada a la acción y
 * formulario de contacto.
 *
 * Todo el cuerpo está hecho con bloques reales del constructor. El color y la
 * tipografía se aplican con variables CSS en el campo CSS de la página, así
 * que el usuario puede seguir editando textos e imágenes con doble clic.
 */
final class AgencyPreset implements LandingPreset
{
    public function slug(): string
    {
        return 'agencia';
    }

    public function name(): string
    {
        return 'Agencia';
    }

    public function tagline(): string
    {
        return 'Estudio creativo de una página con portafolio, cifras y formulario.';
    }

    public function tags(): array
    {
        return ['Una página', 'Portafolio', 'Amarilla'];
    }

    public function thumbnail(): string
    {
        return '/img/plantillas/miniaturas/agencia.webp';
    }

    public function fonts(): array
    {
        return ['montserrat', 'roboto-slab'];
    }

    public function settings(): array
    {
        return [
            'seo_title' => 'Agencia Nova · Diseño y sitios web',
            'seo_description' => 'Estudio creativo de diseño de marca, sitios web y campañas. Convertimos ideas en experiencias digitales memorables.',
            'fonts' => $this->fonts(),
            'theme_css' => $this->themeCss(),
        ];
    }

    public function menu(): array
    {
        return [
            ['label' => 'Inicio', 'value' => '#inicio'],
            ['label' => 'Servicios', 'value' => '#servicios'],
            ['label' => 'Portafolio', 'value' => '#portafolio'],
            ['label' => 'Nosotros', 'value' => '#nosotros'],
            ['label' => 'Contacto', 'value' => '#contacto'],
        ];
    }

    public function html(): string
    {
        $blocks = new PresetBlocks;

        $hero = $blocks->fill($blocks->get('cf-hero'), [
            'Bienvenidos' => 'Estudio de diseño',
            'Tu título principal' => 'Diseño que<br>impulsa tu marca',
            'Escribe aquí una frase breve que explique de qué se trata esta página.' => 'Somos un estudio creativo que convierte ideas en experiencias digitales memorables. Estrategia, identidad y sitios web que venden.',
            '>Comenzar</a>' => '>Ver servicios</a>',
            'href="#contacto"' => 'href="#servicios"',
            'https://picsum.photos/seed/cielo-hero/1200/800' => '/img/plantillas/agencia/hero.jpg',
            'alt="Imagen principal"' => 'alt="Equipo creativo trabajando en el estudio"',
        ]);

        $servicios = $blocks->withId($blocks->fill($blocks->get('cf-cards'), [
            'Sobre nosotros' => 'Servicios',
            'Tres razones para elegirnos' => 'Lo que hacemos por tu marca',
            '>✨<' => '>🎨<',
            '>🏆<' => '>💻<',
            '>🤝<' => '>📣<',
            '>Calidad<' => '>Identidad visual<',
            '>Comunidad<' => '>Sitios web<',
            '>Valores<' => '>Campañas<',
        ]), 'servicios');

        $servicios = $blocks->nth($servicios, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', [
            'Logotipos, paletas y manuales que hacen reconocible tu negocio en cualquier soporte.',
            'Diseño y desarrollo de páginas rápidas, claras y fáciles de administrar.',
            'Contenido y anuncios que llevan tu propuesta al público correcto.',
        ]);

        $portafolio = $blocks->withId($blocks->fill($blocks->get('cf-galeria-4'), [
            '>Galería<' => '>Portafolio<',
            'Nuestros momentos' => 'Proyectos recientes',
            'Haz clic en una imagen para verla en grande.' => 'Una selección de marcas y sitios que diseñamos este año. Haz clic en una imagen para ampliarla.',
            'Entrenamientos' => 'Marca Nube',
            'Sesiones por categoría en la Tortuga de Talcahuano.' => 'Identidad visual y empaques para una marca de café de especialidad.',
            'Partidos' => 'Café Alba',
            'La energía de cada jornada, dentro y fuera de la cancha.' => 'Sitio web y sistema de reservas para una cafetería de barrio.',
            'Celebraciones' => 'Estudio Raíz',
            'Los momentos que nos unen como familia del básquetbol.' => 'Naming, logotipo y papelería para un estudio de fotografía.',
            'Torneos' => 'Tienda Faro',
            'Torneos y actividades abiertas a toda la comunidad.' => 'Tienda en línea con catálogo, pagos y envíos a todo el país.',
            'https://picsum.photos/seed/cielo-entrenamientos/800/600' => '/img/plantillas/agencia/portafolio-1.jpg',
            'https://picsum.photos/seed/cielo-partidos/800/600' => '/img/plantillas/agencia/portafolio-2.jpg',
            'https://picsum.photos/seed/cielo-celebraciones/800/600' => '/img/plantillas/agencia/portafolio-3.jpg',
            'https://picsum.photos/seed/cielo-torneos/800/600' => '/img/plantillas/agencia/portafolio-4.jpg',
            'alt="Jugadores entrenando en la cancha"' => 'alt="Proyecto de identidad visual para Marca Nube"',
            'alt="Partido del club en la cancha"' => 'alt="Sitio web del Café Alba"',
            'alt="Celebración del equipo"' => 'alt="Identidad del Estudio Raíz"',
            'alt="Torneo del club"' => 'alt="Tienda en línea Faro"',
            'aria-label="Ampliar imagen: Jugadores entrenando en la cancha"' => 'aria-label="Ampliar imagen: Proyecto de identidad visual para Marca Nube"',
            'aria-label="Ampliar imagen: Partido del club en la cancha"' => 'aria-label="Ampliar imagen: Sitio web del Café Alba"',
            'aria-label="Ampliar imagen: Celebración del equipo"' => 'aria-label="Ampliar imagen: Identidad del Estudio Raíz"',
            'aria-label="Ampliar imagen: Torneo del club"' => 'aria-label="Ampliar imagen: Tienda en línea Faro"',
        ]), 'portafolio');

        $nosotros = $blocks->withId($blocks->fill($blocks->get('cf-anim-split'), [
            'https://picsum.photos/seed/cielo-columna/1200/800' => '/img/plantillas/agencia/nosotros.jpg',
            'alt="Imagen de la sección"' => 'alt="Equipo de Agencia Nova en una sesión de trabajo"',
            '>Columnas<' => '>Nosotros<',
            'Cada lado entra por su costado' => 'Un equipo pequeño con ideas grandes',
            'La imagen se desliza desde la izquierda y el texto desde la derecha. En móvil se apilan sin perder la animación.' => 'Somos seis personas entre diseño, desarrollo y contenido. Trabajamos con pocos clientes a la vez para cuidar cada detalle, desde la primera reunión hasta el lanzamiento.',
            '>Conócenos</a>' => '>Hablemos</a>',
        ]), 'nosotros');

        $cifras = $blocks->fill($blocks->get('cf-stats'), [
            'data-counter-value="35"' => 'data-counter-value="12"',
            'data-counter-value="12"' => 'data-counter-value="18"',
            'data-counter-value="8"' => 'data-counter-value="96"',
            'data-counter-label="Jugadores"' => 'data-counter-label="Proyectos entregados"',
            'data-counter-label="Años de historia"' => 'data-counter-label="Años de experiencia"',
            'data-counter-label="Categorías"' => 'data-counter-label="Premios de diseño"',
            'data-counter-label="Títulos"' => 'data-counter-label="Clientes que repiten"',
            'class="cf-counter-label">Jugadores<' => 'class="cf-counter-label">Proyectos entregados<',
            'class="cf-counter-label">Años de historia<' => 'class="cf-counter-label">Años de experiencia<',
            'class="cf-counter-label">Categorías<' => 'class="cf-counter-label">Premios de diseño<',
            'class="cf-counter-label">Títulos<' => 'class="cf-counter-label">Clientes que repiten<',
            'class="cf-counter-number">35<' => 'class="cf-counter-number">12<',
            'class="cf-counter-number">12<' => 'class="cf-counter-number">18<',
            'class="cf-counter-number">8<' => 'class="cf-counter-number">96<',
        ]);

        $testimonio = $blocks->get('cf-section-gray');

        $testimonio = $blocks->fill($testimonio, [
            'Título de la sección' => 'Lo que dicen nuestros clientes',
        ]);

        $testimonio = preg_replace(
            '~<p style="[^"]*">Arrastra aquí tus textos.*?</p>~s',
            '<blockquote style="margin:0;padding:8px 0 8px 24px;border-left:4px solid var(--cf-brand, #ffc800);color:var(--cf-ink-soft, #343a40);font-size:20px;line-height:1.6;">'
            .'«Entendieron nuestra marca mejor que nosotros. En tres semanas teníamos un sitio claro, rápido y con una identidad que nos representa.»'
            .'<footer style="margin-top:12px;color:var(--cf-muted, #adb5bd);font-size:14px;font-style:normal;">— María Pérez, fundadora de Café Alba</footer>'
            .'</blockquote>',
            $testimonio,
            1,
        ) ?? $testimonio;

        $llamada = $blocks->fill($blocks->get('cf-anim-load'), [
            '>Bienvenida<' => '>Hablemos<',
            'Entrada al cargar la página' => '¿Tienes un proyecto en mente?',
            'Este bloque anima sus elementos apenas se abre la página, sin esperar al scroll. Úsalo en la primera pantalla.' => 'Cuéntanos qué necesitas y te responderemos con una propuesta clara en menos de 48 horas.',
            '>Escríbenos</a>' => '>Cuéntanos tu idea</a>',
        ]);

        $contacto = FormWidget::block([
            'id' => 'contacto',
            'titulo' => 'Cuéntanos tu proyecto',
            'descripcion' => 'Completa el formulario y te escribiremos con los siguientes pasos. También puedes escribirnos directamente a hola@agencianova.cl.',
            'boton' => 'Enviar mensaje',
            'exito' => '¡Gracias! Recibimos tu mensaje y te responderemos en menos de 48 horas.',
            'email' => 'hola@agencianova.cl',
            'instagram' => '@agencianova',
            'instagramUrl' => 'https://www.instagram.com/agencianova/',
        ]);

        $anio = now()->year;

        $pie = $blocks->fill($blocks->get('cf-footer-columns'), [
            '<a href="#inicio" style="display:inline-block;margin-bottom:14px;text-decoration:none;">
          <img src="/branding/logo-light" data-brand-logo-light="/branding/logo-light" data-brand-logo-dark="/branding/logo-dark" alt="Logotipo" style="height:52px;width:auto;">
        </a>' => '<a href="#inicio" style="display:inline-block;margin-bottom:14px;color:var(--cf-ink, #212529);font-size:22px;font-weight:800;text-decoration:none;">Agencia Nova</a>',
            'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.' => 'Diseñamos marcas y sitios web para negocios que quieren crecer con una imagen clara y coherente.',
            '>Nosotros<' => '>Empresa<',
            '>Recursos<' => '>Servicios<',
            '>Quiénes somos<' => '>Nosotros<',
            'href="#quienes-somos"' => 'href="#nosotros"',
            'href="#categorias"' => 'href="#servicios"',
            'href="#noticias"' => 'href="#portafolio"',
            '>Noticias<' => '>Portafolio<',
            'href="#galeria"' => 'href="#portafolio"',
            '>Galería<' => '>Identidad visual<',
            'href="#horario"' => 'href="#servicios"',
            '>Horarios<' => '>Sitios web<',
            'href="#preguntas"' => 'href="#contacto"',
            '>Preguntas frecuentes<' => '>Campañas<',
            'contacto@tudominio.com' => 'hola@agencianova.cl',
            'https://www.instagram.com/tucuenta/' => 'https://www.instagram.com/agencianova/',
            '>📸 @tucuenta<' => '>📸 @agencianova<',
            '>📍 Tu ciudad, País<' => '>📍 Santiago, Chile<',
            '© 2026 Tu marca · Tu ciudad, País' => "© {$anio} Agencia Nova · Santiago, Chile",
        ]);

        $pie = preg_replace(
            '~© \d{4} Tu marca · Tu ciudad, País~',
            "© {$anio} Agencia Nova · Santiago, Chile",
            $pie,
        ) ?? $pie;

        return implode("\n\n", array_filter([
            $hero,
            $servicios,
            $portafolio,
            $nosotros,
            $cifras,
            $testimonio,
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
     *
     * Se guardan en `settings.theme_css` y el render las inyecta tanto en la
     * página pública como en el lienzo del editor. Así el usuario puede seguir
     * editando el HTML sin que GrapesJS sobrescriba el tema al guardar.
     */
    public function themeCss(): string
    {
        return <<<'CSS'
body {
    --cf-brand: #ffc800;
    --cf-brand-light: #ffd84d;
    --cf-brand-ink: #8a6d00;
    --cf-brand-soft: #fff8dd;
    --cf-brand-border: #ffe89a;
    --cf-on-brand: #212529;
    --cf-dark-surface: #212529;
    --cf-ink: #212529;
    --cf-ink-soft: #343a40;
    --cf-text: #6c757d;
    --cf-muted: #adb5bd;
    --cf-surface: #ffffff;
    --cf-surface-muted: #f8f9fa;
    --cf-surface-gray: #f1f3f5;
    --cf-border: #e9ecef;
    --cf-border-strong: #dee2e6;
    background-color: #ffffff;
    color: var(--cf-text);
}
.dark body {
    --cf-brand: #ffc800;
    --cf-brand-light: #ffd84d;
    --cf-brand-ink: #ffd84d;
    --cf-brand-soft: rgba(255, 200, 0, .14);
    --cf-brand-border: rgba(255, 200, 0, .42);
    --cf-on-brand: #212529;
    --cf-dark-surface: #101010;
    --cf-ink: #f4f4f5;
    --cf-ink-soft: #e4e4e7;
    --cf-text: #c4c4c7;
    --cf-muted: #a1a1aa;
    --cf-surface: #18181b;
    --cf-surface-muted: #131316;
    --cf-surface-gray: #1f1f23;
    --cf-border: #3f3f46;
    --cf-border-strong: #52525b;
    background-color: #111113;
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
    letter-spacing: .02em;
}
h1 {
    line-height: 1.05;
}
section[id],
footer[id] {
    scroll-margin-top: 6rem;
}
CSS;
    }
}
