<?php

namespace App\Support;

/**
 * Catálogo de bloques que se ofrecen dentro del editor GrapesJS.
 *
 * Cada bloque es HTML con estilos en línea para que se vea igual en el lienzo
 * del editor y en la página pública. Los bloques con data-cf-section se
 * sustituyen en el servidor por las secciones dinámicas del sitio.
 */
final class PageBlockLibrary
{
    /**
     * @return list<array{id: string, label: string, category: string, content: string}>
     */
    public static function all(): array
    {
        return [
            self::block('cf-hero', '🏠 Hero de bienvenida', 'Básicos', self::hero()),
            self::block('cf-hero-club', '🏀 Hero del club', 'Básicos', ClassicHomeWidget::hero()),
            self::block('cf-section', '📦 Sección', 'Básicos', self::section()),
            self::block('cf-heading-1', '🔠 Título grande (H1)', 'Básicos', self::headingOne()),
            self::block('cf-heading', '🔠 Título de sección (H2)', 'Básicos', self::heading()),
            self::block('cf-heading-3', '🔠 Subtítulo (H3)', 'Básicos', self::headingThree()),
            self::block('cf-paragraph', '📝 Párrafo', 'Básicos', self::paragraph()),
            self::block('cf-list', '📋 Lista con viñetas', 'Básicos', self::bullets()),
            self::block('cf-image', '🖼️ Imagen', 'Básicos', self::image()),
            self::block('cf-video', '🎬 Video', 'Básicos', VideoWidget::block()),
            self::block('cf-button', '🔘 Botón principal', 'Básicos', self::button()),
            self::block('cf-button-outline', '⚪ Botón secundario', 'Básicos', self::buttonOutline()),
            self::block('cf-link', '🔗 Enlace de texto', 'Básicos', self::link()),
            self::block('cf-badge', '🏷️ Etiqueta', 'Básicos', self::badge()),
            self::block('cf-note', '💡 Nota / aviso', 'Básicos', self::note()),

            self::block('cf-columns-2', '🧱 2 columnas', 'Estructura', self::columns(2)),
            self::block('cf-columns-3', '🧱 3 columnas', 'Estructura', self::columns(3)),
            self::block('cf-columns-4', '🧱 4 columnas', 'Estructura', self::columns(4)),
            self::block('cf-columns-1-2', '🧱 1/3 + 2/3', 'Estructura', self::asymmetricColumns(33, 67)),
            self::block('cf-columns-2-1', '🧱 2/3 + 1/3', 'Estructura', self::asymmetricColumns(67, 33)),
            self::block('cf-grid-2x2', '🔲 Cuadrícula 2×2', 'Estructura', self::grid()),
            self::block('cf-cards', '🗂️ Tarjetas', 'Estructura', self::cards()),
            self::block('cf-quienes-somos', '🏀 Quiénes somos', 'Estructura', ClassicHomeWidget::about()),
            self::block('cf-galeria-3', '🖼️ 3 tarjetas ampliables', 'Estructura', GalleryCardsWidget::block(3)),
            self::block('cf-galeria-4', '🖼️ 4 tarjetas ampliables', 'Estructura', GalleryCardsWidget::block(4)),
            self::block('cf-directiva', '👥 Nuestro equipo', 'Estructura', DirectivaWidget::block()),
            self::block('cf-stats', '📊 4 contadores', 'Estructura', StatsWidget::block()),
            self::block('cf-card', '🃏 Tarjeta', 'Estructura', self::card()),
            self::block('cf-section-gray', '🎨 Sección gris', 'Estructura', self::sectionTone('var(--cf-surface-gray, #f4f4f5)', 'var(--cf-ink, #18181b)')),
            self::block('cf-section-dark', '🌑 Sección oscura', 'Estructura', self::sectionTone('var(--cf-dark-surface, #0f172a)', 'var(--cf-on-dark, #ffffff)')),
            self::block('cf-table', '📊 Tabla', 'Estructura', self::table()),
            self::block('cf-steps', '🔢 Pasos numerados', 'Estructura', self::steps()),
            self::block('cf-divider', '➖ Separador', 'Estructura', self::divider()),
            self::block('cf-spacer', '↕️ Espaciador', 'Estructura', self::spacer()),
            self::block('cf-quote', '💬 Cita', 'Estructura', self::quote()),

            self::block('cf-carrusel', '🎠 Carrusel de imágenes', 'Secciones dinámicas', CarouselWidget::block()),
            self::block('cf-galeria', '🖼️ Galería de fotos', 'Secciones dinámicas', self::dynamic('galeria', 'Galería fotográfica')),
            self::block('cf-noticias', '📰 Noticias', 'Secciones dinámicas', self::dynamic('noticias', 'Noticias')),
            self::block('cf-horarios', '🕒 Horarios', 'Secciones dinámicas', self::dynamic('horarios', 'Horarios de entrenamiento')),
            self::block('cf-contacto', '✉️ Contacto completo', 'Secciones dinámicas', self::dynamic('contacto', 'Formulario de contacto')),
            self::block('cf-ventas', '🛒 Productos en venta', 'Secciones dinámicas', self::dynamic('ventas', 'Productos en venta')),
            self::block('cf-faq', '❓ Preguntas frecuentes', 'Secciones dinámicas', self::dynamic('faq', 'Preguntas frecuentes', [
                'columns' => '1',
                'open-first' => 'yes',
            ])),
            self::block('cf-marcas', '🏷️ Franja de marcas', 'Secciones dinámicas', self::dynamic('marcas', 'Marcas', [
                'speed' => '40',
                'grayscale' => 'yes',
                'pause' => 'yes',
                'invert' => 'yes',
            ])),
            self::block('cf-testimonios', '💬 Testimonios', 'Secciones dinámicas', self::dynamic('testimonios', 'Testimonios', [
                'layout' => 'slider',
                'autoplay' => 'yes',
                'interval' => '6000',
                'arrows' => 'yes',
                'dots' => 'yes',
            ])),

            self::block('cf-formulario', '📝 Formulario de contacto', 'Contacto y redes', FormWidget::block()),
            self::block('cf-whatsapp', '💬 Widget de WhatsApp', 'Contacto y redes', WhatsAppWidget::block()),
            self::block('cf-whatsapp-club', '💬 WhatsApp del club', 'Contacto y redes', WhatsAppWidget::classicBlock()),
            self::block('cf-social', '🔗 Redes sociales', 'Contacto y redes', SocialLinkLibrary::block()),
            self::block('cf-social-dark', '🌑 Redes sociales (oscuro)', 'Contacto y redes', SocialLinkLibrary::block(true)),

            self::block('cf-top-button', '⬆️ Botón volver arriba', 'Navegación', self::topButton()),
            self::block('cf-top-button-ball', '🏀 Botón volver arriba (balón)', 'Navegación', ClassicHomeWidget::topButton()),
            self::block('cf-footer-club', '🏁 Pie del club', 'Navegación', ClassicHomeWidget::footer()),
            self::block('cf-footer-simple', '🏁 Pie simple centrado', 'Navegación', self::footerSimple()),
            self::block('cf-footer-columns', '🏁 Pie con columnas', 'Navegación', self::footerColumns()),
            self::block('cf-footer-dark', '🌑 Pie oscuro', 'Navegación', self::footerDark()),
            self::block('cf-footer-minimal', '➖ Pie minimalista', 'Navegación', self::footerMinimal()),

            self::block('cf-anim-stagger', '✨ Aparición escalonada', 'Animaciones', self::animStagger()),
            self::block('cf-anim-split', '⬅️➡️ Columnas deslizantes', 'Animaciones', self::animSplit()),
            self::block('cf-anim-zoom', '🔍 Zoom de imagen', 'Animaciones', self::animZoom()),
            self::block('cf-anim-load', '⚡ Entrada al cargar', 'Animaciones', self::animLoad()),
            self::block('cf-anim-loop', '🌊 Movimiento continuo', 'Animaciones', self::animLoop()),
        ];
    }

