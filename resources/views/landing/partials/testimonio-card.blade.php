@php
    $estrellas = (int) ($testimonio->rating ?? 0);
    $avatar = $testimonio->avatarUrl();
@endphp

<article class="cf-testimonios__card">
    @if ($estrellas > 0)
        <p class="cf-testimonios__stars" aria-label="Valoración: {{ $estrellas }} de 5">
            {{ str_repeat('★', $estrellas) }}
        </p>
    @endif

    <blockquote class="cf-testimonios__quote">«{{ $testimonio->quote }}»</blockquote>

    <div class="cf-testimonios__author">
        <span class="cf-testimonios__avatar" aria-hidden="true">
            @if ($avatar)
                <img src="{{ $avatar }}" alt="" loading="lazy">
            @else
                {{ $testimonio->initials() }}
            @endif
        </span>
        <div>
            <p class="cf-testimonios__name">{{ $testimonio->name }}</p>
            @if (filled($testimonio->role))
                <p class="cf-testimonios__role">{{ $testimonio->role }}</p>
            @endif
        </div>
    </div>
</article>
