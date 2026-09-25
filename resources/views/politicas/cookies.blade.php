@extends('layouts.policy')

@section('titulo', 'Política de Cookies')

@section('contenido')
    <h1 class="font-heading mb-2 text-3xl font-semibold text-zinc-900 dark:text-white">Política de Cookies</h1>
    <p class="mb-8 text-sm text-zinc-500 dark:text-zinc-400">Última actualización: {{ now()->format('d/m/Y') }}</p>

    <div class="space-y-8 text-sm leading-relaxed text-zinc-600 dark:text-zinc-300">
        <section>
            <h2 class="font-heading mb-2 text-lg font-semibold text-zinc-900 dark:text-white">1. ¿Qué son las cookies?</h2>
            <p>
                Las cookies son pequeños archivos de texto que se almacenan en tu dispositivo al visitar un sitio web.
                Permiten que el sitio recuerde información sobre tu visita y funcione correctamente.
            </p>
        </section>

        <section>
            <h2 class="font-heading mb-2 text-lg font-semibold text-zinc-900 dark:text-white">2. Cookies que utilizamos</h2>
            <p>Este sitio web utiliza únicamente cookies estrictamente necesarias para su funcionamiento:</p>

            <div class="mt-3 overflow-x-auto rounded-xl border border-zinc-200 dark:border-white/10">
                <table class="w-full text-left text-sm">
                    <thead class="bg-zinc-50 dark:bg-white/5">
                        <tr class="border-b border-zinc-200 dark:border-white/10">
                            <th class="px-4 py-2 font-semibold text-zinc-700 dark:text-zinc-200">Cookie</th>
                            <th class="px-4 py-2 font-semibold text-zinc-700 dark:text-zinc-200">Finalidad</th>
                            <th class="px-4 py-2 font-semibold text-zinc-700 dark:text-zinc-200">Duración</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-zinc-200 dark:border-white/10">
                            <td class="px-4 py-2 font-mono text-xs">laravel_session</td>
                            <td class="px-4 py-2">Identifica tu sesión mientras navegas por el sitio.</td>
                            <td class="px-4 py-2">Sesión</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 font-mono text-xs">XSRF-TOKEN</td>
                            <td class="px-4 py-2">Protege los formularios contra solicitudes falsificadas.</td>
                            <td class="px-4 py-2">Sesión</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <p class="mt-3">
                Estas cookies son necesarias para que el sitio funcione y no requieren de tu consentimiento.
                No utilizamos cookies de análisis, publicidad ni de seguimiento de terceros.
            </p>
        </section>

        <section>
            <h2 class="font-heading mb-2 text-lg font-semibold text-zinc-900 dark:text-white">3. Gestión del consentimiento</h2>
            <p>
                Al visitar el sitio por primera vez verás un aviso de cookies. Puedes elegir entre
                <strong>"Aceptar todas"</strong> o <strong>"Solo necesarias"</strong>. Tu elección se guarda en tu
                navegador y no volverá a mostrarse, salvo que borres los datos de tu navegador.
            </p>
        </section>

        <section>
            <h2 class="font-heading mb-2 text-lg font-semibold text-zinc-900 dark:text-white">4. Cómo gestionar o eliminar las cookies</h2>
            <p>
                Puedes configurar tu navegador para bloquear o eliminar cookies en cualquier momento. Ten en cuenta que,
                si las desactivas, algunas funciones del sitio podrían no estar disponibles. Consulta la ayuda de tu
                navegador para más información.
            </p>
        </section>

        <section>
            <h2 class="font-heading mb-2 text-lg font-semibold text-zinc-900 dark:text-white">5. Contacto</h2>
            <p>
                Si tienes dudas sobre esta política, escríbenos a
                <a href="mailto:{{ config('services.contact.email') }}" class="font-medium text-brand-600 hover:text-brand-500 dark:text-brand-400 dark:hover:text-brand-300">{{ config('services.contact.email') }}</a>.
            </p>
        </section>
    </div>
@endsection