    /**
     * @return list<string>
     */
    public static function labels(): array
    {
        return array_column(self::all(), 'label');
    }

    /**
     * Página de ejemplo compuesta con las mismas secciones del catálogo.
     */
    public static function demoPage(): string
    {
        $blocks = collect(self::all())->keyBy('id');

        $order = [
            'cf-hero',
            'cf-section',
            'cf-cards',
            'cf-carrusel',
            'cf-ventas',
            'cf-galeria',
            'cf-noticias',
            'cf-formulario',
            'cf-top-button',
            'cf-footer-columns',
        ];

        return collect($order)
            ->map(fn (string $id): string => $blocks->get($id)['content'] ?? '')
            ->filter()
            ->implode("\n\n");
    }

    /**
     * @return array{id: string, label: string, category: string, content: string}
     */
    private static function block(string $id, string $label, string $category, string $content): array
    {
        return compact('id', 'label', 'category', 'content');
    }

    private static function hero(): string
    {
        return <<<'HTML'
<section id="inicio" style="background-color:var(--cf-dark-surface, #0f172a);color:var(--cf-on-dark, #ffffff);padding:clamp(56px,8vw,96px) 24px;font-family:var(--cf-font-body, ui-sans-serif, system-ui, sans-serif);">
  <div style="max-width:1100px;margin:0 auto;display:flex;flex-wrap:wrap;align-items:center;gap:clamp(28px,5vw,48px);">
    <div style="flex:1 1 420px;">
      <p style="margin:0 0 16px;color:var(--cf-brand-light, #fb923c);font-size:14px;font-weight:700;letter-spacing:3px;text-transform:uppercase;">Bienvenidos</p>
      <h1 style="margin:0 0 20px;font-size:clamp(36px,8vw,56px);line-height:1.05;font-weight:800;">Tu título principal</h1>
      <p style="margin:0 0 28px;color:var(--cf-on-dark-muted, #d4d4d8);font-size:18px;line-height:1.7;">Escribe aquí una frase breve que explique de qué se trata esta página.</p>
      <a href="#contacto" style="display:inline-block;background-color:var(--cf-brand, #ff4c00);color:var(--cf-on-brand, #ffffff);padding:14px 28px;border-radius:999px;font-weight:700;text-decoration:none;">Comenzar</a>
    </div>
    <div style="flex:1 1 320px;text-align:center;">
      <img src="https://picsum.photos/seed/cielo-hero/1200/800" alt="Imagen principal" style="max-width:100%;height:auto;border-radius:24px;">
    </div>
  </div>
</section>
HTML;
    }

    private static function section(): string
    {
        return <<<'HTML'
<section style="padding:clamp(48px,7vw,80px) 24px;background-color:var(--cf-surface, #ffffff);font-family:var(--cf-font-body, ui-sans-serif, system-ui, sans-serif);">
  <div style="max-width:1100px;margin:0 auto;">
    <h2 style="margin:0 0 16px;font-size:clamp(28px,5.5vw,40px);line-height:1.1;font-weight:800;color:var(--cf-ink, #18181b);">Título de la sección</h2>
    <p style="margin:0;color:var(--cf-text, #52525b);font-size:17px;line-height:1.7;">Arrastra aquí tus textos, imágenes o columnas. Puedes cambiar el fondo y el espaciado desde el panel de estilos.</p>
  </div>
</section>
HTML;
    }

    private static function heading(): string
    {
        return <<<'HTML'
<h2 style="margin:0 0 16px;font-size:clamp(28px,5.5vw,40px);line-height:1.1;font-weight:800;color:var(--cf-ink, #18181b);font-family:var(--cf-font-heading, ui-sans-serif, system-ui, sans-serif);">Título de la sección</h2>
HTML;
    }

    private static function paragraph(): string
    {
        return <<<'HTML'
<p style="margin:0;color:var(--cf-text, #52525b);font-size:17px;line-height:1.7;font-family:var(--cf-font-body, ui-sans-serif, system-ui, sans-serif);">Escribe aquí un párrafo. Puedes cambiar el tamaño, el color y el espaciado desde el panel de estilos.</p>
HTML;
    }

    private static function headingOne(): string
    {
        return <<<'HTML'
<h1 style="margin:0 0 20px;color:var(--cf-ink, #18181b);font-size:clamp(36px,8vw,56px);line-height:1.05;font-weight:800;font-family:var(--cf-font-heading, ui-sans-serif, system-ui, sans-serif);">Título principal</h1>
HTML;
    }

    private static function headingThree(): string
    {
        return <<<'HTML'
<h3 style="margin:0 0 12px;color:var(--cf-ink, #18181b);font-size:24px;line-height:1.25;font-weight:700;font-family:var(--cf-font-heading, ui-sans-serif, system-ui, sans-serif);">Subtítulo de la sección</h3>
HTML;
    }

    private static function bullets(): string
    {
        return <<<'HTML'
<ul style="margin:0;padding-left:24px;color:var(--cf-ink-soft, #3f3f46);font-size:17px;line-height:1.8;font-family:var(--cf-font-body, ui-sans-serif, system-ui, sans-serif);">
  <li>Primer punto importante.</li>
  <li>Segundo punto importante.</li>
  <li>Tercer punto importante.</li>
</ul>
HTML;
    }

    private static function buttonOutline(): string
    {
        return <<<'HTML'
<a href="#contacto" style="display:inline-block;border:2px solid var(--cf-brand, #ff4c00);color:var(--cf-brand, #ff4c00);padding:12px 26px;border-radius:999px;font-weight:700;text-decoration:none;font-family:var(--cf-font-body, ui-sans-serif, system-ui, sans-serif);">Botón secundario</a>
HTML;
    }

    private static function link(): string
    {
        return <<<'HTML'
<a href="#contacto" style="color:var(--cf-brand, #ff4c00);font-weight:700;text-decoration:underline;font-family:var(--cf-font-body, ui-sans-serif, system-ui, sans-serif);">Texto del enlace</a>
HTML;
    }

    private static function badge(): string
    {
        return <<<'HTML'
<span style="display:inline-block;padding:6px 14px;border:1px solid var(--cf-brand-border, #fed7aa);border-radius:999px;background-color:var(--cf-brand-soft, #fff7ed);color:var(--cf-brand-ink, #c2410c);font-size:13px;font-weight:700;font-family:var(--cf-font-body, ui-sans-serif, system-ui, sans-serif);">Etiqueta</span>
HTML;
    }

