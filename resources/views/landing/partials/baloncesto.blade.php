@php($idBalon = uniqid('balon-'))
<svg viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" {!! $attributes ?? '' !!}>
    <defs>
        <radialGradient id="{{ $idBalon }}" cx="35%" cy="30%" r="80%">
            <stop offset="0%" stop-color="#ff7a38"/>
            <stop offset="60%" stop-color="#ff4c00"/>
            <stop offset="100%" stop-color="#c43100"/>
        </radialGradient>
    </defs>
    <circle cx="32" cy="32" r="29" fill="url(#{{ $idBalon }})" stroke="#7d2408" stroke-width="3"/>
    <g stroke="#7d2408" stroke-width="2.5" stroke-linecap="round" fill="none">
        <path d="M32 6v52"/>
        <path d="M6 32h52"/>
        <path d="M13 12c7 7.5 7 32.5 0 40"/>
        <path d="M51 12c-7 7.5-7 32.5 0 40"/>
    </g>
</svg>
