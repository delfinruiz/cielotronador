<footer class="border-t border-gray-200 py-6 dark:border-white/10">
    <div class="flex flex-col items-center justify-center gap-2 text-center">
        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
            «Más que un club, una familia»
        </p>

        <a
            href="{{ route('landing') }}"
            class="inline-flex items-center gap-1.5 text-sm font-semibold text-primary-600 transition-colors hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300"
        >
            Volver al sitio web
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
            </svg>
        </a>

        <p class="text-xs text-gray-400 dark:text-gray-500">
            Copyright {{ now()->year }} por CieloTronador · Talcahuano, Chile
        </p>
    </div>
</footer>