    private static function note(): string
    {
        return <<<'HTML'
<div style="padding:16px 18px;border:1px solid var(--cf-info-border, #bfdbfe);border-left:4px solid var(--cf-info-accent, #3b82f6);border-radius:10px;background-color:var(--cf-info-bg, #eff6ff);color:var(--cf-info-ink, #1e3a8a);font-family:var(--cf-font-body, ui-sans-serif, system-ui, sans-serif);line-height:1.7;">
  <strong>Nota:</strong> escribe aquí un aviso importante para los lectores.
</div>
HTML;
    }

    private static function image(): string
    {
        return <<<'HTML'
<img src="https://picsum.photos/seed/cielo-imagen/800/600" alt="Descripción de la imagen" style="max-width:100%;height:auto;border-radius:20px;">
HTML;
    }

    private static function button(): string
    {
        return <<<'HTML'
<a href="#contacto" style="display:inline-block;background-color:var(--cf-brand, #ff4c00);color:var(--cf-on-brand, #ffffff);padding:14px 28px;border-radius:999px;font-weight:700;text-decoration:none;font-family:var(--cf-font-body, ui-sans-serif, system-ui, sans-serif);">Escribir un botón</a>
HTML;
    }

    private static function columns(int $count): string
    {
        $columns = '';

        for ($index = 1; $index <= $count; $index++) {
            $columns .= <<<HTML
    <div style="flex:1 1 260px;background-color:var(--cf-surface, #ffffff);border:1px solid var(--cf-border, #e4e4e7);border-radius:20px;padding:clamp(22px,4vw,32px);">
      <h3 style="margin:0 0 12px;font-size:22px;font-weight:700;color:var(--cf-ink, #18181b);">Columna {$index}</h3>
      <p style="margin:0;color:var(--cf-text, #52525b);line-height:1.7;">Escribe aquí el contenido de esta columna.</p>
    </div>
HTML;
        }

        return <<<HTML
<section style="padding:clamp(44px,6vw,64px) 24px;background-color:var(--cf-surface-gray, #f4f4f5);font-family:var(--cf-font-body, ui-sans-serif, system-ui, sans-serif);">
  <div style="max-width:1100px;margin:0 auto;display:flex;flex-wrap:wrap;gap:24px;">
{$columns}
  </div>
</section>
HTML;
    }

    private static function asymmetricColumns(int $left, int $right): string
    {
        $leftGrow = max(1, (int) round($left / 33));
        $rightGrow = max(1, (int) round($right / 33));

        return <<<HTML
<section style="padding:clamp(44px,6vw,64px) 24px;background-color:var(--cf-surface-muted, #f8fafc);font-family:var(--cf-font-body, ui-sans-serif, system-ui, sans-serif);">
  <div style="max-width:1100px;margin:0 auto;display:flex;flex-wrap:wrap;gap:24px;">
    <div style="flex:{$leftGrow} 1 280px;min-width:0;background-color:var(--cf-surface, #ffffff);border:1px solid var(--cf-border, #e4e4e7);border-radius:20px;padding:clamp(22px,4vw,32px);">
      <h3 style="margin:0 0 12px;color:var(--cf-ink, #18181b);font-size:22px;font-weight:700;">Columna principal</h3>
      <p style="margin:0;color:var(--cf-text, #52525b);line-height:1.7;">Escribe aquí el contenido de la columna más ancha.</p>
    </div>
    <div style="flex:{$rightGrow} 1 280px;min-width:0;background-color:var(--cf-surface, #ffffff);border:1px solid var(--cf-border, #e4e4e7);border-radius:20px;padding:clamp(22px,4vw,32px);">
      <h3 style="margin:0 0 12px;color:var(--cf-ink, #18181b);font-size:22px;font-weight:700;">Columna lateral</h3>
      <p style="margin:0;color:var(--cf-text, #52525b);line-height:1.7;">Escribe aquí el contenido de la columna más angosta.</p>
    </div>
  </div>
</section>
HTML;
    }

    private static function grid(): string
    {
        return <<<'HTML'
<section style="padding:clamp(44px,6vw,64px) 24px;background-color:var(--cf-surface-muted, #f8fafc);font-family:var(--cf-font-body, ui-sans-serif, system-ui, sans-serif);">
  <div style="max-width:1100px;margin:0 auto;display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:24px;">
    <div style="background-color:var(--cf-surface, #ffffff);border:1px solid var(--cf-border, #e4e4e7);border-radius:20px;padding:28px;">
      <h3 style="margin:0 0 10px;color:var(--cf-ink, #18181b);font-size:20px;font-weight:700;">Elemento 1</h3>
      <p style="margin:0;color:var(--cf-text, #52525b);line-height:1.7;">Texto del primer elemento.</p>
    </div>
    <div style="background-color:var(--cf-surface, #ffffff);border:1px solid var(--cf-border, #e4e4e7);border-radius:20px;padding:28px;">
      <h3 style="margin:0 0 10px;color:var(--cf-ink, #18181b);font-size:20px;font-weight:700;">Elemento 2</h3>
      <p style="margin:0;color:var(--cf-text, #52525b);line-height:1.7;">Texto del segundo elemento.</p>
    </div>
    <div style="background-color:var(--cf-surface, #ffffff);border:1px solid var(--cf-border, #e4e4e7);border-radius:20px;padding:28px;">
      <h3 style="margin:0 0 10px;color:var(--cf-ink, #18181b);font-size:20px;font-weight:700;">Elemento 3</h3>
      <p style="margin:0;color:var(--cf-text, #52525b);line-height:1.7;">Texto del tercer elemento.</p>
    </div>
    <div style="background-color:var(--cf-surface, #ffffff);border:1px solid var(--cf-border, #e4e4e7);border-radius:20px;padding:28px;">
      <h3 style="margin:0 0 10px;color:var(--cf-ink, #18181b);font-size:20px;font-weight:700;">Elemento 4</h3>
      <p style="margin:0;color:var(--cf-text, #52525b);line-height:1.7;">Texto del cuarto elemento.</p>
    </div>
  </div>
</section>
HTML;
    }

    private static function card(): string
    {
        return <<<'HTML'
<div style="max-width:380px;background-color:var(--cf-surface, #ffffff);border:1px solid var(--cf-border, #e4e4e7);border-radius:20px;padding:clamp(22px,4vw,32px);font-family:var(--cf-font-body, ui-sans-serif, system-ui, sans-serif);">
  <p style="margin:0 0 8px;color:var(--cf-brand, #ff4c00);font-size:12px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;">Etiqueta</p>
  <h3 style="margin:0 0 12px;color:var(--cf-ink, #18181b);font-size:22px;font-weight:700;">Título de la tarjeta</h3>
  <p style="margin:0 0 20px;color:var(--cf-text, #52525b);line-height:1.7;">Escribe aquí el contenido de la tarjeta.</p>
  <a href="#contacto" style="color:var(--cf-brand, #ff4c00);font-weight:700;text-decoration:none;">Ver más →</a>
</div>
HTML;
    }

