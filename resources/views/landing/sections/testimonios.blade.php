@php
    $layout = ($options['layout'] ?? 'slider') === 'grid' ? 'grid' : 'slider';
    $autoplay = ($options['autoplay'] ?? 'yes') === 'yes';
    $intervalo = max(3000, min(20000, (int) ($options['interval'] ?? 6000)));
    $flechas = ($options['arrows'] ?? 'yes') === 'yes';
    $puntos = ($options['dots'] ?? 'yes') === 'yes';
    $varios = $testimonios->count() > 1;
@endphp

@if ($testimonios->isNotEmpty())
    <section id="testimonios" class="cf-dyn cf-testimonios" aria-labelledby="cf-testimonios-title">
        <div class="cf-dyn__inner">
            <div class="cf-dyn__head" data-reveal>
                <p class="cf-dyn__kicker">Testimonios</p>
                <h2 id="cf-testimonios-title" class="cf-dyn__title">Lo que dicen de nosotros</h2>
                <p class="cf-dyn__intro">
                    Proyectos que terminaron bien y relaciones que siguen creciendo.
                </p>
            </div>

            @if ($layout === 'grid')
                <div class="cf-testimonios__grid">
                    @foreach ($testimonios as $testimonio)
                        <div data-reveal data-reveal-delay="{{ $loop->index * 90 }}">
                            @include('landing.partials.testimonio-card', ['testimonio' => $testimonio])
                        </div>
                    @endforeach
                </div>
            @else
                <div class="cf-testimonios__slider"
                     data-testimonios
                     data-autoplay="{{ $autoplay ? 'yes' : 'no' }}"
                     data-interval="{{ $intervalo }}">
                    <div class="cf-testimonios__track" data-testimonios-track>
                        @foreach ($testimonios as $testimonio)
                            <div class="cf-testimonios__slide" data-testimonios-slide>
                                @include('landing.partials.testimonio-card', ['testimonio' => $testimonio])
                            </div>
                        @endforeach
                    </div>

                    @if ($flechas && $varios)
                        <button type="button" class="cf-testimonios__arrow" data-testimonios-prev aria-label="Testimonio anterior">‹</button>
                        <button type="button" class="cf-testimonios__arrow" data-testimonios-next aria-label="Testimonio siguiente">›</button>
                    @endif

                    @if ($puntos && $varios)
                        <div class="cf-testimonios__dots" data-testimonios-dots role="tablist" aria-label="Elegir testimonio">
                            @foreach ($testimonios as $testimonio)
                                <button type="button"
                                        class="cf-testimonios__dot{{ $loop->first ? ' is-active' : '' }}"
                                        data-testimonios-dot
                                        role="tab"
                                        aria-label="Testimonio {{ $loop->iteration }} de {{ $testimonios->count() }}"
                                        aria-selected="{{ $loop->first ? 'true' : 'false' }}"></button>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </section>
@endif
