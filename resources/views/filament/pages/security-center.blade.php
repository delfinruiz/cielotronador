<x-filament-panels::page>
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        @foreach ($this->getChecks() as $check)
            <div @class([
                'rounded-xl border p-4 shadow-sm',
                'border-green-200 bg-green-50 dark:border-green-500/30 dark:bg-green-500/10' => $check['ok'],
                'border-red-200 bg-red-50 dark:border-red-500/30 dark:bg-red-500/10' => ! $check['ok'],
            ])>
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-sm font-semibold text-zinc-900 dark:text-white">{{ $check['label'] }}</p>
                        <p class="mt-1 text-xl font-bold {{ $check['ok'] ? 'text-green-700 dark:text-green-300' : 'text-red-700 dark:text-red-300' }}">
                            {{ $check['value'] }}
                        </p>
                    </div>
                    <span @class([
                        'mt-1 inline-flex h-3 w-3 shrink-0 rounded-full',
                        'bg-green-500' => $check['ok'],
                        'bg-red-500' => ! $check['ok'],
                    ])></span>
                </div>
                <p class="mt-3 text-xs leading-relaxed text-zinc-600 dark:text-zinc-400">{{ $check['hint'] }}</p>
            </div>
        @endforeach
    </div>

    <x-filament::section heading="Respaldos recientes" description="Archivos privados en storage/app/backups. Descarga mediante URL firmada por 15 minutos.">
        @php($backups = $this->getBackups())

        @if ($backups->isEmpty())
            <p class="text-sm text-zinc-500 dark:text-zinc-400">
                Todavía no hay respaldos. Usa el botón «Ejecutar respaldo ahora» o espera la ejecución programada de las 02:00.
            </p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-zinc-200 text-xs uppercase tracking-wide text-zinc-500 dark:border-white/10 dark:text-zinc-400">
                        <tr>
                            <th class="py-2 pr-4">Archivo</th>
                            <th class="py-2 pr-4">Fecha</th>
                            <th class="py-2 pr-4">Tamaño</th>
                            <th class="py-2">Descargar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-white/5">
                        @foreach ($backups as $backup)
                            <tr>
                                <td class="py-2 pr-4 font-mono text-xs text-zinc-700 dark:text-zinc-300">{{ $backup['name'] }}</td>
                                <td class="py-2 pr-4 text-zinc-600 dark:text-zinc-400">{{ $backup['date']->format('d/m/Y H:i') }}</td>
                                <td class="py-2 pr-4 text-zinc-600 dark:text-zinc-400">{{ \App\Filament\Pages\SecurityCenter::formatBytes($backup['bytes']) }}</td>
                                <td class="py-2">
                                    <a href="{{ $backup['url'] }}" class="font-semibold text-primary-600 hover:underline dark:text-primary-400">Descargar</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-filament::section>

    <x-filament::section heading="Últimos eventos de auditoría" description="Accesos, cambios de plantillas, activaciones y respaldos.">
        @php($events = $this->getRecentAudit())

        @if ($events->isEmpty())
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Sin eventos registrados.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-zinc-200 text-xs uppercase tracking-wide text-zinc-500 dark:border-white/10 dark:text-zinc-400">
                        <tr>
                            <th class="py-2 pr-4">Fecha</th>
                            <th class="py-2 pr-4">Evento</th>
                            <th class="py-2 pr-4">Usuario</th>
                            <th class="py-2">IP</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-white/5">
                        @foreach ($events as $event)
                            <tr>
                                <td class="py-2 pr-4 text-zinc-600 dark:text-zinc-400">{{ $event->created_at?->format('d/m/Y H:i') }}</td>
                                <td class="py-2 pr-4 font-mono text-xs text-zinc-700 dark:text-zinc-300">{{ $event->event }}</td>
                                <td class="py-2 pr-4 text-zinc-600 dark:text-zinc-400">{{ $event->user?->name ?? 'Sistema' }}</td>
                                <td class="py-2 font-mono text-xs text-zinc-500 dark:text-zinc-400">{{ $event->ip_address }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-filament::section>
</x-filament-panels::page>