    private static function sectionTone(string $background, string $color): string
    {
        return <<<HTML
<section style="padding:clamp(48px,7vw,80px) 24px;background-color:{$background};color:{$color};font-family:var(--cf-font-body, ui-sans-serif, system-ui, sans-serif);">
  <div style="max-width:1100px;margin:0 auto;">
    <h2 style="margin:0 0 16px;font-size:clamp(28px,5.5vw,40px);line-height:1.1;font-weight:800;">Título de la sección</h2>
    <p style="margin:0;font-size:17px;line-height:1.7;opacity:.8;">Arrastra aquí tus textos, imágenes o columnas. Puedes cambiar el fondo desde el panel de estilos.</p>
  </div>
</section>
HTML;
    }

    private static function table(): string
    {
        return <<<'HTML'
<section style="padding:clamp(44px,6vw,64px) 24px;background-color:var(--cf-surface, #ffffff);font-family:var(--cf-font-body, ui-sans-serif, system-ui, sans-serif);">
  <div style="max-width:1000px;margin:0 auto;overflow-x:auto;">
    <table style="width:100%;border-collapse:collapse;background-color:var(--cf-surface, #ffffff);border-radius:16px;overflow:hidden;font-size:14px;">
      <thead>
        <tr style="background-color:var(--cf-dark-surface, #0f172a);color:var(--cf-on-dark, #ffffff);">
          <th style="padding:14px 16px;text-align:left;">Columna 1</th>
          <th style="padding:14px 16px;text-align:left;">Columna 2</th>
          <th style="padding:14px 16px;text-align:left;">Columna 3</th>
        </tr>
      </thead>
      <tbody>
        <tr style="border-bottom:1px solid var(--cf-border, #e4e4e7);">
          <td style="padding:14px 16px;color:var(--cf-ink-soft, #3f3f46);">Dato 1</td>
          <td style="padding:14px 16px;color:var(--cf-ink-soft, #3f3f46);">Dato 2</td>
          <td style="padding:14px 16px;color:var(--cf-ink-soft, #3f3f46);">Dato 3</td>
        </tr>
        <tr style="border-bottom:1px solid var(--cf-border, #e4e4e7);">
          <td style="padding:14px 16px;color:var(--cf-ink-soft, #3f3f46);">Dato 4</td>
          <td style="padding:14px 16px;color:var(--cf-ink-soft, #3f3f46);">Dato 5</td>
          <td style="padding:14px 16px;color:var(--cf-ink-soft, #3f3f46);">Dato 6</td>
        </tr>
        <tr>
          <td style="padding:14px 16px;color:var(--cf-ink-soft, #3f3f46);">Dato 7</td>
          <td style="padding:14px 16px;color:var(--cf-ink-soft, #3f3f46);">Dato 8</td>
          <td style="padding:14px 16px;color:var(--cf-ink-soft, #3f3f46);">Dato 9</td>
        </tr>
      </tbody>
    </table>
  </div>
</section>
HTML;
    }

    private static function steps(): string
    {
        return <<<'HTML'
<section style="padding:clamp(44px,6vw,64px) 24px;background-color:var(--cf-surface-muted, #f8fafc);font-family:var(--cf-font-body, ui-sans-serif, system-ui, sans-serif);">
  <div style="max-width:1100px;margin:0 auto;display:flex;flex-wrap:wrap;gap:24px;">
    <div style="flex:1 1 260px;">
      <div style="width:44px;height:44px;border-radius:999px;background-color:var(--cf-brand, #ff4c00);color:var(--cf-on-brand, #ffffff);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:18px;margin-bottom:14px;">1</div>
      <h3 style="margin:0 0 8px;color:var(--cf-ink, #18181b);font-size:20px;font-weight:700;">Primer paso</h3>
      <p style="margin:0;color:var(--cf-text, #52525b);line-height:1.7;">Explica aquí el primer paso del proceso.</p>
    </div>
    <div style="flex:1 1 260px;">
      <div style="width:44px;height:44px;border-radius:999px;background-color:var(--cf-brand, #ff4c00);color:var(--cf-on-brand, #ffffff);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:18px;margin-bottom:14px;">2</div>
      <h3 style="margin:0 0 8px;color:var(--cf-ink, #18181b);font-size:20px;font-weight:700;">Segundo paso</h3>
      <p style="margin:0;color:var(--cf-text, #52525b);line-height:1.7;">Explica aquí el segundo paso del proceso.</p>
    </div>
    <div style="flex:1 1 260px;">
      <div style="width:44px;height:44px;border-radius:999px;background-color:var(--cf-brand, #ff4c00);color:var(--cf-on-brand, #ffffff);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:18px;margin-bottom:14px;">3</div>
      <h3 style="margin:0 0 8px;color:var(--cf-ink, #18181b);font-size:20px;font-weight:700;">Tercer paso</h3>
      <p style="margin:0;color:var(--cf-text, #52525b);line-height:1.7;">Explica aquí el tercer paso del proceso.</p>
    </div>
  </div>
</section>
HTML;
    }

    private static function cards(): string
    {
        return <<<'HTML'
<section style="padding:clamp(48px,7vw,80px) 24px;background-color:var(--cf-surface-muted, #f8fafc);font-family:var(--cf-font-body, ui-sans-serif, system-ui, sans-serif);">
  <div style="max-width:1100px;margin:0 auto;">
    <div style="text-align:center;margin-bottom:40px;">
      <p style="margin:0 0 12px;color:var(--cf-brand, #ff4c00);font-size:13px;font-weight:700;letter-spacing:2px;text-transform:uppercase;">Sobre nosotros</p>
      <h2 style="margin:0;font-size:clamp(28px,5.5vw,40px);line-height:1.1;font-weight:800;color:var(--cf-ink, #18181b);">Tres razones para elegirnos</h2>
    </div>
    <div style="display:flex;flex-wrap:wrap;gap:24px;">
      <div style="flex:1 1 260px;background-color:var(--cf-surface, #ffffff);border:1px solid var(--cf-border, #e4e4e7);border-radius:20px;padding:clamp(22px,4vw,32px);text-align:center;">
        <div style="font-size:40px;margin-bottom:12px;">✨</div>
        <h3 style="margin:0 0 12px;font-size:22px;font-weight:700;color:var(--cf-ink, #18181b);">Calidad</h3>
        <p style="margin:0;color:var(--cf-text, #52525b);line-height:1.7;">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
      </div>
      <div style="flex:1 1 260px;background-color:var(--cf-surface, #ffffff);border:1px solid var(--cf-border, #e4e4e7);border-radius:20px;padding:clamp(22px,4vw,32px);text-align:center;">
        <div style="font-size:40px;margin-bottom:12px;">🏆</div>
        <h3 style="margin:0 0 12px;font-size:22px;font-weight:700;color:var(--cf-ink, #18181b);">Comunidad</h3>
        <p style="margin:0;color:var(--cf-text, #52525b);line-height:1.7;">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
      </div>
      <div style="flex:1 1 260px;background-color:var(--cf-surface, #ffffff);border:1px solid var(--cf-border, #e4e4e7);border-radius:20px;padding:clamp(22px,4vw,32px);text-align:center;">
        <div style="font-size:40px;margin-bottom:12px;">🤝</div>
        <h3 style="margin:0 0 12px;font-size:22px;font-weight:700;color:var(--cf-ink, #18181b);">Valores</h3>
        <p style="margin:0;color:var(--cf-text, #52525b);line-height:1.7;">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
      </div>
    </div>
  </div>
</section>
HTML;
    }

