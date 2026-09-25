<div>
    @include('filament.auth.partials.theme-toggle')

    <x-filament-panels::page.simple>
        {{ $this->content }}

        @include('filament.auth.partials.brand-footer')
    </x-filament-panels::page.simple>
</div>
