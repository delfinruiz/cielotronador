@php($aviso = \App\Support\CookieBanner::current())

<div
    id="cookie-banner"
    role="dialog"
    aria-modal="false"
    aria-label="Consentimiento de cookies"
    class="cookie-banner fixed inset-x-0 bottom-0 z-[70] px-4 pb-4 sm:px-6 lg:px-8"
    hidden
>
    <div class="mx-auto max-w-7xl">
        <div class="flex max-h-[70vh] flex-col gap-4 overflow-x-hidden overflow-y-auto rounded-2xl border border-zinc-200 bg-white p-5 shadow-2xl sm:flex-row sm:items-center sm:justify-between dark:border-white/10 dark:bg-zinc-900">
            <p class="text-sm leading-relaxed text-zinc-600 dark:text-zinc-300">
                {{ $aviso['message'] }}
                {{ $aviso['policy_prefix'] }}
                <a href="{{ route('politica.cookies') }}" class="font-semibold text-brand-600 hover:text-brand-500 dark:text-brand-400 dark:hover:text-brand-300">{{ $aviso['policy_label'] }}</a>.
            </p>

            <div class="flex max-w-full shrink-0 flex-wrap items-center gap-3">
                <button
                    type="button"
                    data-cookie-choice="necessary"
                    class="inline-flex items-center justify-center rounded-full border border-zinc-300 px-5 py-2.5 text-sm font-semibold text-zinc-700 transition-colors hover:bg-zinc-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-500 dark:border-white/20 dark:text-zinc-200 dark:hover:bg-white/10"
                >
                    {{ $aviso['necessary_label'] }}
                </button>
                <button
                    type="button"
                    data-cookie-choice="all"
                    class="inline-flex items-center justify-center rounded-full bg-brand-500 px-5 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-brand-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-500 focus-visible:ring-offset-2 dark:bg-brand-500 dark:hover:bg-brand-400"
                >
                    {{ $aviso['accept_label'] }}
                </button>
            </div>
        </div>
    </div>
</div>
