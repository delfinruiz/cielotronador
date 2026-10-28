@php
    $menuItems = $menuItems ?? collect();
@endphp

<header id="site-header" class="sticky top-0 z-50 border-b border-zinc-200/70 bg-white/90 backdrop-blur-md dark:border-white/10 dark:bg-zinc-950/90">
    <div class="header-bar mx-auto flex h-20 max-w-7xl items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
        <a href="{{ route('landing') }}" class="flex shrink-0 items-center gap-3" aria-label="{{ \App\Support\Branding::name() }} - Inicio">
            <img src="{{ \App\Support\Branding::logoLightUrl() }}" alt="{{ \App\Support\Branding::name() }}" class="h-12 w-auto dark:hidden">
            <img src="{{ \App\Support\Branding::logoDarkUrl() }}" alt="{{ \App\Support\Branding::name() }}" class="hidden h-12 w-auto dark:block">
        </a>

        <nav class="hidden items-center gap-6 lg:flex" aria-label="Navegación principal">
            @foreach ($menuItems as $item)
                @if ($item->is_visible)
                    <a href="{{ $item->resolvedUrl() }}"
                       @if ($item->link_type === 'anchor') data-nav-link @endif
                       @if ($item->isExternal()) target="{{ $item->target }}" rel="noopener noreferrer" @endif
                       class="text-sm font-medium text-zinc-600 transition-colors hover:text-brand-600 dark:text-zinc-300 dark:hover:text-brand-400">{{ $item->label }}</a>
                @endif
            @endforeach

            {{-- El acceso al panel es parte fija del menú y no se puede eliminar. --}}
            @auth
                <a href="{{ url('/admin') }}" class="rounded-full bg-brand-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-brand-600">Ir al panel</a>
            @else
                <a href="{{ url('/admin/login') }}" class="rounded-full bg-brand-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-brand-600">Ingresar</a>
            @endauth
        </nav>

        <div class="flex items-center gap-3">
            <button id="theme-toggle" type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-full text-zinc-600 transition-colors hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-300 dark:hover:bg-white/10 dark:hover:text-white" aria-label="Cambiar tema">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icon-moon h-5 w-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z"/>
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icon-sun hidden h-5 w-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"/>
                </svg>
            </button>

            <button id="mobile-menu-toggle" type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-full text-zinc-600 transition-colors hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-white/10 lg:hidden" aria-label="Abrir menú" aria-expanded="false">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                </svg>
            </button>
        </div>
    </div>

    <nav id="mobile-menu" class="hidden border-t border-zinc-200/70 px-4 pt-2 pb-4 dark:border-white/10 lg:hidden" aria-label="Navegación móvil">
        <div class="flex flex-col gap-1">
            @foreach ($menuItems as $item)
                @if ($item->is_visible)
                    <a href="{{ $item->resolvedUrl() }}"
                       @if ($item->link_type === 'anchor') data-nav-link @endif
                       @if ($item->isExternal()) target="{{ $item->target }}" rel="noopener noreferrer" @endif
                       class="rounded-lg px-3 py-2.5 text-sm font-medium text-zinc-700 hover:bg-zinc-100 dark:text-zinc-200 dark:hover:bg-white/10">{{ $item->label }}</a>
                @endif
            @endforeach

            @auth
                <a href="{{ url('/admin') }}" class="mt-2 inline-flex items-center justify-center gap-2 rounded-full bg-brand-500 px-5 py-2.5 text-sm font-semibold text-white hover:bg-brand-600">Ir al panel</a>
            @else
                <a href="{{ url('/admin/login') }}" class="mt-2 inline-flex items-center justify-center gap-2 rounded-full bg-brand-500 px-5 py-2.5 text-sm font-semibold text-white hover:bg-brand-600">Ingresar</a>
            @endauth
        </div>
    </nav>
</header>
