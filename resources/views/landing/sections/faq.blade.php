@php
    $columnas = ($options['columns'] ?? '1') === '2' ? 2 : 1;
    $abrirPrimera = ($options['open_first'] ?? 'yes') === 'yes';
@endphp

@if ($faqs->isNotEmpty())
    <section id="faq" class="cf-dyn cf-faq" aria-labelledby="cf-faq-title">
        <div class="cf-dyn__inner">
            <div class="cf-dyn__head" data-reveal>
                <p class="cf-dyn__kicker">Preguntas frecuentes</p>
                <h2 id="cf-faq-title" class="cf-dyn__title">Todo lo que necesitas saber</h2>
                <p class="cf-dyn__intro">
                    Si tu duda no está aquí, escríbenos y te respondemos a la brevedad.
                </p>
            </div>

            <div class="cf-faq__grid" data-columns="{{ $columnas }}">
                @foreach ($faqs as $faq)
                    @php($abierta = $abrirPrimera && $loop->first)
                    <div class="cf-faq__item{{ $abierta ? ' is-open' : '' }}"
                         data-faq-item
                         data-reveal
                         data-reveal-delay="{{ $loop->index * 80 }}">
                        <button type="button"
                                class="cf-faq__toggle"
                                data-faq-toggle
                                aria-expanded="{{ $abierta ? 'true' : 'false' }}"
                                aria-controls="cf-faq-panel-{{ $faq->id }}">
                            <span>{{ $faq->question }}</span>
                            <span class="cf-faq__icon" aria-hidden="true"></span>
                        </button>
                        <div class="cf-faq__panel"
                             id="cf-faq-panel-{{ $faq->id }}"
                             data-faq-panel
                             @if ($abierta) style="max-height:none" @endif>
                            <div class="cf-faq__panel-inner">
                                <p>{{ $faq->answer }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
