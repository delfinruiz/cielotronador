---
paths:
  - app/Models/SiteSetting.php
---

# Models

## Configuración global en site_settings
Los ajustes globales se guardan como JSON en site_settings y se leen con SiteSetting::get/put. put y los eventos saved/deleted invalidan la caché site-setting:{key}. No guardes aquí datos por página: eso va en PageTemplate::settings.