    private static function divider(): string
    {
        return <<<'HTML'
<hr style="border:0;border-top:1px solid var(--cf-border, #e4e4e7);margin:32px 0;">
HTML;
    }

    private static function spacer(): string
    {
        return <<<'HTML'
<div style="height:48px;" aria-hidden="true"></div>
HTML;
    }

    private static function quote(): string
    {
        return <<<'HTML'
<blockquote style="margin:0;padding:8px 0 8px 24px;border-left:4px solid var(--cf-brand, #ff4c00);color:var(--cf-ink-soft, #3f3f46);font-size:20px;line-height:1.6;font-family:var(--cf-font-body, ui-sans-serif, system-ui, sans-serif);">
  «Un lema inspirador».
  <footer style="margin-top:12px;color:var(--cf-muted, #71717a);font-size:14px;font-style:normal;">— Tu marca</footer>
</blockquote>
HTML;
    }

    private static function animStagger(): string
    {
        return <<<'HTML'
<section style="padding:clamp(48px,7vw,80px) 24px;background-color:var(--cf-surface-muted, #f8fafc);font-family:var(--cf-font-body, ui-sans-serif, system-ui, sans-serif);">
  <div style="max-width:1100px;margin:0 auto;">
    <div style="text-align:center;margin-bottom:40px;">
      <p data-reveal="up" style="margin:0 0 12px;color:var(--cf-brand, #ff4c00);font-size:13px;font-weight:700;letter-spacing:2px;text-transform:uppercase;">Animación</p>
      <h2 data-reveal="up" data-reveal-delay="100" style="margin:0 0 12px;color:var(--cf-ink, #18181b);font-size:clamp(28px,5.5vw,40px);line-height:1.1;font-weight:800;">Aparece al hacer scroll</h2>
      <p data-reveal="up" data-reveal-delay="200" style="margin:0;color:var(--cf-text, #52525b);font-size:17px;line-height:1.7;">Las tarjetas entran en secuencia para guiar la mirada sin distraer.</p>
    </div>
    <div style="display:flex;flex-wrap:wrap;gap:24px;">
      <div data-reveal="up" style="flex:1 1 260px;background-color:var(--cf-surface, #ffffff);border:1px solid var(--cf-border, #e4e4e7);border-radius:20px;padding:28px;">
        <p style="margin:0 0 8px;color:var(--cf-brand, #ff4c00);font-size:12px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;">Primero</p>
        <h3 style="margin:0 0 10px;color:var(--cf-ink, #18181b);font-size:20px;font-weight:700;">Entra desde abajo</h3>
        <p style="margin:0;color:var(--cf-text, #52525b);line-height:1.7;">Texto de ejemplo para la primera tarjeta.</p>
      </div>
      <div data-reveal="up" data-reveal-delay="150" style="flex:1 1 260px;background-color:var(--cf-surface, #ffffff);border:1px solid var(--cf-border, #e4e4e7);border-radius:20px;padding:28px;">
        <p style="margin:0 0 8px;color:var(--cf-brand, #ff4c00);font-size:12px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;">Después</p>
        <h3 style="margin:0 0 10px;color:var(--cf-ink, #18181b);font-size:20px;font-weight:700;">Con un pequeño retardo</h3>
        <p style="margin:0;color:var(--cf-text, #52525b);line-height:1.7;">Texto de ejemplo para la segunda tarjeta.</p>
      </div>
      <div data-reveal="up" data-reveal-delay="300" style="flex:1 1 260px;background-color:var(--cf-surface, #ffffff);border:1px solid var(--cf-border, #e4e4e7);border-radius:20px;padding:28px;">
        <p style="margin:0 0 8px;color:var(--cf-brand, #ff4c00);font-size:12px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;">Al final</p>
        <h3 style="margin:0 0 10px;color:var(--cf-ink, #18181b);font-size:20px;font-weight:700;">Cierra la secuencia</h3>
        <p style="margin:0;color:var(--cf-text, #52525b);line-height:1.7;">Texto de ejemplo para la tercera tarjeta.</p>
      </div>
    </div>
  </div>
</section>
HTML;
    }

    private static function animSplit(): string
    {
        return <<<'HTML'
<section style="padding:clamp(48px,7vw,80px) 24px;background-color:var(--cf-surface, #ffffff);font-family:var(--cf-font-body, ui-sans-serif, system-ui, sans-serif);">
  <div style="max-width:1100px;margin:0 auto;display:flex;flex-wrap:wrap;align-items:center;gap:clamp(28px,5vw,48px);">
    <div data-reveal="left" style="flex:1 1 320px;">
      <img src="https://picsum.photos/seed/cielo-columna/1200/800" alt="Imagen de la sección" style="max-width:100%;height:auto;border-radius:24px;">
    </div>
    <div data-reveal="right" data-reveal-delay="120" style="flex:1 1 420px;">
      <p style="margin:0 0 12px;color:var(--cf-brand, #ff4c00);font-size:13px;font-weight:700;letter-spacing:2px;text-transform:uppercase;">Columnas</p>
      <h2 style="margin:0 0 16px;color:var(--cf-ink, #18181b);font-size:clamp(28px,5.5vw,40px);line-height:1.1;font-weight:800;">Cada lado entra por su costado</h2>
      <p style="margin:0 0 24px;color:var(--cf-text, #52525b);font-size:17px;line-height:1.7;">La imagen se desliza desde la izquierda y el texto desde la derecha. En móvil se apilan sin perder la animación.</p>
      <a href="#contacto" style="display:inline-block;background-color:var(--cf-brand, #ff4c00);color:var(--cf-on-brand, #ffffff);padding:14px 28px;border-radius:999px;font-weight:700;text-decoration:none;">Conócenos</a>
    </div>
  </div>
</section>
HTML;
    }

    private static function animZoom(): string
    {
        return <<<'HTML'
<section style="padding:clamp(48px,7vw,80px) 24px;background-color:var(--cf-surface-muted, #f8fafc);font-family:var(--cf-font-body, ui-sans-serif, system-ui, sans-serif);">
  <div style="max-width:1100px;margin:0 auto;display:flex;flex-wrap:wrap;align-items:center;gap:clamp(28px,5vw,48px);">
    <div data-reveal="zoom" style="flex:1 1 380px;">
      <img src="https://picsum.photos/seed/cielo-zoom/800/800" alt="Imagen principal" style="max-width:100%;height:auto;max-height:340px;">
    </div>
    <div data-reveal="up" data-reveal-delay="150" style="flex:1 1 380px;">
      <p style="margin:0 0 12px;color:var(--cf-brand, #ff4c00);font-size:13px;font-weight:700;letter-spacing:2px;text-transform:uppercase;">Zoom</p>
      <h2 style="margin:0 0 16px;color:var(--cf-ink, #18181b);font-size:clamp(28px,5.5vw,40px);line-height:1.1;font-weight:800;">Un acercamiento suave</h2>
      <p style="margin:0;color:var(--cf-text, #52525b);font-size:17px;line-height:1.7;">Ideal para escudos, fotografías y productos. La imagen crece ligeramente mientras el texto aparece desde abajo.</p>
    </div>
  </div>
</section>
HTML;
    }

