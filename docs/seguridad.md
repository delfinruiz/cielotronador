# Seguridad y operación

Este documento resume los controles implementados en el sitio y el procedimiento para operarlos. Está pensado para que otra persona pueda respaldar, actualizar o responder a un incidente sin depender de memoria.

## 1. Controles de acceso

- **Panel Filament** en `/admin`, autenticación con sesión y *throttle* de 5 intentos por minuto en el login.
- **Segundo factor (TOTP)**: implementado pero **desactivado por ahora**. Para exigirlo, define `SECURITY_MFA_REQUIRED=true`; los usuarios deberán configurar una app autenticadora (Google Authenticator, Authy, etc.) al iniciar sesión. La columna `users.app_authentication_secret` se guarda cifrada por el trait de Filament.
- **Roles y permisos** con Filament Shield + Spatie Permission. `super_admin` tiene todos los permisos; `panel_user` no tiene permisos por defecto.
- **Plantillas de portada**: `ViewAny/View/Create/Update/Delete:PageTemplate` para el recurso. Solo `super_admin` puede activar o eliminar plantillas y acceder al Centro de seguridad.
- **Editor GrapesJS**: rutas detrás de `web`, `auth` y `throttle:60,1`. Además, el registro del editor exige `Gate::authorize('update', $template)`.
- **Sesiones**: driver `database`, `http_only` activo, `SameSite=Lax`. En producción activar `SESSION_ENCRYPT=true` y `SESSION_SECURE_COOKIE=true`.
- **Formulario de contacto**: límite de 5 envíos por minuto por IP y campo *honeypot* invisible. Los bots reciben una respuesta de éxito sin enviar correo.

## 2. Respaldos

Comando:

```bash
php artisan backup:run --keep=30
```

- Crea `storage/app/backups/backup-AAAA-MM-DD_HHMMSS.zip` con permisos `0600`.
- Incluye una copia consistente de SQLite (`VACUUM INTO`), `public/img` y `storage/app/public`.
- **No incluye** `.env` ni credenciales.
- Conserva los últimos `--keep` respaldos (30 por defecto).
- Registra el evento `backup.created` en `audit_logs`.
- Se ejecuta a diario a las 02:00 vía scheduler (`routes/console.php`).

En el servidor, el cron debe ejecutar el scheduler cada minuto:

```cron
* * * * * cd /ruta/al/proyecto && php artisan schedule:run >> /dev/null 2>&1
```

Los respaldos se descargan desde **Configuración → Centro de seguridad** con una URL firmada de 15 minutos. También se pueden copiar fuera del servidor (S3, Dropbox, disco externo) sin cambiar el código.

### Restauración

1. Activar mantenimiento: `php artisan down --secret="una-clave-temporal"`.
2. Descomprimir el ZIP en una carpeta temporal.
3. Reemplazar `database/database.sqlite`, `public/img` y `storage/app/public` con el contenido del respaldo.
4. Ajustar propietario/permisos (`delfinruiz:www-data`, `storage` y `bootstrap/cache` escribibles).
5. Ejecutar `php artisan migrate --force`, `php artisan optimize:clear`.
6. Verificar la portada, el login y el panel; luego `php artisan up`.
7. Registrar el incidente y la restauración en `audit_logs` o en el informe correspondiente.

Hacer una prueba de restauración en un entorno de staging al menos una vez al año.

## 3. Actualizaciones

- Dependabot revisa semanalmente `composer`, `npm` y las GitHub Actions (`.github/dependabot.yml`).
- CI ejecuta `composer audit --locked` y `npm audit --audit-level=high`; una vulnerabilidad conocida rompe el pipeline.
- Flujo recomendado: rama → actualización de parches → `php artisan test --compact` → `npm run build` → revisar staging → desplegar → verificar `/`, `/admin/login`, guardar una plantilla y ejecutar un respaldo.
- El paquete `hansdeboeck/laravel-grapesjs` está fijado en `5.2.0`. Antes de subir de versión, revisar sus cambios y volver a publicar los assets:

