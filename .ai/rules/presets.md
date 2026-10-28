---
paths:
  - 'app/Support/Presets/**'
---

# Presets

## Plantillas del constructor: cuerpo con bloques y tema en settings
Las plantillas viven en app/Support/Presets y NO se guardan como PageTemplate: la galería (admin/plantillas) las lista y 'Usar' crea un borrador con CreatePageFromPreset. El cuerpo se compone de los bloques reales de PageBlockLibrary (nada de HTML inventado), el menú superior siempre es el del sitio y la plantilla solo aporta cuerpo y pie. El color y las reglas visuales van en settings.theme_css y las fuentes en settings.fonts: NUNCA en la columna css, porque GrapesJS la reescribe al guardar y borraría el tema. El render lo inyecta con PageTheme::css() (público con scopeCss y editor vía canvasCss). Al guardar el formulario de SEO, EditPageTemplate fusiona settings para no perder theme_css/fonts.

## Cada plantilla debe traer variante clara y oscura
El tema de una plantilla se guarda en settings.theme_css y debe incluir dos bloques: `body { ...tokens claros... }` y `.dark body { ...tokens oscuros... }`. El público los recibe con scopeCss, que traduce `body` a `#cf-template` y `.dark body` o `html.dark body` a `html.dark #cf-template` (el modo oscuro vive en <html>, fuera del contenedor). El editor aplica el mismo CSS en el lienzo y su botón Oscuro/Claro pone `dark` en el <html> del iframe. No uses selectores `.dark` sin ese formato o quedarán como `#cf-template .dark ...` y nunca aplicarán.
