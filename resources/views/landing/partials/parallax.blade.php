{{-- Fondo decorativo de básquetbol con parallax. $color = clase de color (text-*), $x = velocidad/dirección horizontal. --}}
@php($x = (float) ($x ?? 0.05))
<div class="pointer-events-none absolute inset-0 overflow-hidden" aria-hidden="true">
    <div data-parallax="0.18" data-parallax-x="{{ $x }}" class="absolute -inset-x-[15%] -top-[30%] h-[160%] {{ $color ?? 'text-brand-500' }} opacity-10">
        <svg class="h-full w-full" viewBox="0 0 1440 800" fill="none" preserveAspectRatio="xMidYMid slice">
            <circle cx="1120" cy="400" r="230" stroke="currentColor" stroke-width="2"/>
            <circle cx="1120" cy="400" r="150" stroke="currentColor" stroke-width="1.5"/>
            <line x1="1120" y1="0" x2="1120" y2="800" stroke="currentColor" stroke-width="1.5"/>
            <path d="M 240 800 A 520 520 0 0 1 1320 800" stroke="currentColor" stroke-width="2"/>
            <circle cx="780" cy="800" r="220" stroke="currentColor" stroke-width="1.5"/>
            <g transform="translate(1210, 110)" stroke="currentColor" stroke-width="2.5">
                <circle r="120"/>
                <path d="M0 -120 V 120"/>
                <path d="M-120 0 H 120"/>
                <path d="M-41 -113 c24 37 24 76 0 113"/>
                <path d="M41 -113 c-24 37 -24 76 0 113"/>
            </g>
            <circle cx="330" cy="160" r="5" fill="currentColor" stroke="none"/>
            <circle cx="1400" cy="640" r="5" fill="currentColor" stroke="none"/>
        </svg>
    </div>
    <div data-parallax="-0.12" data-parallax-x="{{ -$x }}" class="absolute right-[10%] bottom-[6%] {{ $color ?? 'text-brand-500' }} opacity-10">
        <svg class="h-28 w-28 sm:h-36 sm:w-36" viewBox="0 0 64 64" fill="none">
            <g stroke="currentColor" stroke-width="2.5">
                <circle cx="32" cy="32" r="30"/>
                <path d="M32 2v60"/>
                <path d="M2 32h60"/>
                <path d="M13 10c7.5 7 7.5 37 0 44"/>
                <path d="M51 10c-7.5 7-7.5 37 0 44"/>
            </g>
        </svg>
    </div>
</div>