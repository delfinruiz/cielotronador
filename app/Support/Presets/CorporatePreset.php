<?php

namespace App\Support\Presets;

use App\Support\FormWidget;

/**
 * Plantilla "Corporativo": consultoría y servicios profesionales.
 *
 * Combina los bloques clásicos con los tres módulos dinámicos nuevos:
 * franja de marcas, testimonios y preguntas frecuentes. El contenido de esos
 * módulos se administra en el panel y se comparte entre páginas.
 */
final class CorporatePreset implements LandingPreset
{
    public function slug(): string
    {
        return 'corporativo';
    }

    public function name(): string
    {
        return 'Corporativo';
    }

    public function tagline(): string
    {
        return 'Servicios profesionales con marcas, testimonios y preguntas frecuentes dinámicas.';
    }

    public function tags(): array
    {
        return ['Una página', 'Dinámica', 'Azul'];
    }

    public function thumbnail(): string
    {
        return '/img/plantillas/miniaturas/corporativo.webp';
    }

    public function fonts(): array
    {
        return ['raleway', 'inter'];
    }

    public function settings(): array
    {
        return [
            'seo_title' => 'Corporativo · Estrategia y crecimiento',
            'seo_description' => 'Consultoría en estrategia, operaciones y marketing para empresas que quieren crecer con orden.',
            'fonts' => $this->fonts(),
            'theme_css' => $this->themeCss(),
        ];
    }

    public function menu(): array
    {
        return [
            ['label' => 'Inicio', 'value' => '#inicio'],
            ['label' => 'Servicios', 'value' => '#servicios'],
            ['label' => 'Nosotros', 'value' => '#nosotros'],
            ['label' => 'Testimonios', 'value' => '#testimonios'],
            ['label' => 'Contacto', 'value' => '#contacto'],
        ];
    }

    public function html(): string
    {
        $blocks = new PresetBlocks;

        $hero = $blocks->fill($blocks->get('cf-hero'), [
            'Bienvenidos' => 'Consultoría y estrategia',
            'Tu título principal' => 'Crecemos<br>contigo',
            'Escribe aquí una frase breve que explique de qué se trata esta página.' => 'Acompañamos a empresas y equipos a ordenar sus procesos, definir su estrategia y crecer con foco.',
            '>Comenzar</a>' => '>Conversemos</a>',
            'https://picsum.photos/seed/cielo-hero/1200/800' => '/img/plantillas/corporativo/hero.jpg',
            'alt="Imagen principal"' => 'alt="Equipo de trabajo en una oficina luminosa"',
        ]);

        $marcas = $blocks->fill($blocks->get('cf-marcas'), [
            'data-cf-option-speed="40"' => 'data-cf-option-speed="48"',
            'data-cf-option-grayscale="yes"' => 'data-cf-option-grayscale="no"',
        ]);

        $servicios = $blocks->withId($blocks->fill($blocks->get('cf-cards'), [
            'Sobre nosotros' => 'Servicios',
            'Tres razones para elegirnos' => 'Cómo podemos ayudarte',
            '>✨<' => '>🧭<',
            '>🏆<' => '>⚙️<',
            '>🤝<' => '>📈<',
            '>Calidad<' => '>Estrategia<',
            '>Comunidad<' => '>Operaciones<',
            '>Valores<' => '>Marketing<',
        ]), 'servicios');

        $servicios = $blocks->nth($servicios, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', [
            'Definimos el rumbo, priorizamos iniciativas y bajamos la estrategia a un plan concreto.',
            'Revisamos procesos, costos y equipos para que la operación acompañe el crecimiento.',
            'Construimos una propuesta de valor clara y la llevamos al mercado con contenido y campañas.',
        ]);

        $cifras = $blocks->fill($blocks->get('cf-stats'), [
            'data-counter-value="120"' => 'data-counter-value="240"',
            'data-counter-value="35"' => 'data-counter-value="18"',
            'data-counter-value="12"' => 'data-counter-value="32"',
            'data-counter-value="8"' => 'data-counter-value="96"',
            'data-counter-label="Jugadores"' => 'data-counter-label="Clientes acompañados"',
            'data-counter-label="Años de historia"' => 'data-counter-label="Años de experiencia"',
            'data-counter-label="Categorías"' => 'data-counter-label="Países con proyectos"',
            'data-counter-label="Títulos"' => 'data-counter-label="Satisfacción"',
            'class="cf-counter-label">Jugadores<' => 'class="cf-counter-label">Clientes acompañados<',
            'class="cf-counter-label">Años de historia<' => 'class="cf-counter-label">Años de experiencia<',
            'class="cf-counter-label">Categorías<' => 'class="cf-counter-label">Países con proyectos<',
            'class="cf-counter-label">Títulos<' => 'class="cf-counter-label">Satisfacción<',
            'class="cf-counter-number">120<' => 'class="cf-counter-number">240<',
            'class="cf-counter-number">35<' => 'class="cf-counter-number">18<',
            'class="cf-counter-number">12<' => 'class="cf-counter-number">32<',
            'class="cf-counter-number">8<' => 'class="cf-counter-number">96<',
        ]);

        $nosotros = $blocks->withId($blocks->fill($blocks->get('cf-anim-split'), [
            'https://picsum.photos/seed/cielo-columna/1200/800' => '/img/plantillas/corporativo/nosotros.jpg',
            'alt="Imagen de la sección"' => 'alt="Escritorio de trabajo del equipo corporativo"',
            '>Columnas<' => '>Nosotros<',
            'Cada lado entra por su costado' => 'Un socio, no un proveedor',
            'La imagen se desliza desde la izquierda y el texto desde la derecha. En móvil se apilan sin perder la animación.' => 'Trabajamos con un equipo senior y pocos proyectos a la vez. Nos involucramos en la operación, hacemos las preguntas incómodas y dejamos instaladas las capacidades para que el cambio continúe sin nosotros.',
            '>Conócenos</a>' => '>Hablemos</a>',
        ]), 'nosotros');

