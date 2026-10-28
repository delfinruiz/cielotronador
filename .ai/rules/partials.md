---
paths:
  - resources/views/landing/partials/cookie-banner.blade.php
---

# Partials

## El aviso de cookies es global y no se puede quitar
El aviso se renderiza desde landing.custom, landing y layouts.policy, fuera del contenido editable, así que ninguna plantilla puede borrarlo desde el constructor. Sus textos viven en site_settings (clave cookie_banner) y se editan en Configuración → Aviso de cookies. No lo conviertas en bloque de la biblioteca ni añadas un interruptor para ocultarlo; CookieBannerTest exige que aparezca en todas las plantillas y en las páginas legales.
