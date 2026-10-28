# Club CieloTronador

Sitio web y panel de administración del **Club de Básquetbol Cielo Tronador** (Talcahuano, Chile). Incluye una landing pública orientada a familias y jugadores, y un panel administrativo con gestión de noticias, galería, usuarios y permisos.

## Características

**Sitio público** (`/`)

- Landing con secciones: hero, quiénes somos, noticias, actividades, horarios, galería y contacto.
- Noticias y galería paginadas, con modales de detalle y carga parcial de la galería vía cabecera `X-Partial: galeria`.
- Formulario de contacto que envía un correo al club (`ContactMessage`).
- Modo claro/oscuro, banner de cookies y barra de progreso de scroll.
- Páginas de Política de Privacidad, Cookies y Datos.

**Panel de administración** (`/admin`)

- Filament v5 con tema propio (color primario `#FF4C00`), autenticación personalizada y recuperación de contraseña. **MFA (TOTP) implementado pero desactivado**; se activa con `SECURITY_MFA_REQUIRED=true`.
- Recursos: **Páginas**, **Noticias**, **Galería** (subida de imágenes), **Usuarios** y **Roles/Permisos** (Filament Shield + Spatie Permission).
- **Constructor de portada**: plantillas GrapesJS con contenido libre, menú por plantilla, vista previa firmada, activación inmediata y bloques opcionales para noticias, galería, carrusel, ventas, contacto, WhatsApp y redes sociales. Al crear una plantilla GrapesJS se abre el constructor automáticamente; la plantilla **Clásica** se puede duplicar como GrapesJS desde la pestaña Contenido. El login del sitio siempre queda fijo en el menú.
- **Guía del constructor**: página de ayuda en el panel y botón **? Ayuda** dentro del editor, con el significado de cada botón de GrapesJS, los bloques disponibles, cómo enlazar el menú, subir imágenes y publicar la plantilla.
- **Paleta ampliada**: bloques básicos (títulos H1/H2/H3, párrafos, listas, imágenes, botones, enlaces, etiquetas y notas), estructuras (2/3/4 columnas, combinaciones 1/3 + 2/3, cuadrícula 2×2, tarjetas, secciones grises/oscuras, tablas y pasos), secciones dinámicas (carrusel, galería y noticias), ventas y un formulario de contacto configurable. Los bloques se agrupan en siete categorías para que el panel no tenga un grupo por componente.
- **Animaciones profesionales**: cada componente tiene ajustes de entrada (abajo, arriba, izquierda, derecha, zoom, desvanecer, giro 3D y desenfoque), disparo al hacer scroll o al cargar, retardo escalonado y movimientos continuos (flotación, pulso y latido). Incluye una categoría de bloques ya animados y respeta `prefers-reduced-motion`.
- **Modo oscuro en los bloques**: los bloques guardados usan variables de color (`--cf-surface`, `--cf-ink`, `--cf-border`, etc.) definidas para el tema claro y el oscuro. Así el contenido del constructor cambia junto con el interruptor del menú, sin volver a editarlo. Los iconos de redes sociales se invierten automáticamente sobre fondos oscuros.
- **Botón volver arriba configurable**: el bloque **Botón volver arriba** de la categoría **Navegación** permite activarlo o desactivarlo desde sus ajustes y reemplazar el icono como una imagen normal. Si la plantilla no incluye el bloque, se mantiene el botón clásico por defecto.
- **Pies de página editables**: la pestaña de enlaces del pie se retiró del formulario. Ahora hay cuatro bloques prediseñados en la categoría **Navegación** (simple centrado, con columnas, oscuro y minimalista) para arrastrar, editar textos y cambiar enlaces. Si la plantilla no incluye un pie, se conserva el pie clásico.
- **Redes sociales editables**: bloques claro y oscuro con los iconos de LinkedIn, YouTube, X, Instagram y Facebook. Desde los ajustes del componente se activan o desactivan redes y se edita el enlace de cada una.
- **Widget de WhatsApp configurable**: bloque en **Contacto y redes** con un panel de chat flotante. Desde sus ajustes se cambian el número telefónico (con código de país), el título del chat y el mensaje. En las plantillas GrapesJS el widget solo aparece si agregas el bloque; la portada clásica y las páginas legales conservan el widget original.
- **Centro de seguridad**: estado de MFA, HTTPS, depuración, respaldos recientes y últimos eventos de auditoría.
- Roles sembrados: `super_admin` (todos los permisos) y `panel_user`.

