@php
    $velocidad = (int) ($options['speed'] ?? 40);
    $velocidad = max(10, min(120, $velocidad));
    $grayscale = ($options['grayscale'] ?? 'yes') === 'yes';
    $pausa = ($options['pause'] ?? 'yes') === 'yes';
    $invertir = ($options['invert'] ?? 'yes') === 'yes';
@endphp

@if ($marcas->isNotEmpty())
    <section id="marcas"
             class="cf-marcas{{ $grayscale ? ' is-grayscale' : '' }}"
             data-pause="{{ $pausa ? 'yes' : 'no' }}"
             data-invert="{{ $invertir ? 'yes' : 'no' }}"
             aria-label="Marcas que confían en nosotros">
        <p class="cf-dyn__kicker" style="text-align:center;margin:0 0 28px;">Marcas que confían en nosotros</p>

        <div class="cf-marcas__viewport">
            <div class="cf-marcas__track" style="--cf-marquesa: {{ $velocidad }}s">
                @foreach ($marcas->concat($marcas) as $marca)
                    @php($duplicado = $loop->index >= $marcas->count())
                    @if ($marca->url)
                        <a href="{{ $marca->url }}"
                           class="cf-marcas__item"
                           target="_blank"
                           rel="noopener"
                           @if ($duplicado) aria-hidden="true" tabindex="-1" @endif>
                            <img src="{{ $marca->logoUrl() }}" alt="{{ $duplicado ? '' : $marca->name }}" loading="lazy">
                        </a>
                    @else
                        <span class="cf-marcas__item" @if ($duplicado) aria-hidden="true" @endif>
                            <img src="{{ $marca->logoUrl() }}" alt="{{ $duplicado ? '' : $marca->name }}" loading="lazy">
                        </span>
                    @endif
                @endforeach
            </div>
        </div>
    </section>
@endif
