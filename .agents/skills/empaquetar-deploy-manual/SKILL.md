---
name: empaquetar-deploy-manual
description: "Use when the user asks to prepare the zip of the files uploaded manually to the shared hosting, to deploy to cPanel without SSH/terminal, or mentions 'subir manualmente', 'paquete', 'zip', 'hosting', 'vendor', 'public/build'. Makes an automatic git commit first and names the zip after that commit."
---

# Empaquetar despliegue manual (hosting compartido)

Este proyecto se publica en hosting compartido sin terminal. `vendor/` y
`public/build/` están en `.gitignore`, así que NO llegan con `git pull` y deben
subirse a mano. Este skill empaqueta esos archivos en un zip nombrado según el
commit.

## Archivos que van en el zip (subida manual)

- `vendor/` — dependencias PHP de producción (sin dev, sin `.git` internos)
- `public/build/` — assets Vite compilados
- `bootstrap/cache/packages.php` y `bootstrap/cache/services.php`

NO incluir `database/database.sqlite`: sobrescribiría noticias, galería y
usuarios de producción. Incluirla solo si el usuario lo pide explícitamente.

## Procedimiento

1. **Revisar cambios.** `git status` y `git diff` para entender qué cambió.
   No incluir secretos (`.env` está en `.gitignore`).

2. **Verificar antes de empaquetar** (si aplica):
   - `vendor/bin/pint --dirty --format agent`
   - `php artisan test --compact`
   - `node --check resources/js/app.js` si se tocó JS

   No empaquetar si algo falla.

3. **Commit automático** (sirve para nombrar el zip):
   - `git add -A`
   - `git commit -m "<mensaje corto, imperativo, en minúsculas>"`, generando el
     mensaje a partir del diff (p. ej. `formulario contacto ux`).
   - Si no hay cambios, continuar usando `HEAD`.
   - Guardar el nombre del commit:
     ```bash
     SHORT=$(git rev-parse --short HEAD)
     SLUG=$(git log -1 --pretty=%s | tr '[:upper:]' '[:lower:]' \
       | sed 's/[^a-z0-9]\+/-/g; s/^-//; s/-$//' | cut -c1-40)
     ```

4. **Compilar assets:** `npm run build` (si no hay `node_modules`, antes
   `npm ci --ignore-scripts`).

5. **Preparar vendor limpio:**
   - Reutilizar `/tmp/opencode/deploy-work/vendor` solo si `composer.lock` no
     cambió.
   - Si no, crear `/tmp/opencode/deploy-work`, copiar el código con `rsync`
     excluyendo `vendor`, `node_modules`, `.git` y `public/build`, incluyendo
     `.env`, y ejecutar:
     ```bash
     COMPOSER_ALLOW_SUPERUSER=1 composer install \
       --no-dev --optimize-autoloader --prefer-dist
     ```
     Esto genera además `bootstrap/cache/packages.php` y `services.php`.

6. **Armar y comprimir** (borrando antes los zips anteriores):
   ```bash
   rm -f /tmp/opencode/cielotronador-*.zip
   mkdir -p /tmp/opencode/deploy-stage/cielotronador.cl/web/{vendor,public/build,bootstrap/cache}
   # copiar vendor/, public/build/ y los dos cache al stage
   cd /tmp/opencode/deploy-stage
   zip -r -q "/tmp/opencode/cielotronador-${SHORT}-${SLUG}.zip" .
   ```

7. **Limpiar temporales:** borrar `/tmp/opencode/deploy-work` y
   `/tmp/opencode/deploy-stage`. Conservar SOLO el zip con nombre de commit,
   para no llenar `/tmp/opencode`.

8. **Reportar** la ruta del zip `cielotronador-<SHORT>-<SLUG>.zip` y recordar:
   - `git pull` en el hosting trae el código (Blade, CSS, JS, PHP).
   - Subir el zip a la **raíz de la cuenta** y extraer ahí; NO dentro de
     `cielotronador.cl/web/web/`.
   - No re-subir `database.sqlite`.
   - Permisos 775 en `storage`, `bootstrap/cache`, `database`,
     `public/img/galeria` y `public/img/noticias`.
