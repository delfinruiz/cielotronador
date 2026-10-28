<?php

namespace App\Providers;

use App\Models\AuditLog;
use App\Models\PageTemplate;
use HansDeBoeck\Grapesjs\Editor\Ontwerpregister;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if ($this->app->isProduction()) {
            URL::forceScheme('https');
        }

        RateLimiter::for('contacto', fn (Request $request) => Limit::perMinute(5)->by($request->ip()));

        app(Ontwerpregister::class)->registreer(
            'plantilla',
            PageTemplate::class,
            fn (PageTemplate $template): mixed => Gate::authorize('update', $template),
        );

        Event::listen(Login::class, function (Login $event): void {
            AuditLog::record('auth.login', $event->user);
        });

        Event::listen(Failed::class, function (Failed $event): void {
            AuditLog::record('auth.failed', null, [], [
                'email' => $event->credentials['email'] ?? null,
            ]);
        });
    }
}
