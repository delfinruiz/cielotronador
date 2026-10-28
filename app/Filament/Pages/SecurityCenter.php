<?php

namespace App\Filament\Pages;

use App\Models\AuditLog;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;

class SecurityCenter extends Page
{
    protected string $view = 'filament.pages.security-center';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;

    protected static string|\UnitEnum|null $navigationGroup = 'Configuración';

    protected static ?string $navigationLabel = 'Centro de seguridad';

    protected static ?string $title = 'Centro de seguridad';

    protected static ?int $navigationSort = 10;

    public static function canAccess(): bool
    {
        return auth()->user()?->hasRole('super_admin') ?? false;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('backup')
                ->label('Ejecutar respaldo ahora')
                ->icon(Heroicon::OutlinedArrowDownTray)
                ->requiresConfirmation()
                ->modalDescription('Se creará un ZIP privado con la base de datos y las imágenes públicas.')
                ->action(function (): void {
                    $exitCode = Artisan::call('backup:run');

                    if ($exitCode !== 0) {
                        Notification::make()
                            ->title('No se pudo crear el respaldo')
                            ->body(Artisan::output())
                            ->danger()
                            ->send();

                        return;
                    }

                    Notification::make()
                        ->title('Respaldo creado')
                        ->body(trim(Artisan::output()))
                        ->success()
                        ->send();

                    $this->redirect(static::getUrl());
                }),
        ];
    }

    /**
     * @return array<int, array{label: string, value: string, ok: bool, hint: string}>
     */
    public function getChecks(): array
    {
        $users = User::query()->count();
        $mfaUsers = User::query()->whereNotNull('app_authentication_secret')->count();
        $lastBackup = $this->getBackups()->first();

        return [
            [
                'label' => 'Entorno',
                'value' => app()->environment(),
                'ok' => app()->isProduction(),
                'hint' => 'En producción debe ser "production".',
            ],
            [
                'label' => 'Depuración',
                'value' => config('app.debug') ? 'Activada' : 'Desactivada',
                'ok' => ! config('app.debug'),
                'hint' => 'APP_DEBUG debe estar en false en producción.',
            ],
            [
                'label' => 'HTTPS',
                'value' => request()->isSecure() ? 'Sí' : 'No',
                'ok' => request()->isSecure() || ! app()->isProduction(),
                'hint' => 'El sitio público debe servirse por HTTPS.',
            ],
            [
                'label' => 'Sesiones cifradas',
                'value' => config('session.encrypt') ? 'Sí' : 'No',
                'ok' => (bool) config('session.encrypt'),
                'hint' => 'Activa SESSION_ENCRYPT=true en producción.',
            ],
            [
                'label' => 'Segundo factor (MFA)',
                'value' => config('security.mfa_required')
                    ? $mfaUsers.' / '.$users.' usuarios'
                    : 'Desactivado',
                'ok' => ! config('security.mfa_required') || ($users > 0 && $mfaUsers === $users),
                'hint' => config('security.mfa_required')
                    ? 'Todos los usuarios del panel deben configurar TOTP.'
                    : 'Desactivado por ahora. Se activa con SECURITY_MFA_REQUIRED=true.',
            ],
            [
                'label' => 'Último respaldo',
                'value' => $lastBackup ? $lastBackup['date']->diffForHumans() : 'Nunca',
                'ok' => $lastBackup !== null && $lastBackup['date']->greaterThan(now()->subDays(2)),
                'hint' => 'El respaldo diario se ejecuta a las 02:00.',
            ],
            [
                'label' => 'Assets del editor',
                'value' => File::exists(public_path('vendor/grapesjs/editor.js')) ? 'Publicados' : 'Faltantes',
                'ok' => File::exists(public_path('vendor/grapesjs/editor.js')),
                'hint' => 'Publica los assets del constructor antes de usarlo.',
            ],
        ];
    }

    /**
     * @return Collection<int, array{name: string, bytes: int, date: Carbon, url: string}>
     */
    public function getBackups(): Collection
    {
        return collect(File::glob(storage_path('app/backups/backup-*.zip')) ?: [])
            ->map(fn (string $path): array => [
                'name' => basename($path),
                'bytes' => filesize($path) ?: 0,
                'date' => Carbon::createFromTimestamp(filemtime($path) ?: 0),
                'url' => URL::temporarySignedRoute(
                    'security.backups.download',
                    now()->addMinutes(15),
                    ['file' => basename($path)],
                ),
            ])
            ->sortByDesc('date')
            ->values();
    }

    /**
     * @return Collection<int, AuditLog>
     */
    public function getRecentAudit(): Collection
    {
        return AuditLog::query()
            ->with('user')
            ->latest()
            ->limit(15)
            ->get();
    }

    public static function formatBytes(int $bytes): string
    {
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2, ',', '.').' MB';
        }

        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 1, ',', '.').' KB';
        }

        return $bytes.' B';
    }
}