> **Mantenimiento del editor:** el bundle publicado `public/vendor/grapesjs/editor.js` lleva un parche mínimo que expone la instancia en `window.__cielotronadorGjs`; es lo que permite añadir los ajustes de animación sin bifurcar el paquete. Si se vuelve a ejecutar `vendor:publish --tag=grapesjs-assets`, hay que reaplicar el parche (buscar `s4(e,r.plaatshouders),l4(e,r)}` y añadir `window.__cielotronadorGjs=e,` antes de `l4`). También se traducen los mensajes de estado del guardado (`Bewaren...` → `Guardando...`, `Bewaard` → `Guardado`, `Niet bewaard` → `Sin guardar`, `Niet bewaard: ...` → `No guardado: ...`, y el aviso de marcadores desconocidos).
>
> **Plantillas antiguas y modo oscuro:** las plantillas guardadas antes de usar variables de color conservan colores fijos. Para convertirlas, ejecuta `php artisan landing:theme-tokens` (añade `--dry-run` para revisar primero). El comando actualiza el HTML, el CSS y los estilos del proyecto de GrapesJS; es idempotente.
>
> **Lienzo del editor:** los assets de GrapesJS se sirven con un parámetro de versión basado en su fecha de modificación y la página del editor no se cachea. Además, los tokens incluyen su valor claro como respaldo (`var(--cf-surface, #ffffff)`), de modo que el lienzo sigue viéndose aunque el CSS de tokens no llegue a cargarse. Si tras actualizar el editor ves el lienzo sin estilos, recarga con **Ctrl + F5**.

## Stack

- Laravel 13 / PHP 8.3+ (entorno de desarrollo PHP 8.5)
- Filament 5.7 + Livewire 4
- Filament Shield + Spatie Laravel Permission
- Tailwind CSS 4 (CSS-first, sin `tailwind.config.js`)
- Vite 8 + `laravel-vite-plugin`
- SQLite (desarrollo y tests)
- PHPUnit 12

## Requisitos

- PHP 8.3 o superior con extensiones habituales de Laravel
- Composer
- Node.js + npm

## Instalación

```bash
composer run setup
```

Este comando instala dependencias PHP, copia `.env`, genera la clave, ejecuta migraciones, instala dependencias JS y compila assets.

Después de configurar el correo en `.env`, puedes sembrar datos de ejemplo (noticias, galería desde `public/img/galeria` y roles de Shield):

```bash
php artisan db:seed
```

## Desarrollo

```bash
composer run dev
```

Levanta en un solo proceso el servidor PHP, la cola, los logs (Pail) y Vite. El sitio queda en `http://localhost:8000` y el panel en `http://localhost:8000/admin`.

Compilar assets manualmente:

```bash
npm run build
```

> Nota: `.npmrc` define `ignore-scripts=true`, por lo que `npm install` no ejecuta scripts de build/postinstall.

## Variables de entorno

Las principales se documentan en `.env.example`. Destacan:

| Variable | Descripción |
| --- | --- |
| `APP_NAME` | Nombre de la aplicación (`CieloTronador`). |
| `DB_CONNECTION` | Por defecto `sqlite` (`database/database.sqlite`). |
| `CONTACT_TO_EMAIL` | Destinatario del formulario de contacto. |
| `MAIL_*` | Configuración SMTP para el envío de correos. |
| `SESSION_ENCRYPT` | Cifrado de sesión; activar en producción. |
| `SESSION_SECURE_COOKIE` | Cookie solo por HTTPS; activar en producción. |
| `LOG_CHANNEL` / `LOG_LEVEL` | En producción usar `daily` y `warning`. |

## Pruebas y formato

```bash
php artisan test --compact                                   # suite completa
php artisan test --compact tests/Feature/NoticiaTest.php     # un archivo
php artisan test --compact --filter=testName                 # un test
composer audit                                               # vulnerabilidades PHP
npm audit --audit-level=high                                 # vulnerabilidades JS
vendor/bin/pint --dirty --format agent                       # formato de código PHP
```

## Seguridad y respaldos

```bash
php artisan backup:run              # crea storage/app/backups/backup-*.zip
php artisan backup:run --keep=30    # conserva los últimos 30
php artisan schedule:list           # verifica el respaldo diario de las 02:00
```

El procedimiento completo (control de acceso, respaldos, actualizaciones, gestión de vulnerabilidades y respuesta ante incidentes) está en [`docs/seguridad.md`](docs/seguridad.md).

## Estructura

```
app/
├── Filament/          # Panel admin: Resources (Galerías, Noticias, Users) y Pages
├── Http/              # Controladores (Landing, Contact, Policy), FormRequest y Mail
├── Models/            # Noticia, Galeria, User
└── Policies/          # Autorización por recurso
database/
├── migrations/        # noticias, galerias, permisos, tablas base
└── seeders/           # DatabaseSeeder, NoticiaSeeder, GaleriaSeeder, ShieldSeeder
resources/
├── css/               # Tailwind (app.css y tema Filament)
├── js/
└── views/             # landing, políticas, mail, filament y parciales
routes/web.php         # Landing, contacto y páginas legales
```

Las imágenes se almacenan en `public/img/galeria` (disco `galeria`) y `public/img/noticias` (disco `noticias`).
