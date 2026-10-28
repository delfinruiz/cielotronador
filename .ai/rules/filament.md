---
paths:
  - app/Providers/Filament/AdminPanelProvider.php
---

# Filament

## El grupo Configuración va al final del menú lateral
El orden de los grupos del panel se define en AdminPanelProvider con navigationGroups([...]). Mantener 'Configuración' al final, después de Contenido y Tienda. El orden de esta lista manda en Filament v5.
