<?php

namespace App\Support;

use App\Models\Producto;

/**
 * Tarjetas de venta del constructor.
 *
 * El contenido ya no se escribe a mano en el editor: cada tarjeta se construye
 * a partir de un producto publicado en el módulo "Tienda" del panel. La
 * sección dinámica `data-cf-section="ventas"` reemplaza el marcador por esta
 * cuadrícula en la página pública.
 */
final class SalesWidget
{
    /**
     * CSS de la cuadrícula, los distintivos y los filtros por categoría.
     * Se inyecta en el lienzo del editor y en la página pública.
     */
    public static function css(): string
    {
        return <<<'CSS'
[data-product-grid] { grid-template-columns: repeat(3, minmax(0, 1fr)); }
@media (max-width: 900px) { [data-product-grid] { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
@media (max-width: 560px) { [data-product-grid] { grid-template-columns: 1fr; } }

[data-cf-product] [data-product-badge][data-product-badge-off],
[data-cf-product] [data-product-currency][data-product-currency-off],
[data-cf-product] [data-product-price-note][data-product-price-note-off],
[data-cf-product] [data-product-old-price][data-product-old-price-off] { display: none; }

[data-cf-product] [data-product-cta] { transition: filter .18s ease, opacity .18s ease; }
[data-cf-product] [data-product-cta]:hover { filter: brightness(1.06); }
[data-cf-product] [data-product-cta][data-product-cta-empty] { opacity: .55; cursor: not-allowed; pointer-events: none; }

[data-cf-sales] [data-sales-filters] { display: flex; flex-wrap: wrap; justify-content: center; gap: 8px; margin: 0 0 32px; padding: 0; list-style: none; }
[data-cf-sales] [data-sales-filter] { cursor: pointer; border: 1px solid var(--cf-border, #e4e4e7); border-radius: 999px; background-color: var(--cf-surface, #ffffff); color: var(--cf-text, #52525b); padding: 9px 18px; font: inherit; font-size: 13px; font-weight: 700; transition: background-color .18s ease, color .18s ease, border-color .18s ease; }
[data-cf-sales] [data-sales-filter]:hover { border-color: var(--cf-brand, #ff4c00); color: var(--cf-brand-ink, #c2410c); }
[data-cf-sales] [data-sales-filter][aria-pressed="true"] { border-color: var(--cf-brand, #ff4c00); background-color: var(--cf-brand, #ff4c00); color: var(--cf-on-dark, #ffffff); }
[data-cf-sales] [data-sales-filter]:focus-visible { outline: 2px solid var(--cf-brand, #ff4c00); outline-offset: 2px; }
[data-cf-sales] [data-cf-product][hidden] { display: none; }
CSS;
    }

    /**
     * Tarjeta de un producto del módulo de tienda.
     *
     * @param  'vertical'|'horizontal'  $layout
     */
    public static function cardFor(Producto $producto, string $layout = 'vertical'): string
    {
        $etiqueta = trim((string) $producto->etiqueta);
        $nota = trim((string) $producto->nota_precio);
        $anterior = $producto->precioAnteriorFormateado();

        return self::build($layout, [
            'id' => (string) $producto->getKey(),
            'nombre' => (string) $producto->nombre,
            'descripcion' => (string) $producto->descripcion,
            'precio' => $producto->precioFormateado(),
            'moneda' => (string) $producto->moneda,
            'nota' => $nota,
            'anterior' => (string) ($anterior ?? ''),
            'etiqueta' => $etiqueta,
            'visible' => $etiqueta !== '' ? 'yes' : 'no',
            'boton' => trim((string) $producto->boton_texto) ?: 'Comprar ahora',
            'enlace' => (string) $producto->enlace_pago,
            'imagen' => $producto->imagenUrl(),
            'alt' => (string) $producto->nombre,
            'categoria' => (string) ($producto->categoria?->nombre ?? ''),
            'categoria_slug' => (string) ($producto->categoria?->slug ?? ''),
        ]);
    }

    /**
     * @param  'vertical'|'horizontal'  $layout
     * @param  array{id: string, nombre: string, descripcion: string, precio: string, moneda: string, nota: string, anterior: string, etiqueta: string, visible: string, boton: string, enlace: string, imagen: string, alt: string, categoria: string, categoria_slug: string}  $data
     */
    private static function build(string $layout, array $data): string
    {
        $id = e($data['id']);
        $nombre = e($data['nombre']);
        $descripcion = e($data['descripcion']);
        $precio = e($data['precio']);
        $moneda = e($data['moneda']);
        $nota = e($data['nota']);
        $anterior = e($data['anterior']);
        $etiqueta = e($data['etiqueta']);
        $visible = e($data['visible']);
        $boton = e($data['boton']);
        $enlace = e($data['enlace']);
        $imagen = e($data['imagen']);
        $alt = e($data['alt']);
        $categoria = e($data['categoria']);
        $categoriaSlug = e($data['categoria_slug']);

        $layoutAttr = $layout === 'horizontal' ? ' data-product-layout="horizontal"' : '';
        $categoriaAttr = $categoriaSlug !== '' ? ' data-product-category="'.$categoriaSlug.'"' : '';

        $rootStyle = 'overflow:hidden;border:1px solid var(--cf-border, #e4e4e7);border-radius:20px;background-color:var(--cf-surface, #ffffff);font-family:var(--cf-font-body, ui-sans-serif, system-ui, sans-serif);box-shadow:0 10px 25px -12px rgba(0,0,0,0.15);';

        if ($layout === 'horizontal') {
            $rootStyle .= 'display:flex;flex-wrap:wrap;align-items:center;gap:0;max-width:820px;';
            $mediaStyle = 'flex:1 1 240px;aspect-ratio:4/3;';
            $bodyStyle = 'flex:1 1 320px;';
        } else {
            $rootStyle .= 'max-width:380px;';
            $mediaStyle = 'aspect-ratio:4/3;';
            $bodyStyle = '';
        }

        $badgeOff = ($etiqueta === '' || $visible === 'no') ? ' data-product-badge-off="yes"' : '';
        $oldPriceOff = $anterior === '' ? ' data-product-old-price-off="yes"' : '';
        $noteOff = $nota === '' ? ' data-product-price-note-off="yes"' : '';
        $ctaEmpty = $enlace === '' ? ' data-product-cta-empty="yes" aria-disabled="true" tabindex="-1"' : '';
        $href = $enlace !== '' ? $enlace : '#';
        $categoriaHtml = $categoria !== ''
            ? '<p data-product-category-text="yes" style="margin:0 0 6px;color:var(--cf-brand-ink, #c2410c);font-size:11px;font-weight:800;letter-spacing:0.12em;text-transform:uppercase;">'.$categoria.'</p>'
            : '';

        $badge = <<<HTML
    <span data-product-badge="yes" data-product-badge-label="yes"{$badgeOff} style="position:absolute;top:12px;left:12px;z-index:2;display:inline-block;padding:4px 12px;border-radius:999px;background-color:var(--cf-brand, #ff4c00);color:var(--cf-on-brand, #ffffff);font-size:12px;font-weight:700;">{$etiqueta}</span>
HTML;

        return <<<HTML
<article data-cf-product="yes" data-product-id="{$id}" data-gjs-name="Producto en venta"{$layoutAttr}{$categoriaAttr}
     data-reveal="up" data-reveal-trigger="scroll" data-reveal-delay="0"
     data-product-name="{$nombre}"
     data-product-description="{$descripcion}"
     data-product-price="{$precio}"
     data-product-currency="{$moneda}"
     data-product-price-note="{$nota}"
     data-product-old-price="{$anterior}"
     data-product-badge-text="{$etiqueta}"
     data-product-badge-enabled="{$visible}"
     data-product-cta-text="{$boton}"
     data-product-url="{$enlace}"
     data-product-image-url="{$imagen}"
     data-product-image-alt="{$alt}"
     style="{$rootStyle}">
  <div style="position:relative;{$mediaStyle}overflow:hidden;background-color:var(--cf-surface-muted, #f8fafc);">
{$badge}    <img data-product-image="yes" src="{$imagen}" alt="{$alt}" loading="lazy" style="width:100%;height:100%;object-fit:cover;display:block;">
  </div>
  <div style="padding:24px;{$bodyStyle}">
    {$categoriaHtml}
    <h3 data-product-name-text="yes" style="margin:0 0 8px;color:var(--cf-ink, #18181b);font-size:20px;font-weight:700;line-height:1.3;">{$nombre}</h3>
    <p data-product-description-text="yes" style="margin:0 0 16px;color:var(--cf-text, #52525b);font-size:14px;line-height:1.6;">{$descripcion}</p>
    <div style="display:flex;align-items:baseline;flex-wrap:wrap;gap:8px;margin:0 0 4px;">
      <span data-product-old-price="yes"{$oldPriceOff} style="color:var(--cf-muted, #71717a);font-size:14px;"><s data-product-old-price-text="yes">{$anterior}</s></span>
      <strong data-product-price-text="yes" style="color:var(--cf-brand-ink, #c2410c);font-size:26px;line-height:1;">{$precio}</strong>
      <span data-product-currency="yes" data-product-currency-text="yes" style="color:var(--cf-muted, #71717a);font-size:13px;font-weight:600;">{$moneda}</span>
    </div>
    <p data-product-price-note="yes"{$noteOff} style="margin:0 0 20px;color:var(--cf-muted, #71717a);font-size:13px;">{$nota}</p>
    <a data-product-cta="yes" href="{$href}" target="_blank" rel="noopener noreferrer"{$ctaEmpty} style="display:flex;align-items:center;justify-content:center;background-color:var(--cf-brand, #ff4c00);color:var(--cf-on-brand, #ffffff);padding:14px 24px;border-radius:999px;font-weight:700;font-size:15px;text-decoration:none;"><span data-product-cta-text="yes">{$boton}</span></a>
  </div>
</article>
HTML;
    }
}