        $testimonios = $blocks->fill($blocks->get('cf-testimonios'), [
            'data-cf-option-autoplay="yes"' => 'data-cf-option-autoplay="no"',
        ]);

        $faq = $blocks->fill($blocks->get('cf-faq'), [
            'data-cf-option-columns="1"' => 'data-cf-option-columns="2"',
        ]);

        $llamada = $blocks->fill($blocks->get('cf-anim-load'), [
            '>Bienvenida<' => '>Siguiente paso<',
            'Entrada al cargar la página' => 'Conversemos sobre tu próximo desafío',
            'Este bloque anima sus elementos apenas se abre la página, sin esperar al scroll. Úsalo en la primera pantalla.' => 'Agenda una primera reunión de 30 minutos. Sin costo y sin compromiso: salimos con un diagnóstico y próximos pasos claros.',
            '>Escríbenos</a>' => '>Agendar reunión</a>',
        ]);

        $contacto = FormWidget::block([
            'id' => 'contacto',
            'titulo' => 'Cuéntanos tu desafío',
            'descripcion' => 'Completa el formulario y coordinamos una primera conversación. También puedes escribirnos a hola@consultora.cl.',
            'boton' => 'Enviar mensaje',
            'exito' => '¡Gracias! Recibimos tu mensaje y coordinaremos una reunión en los próximos días.',
            'email' => 'hola@consultora.cl',
            'instagram' => '@consultora',
            'instagramUrl' => 'https://www.instagram.com/consultora/',
        ]);

        $anio = now()->year;

        $pie = $blocks->fill($blocks->get('cf-footer-columns'), [
            '<a href="#inicio" style="display:inline-block;margin-bottom:14px;text-decoration:none;">
          <img src="/branding/logo-light" data-brand-logo-light="/branding/logo-light" data-brand-logo-dark="/branding/logo-dark" alt="Logotipo" style="height:52px;width:auto;">
        </a>' => '<a href="#inicio" style="display:inline-block;margin-bottom:14px;color:var(--cf-ink, #0f172a);font-family:var(--cf-font-heading);font-size:23px;font-weight:700;text-decoration:none;">Consultora Norte</a>',
            'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.' => 'Estrategia, operaciones y marketing para empresas que quieren crecer con orden y foco.',
            '>Nosotros<' => '>Compañía<',
            '>Recursos<' => '>Servicios<',
            '>Quiénes somos<' => '>Nosotros<',
            'href="#quienes-somos"' => 'href="#nosotros"',
            'href="#categorias"' => 'href="#servicios"',
            'href="#noticias"' => 'href="#testimonios"',
            '>Noticias<' => '>Casos<',
            'href="#galeria"' => 'href="#marcas"',
            '>Galería<' => '>Marcas<',
            'href="#horario"' => 'href="#faq"',
            '>Horarios<' => '>Preguntas frecuentes<',
            'href="#preguntas"' => 'href="#testimonios"',
            '>Preguntas frecuentes<' => '>Testimonios<',
            'contacto@tudominio.com' => 'hola@consultora.cl',
            'https://www.instagram.com/tucuenta/' => 'https://www.instagram.com/consultora/',
            '>📸 @tucuenta<' => '>📸 @consultora<',
            '>📍 Tu ciudad, País<' => '>📍 Santiago, Chile<',
        ]);

        $pie = preg_replace(
            '~© \d{4} Tu marca · Tu ciudad, País~',
            "© {$anio} Consultora Norte · Santiago, Chile",
            $pie,
        ) ?? $pie;

        return implode("\n\n", array_filter([
            $hero,
            $marcas,
            $servicios,
            $cifras,
            $nosotros,
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
    --cf-brand: #0e7490;
    --cf-brand-light: #22b8cf;
    --cf-brand-ink: #155e75;
    --cf-brand-soft: #ecfeff;
    --cf-brand-border: #a5f3fc;
    --cf-on-brand: #ffffff;
    --cf-dark-surface: #0b1220;
    --cf-ink: #0f172a;
    --cf-ink-soft: #1e293b;
    --cf-text: #475569;
    --cf-muted: #64748b;
    --cf-surface: #ffffff;
    --cf-surface-muted: #f8fafc;
    --cf-surface-gray: #eef2f6;
    --cf-border: #e2e8f0;
    --cf-border-strong: #cbd5e1;
    background-color: #ffffff;
    color: var(--cf-text);
}
.dark body {
    --cf-brand: #22b8cf;
    --cf-brand-light: #67e8f9;
    --cf-brand-ink: #a5f3fc;
    --cf-brand-soft: rgba(34, 184, 207, .14);
    --cf-brand-border: rgba(34, 184, 207, .40);
    --cf-on-brand: #082f49;
    --cf-dark-surface: #060b14;
    --cf-ink: #f1f5f9;
    --cf-ink-soft: #e2e8f0;
    --cf-text: #cbd5e1;
    --cf-muted: #94a3b8;
    --cf-surface: #0f172a;
    --cf-surface-muted: #0b1220;
    --cf-surface-gray: #111c2e;
    --cf-border: #1e293b;
    --cf-border-strong: #334155;
    background-color: #0b1220;
    color: var(--cf-text);
}
h1,
h2,
h3,
h4,
h5,
h6 {
    font-family: var(--cf-font-heading);
    letter-spacing: -.01em;
}
section[id],
footer[id] {
    scroll-margin-top: 6rem;
}
CSS;
    }
}
