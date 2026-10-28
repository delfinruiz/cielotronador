---
paths:
  - app/Support/PageBlockLibrary.php
  - app/Support/TemplateSanitizer.php
  - app/Support/ParallaxWidget.php
  - app/Support/DirectivaWidget.php
  - app/Support/ClassicHomeWidget.php
  - app/Support/VideoWidget.php
  - app/Support/TemplateRenderer.php
  - app/Support/Branding.php
---

# Support

## Ventas es una sección dinámica, no tarjetas estáticas
El bloque 'Productos en venta' inserta un placeholder data-cf-section="ventas" y TemplateRenderer lo reemplaza con resources/views/landing/sections/ventas.blade.php, que lee Producto/CategoriaProducto publicados. No reintroduzcas bloques de producto manuales: la tienda se administra en Filament (grupo Tienda). Si un producto no tiene imagen, SalesWidget usa un placeholder de picsum.

## Todo data-* nuevo debe declararse en el sanitizador
El sanitizador elimina cualquier atributo data-* que no esté en la lista blanca, incluso si se pega en 'Ver código'. Al crear un bloque con interacción (por ejemplo data-cf-lightbox), agrega el atributo con allowAttribute y verifica el HTML ya sanitizado en un test; si no, el JS no lo encuentra en la página pública.

## El parallax se activa por sección con data-cf-parallax
Usa data-cf-parallax="none|soft|strong" en <section> o en un contenedor. TemplateRenderer inserta la capa con ParallaxWidget::inject() después de resolver las secciones dinámicas; no guardes la capa en el HTML del bloque ni la dupliques. La capa va con z-index 0 y pointer-events:none para no bloquear clics; 'none' no aplica estilos. El editor la clona solo como vista previa en el lienzo. Si agregas atributos data-*, decláralos en TemplateSanitizer.

## Nuestro equipo es una tarjeta de presentación, no una galería
El bloque cf-directiva muestra foto, nombre, puesto y enlaces opcionales (data-directiva-link con data-directiva-link-off cuando están vacíos). La foto NO lleva data-cf-lightbox ni un botón: no debe abrir el visor. Los campos se editan en Settings → Miembro del equipo; el HTML guardado no incluye la capa de vista previa. Mantén los selectores CSS por etiqueta (h3/p) para que las tarjetas antiguas sigan viéndose bien.

## La portada clásica replicada se queda como borrador
ClassicHomeBuilderSeeder crea 'Portada clásica (borrador)' (slug portada-clasica-constructor) con is_active=false, published_at=null y published_html=null. No la publiques sin revisión: la portada pública debe seguir siendo la clásica. La composición vive en ClassicHomeWidget::page() y usa Hero del club, Quiénes somos, Nuestro equipo en variante clásica, Horarios, Contacto completo, Pie del club, Botón volver arriba (balón) y WhatsApp del club; las secciones dinámicas van envueltas en data-cf-parallax.

## El bloque de video nunca guarda un iframe
VideoWidget guarda la URL y los ajustes en data-video-* (url, provider, ratio, title, poster-url) y muestra una portada con botón. Al pulsar, resources/js/app.js inserta el iframe de YouTube (youtube-nocookie), Vimeo o Bunny Stream. El saneador elimina iframes, así que no metas el iframe en el HTML del bloque. El editor no ejecuta el JS público: muestra la portada y refresca la miniatura de YouTube desde actualizarVideoVisual.

## Las secciones dinámicas se configuran con data-cf-option-*
Los bloques dinámicos (FAQ, marcas, testimonios) llevan atributos data-cf-option-* en la etiqueta <section data-cf-section=...>. TemplateRenderer los extrae y los pasa a la vista como $options. Cualquier opción nueva debe declararse en TemplateSanitizer (allowAttribute exacto) y añadirse a los traits de la sección en el editor (categoriaSeccion). Las vistas usan $options con valores por defecto.

## La identidad del sitio vive en site_settings y solo la edita super_admin
Nombre, lema, ciudad, contacto, Instagram, logos y favicon se guardan en site_settings (clave branding) mediante App\Support\Branding. La página Configuración del sistema solo es accesible con el rol super_admin, no por permisos de contenido. Los logos se sirven desde la ruta estable /branding/{asset} para que el HTML ya guardado de las plantillas muestre el archivo actual; no reemplazar por asset('img/logo.png') en código nuevo.
