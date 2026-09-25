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

- Filament v5 con tema propio (color primario `#FF4C00`), autenticación personalizada y recuperación de contraseña.
- Recursos: **Noticias**, **Galería** (subida de imágenes), **Usuarios** y **Roles/Permisos** (Filament Shield + Spatie Permission).
- Roles sembrados: `super_admin` (todos los permisos) y `panel_user`.

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

## Pruebas y formato

```bash
php artisan test --compact                                   # suite completa
php artisan test --compact tests/Feature/NoticiaTest.php     # un archivo
php artisan test --compact --filter=testName                 # un test
vendor/bin/pint --dirty --format agent                       # formato de código PHP
```

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