```bash
php artisan vendor:publish --tag=grapesjs-assets --force
```

## 4. Gestión de vulnerabilidades

- Cabeceras de seguridad globales (`X-Content-Type-Options`, `X-Frame-Options`, `Referrer-Policy`, `Permissions-Policy`).
- CSP en modo **Report-Only** al inicio, para no romper los scripts inline existentes. Revisar los reportes antes de pasar a modo bloqueante.
- HSTS solo en producción y sobre HTTPS.
- `APP_DEBUG=false` obligatorio en producción.
- Sanitización del HTML de GrapesJS al guardar y al renderizar: se eliminan scripts, iframes, eventos inline y URLs `javascript:`.
- El CSS de la plantilla se limpia y se prefija con `#cf-template` para que no altere el shell del sitio.
- Validación de enlaces del menú: anclas (`#seccion`), rutas internas existentes o URLs `http/https/mailto/tel`.
- Subidas del editor: solo imágenes, máximo 2 MB, nombre ULID y disco dedicado `plantillas` (`public/img/plantillas`).
- Contraseñas con `bcrypt` (12 rondas), restablecimiento con token y sin registro público.

## 5. Prevención y respuesta ante incidentes

### Señales

- Accesos fallidos repetidos (`auth.failed` en `audit_logs`).
- Cambios de plantilla, activaciones o respaldos fuera de horario.
- Errores 500, tráfico anómalo o quejas por correo/spam.
- Alertas de Dependabot o del CI por vulnerabilidades.

### Procedimiento

1. **Detectar y registrar**: anotar hora, síntoma, usuario/IP y evidencia (logs, `audit_logs`, capturas). No borrar logs.
2. **Contener**: activar mantenimiento (`php artisan down --secret=...`), revocar sesiones, cambiar contraseñas comprometidas y desactivar usuarios sospechosos.
3. **Erradicar**: aplicar el parche o la actualización, eliminar contenido malicioso, revisar plantillas y archivos subidos.
4. **Recuperar**: restaurar desde el último respaldo limpio, verificar integridad, volver a `up` y monitorear.
5. **Aprender**: documentar causa raíz, impacto y acciones correctivas; actualizar este documento y los controles.

### Contactos y evidencias

- Mantener una lista de responsables del club y del hosting fuera del repositorio.
- Conservar los logs al menos 30 días (`LOG_CHANNEL=daily`, `LOG_LEVEL=warning` en producción).
- Guardar una copia de los respaldos antes de una intervención mayor.

## 6. Checklist de producción

- [ ] `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://...`.
- [ ] HTTPS activo y redirect HTTP → HTTPS en el hosting.
- [ ] `SESSION_ENCRYPT=true`, `SESSION_SECURE_COOKIE=true`, `SESSION_SAME_SITE=lax`.
- [ ] `LOG_CHANNEL=daily`, `LOG_LEVEL=warning`.
- [ ] Cron de `schedule:run` configurado.
- [ ] `php artisan storage:link` si se usa el disco `public` (el editor usa `public/img/plantillas`, no lo necesita).
- [ ] Assets de GrapesJS publicados en `public/vendor/grapesjs`.
- [ ] `composer audit` y `npm audit` sin vulnerabilidades conocidas.
- [ ] MFA configurado por todos los usuarios del panel (o `SECURITY_MFA_REQUIRED=false` asumido conscientemente).
- [ ] Primer respaldo manual verificado y restauración probada.
- [ ] Centro de seguridad en verde.

## 7. Limitaciones conocidas

- El paquete de GrapesJS tiene baja adopción (un mantenedor). Se mitiga fijando la versión, aislándolo tras el registro/autorización y sanitizando su salida. Si se abandona, el editor se puede reemplazar manteniendo el modelo de datos (`gjs_data`, `html`, `css`).
- La CSP está en `Report-Only`; pasar a bloqueo requiere mover los scripts inline de la portada a Vite o usar nonces.
- Las plantillas GrapesJS no generan variantes de modo oscuro automáticas; si se necesitan, se diseñan dentro del editor o se añade un tema específico.