    private static function animLoad(): string
    {
        return <<<'HTML'
<section style="padding:clamp(56px,8vw,96px) 24px;background-color:var(--cf-dark-surface, #0f172a);color:var(--cf-on-dark, #ffffff);text-align:center;font-family:var(--cf-font-body, ui-sans-serif, system-ui, sans-serif);">
  <div style="max-width:800px;margin:0 auto;">
    <p data-reveal="up" data-reveal-trigger="load" style="margin:0 0 14px;color:var(--cf-brand-light, #fb923c);font-size:13px;font-weight:700;letter-spacing:3px;text-transform:uppercase;">Bienvenida</p>
    <h2 data-reveal="up" data-reveal-trigger="load" data-reveal-delay="120" style="margin:0 0 16px;font-size:clamp(32px,7vw,46px);line-height:1.08;font-weight:800;">Entrada al cargar la página</h2>
    <p data-reveal="up" data-reveal-trigger="load" data-reveal-delay="240" style="margin:0 auto 28px;max-width:560px;color:var(--cf-on-dark-muted, #d4d4d8);font-size:17px;line-height:1.7;">Este bloque anima sus elementos apenas se abre la página, sin esperar al scroll. Úsalo en la primera pantalla.</p>
    <a data-reveal="up" data-reveal-trigger="load" data-reveal-delay="360" href="#contacto" style="display:inline-block;background-color:var(--cf-brand, #ff4c00);color:var(--cf-on-brand, #ffffff);padding:14px 28px;border-radius:999px;font-weight:700;text-decoration:none;">Escríbenos</a>
  </div>
</section>
HTML;
    }

    private static function animLoop(): string
    {
        return <<<'HTML'
<section style="padding:clamp(48px,7vw,80px) 24px;background-color:var(--cf-surface, #ffffff);font-family:var(--cf-font-body, ui-sans-serif, system-ui, sans-serif);">
  <div style="max-width:1100px;margin:0 auto;display:flex;flex-wrap:wrap;align-items:center;gap:clamp(28px,5vw,48px);">
    <div data-reveal="left" style="flex:1 1 320px;text-align:center;">
      <div data-cf-loop="float" style="display:inline-flex;align-items:center;justify-content:center;width:160px;height:160px;border-radius:999px;background-color:var(--cf-brand-soft, #fff7ed);border:1px solid var(--cf-brand-border, #fed7aa);font-size:64px;">✨</div>
      <p style="margin:14px 0 0;color:var(--cf-muted, #71717a);font-size:13px;">Flotación continua</p>
    </div>
    <div data-reveal="right" style="flex:1 1 420px;">
      <p style="margin:0 0 12px;color:var(--cf-brand, #ff4c00);font-size:13px;font-weight:700;letter-spacing:2px;text-transform:uppercase;">Movimiento</p>
      <h2 style="margin:0 0 16px;color:var(--cf-ink, #18181b);font-size:clamp(28px,5.5vw,40px);line-height:1.1;font-weight:800;">Detalles que respiran</h2>
      <p style="margin:0 0 18px;color:var(--cf-text, #52525b);font-size:17px;line-height:1.7;">Las animaciones continuas son sutiles y se pueden combinar con la entrada. Úsalas en iconos, logotipos o etiquetas, no en bloques enteros de texto.</p>
      <span data-cf-loop="pulse" style="display:inline-block;padding:6px 14px;border:1px solid var(--cf-brand-border, #fed7aa);border-radius:999px;background-color:var(--cf-brand-soft, #fff7ed);color:var(--cf-brand-ink, #c2410c);font-size:13px;font-weight:700;">Etiqueta con pulso</span>
    </div>
  </div>
</section>
HTML;
    }

    private static function topButton(): string
    {
        return <<<'HTML'
<button id="top-button" type="button" aria-label="Volver arriba" data-top-button="yes" data-top-enabled="yes"
        class="fixed bottom-6 left-1/2 z-50 -translate-x-1/2 cursor-pointer transition-opacity duration-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-400"
        style="position:fixed;bottom:1.5rem;left:50%;transform:translateX(-50%);z-index:50;background:transparent;border:0;padding:0;cursor:pointer;">
  <span class="top-button-bounce block" aria-hidden="true" style="display:block;transform-origin:50% 100%;">
    <span class="top-button-rotator block" style="display:block;filter:drop-shadow(0 10px 15px rgba(0,0,0,0.25));--rotacion:0deg;">
      <span style="display:flex;align-items:center;justify-content:center;width:56px;height:56px;border-radius:999px;background-color:var(--cf-brand, #ff4c00);color:var(--cf-on-brand, #ffffff);font-size:28px;line-height:1;">↑</span>
    </span>
  </span>
</button>
HTML;
    }

