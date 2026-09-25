---
paths:
  - '**'
---

# General

## Don't run artisan/test as root — it breaks the dev server
The dev server (php artisan dev) runs as user delfinruiz. Running `php artisan`/`php artisan test` commands as root recompiles Blade views into storage/framework/views owned by root, which the server can't touch() → "Utime failed: Operation not permitted" on login/page render. Run dev tools as delfinruiz, or after root runs fix with: chown -R delfinruiz:www-data storage framework bootstrap/cache.

## Run artisan/test as delfinruiz via runuser, never as root
Use `runuser -u delfinruiz -- php artisan ...` (not root) for any artisan/phpunit invocation in this shell (running as root). Root compiles Blade views into storage/framework/views as root, breaking the delfinruiz dev server with "touch(): Utime failed: Operation not permitted". Also chown -R delfinruiz:www-data storage bootstrap/cache .phpunit.result.cache if they become root-owned.