    private static function footerSimple(bool $global = false): string
    {
        $marca = $global ? ' data-global-footer' : '';
        $nombre = e(Branding::name());
        $lema = e(Branding::tagline());
        $ciudad = e(Branding::city());
        $correo = e(Branding::contactEmail());
        $instagram = e(Branding::instagramUrl());
        $anio = now()->year;

        return <<<HTML
<footer data-cf-footer="yes"{$marca} style="padding:clamp(40px,6vw,56px) 24px clamp(24px,4vw,32px);background-color:var(--cf-surface, #ffffff);border-top:1px solid var(--cf-border, #e4e4e7);font-family:var(--cf-font-body, ui-sans-serif, system-ui, sans-serif);">
  <div style="max-width:1100px;margin:0 auto;text-align:center;">
    <a href="#inicio" style="display:inline-block;margin-bottom:16px;text-decoration:none;">
      <img src="/branding/logo-light" data-brand-logo-light="/branding/logo-light" data-brand-logo-dark="/branding/logo-dark" alt="{$nombre}" style="height:56px;width:auto;">
    </a>
    <p style="margin:0 0 24px;color:var(--cf-text, #52525b);font-size:15px;line-height:1.7;">{$lema}</p>
    <nav style="display:flex;flex-wrap:wrap;justify-content:center;gap:8px 24px;margin:0 0 24px;">
      <a href="#inicio" style="color:var(--cf-ink-soft, #3f3f46);font-size:14px;font-weight:600;text-decoration:none;">Inicio</a>
      <a href="#quienes-somos" style="color:var(--cf-ink-soft, #3f3f46);font-size:14px;font-weight:600;text-decoration:none;">Nosotros</a>
      <a href="#categorias" style="color:var(--cf-ink-soft, #3f3f46);font-size:14px;font-weight:600;text-decoration:none;">Servicios</a>
      <a href="#noticias" style="color:var(--cf-ink-soft, #3f3f46);font-size:14px;font-weight:600;text-decoration:none;">Noticias</a>
      <a href="#contacto" style="color:var(--cf-ink-soft, #3f3f46);font-size:14px;font-weight:600;text-decoration:none;">Contacto</a>
    </nav>
    <div style="display:flex;flex-wrap:wrap;justify-content:center;gap:12px;margin:0 0 24px;">
      <a href="{$instagram}" target="_blank" rel="noopener" style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border:1px solid var(--cf-border-strong, #d4d4d8);border-radius:999px;color:var(--cf-text, #52525b);font-size:13px;font-weight:600;text-decoration:none;">📸 Instagram</a>
      <a href="mailto:{$correo}" style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border:1px solid var(--cf-border-strong, #d4d4d8);border-radius:999px;color:var(--cf-text, #52525b);font-size:13px;font-weight:600;text-decoration:none;">✉️ Correo</a>
    </div>
    <nav style="display:flex;flex-wrap:wrap;justify-content:center;gap:8px 20px;padding-top:20px;border-top:1px solid var(--cf-border, #e4e4e7);">
      <a href="/politica-de-privacidad" style="color:var(--cf-muted, #71717a);font-size:13px;text-decoration:none;">Política de Privacidad</a>
      <a href="/politica-de-cookies" style="color:var(--cf-muted, #71717a);font-size:13px;text-decoration:none;">Política de Cookies</a>
      <a href="/politica-de-datos" style="color:var(--cf-muted, #71717a);font-size:13px;text-decoration:none;">Seguridad y Datos</a>
    </nav>
    <p style="margin:16px 0 0;color:var(--cf-muted, #71717a);font-size:13px;">© {$anio} {$nombre} · {$ciudad}</p>
  </div>
</footer>
HTML;
    }

    public static function defaultFooter(): string
    {
        return self::footerSimple(global: true);
    }

    private static function footerColumns(): string
    {
        return <<<'HTML'
<footer data-cf-footer="yes" style="padding:clamp(40px,6vw,56px) 24px 24px;background-color:var(--cf-surface-muted, #f8fafc);border-top:1px solid var(--cf-border, #e4e4e7);font-family:var(--cf-font-body, ui-sans-serif, system-ui, sans-serif);">
  <div style="max-width:1100px;margin:0 auto;">
    <div style="display:flex;flex-wrap:wrap;gap:40px;">
      <div style="flex:1 1 260px;min-width:220px;">
        <a href="#inicio" style="display:inline-block;margin-bottom:14px;text-decoration:none;">
          <img src="/branding/logo-light" data-brand-logo-light="/branding/logo-light" data-brand-logo-dark="/branding/logo-dark" alt="Logotipo" style="height:52px;width:auto;">
        </a>
        <p style="margin:0 0 18px;color:var(--cf-text, #52525b);font-size:14px;line-height:1.7;">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
        <div style="display:flex;gap:10px;">
          <a href="https://www.instagram.com/tucuenta/" target="_blank" rel="noopener" style="display:inline-flex;align-items:center;justify-content:center;width:40px;height:40px;border:1px solid var(--cf-border-strong, #d4d4d8);border-radius:999px;color:var(--cf-text, #52525b);font-size:16px;text-decoration:none;">📸</a>
          <a href="mailto:contacto@tudominio.com" style="display:inline-flex;align-items:center;justify-content:center;width:40px;height:40px;border:1px solid var(--cf-border-strong, #d4d4d8);border-radius:999px;color:var(--cf-text, #52525b);font-size:16px;text-decoration:none;">✉️</a>
        </div>
      </div>
      <div style="flex:1 1 160px;min-width:140px;">
        <h3 style="margin:0 0 14px;color:var(--cf-ink, #18181b);font-size:12px;font-weight:800;letter-spacing:1.5px;text-transform:uppercase;">Nosotros</h3>
        <ul style="margin:0;padding:0;list-style:none;display:grid;gap:8px;">
          <li><a href="#inicio" style="color:var(--cf-text, #52525b);font-size:14px;text-decoration:none;">Inicio</a></li>
          <li><a href="#quienes-somos" style="color:var(--cf-text, #52525b);font-size:14px;text-decoration:none;">Quiénes somos</a></li>
          <li><a href="#categorias" style="color:var(--cf-text, #52525b);font-size:14px;text-decoration:none;">Servicios</a></li>
          <li><a href="#noticias" style="color:var(--cf-text, #52525b);font-size:14px;text-decoration:none;">Noticias</a></li>
        </ul>
      </div>
      <div style="flex:1 1 160px;min-width:140px;">
        <h3 style="margin:0 0 14px;color:var(--cf-ink, #18181b);font-size:12px;font-weight:800;letter-spacing:1.5px;text-transform:uppercase;">Recursos</h3>
        <ul style="margin:0;padding:0;list-style:none;display:grid;gap:8px;">
          <li><a href="#galeria" style="color:var(--cf-text, #52525b);font-size:14px;text-decoration:none;">Galería</a></li>
          <li><a href="#horario" style="color:var(--cf-text, #52525b);font-size:14px;text-decoration:none;">Horarios</a></li>
          <li><a href="#preguntas" style="color:var(--cf-text, #52525b);font-size:14px;text-decoration:none;">Preguntas frecuentes</a></li>
          <li><a href="#contacto" style="color:var(--cf-text, #52525b);font-size:14px;text-decoration:none;">Contacto</a></li>
        </ul>
      </div>
      <div style="flex:1 1 180px;min-width:160px;">
        <h3 style="margin:0 0 14px;color:var(--cf-ink, #18181b);font-size:12px;font-weight:800;letter-spacing:1.5px;text-transform:uppercase;">Contacto</h3>
        <ul style="margin:0;padding:0;list-style:none;display:grid;gap:8px;color:var(--cf-text, #52525b);font-size:14px;">
          <li>✉️ <a href="mailto:contacto@tudominio.com" style="color:var(--cf-text, #52525b);text-decoration:none;">contacto@tudominio.com</a></li>
          <li>📸 @tucuenta</li>
          <li>📍 Tu ciudad, País</li>
        </ul>
      </div>
    </div>
    <div style="margin-top:40px;padding-top:20px;border-top:1px solid var(--cf-border, #e4e4e7);display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:12px;">
      <p style="margin:0;color:var(--cf-muted, #71717a);font-size:13px;">© 2026 Tu marca · Tu ciudad, País</p>
      <nav style="display:flex;flex-wrap:wrap;gap:8px 18px;">
        <a href="/politica-de-privacidad" style="color:var(--cf-muted, #71717a);font-size:13px;text-decoration:none;">Privacidad</a>
        <a href="/politica-de-cookies" style="color:var(--cf-muted, #71717a);font-size:13px;text-decoration:none;">Cookies</a>
        <a href="/politica-de-datos" style="color:var(--cf-muted, #71717a);font-size:13px;text-decoration:none;">Datos</a>
      </nav>
    </div>
  </div>
</footer>
HTML;
    }

    private static function footerDark(): string
    {
        return <<<'HTML'
<footer data-cf-footer="yes" style="padding:clamp(40px,6vw,56px) 24px 24px;background-color:var(--cf-dark-surface, #0f172a);color:var(--cf-on-dark-muted, #d4d4d8);font-family:var(--cf-font-body, ui-sans-serif, system-ui, sans-serif);">
  <div style="max-width:1100px;margin:0 auto;">
    <div style="display:flex;flex-wrap:wrap;gap:40px;">
      <div style="flex:1 1 280px;min-width:220px;">
        <a href="#inicio" style="display:inline-block;margin-bottom:14px;text-decoration:none;">
          <img src="/branding/logo-light" data-brand-logo-light="/branding/logo-light" data-brand-logo-dark="/branding/logo-dark" alt="Logotipo" style="height:52px;width:auto;">
        </a>
        <p style="margin:0 0 20px;color:var(--cf-on-dark-muted, #d4d4d8);font-size:14px;line-height:1.7;">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
        <div style="display:flex;gap:10px;">
          <a href="https://www.instagram.com/tucuenta/" target="_blank" rel="noopener" style="display:inline-flex;align-items:center;justify-content:center;width:40px;height:40px;border:1px solid rgba(255,255,255,0.2);border-radius:999px;color:var(--cf-on-dark, #ffffff);font-size:16px;text-decoration:none;">📸</a>
          <a href="mailto:contacto@tudominio.com" style="display:inline-flex;align-items:center;justify-content:center;width:40px;height:40px;border:1px solid rgba(255,255,255,0.2);border-radius:999px;color:var(--cf-on-dark, #ffffff);font-size:16px;text-decoration:none;">✉️</a>
        </div>
      </div>
      <div style="flex:1 1 160px;min-width:140px;">
        <h3 style="margin:0 0 14px;color:var(--cf-brand-light, #fb923c);font-size:12px;font-weight:800;letter-spacing:1.5px;text-transform:uppercase;">Nosotros</h3>
        <ul style="margin:0;padding:0;list-style:none;display:grid;gap:8px;">
          <li><a href="#inicio" style="color:var(--cf-on-dark-muted, #d4d4d8);font-size:14px;text-decoration:none;">Inicio</a></li>
          <li><a href="#quienes-somos" style="color:var(--cf-on-dark-muted, #d4d4d8);font-size:14px;text-decoration:none;">Quiénes somos</a></li>
          <li><a href="#categorias" style="color:var(--cf-on-dark-muted, #d4d4d8);font-size:14px;text-decoration:none;">Servicios</a></li>
          <li><a href="#noticias" style="color:var(--cf-on-dark-muted, #d4d4d8);font-size:14px;text-decoration:none;">Noticias</a></li>
        </ul>
      </div>
      <div style="flex:1 1 160px;min-width:140px;">
        <h3 style="margin:0 0 14px;color:var(--cf-brand-light, #fb923c);font-size:12px;font-weight:800;letter-spacing:1.5px;text-transform:uppercase;">Recursos</h3>
        <ul style="margin:0;padding:0;list-style:none;display:grid;gap:8px;">
          <li><a href="#galeria" style="color:var(--cf-on-dark-muted, #d4d4d8);font-size:14px;text-decoration:none;">Galería</a></li>
          <li><a href="#horario" style="color:var(--cf-on-dark-muted, #d4d4d8);font-size:14px;text-decoration:none;">Horarios</a></li>
          <li><a href="#preguntas" style="color:var(--cf-on-dark-muted, #d4d4d8);font-size:14px;text-decoration:none;">Preguntas frecuentes</a></li>
          <li><a href="#contacto" style="color:var(--cf-on-dark-muted, #d4d4d8);font-size:14px;text-decoration:none;">Contacto</a></li>
        </ul>
      </div>
      <div style="flex:1 1 180px;min-width:160px;">
        <h3 style="margin:0 0 14px;color:var(--cf-brand-light, #fb923c);font-size:12px;font-weight:800;letter-spacing:1.5px;text-transform:uppercase;">Contacto</h3>
        <ul style="margin:0;padding:0;list-style:none;display:grid;gap:8px;color:var(--cf-on-dark-muted, #d4d4d8);font-size:14px;">
          <li>✉️ <a href="mailto:contacto@tudominio.com" style="color:var(--cf-on-dark-muted, #d4d4d8);text-decoration:none;">contacto@tudominio.com</a></li>
          <li>📸 @tucuenta</li>
          <li>📍 Tu ciudad, País</li>
        </ul>
      </div>
    </div>
    <div style="margin-top:40px;padding-top:20px;border-top:1px solid rgba(255,255,255,0.12);display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:12px;">
      <p style="margin:0;color:var(--cf-on-dark-subtle, #a1a1aa);font-size:13px;">© 2026 Tu marca · Tu ciudad, País</p>
      <nav style="display:flex;flex-wrap:wrap;gap:8px 18px;">
        <a href="/politica-de-privacidad" style="color:var(--cf-on-dark-subtle, #a1a1aa);font-size:13px;text-decoration:none;">Privacidad</a>
        <a href="/politica-de-cookies" style="color:var(--cf-on-dark-subtle, #a1a1aa);font-size:13px;text-decoration:none;">Cookies</a>
        <a href="/politica-de-datos" style="color:var(--cf-on-dark-subtle, #a1a1aa);font-size:13px;text-decoration:none;">Datos</a>
      </nav>
    </div>
  </div>
</footer>
HTML;
    }

    private static function footerMinimal(): string
    {
        return <<<'HTML'
<footer data-cf-footer="yes" style="padding:24px;background-color:var(--cf-surface, #ffffff);border-top:1px solid var(--cf-border, #e4e4e7);font-family:var(--cf-font-body, ui-sans-serif, system-ui, sans-serif);">
  <div style="max-width:1100px;margin:0 auto;display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:16px;">
    <a href="#inicio" style="display:flex;align-items:center;gap:10px;text-decoration:none;">
      <img src="/branding/logo-light" data-brand-logo-light="/branding/logo-light" data-brand-logo-dark="/branding/logo-dark" alt="Logotipo" style="height:36px;width:auto;">
      <span style="color:var(--cf-ink, #18181b);font-size:15px;font-weight:800;">Tu marca</span>
    </a>
    <nav style="display:flex;flex-wrap:wrap;gap:8px 18px;">
      <a href="#inicio" style="color:var(--cf-text, #52525b);font-size:13px;text-decoration:none;">Inicio</a>
      <a href="#noticias" style="color:var(--cf-text, #52525b);font-size:13px;text-decoration:none;">Noticias</a>
      <a href="#contacto" style="color:var(--cf-text, #52525b);font-size:13px;text-decoration:none;">Contacto</a>
      <a href="/politica-de-privacidad" style="color:var(--cf-text, #52525b);font-size:13px;text-decoration:none;">Privacidad</a>
    </nav>
    <p style="margin:0;color:var(--cf-muted, #71717a);font-size:12px;">© 2026 · Tu ciudad, País</p>
  </div>
</footer>
HTML;
    }

    /**
     * @param  array<string, string>  $options
     */
    private static function dynamic(string $section, string $label, array $options = []): string
    {
        $atributos = '';

        foreach ($options as $name => $value) {
            $atributos .= ' data-cf-option-'.e($name).'="'.e($value).'"';
        }

        return <<<HTML
<section data-cf-section="{$section}" id="{$section}"{$atributos} data-gjs-name="{$label}" style="padding:clamp(48px,7vw,80px) 24px;background-color:var(--cf-surface, #ffffff);border:2px dashed var(--cf-brand, #ff4c00);text-align:center;color:var(--cf-muted, #71717a);font-family:var(--cf-font-body, ui-sans-serif, system-ui, sans-serif);">
  {$label} (se genera automáticamente)
</section>
HTML;
    }
}
