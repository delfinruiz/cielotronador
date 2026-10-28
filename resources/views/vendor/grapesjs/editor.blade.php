{{--
    Editor de plantillas. Página completa porque GrapesJS necesita todo el alto
    y su propio scroll. La configuración viaja como JSON en un atributo, nunca
    interpolada dentro de un script.
--}}
<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <meta name="wire:navigate" content="false">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>{{ $titel }} · {{ \App\Support\Branding::name() }}</title>
    <script>
        // Si el navegador restaura esta página desde el cache de "atrás/adelante",
        // recargamos para que el editor vuelva a inicializarse limpio.
        window.addEventListener('pageshow', (event) => {
            if (event.persisted) {
                window.location.reload();
            }
        });
    </script>
    @php
        $cssPath = public_path(ltrim((string) parse_url(config('grapesjs.assets.css'), PHP_URL_PATH), '/'));
        $jsPath = public_path(ltrim((string) parse_url(config('grapesjs.assets.js'), PHP_URL_PATH), '/'));
        $cssVersion = is_file($cssPath) ? filemtime($cssPath) : '1';
        $jsVersion = is_file($jsPath) ? filemtime($jsPath) : '1';
    @endphp
    <link rel="stylesheet" href="{{ asset(config('grapesjs.assets.css')) }}?v={{ $cssVersion }}">
    <style>
        html, body { height: 100%; margin: 0; font-family: ui-sans-serif, system-ui, sans-serif; }
        .gjs-kop {
            display: flex; align-items: center; justify-content: space-between; gap: 1rem;
            height: 3.25rem; padding: 0 1rem; background: #111827; color: #fff; font-size: .875rem;
        }
        .gjs-kop .marca { display: flex; align-items: center; gap: .6rem; font-weight: 600; }
        .gjs-kop .marca span { color: #FF4C00; }
        .gjs-kop a, .gjs-kop button {
            font: inherit; border-radius: .5rem; padding: .375rem .75rem; cursor: pointer;
            border: 1px solid rgba(255,255,255,.2); background: transparent; color: #e5e7eb;
            text-decoration: none;
        }
        .gjs-kop button.primair { background: #FF4C00; color: #fff; border-color: #FF4C00; font-weight: 600; }
        .gjs-kop button.publicar { background: #15803d; color: #fff; border-color: #15803d; font-weight: 600; }
        .gjs-kop button.publicar:hover { background: #166534; }
        .gjs-kop button.publicar:disabled { opacity: .55; cursor: not-allowed; }
        .gjs-kop .status { color: #9ca3af; font-size: .8125rem; }
        #gjs-editor { height: calc(100% - 3.25rem) !important; }

        .gjs-help { position: fixed; inset: 0; z-index: 10000; }
        .gjs-help[hidden] { display: none; }
        .gjs-help__backdrop {
            position: absolute; inset: 0; background: rgba(15, 23, 42, .68); backdrop-filter: blur(2px);
        }
        .gjs-help__panel {
            position: absolute; inset: 1rem; max-width: 56rem; margin: 0 auto;
            display: flex; flex-direction: column; overflow: hidden;
            border-radius: 1rem; background: #fff; box-shadow: 0 25px 80px rgba(0, 0, 0, .35);
        }
        .gjs-help__header {
            display: flex; align-items: center; justify-content: space-between; gap: 1rem;
            padding: 1rem 1.25rem; border-bottom: 1px solid #e4e4e7; background: #fff;
        }
        .gjs-help__eyebrow {
            margin: 0; color: #FF4C00; font-size: .7rem; font-weight: 700;
            letter-spacing: .2em; text-transform: uppercase;
        }
        .gjs-help__header h1 { margin: .2rem 0 0; color: #18181b; font-size: 1.25rem; line-height: 1.2; }
        .gjs-help__header button {
            flex-shrink: 0; padding: .45rem .85rem; border: 1px solid #d4d4d8; border-radius: .5rem;
            background: #fff; color: #27272a; font: inherit; font-weight: 600; cursor: pointer;
        }
        .gjs-help__header button:hover { background: #f4f4f5; }
        .gjs-help__body { overflow-y: auto; padding: 1.25rem; background: #fff; }
        body.gjs-help-open { overflow: hidden; }

        .cf-borrador {
            position: fixed; left: 50%; bottom: 1.25rem; transform: translateX(-50%);
            z-index: 9500; display: flex; align-items: center; gap: .85rem;
            width: max-content; max-width: min(92vw, 46rem);
            padding: .7rem .9rem; border-radius: .75rem;
            background: #18181b; color: #fafafa; font-size: .8125rem;
            box-shadow: 0 20px 50px rgba(0, 0, 0, .45); border: 1px solid rgba(255, 255, 255, .12);
        }
        .cf-borrador[hidden] { display: none; }
        .cf-borrador strong { display: block; font-size: .875rem; }
        .cf-borrador span { color: #a1a1aa; }
        .cf-borrador__acciones { display: flex; gap: .5rem; flex-shrink: 0; }
        .cf-borrador button {
            font: inherit; font-weight: 600; cursor: pointer; padding: .4rem .75rem;
            border-radius: .5rem; border: 1px solid rgba(255, 255, 255, .2);
            background: transparent; color: #e5e7eb;
        }
        .cf-borrador button:hover { background: rgba(255, 255, 255, .08); }
        .cf-borrador button.primair { background: #ff4c00; border-color: #ff4c00; color: #fff; }
        .cf-borrador button.primair:hover { background: #e64500; }

        .cf-bloques-buscar {
            position: sticky; top: 0; z-index: 6; padding: .5rem;
            background: #27272a; border-bottom: 1px solid #3f3f46;
        }
        .cf-bloques-buscar input {
            width: 100%; box-sizing: border-box; padding: .4rem .55rem;
            border: 1px solid #52525b; border-radius: .375rem; background: #18181b;
            color: #fafafa; font: inherit; font-size: .75rem;
        }
        .cf-bloques-buscar input::placeholder { color: #a1a1aa; }
        .cf-bloques-buscar input:focus { outline: 2px solid #ff4c00; outline-offset: 0; }
        .cf-bloques-buscar small { display: block; margin-top: .3rem; color: #a1a1aa; font-size: .65rem; }

        @media (max-width: 640px) {
            .gjs-help__panel { inset: 0; border-radius: 0; }
            .gjs-help__header h1 { font-size: 1.05rem; }
            .cf-borrador { flex-direction: column; align-items: stretch; text-align: center; }
            .cf-borrador__acciones { justify-content: center; }
        }
    </style>
</head>
<body>
    <div class="gjs-kop">
        <span class="marca"><span>●</span> {{ \App\Support\Branding::name() }} · {{ $titel }}</span>
        <div style="display:flex;align-items:center;gap:.75rem">
            <span class="status" data-gjs-status></span>
            <button type="button" data-gjs-tema aria-pressed="false" title="Ver el lienzo en modo oscuro (solo vista previa)">
                🌙 Oscuro
            </button>
            @if (\App\Filament\Pages\CookieBannerSettings::canAccess())
                <a href="{{ \App\Filament\Resources\PageTemplates\PageTemplateResource::getUrl('index', ['pestana' => 'aviso']) }}" target="_blank" rel="noopener" title="Editar el texto del aviso de cookies">
                    🍪 Cookies
                </a>
            @endif
            <button type="button" data-gjs-help aria-haspopup="dialog" aria-expanded="false" aria-controls="gjs-help">
                ? Ayuda
            </button>
            @if ($terugUrl)
                <a href="{{ $terugUrl }}">Cerrar</a>
            @endif
            <button type="button" class="primair" data-gjs-bewaren>Guardar</button>
            @php($plantillaEditor = request()->route('template'))
            @if ($plantillaEditor)
                <button type="button" class="publicar" data-gjs-publicar
                        data-url="{{ route('page-templates.publish', ['template' => $plantillaEditor]) }}">
                    Publicar
                </button>
            @endif
        </div>
    </div>

    <div id="gjs-editor"
         data-gjs-config="{{ json_encode($config, JSON_THROW_ON_ERROR) }}"></div>

    <div id="cf-borrador-editor" class="cf-borrador" hidden role="status" aria-live="polite">
        <div>
            <strong>Tienes un borrador sin guardar</strong>
            <span data-cf-borrador-fecha></span>
        </div>
        <div class="cf-borrador__acciones">
            <button type="button" data-cf-borrador-descartar>Descartar</button>
            <button type="button" class="primair" data-cf-borrador-restaurar>Restaurar</button>
        </div>
    </div>

    <div id="gjs-help" class="gjs-help" hidden>
        <div class="gjs-help__backdrop" data-gjs-help-close></div>
        <aside class="gjs-help__panel" role="dialog" aria-modal="true" aria-labelledby="gjs-help-title">
            <header class="gjs-help__header">
                <div>
                    <p class="gjs-help__eyebrow">Constructor visual</p>
                    <h1 id="gjs-help-title">Guía rápida del constructor</h1>
                </div>
                <button type="button" data-gjs-help-close>Cerrar</button>
            </header>
            <div class="gjs-help__body">
                @include('grapesjs.help-content')
            </div>
        </aside>
    </div>

    {{--
        El paquete publica su editor como un bundle cerrado. El asset publicado se
        parchea para exponer la instancia en `window.__cielotronadorGjs`; así este
        script puede añadir los ajustes de animación sin bifurcar el paquete.
    --}}
    <script src="{{ asset(config('grapesjs.assets.js')) }}?v={{ $jsVersion }}"></script>
    <script>
        (() => {
            const editor = window.__cielotronadorGjs;

            if (!editor) {
                return;
            }

            /*
             * El preset del paquete agrega tres bloques genéricos en una
             * categoría "Basic" en inglés que duplica el catálogo propio.
             * Los retiramos para que el panel muestre solo los grupos del
             * proyecto, todos con al menos tres bloques.
             */
            const quitarBloquesDelPreset = () => {
                ['link-block', 'quote', 'text-basic'].forEach((id) => {
                    if (editor.BlockManager.get(id)) {
                        editor.BlockManager.remove(id);
                    }
                });
            };

            quitarBloquesDelPreset();
            editor.onReady(quitarBloquesDelPreset);

            const canvasCss = @json(\App\Support\BlockTheme::css()."\n".\App\Support\AnimationLibrary::css()."\n".\App\Support\SocialLinkLibrary::css()."\n".\App\Support\WhatsAppWidget::css()."\n".\App\Support\SalesWidget::css()."\n".\App\Support\StatsWidget::css()."\n".\App\Support\GalleryCardsWidget::css()."\n".\App\Support\DirectivaWidget::css()."\n".\App\Support\ClassicHomeWidget::css()."\n".\App\Support\ParallaxWidget::css()."\n".\App\Support\CarouselWidget::css()."\n".\App\Support\FormWidget::css()."\n".\App\Support\DynamicSectionsWidget::css()."\n".\App\Support\VideoWidget::css()."\n".\App\Support\PageTheme::css(
                request()->route('template') instanceof \App\Models\PageTemplate ? request()->route('template')->settings['theme_css'] ?? null : null,
                request()->route('template') instanceof \App\Models\PageTemplate ? request()->route('template')->settings['fonts'] ?? null : null,
            )."\nhtml.dark body{background-color:#09090b;color:#fafafa;}");
            const capasParallax = @json([
                'soft' => \App\Support\ParallaxWidget::layer('soft'),
                'strong' => \App\Support\ParallaxWidget::layer('strong'),
            ]);

            /*
             * Cada plantilla puede usar tipografías distintas a las del sitio.
             * Se cargan también dentro del iframe para que el lienzo muestre el
             * mismo texto que la página publicada.
             */
            const fuentesCanvas = @json((string) \Illuminate\Support\Facades\Vite::fonts(
                request()->route('template') instanceof \App\Models\PageTemplate
                    ? \App\Support\TemplateFonts::resolve(request()->route('template')->settings['fonts'] ?? null)
                    : \App\Support\TemplateFonts::DEFAULTS
            ));

            /*
             * Las páginas creadas desde una plantilla empiezan sin proyecto de
             * GrapesJS y guardan el diseño en la columna `css`. La cargamos en
             * el compositor para que el lienzo la muestre y para que vuelva a
             * guardarse al pulsar Guardar.
             */

            const inyectarFuentes = () => {
                const doc = editor.Canvas.getDocument();

                if (!doc || !fuentesCanvas || doc.documentElement.dataset.cfFonts === 'yes') {
                    return;
                }

                doc.head.insertAdjacentHTML('beforeend', fuentesCanvas);
                doc.documentElement.dataset.cfFonts = 'yes';
            };

            const opcionesParallax = @json(\App\Support\ParallaxWidget::options());
            const categoriaAnimacion = { id: 'animacion', label: 'Animación', open: true };
            const categoriaParallax = { id: 'parallax', label: 'Fondo parallax', open: true };
            const categoriaEquipo = { id: 'equipo', label: 'Miembro del equipo', open: true };
            const categoriaSitio = { id: 'boton-arriba', label: 'Botón volver arriba', open: true };
            const categoriaRedes = { id: 'redes-sociales', label: 'Redes sociales', open: true };
            const categoriaProducto = { id: 'producto', label: 'Producto', open: true };
            const categoriaCarrusel = { id: 'carrusel', label: 'Carrusel', open: true };
            const categoriaDiapositiva = { id: 'diapositiva', label: 'Diapositiva', open: true };
            const categoriaFormulario = { id: 'formulario', label: 'Formulario', open: true };
            const categoriaContador = { id: 'contador', label: 'Contador', open: true };
            const redesSociales = @json(\App\Support\SocialLinkLibrary::networks());

            const ajustesBotonArriba = [
                {
                    type: 'select',
                    name: 'data-top-enabled',
                    label: 'Mostrar botón',
                    options: [
                        { id: 'yes', label: 'Sí' },
                        { id: 'no', label: 'No' },
                    ],
                    category: categoriaSitio,
                },
            ];

            const buscarBotonArriba = (componente) => {
                let actual = componente;

                while (actual && actual.get) {
                    const attrs = actual.getAttributes?.() || {};
                    const id = String(attrs.id || '');

                    if (attrs['data-top-button'] !== undefined || id.startsWith('top-button')) {
                        return actual;
                    }

                    actual = actual.parent?.();
                }

                return null;
            };

            const buscarVideo = (componente) => {
                let actual = componente;

                while (actual && actual.get) {
                    const attrs = actual.getAttributes?.() || {};

                    if (attrs['data-cf-video'] !== undefined) {
                        return actual;
                    }

                    actual = actual.parent?.();
                }

                return null;
            };

            const buscarWhatsapp = (componente) => {
                let actual = componente;

                while (actual && actual.get) {
                    const attrs = actual.getAttributes?.() || {};

                    if (attrs['data-cf-whatsapp'] !== undefined) {
                        return actual;
                    }

                    actual = actual.parent?.();
                }

                return null;
            };

            const buscarProducto = (componente) => {
                let actual = componente;

                while (actual && actual.get) {
                    const attrs = actual.getAttributes?.() || {};

                    if (attrs['data-cf-product'] !== undefined) {
                        return actual;
                    }

                    actual = actual.parent?.();
                }

                return null;
            };

            const buscarCarrusel = (componente) => {
                let actual = componente;

                while (actual && actual.get) {
                    const attrs = actual.getAttributes?.() || {};

                    if (attrs['data-cf-carousel'] !== undefined) {
                        return actual;
                    }

                    actual = actual.parent?.();
                }

                return null;
            };

            const buscarDiapositiva = (componente) => {
                let actual = componente;

                while (actual && actual.get) {
                    const attrs = actual.getAttributes?.() || {};

                    if (attrs['data-carousel-slide'] !== undefined) {
                        return actual;
                    }

                    actual = actual.parent?.();
                }

                return null;
            };

            const MAX_DIAPOSITIVAS = @json(\App\Support\CarouselWidget::MAX_SLIDES);

            const avisarEditor = (texto) => {
                document.getElementById('cf-editor-aviso')?.remove();

                const aviso = document.createElement('div');
                aviso.id = 'cf-editor-aviso';
                aviso.setAttribute('role', 'status');
                aviso.textContent = texto;
                aviso.style.cssText = 'position:fixed;bottom:1.25rem;left:50%;transform:translateX(-50%);z-index:9999;padding:10px 16px;border-radius:8px;background:#18181b;color:#fff;font:600 13px/1.4 ui-sans-serif,system-ui,sans-serif;box-shadow:0 10px 30px rgba(0,0,0,.28);';
                document.body.appendChild(aviso);
                window.setTimeout(() => aviso.remove(), 3200);
            };

            const agregarDiapositivaCarrusel = (raiz) => {
                const diapositivas = raiz.find('[data-carousel-slide]');
                const modelo = diapositivas[diapositivas.length - 1];

                if (diapositivas.length >= MAX_DIAPOSITIVAS || !modelo) {
                    return false;
                }

                const nueva = modelo.clone();
                const semilla = `carrusel-${Date.now()}`;

                nueva.addAttributes({
                    'data-slide-image': `https://picsum.photos/seed/${semilla}/1600/900`,
                    'data-slide-title': 'Nueva diapositiva',
                    'data-slide-text': 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                    'data-slide-cta-text': 'Ver más',
                    'data-slide-cta-url': '',
                });

                raiz.append(nueva);
                actualizarDiapositiva(nueva);

                return true;
            };

            const crearBotonDiapositiva = (texto, accion, indice, elInput, raiz, deshabilitado = false) => {
                const boton = document.createElement('button');
                boton.type = 'button';
                boton.textContent = texto;
                boton.disabled = deshabilitado;
                boton.style.cssText = [
                    'padding:4px 8px',
                    'border:1px solid #d4d4d8',
                    'border-radius:5px',
                    'background:#fff',
                    'color:#3f3f46',
                    'font-size:11px',
                    'line-height:1.4',
                    deshabilitado ? 'opacity:.45;cursor:not-allowed' : 'cursor:pointer',
                ].join(';');

                if (accion === 'agregar') {
                    boton.style.width = '100%';
                    boton.style.fontWeight = '600';
                }

                boton.addEventListener('click', (event) => {
                    event.preventDefault();
                    event.stopPropagation();

                    if (deshabilitado) {
                        return;
                    }

                    if (accion === 'editar') {
                        const diapositiva = raiz.find('[data-carousel-slide]')[indice];

                        if (diapositiva) {
                            editor.select(diapositiva);
                        }

                        return;
                    }

                    if (accion === 'quitar') {
                        const diapositivas = raiz.find('[data-carousel-slide]');

                        if (diapositivas.length <= 1) {
                            avisarEditor('El carrusel necesita al menos una imagen.');

                            return;
                        }

                        diapositivas[indice]?.remove();
                        actualizarListaDiapositivas(elInput, raiz);

                        return;
                    }

                    if (accion === 'agregar') {
                        if (!agregarDiapositivaCarrusel(raiz)) {
                            avisarEditor(`Máximo ${MAX_DIAPOSITIVAS} imágenes por carrusel.`);

                            return;
                        }

                        actualizarListaDiapositivas(elInput, raiz);
                    }
                });

                return boton;
            };

            const actualizarListaDiapositivas = (elInput, raiz) => {
                if (!elInput || !raiz) {
                    return;
                }

                elInput.innerHTML = '';

                const diapositivas = raiz.find('[data-carousel-slide]');

                const titulo = document.createElement('p');
                titulo.textContent = `Imágenes (${diapositivas.length}/${MAX_DIAPOSITIVAS})`;
                titulo.style.cssText = 'margin:0;color:#3f3f46;font-size:12px;font-weight:700;';
                elInput.appendChild(titulo);

                const ayuda = document.createElement('p');
                ayuda.textContent = 'Pulsa Editar para cambiar la imagen, el título, el texto y el botón de cada diapositiva.';
                ayuda.style.cssText = 'margin:0 0 2px;color:#71717a;font-size:11px;line-height:1.4;';
                elInput.appendChild(ayuda);

                diapositivas.forEach((diapositiva, indice) => {
                    const attrs = diapositiva.getAttributes?.() || {};

                    const fila = document.createElement('div');
                    fila.style.cssText = 'display:grid;grid-template-columns:44px minmax(0,1fr) auto auto;align-items:center;gap:6px;';

                    const miniatura = document.createElement('img');
                    miniatura.src = attrs['data-slide-image'] || '';
                    miniatura.alt = '';
                    miniatura.loading = 'lazy';
                    miniatura.style.cssText = 'display:block;width:44px;height:30px;object-fit:cover;border-radius:4px;background:#e4e4e7;';

                    const nombre = document.createElement('span');
                    nombre.textContent = `Diapositiva ${indice + 1}`;
                    nombre.style.cssText = 'font-size:12px;color:#3f3f46;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;';

                    [miniatura, nombre].forEach((elemento) => {
                        elemento.style.cursor = 'pointer';
                        elemento.title = 'Editar esta diapositiva';
                        elemento.addEventListener('click', () => {
                            const actual = raiz.find('[data-carousel-slide]')[indice];

                            if (actual) {
                                editor.select(actual);
                            }
                        });
                    });

                    fila.append(
                        miniatura,
                        nombre,
                        crearBotonDiapositiva('Editar', 'editar', indice, elInput, raiz),
                        crearBotonDiapositiva('×', 'quitar', indice, elInput, raiz, diapositivas.length <= 1),
                    );
                    elInput.appendChild(fila);
                });

                elInput.appendChild(crearBotonDiapositiva(
                    `Añadir imagen (${diapositivas.length}/${MAX_DIAPOSITIVAS})`,
                    'agregar',
                    -1,
                    elInput,
                    raiz,
                    diapositivas.length >= MAX_DIAPOSITIVAS,
                ));
            };

            const buscarFormulario = (componente) => {
                let actual = componente;

                while (actual && actual.get) {
                    const attrs = actual.getAttributes?.() || {};

                    if (attrs['data-cf-form'] !== undefined) {
                        return actual;
                    }

                    actual = actual.parent?.();
                }

                return null;
            };

            const buscarContador = (componente) => {
                let actual = componente;

                while (actual && actual.get) {
                    const attrs = actual.getAttributes?.() || {};

                    if (attrs['data-counter'] !== undefined) {
                        return actual;
                    }

                    actual = actual.parent?.();
                }

                return null;
            };

            const buscarTarjetaDirectiva = (componente) => {
                let actual = componente;

                while (actual && actual.get) {
                    const attrs = actual.getAttributes?.() || {};

                    if (attrs['data-directiva-card'] !== undefined) {
                        return actual;
                    }

                    if (attrs['data-cf-directiva'] !== undefined) {
                        return null;
                    }

                    actual = actual.parent?.();
                }

                return null;
            };

            const leerTextoDirectiva = (tarjeta, marca) => {
                const objetivo = tarjeta?.find(`[${marca}]`)[0];

                return (objetivo?.components?.().models || [])
                    .map((modelo) => modelo.get?.('content') ?? '')
                    .join('');
            };

            const actualizarAltDirectiva = (tarjeta) => {
                const imagen = tarjeta?.find('[data-directiva-avatar] img')[0];

                if (!imagen) {
                    return;
                }

                const nombre = leerTextoDirectiva(tarjeta, 'data-directiva-name').trim();
                const puesto = leerTextoDirectiva(tarjeta, 'data-directiva-role').trim();
                const alt = [nombre, puesto].filter(Boolean).join(', ');

                imagen.addAttributes({ alt: alt || 'Fotografía del miembro del equipo' });
            };

            const normalizarEnlaceDirectiva = (valor) => {
                const url = String(valor || '').trim();

                if (url === '') {
                    return '';
                }

                if (/^(https?:|mailto:|tel:|\/|#)/i.test(url)) {
                    return url;
                }

                return `https://${url}`;
            };

            const MARCAS_WHATSAPP = [
                'data-wa-panel',
                'data-wa-toggle',
                'data-wa-close',
                'data-wa-form',
                'data-wa-input',
                'data-wa-title-text',
                'data-wa-message-text',
            ];

            const normalizarMarcasWhatsapp = (raiz) => {
                [raiz, ...(raiz.find('*') || [])].forEach((componente) => {
                    const attrs = componente.getAttributes?.() || {};

                    MARCAS_WHATSAPP.forEach((marca) => {
                        if (
                            Object.prototype.hasOwnProperty.call(attrs, marca)
                            && (attrs[marca] === null || attrs[marca] === '')
                        ) {
                            componente.addAttributes({ [marca]: 'yes' });
                        }
                    });
                });

                const boton = raiz.find('[data-wa-toggle]')[0];
                const contenedorBoton = boton?.parent?.();
                const attrsContenedor = contenedorBoton?.getAttributes?.() || {};

                if (
                    contenedorBoton
                    && !Object.prototype.hasOwnProperty.call(attrsContenedor, 'data-wa-button')
                ) {
                    contenedorBoton.addAttributes({ 'data-wa-button': 'yes' });
                }
            };

            editor.Traits.addType('cf-top-enabled', {
                createInput() {
                    const select = document.createElement('select');
                    select.innerHTML = '<option value="yes">Sí</option><option value="no">No</option>';

                    return select;
                },
                onEvent({ elInput, component }) {
                    buscarBotonArriba(component)?.addAttributes({ 'data-top-enabled': elInput.value });
                },
                onUpdate({ elInput, component }) {
                    elInput.value = buscarBotonArriba(component)?.getAttributes()['data-top-enabled'] || 'yes';
                },
            });

            const ajustesBotonArribaImagen = [
                {
                    type: 'cf-top-enabled',
                    name: 'cf-top-enabled',
                    label: 'Mostrar botón',
                    category: categoriaSitio,
                },
            ];

            const buscarRedSocial = (contenedor, red) => {
                const hijos = contenedor?.components ? (contenedor.components().models || []) : [];

                return hijos.find((hijo) => {
                    const attrs = hijo.getAttributes?.() || {};

                    return attrs['data-cf-social-network'] === red;
                }) || null;
            };

            editor.Traits.addType('cf-social', {
                noLabel: true,
                createInput() {
                    const contenedor = document.createElement('div');
                    contenedor.style.cssText = 'display:grid;gap:8px;';

                    const titulo = document.createElement('p');
                    titulo.textContent = 'Marca las redes y edita sus enlaces';
                    titulo.style.cssText = 'margin:0;color:#71717a;font-size:11px;line-height:1.4;';
                    contenedor.appendChild(titulo);

                    redesSociales.forEach((red) => {
                        const fila = document.createElement('div');
                        fila.style.cssText = 'display:grid;grid-template-columns:auto 1fr;align-items:center;gap:4px 8px;';

                        const etiqueta = document.createElement('label');
                        etiqueta.style.cssText = 'display:flex;align-items:center;gap:6px;color:#3f3f46;font-size:12px;font-weight:600;white-space:nowrap;';

                        const checkbox = document.createElement('input');
                        checkbox.type = 'checkbox';
                        checkbox.dataset.network = red.id;
                        checkbox.checked = true;

                        etiqueta.appendChild(checkbox);
                        etiqueta.appendChild(document.createTextNode(red.label));

                        const url = document.createElement('input');
                        url.type = 'url';
                        url.dataset.network = red.id;
                        url.placeholder = red.url;
                        url.style.cssText = 'width:100%;min-width:0;padding:4px 6px;border:1px solid #d4d4d8;border-radius:5px;font-size:11px;';

                        fila.appendChild(etiqueta);
                        fila.appendChild(url);
                        contenedor.appendChild(fila);
                    });

                    return contenedor;
                },
                onEvent({ elInput, component, event }) {
                    const objetivo = event.target;
                    const red = objetivo?.dataset?.network;

                    if (!red) {
                        return;
                    }

                    const hijo = buscarRedSocial(component, red);

                    if (!hijo) {
                        return;
                    }

                    if (objetivo.type === 'checkbox') {
                        if (objetivo.checked) {
                            hijo.removeAttributes('data-cf-social-off');
                        } else {
                            hijo.addAttributes({ 'data-cf-social-off': 'yes' });
                        }

                        return;
                    }

                    hijo.addAttributes({ href: objetivo.value || '#' });
                },
                onUpdate({ elInput, component }) {
                    redesSociales.forEach((red) => {
                        const hijo = buscarRedSocial(component, red.id);
                        const attrs = hijo?.getAttributes?.() || {};
                        const oculto = attrs['data-cf-social-off'] !== undefined && attrs['data-cf-social-off'] !== null;
                        const checkbox = elInput.querySelector(`input[type="checkbox"][data-network="${red.id}"]`);
                        const url = elInput.querySelector(`input[type="url"][data-network="${red.id}"]`);

                        if (checkbox) {
                            checkbox.checked = hijo !== null && !oculto;
                        }

                        if (url) {
                            url.value = attrs.href || '';
                        }
                    });
                },
            });

            const ajustesRedes = [
                {
                    type: 'cf-social',
                    name: 'cf-social-config',
                    label: 'Redes sociales',
                    changeProp: 1,
                    category: categoriaRedes,
                },
            ];

            const categoriaWhatsapp = { id: 'whatsapp', label: 'WhatsApp', open: true };

            const atributoWhatsapp = (trait) => trait.get('atributo') || trait.get('name');

            editor.Traits.addType('cf-wa-field', {
                createInput({ trait }) {
                    const input = document.createElement('input');
                    input.type = 'text';
                    input.placeholder = trait.get('placeholder') || '';
                    input.style.cssText = 'width:100%;padding:6px 8px;border:1px solid #d4d4d8;border-radius:5px;font-size:12px;';

                    return input;
                },
                onEvent({ elInput, component, trait }) {
                    const whatsapp = buscarWhatsapp(component);

                    if (whatsapp) {
                        whatsapp.addAttributes({ [atributoWhatsapp(trait)]: elInput.value });
                    }
                },
                onUpdate({ elInput, component, trait }) {
                    const attrs = buscarWhatsapp(component)?.getAttributes() || {};
                    elInput.value = attrs[atributoWhatsapp(trait)] || '';
                },
            });

            editor.Traits.addType('cf-wa-message', {
                createInput() {
                    const textarea = document.createElement('textarea');
                    textarea.rows = 3;
                    textarea.style.cssText = 'width:100%;min-height:4.5rem;resize:vertical;padding:6px 8px;border:1px solid #d4d4d8;border-radius:5px;font-size:12px;line-height:1.5;';

                    return textarea;
                },
                onEvent({ elInput, component }) {
                    buscarWhatsapp(component)?.addAttributes({ 'data-wa-message': elInput.value });
                },
                onUpdate({ elInput, component }) {
                    const attrs = buscarWhatsapp(component)?.getAttributes() || {};
                    elInput.value = attrs['data-wa-message'] || '';
                },
            });

            const ajustesWhatsapp = [
                {
                    type: 'cf-wa-field',
                    name: 'data-wa-number',
                    atributo: 'data-wa-number',
                    label: 'Número con código de país',
                    placeholder: '56998970550',
                    category: categoriaWhatsapp,
                },
                {
                    type: 'cf-wa-field',
                    name: 'data-wa-title',
                    atributo: 'data-wa-title',
                    label: 'Título del chat',
                    category: categoriaWhatsapp,
                },
                {
                    type: 'cf-wa-message',
                    name: 'data-wa-message',
                    label: 'Mensaje',
                    category: categoriaWhatsapp,
                },
            ];

            const atributoProducto = (trait) => trait.get('atributo') || trait.get('name');

            editor.Traits.addType('cf-sale-field', {
                createInput({ trait }) {
                    const input = document.createElement('input');
                    input.type = trait.get('inputType') || 'text';
                    input.placeholder = trait.get('placeholder') || '';
                    input.style.cssText = 'width:100%;padding:6px 8px;border:1px solid #d4d4d8;border-radius:5px;font-size:12px;';

                    return input;
                },
                onEvent({ elInput, component, trait }) {
                    const producto = buscarProducto(component);

                    if (producto) {
                        producto.addAttributes({ [atributoProducto(trait)]: elInput.value });
                    }
                },
                onUpdate({ elInput, component, trait }) {
                    const attrs = buscarProducto(component)?.getAttributes() || {};
                    elInput.value = attrs[atributoProducto(trait)] || '';
                },
            });

            editor.Traits.addType('cf-sale-area', {
                createInput({ trait }) {
                    const textarea = document.createElement('textarea');
                    textarea.rows = 3;
                    textarea.placeholder = trait.get('placeholder') || '';
                    textarea.style.cssText = 'width:100%;min-height:4.5rem;resize:vertical;padding:6px 8px;border:1px solid #d4d4d8;border-radius:5px;font-size:12px;line-height:1.5;';

                    return textarea;
                },
                onEvent({ elInput, component, trait }) {
                    buscarProducto(component)?.addAttributes({ [atributoProducto(trait)]: elInput.value });
                },
                onUpdate({ elInput, component, trait }) {
                    const attrs = buscarProducto(component)?.getAttributes() || {};
                    elInput.value = attrs[atributoProducto(trait)] || '';
                },
            });

            editor.Traits.addType('cf-sale-toggle', {
                createInput() {
                    const select = document.createElement('select');
                    select.innerHTML = '<option value="yes">Sí</option><option value="no">No</option>';

                    return select;
                },
                onEvent({ elInput, component, trait }) {
                    buscarProducto(component)?.addAttributes({ [atributoProducto(trait)]: elInput.value });
                },
                onUpdate({ elInput, component, trait }) {
                    const attrs = buscarProducto(component)?.getAttributes() || {};
                    elInput.value = attrs[atributoProducto(trait)] || 'yes';
                },
            });

            const ajustesProducto = [
                {
                    type: 'cf-sale-field',
                    name: 'data-product-name',
                    atributo: 'data-product-name',
                    label: 'Nombre del producto',
                    category: categoriaProducto,
                },
                {
                    type: 'cf-sale-area',
                    name: 'data-product-description',
                    atributo: 'data-product-description',
                    label: 'Descripción',
                    category: categoriaProducto,
                },
                {
                    type: 'cf-sale-field',
                    name: 'data-product-price',
                    atributo: 'data-product-price',
                    label: 'Precio',
                    placeholder: '$25.000',
                    category: categoriaProducto,
                },
                {
                    type: 'cf-sale-field',
                    name: 'data-product-currency',
                    atributo: 'data-product-currency',
                    label: 'Moneda',
                    placeholder: 'CLP',
                    category: categoriaProducto,
                },
                {
                    type: 'cf-sale-field',
                    name: 'data-product-price-note',
                    atributo: 'data-product-price-note',
                    label: 'Nota del precio',
                    placeholder: 'pago único',
                    category: categoriaProducto,
                },
                {
                    type: 'cf-sale-field',
                    name: 'data-product-old-price',
                    atributo: 'data-product-old-price',
                    label: 'Precio anterior',
                    placeholder: '$30.000',
                    category: categoriaProducto,
                },
                {
                    type: 'cf-sale-field',
                    name: 'data-product-badge-text',
                    atributo: 'data-product-badge-text',
                    label: 'Etiqueta',
                    placeholder: 'Oferta',
                    category: categoriaProducto,
                },
                {
                    type: 'cf-sale-toggle',
                    name: 'data-product-badge-enabled',
                    atributo: 'data-product-badge-enabled',
                    label: 'Mostrar etiqueta',
                    category: categoriaProducto,
                },
                {
                    type: 'cf-sale-field',
                    name: 'data-product-cta-text',
                    atributo: 'data-product-cta-text',
                    label: 'Texto del botón',
                    placeholder: 'Comprar ahora',
                    category: categoriaProducto,
                },
                {
                    type: 'cf-sale-field',
                    name: 'data-product-url',
                    atributo: 'data-product-url',
                    label: 'Enlace de pago',
                    placeholder: 'https://...',
                    inputType: 'url',
                    category: categoriaProducto,
                },
                {
                    type: 'cf-sale-field',
                    name: 'data-product-image-url',
                    atributo: 'data-product-image-url',
                    label: 'URL de la imagen',
                    placeholder: 'https://picsum.photos/seed/cielo/800/600',
                    category: categoriaProducto,
                },
            ];

            const atributoDiapositiva = (trait) => trait.get('atributo') || trait.get('name');

            editor.Traits.addType('cf-slide-field', {
                createInput({ trait }) {
                    const input = document.createElement('input');
                    input.type = trait.get('inputType') || 'text';
                    input.placeholder = trait.get('placeholder') || '';
                    input.style.cssText = 'width:100%;padding:6px 8px;border:1px solid #d4d4d8;border-radius:5px;font-size:12px;';

                    return input;
                },
                onEvent({ elInput, component, trait }) {
                    const diapositiva = buscarDiapositiva(component);

                    if (diapositiva) {
                        diapositiva.addAttributes({ [atributoDiapositiva(trait)]: elInput.value });
                    }
                },
                onUpdate({ elInput, component, trait }) {
                    const attrs = buscarDiapositiva(component)?.getAttributes() || {};
                    elInput.value = attrs[atributoDiapositiva(trait)] || '';
                },
            });

            editor.Traits.addType('cf-slide-area', {
                createInput({ trait }) {
                    const textarea = document.createElement('textarea');
                    textarea.rows = 3;
                    textarea.placeholder = trait.get('placeholder') || '';
                    textarea.style.cssText = 'width:100%;min-height:4rem;resize:vertical;padding:6px 8px;border:1px solid #d4d4d8;border-radius:5px;font-size:12px;line-height:1.5;';

                    return textarea;
                },
                onEvent({ elInput, component, trait }) {
                    buscarDiapositiva(component)?.addAttributes({ [atributoDiapositiva(trait)]: elInput.value });
                },
                onUpdate({ elInput, component, trait }) {
                    const attrs = buscarDiapositiva(component)?.getAttributes() || {};
                    elInput.value = attrs[atributoDiapositiva(trait)] || '';
                },
            });

            editor.Traits.addType('cf-carousel-slides', {
                noLabel: true,
                createInput() {
                    const contenedor = document.createElement('div');
                    contenedor.setAttribute('data-carousel-slides-list', 'yes');
                    contenedor.style.cssText = 'display:grid;gap:6px;';

                    return contenedor;
                },
                onUpdate({ elInput, component }) {
                    actualizarListaDiapositivas(elInput, buscarCarrusel(component));
                },
            });

            const ajustesCarrusel = [
                {
                    type: 'cf-carousel-slides',
                    name: 'cf-carousel-slides',
                    label: 'Imágenes del carrusel',
                    changeProp: 1,
                    category: categoriaCarrusel,
                },
                {
                    type: 'select',
                    name: 'data-carousel-autoplay',
                    label: 'Reproducción automática',
                    options: [
                        { id: 'yes', label: 'Sí' },
                        { id: 'no', label: 'No' },
                    ],
                    category: categoriaCarrusel,
                },
                {
                    type: 'number',
                    name: 'data-carousel-interval',
                    label: 'Intervalo (ms)',
                    min: 1500,
                    max: 30000,
                    step: 500,
                    placeholder: '5000',
                    category: categoriaCarrusel,
                },
                {
                    type: 'select',
                    name: 'data-carousel-arrows',
                    label: 'Mostrar flechas',
                    options: [
                        { id: 'yes', label: 'Sí' },
                        { id: 'no', label: 'No' },
                    ],
                    category: categoriaCarrusel,
                },
                {
                    type: 'select',
                    name: 'data-carousel-dots',
                    label: 'Mostrar puntos',
                    options: [
                        { id: 'yes', label: 'Sí' },
                        { id: 'no', label: 'No' },
                    ],
                    category: categoriaCarrusel,
                },
            ];

            const ajustesDiapositiva = [
                {
                    type: 'cf-slide-field',
                    name: 'data-slide-image',
                    atributo: 'data-slide-image',
                    label: 'Imagen',
                    placeholder: 'https://picsum.photos/seed/cielo/800/600',
                    category: categoriaDiapositiva,
                },
                {
                    type: 'cf-slide-field',
                    name: 'data-slide-title',
                    atributo: 'data-slide-title',
                    label: 'Título',
                    category: categoriaDiapositiva,
                },
                {
                    type: 'cf-slide-area',
                    name: 'data-slide-text',
                    atributo: 'data-slide-text',
                    label: 'Texto',
                    category: categoriaDiapositiva,
                },
                {
                    type: 'cf-slide-field',
                    name: 'data-slide-cta-text',
                    atributo: 'data-slide-cta-text',
                    label: 'Texto del botón',
                    placeholder: 'Ver más',
                    category: categoriaDiapositiva,
                },
                {
                    type: 'cf-slide-field',
                    name: 'data-slide-cta-url',
                    atributo: 'data-slide-cta-url',
                    label: 'Enlace del botón',
                    placeholder: '#contacto',
                    category: categoriaDiapositiva,
                },
            ];

            const atributoFormulario = (trait) => trait.get('atributo') || trait.get('name');

            editor.Traits.addType('cf-form-field', {
                createInput({ trait }) {
                    const input = document.createElement('input');
                    input.type = 'text';
                    input.placeholder = trait.get('placeholder') || '';
                    input.style.cssText = 'width:100%;padding:6px 8px;border:1px solid #d4d4d8;border-radius:5px;font-size:12px;';

                    return input;
                },
                onEvent({ elInput, component, trait }) {
                    buscarFormulario(component)?.addAttributes({ [atributoFormulario(trait)]: elInput.value });
                },
                onUpdate({ elInput, component, trait }) {
                    const attrs = buscarFormulario(component)?.getAttributes() || {};
                    elInput.value = attrs[atributoFormulario(trait)] || '';
                },
            });

            editor.Traits.addType('cf-form-area', {
                createInput({ trait }) {
                    const textarea = document.createElement('textarea');
                    textarea.rows = 3;
                    textarea.placeholder = trait.get('placeholder') || '';
                    textarea.style.cssText = 'width:100%;min-height:4rem;resize:vertical;padding:6px 8px;border:1px solid #d4d4d8;border-radius:5px;font-size:12px;line-height:1.5;';

                    return textarea;
                },
                onEvent({ elInput, component, trait }) {
                    buscarFormulario(component)?.addAttributes({ [atributoFormulario(trait)]: elInput.value });
                },
                onUpdate({ elInput, component, trait }) {
                    const attrs = buscarFormulario(component)?.getAttributes() || {};
                    elInput.value = attrs[atributoFormulario(trait)] || '';
                },
            });

            const atributoContador = (trait) => trait.get('atributo') || trait.get('name');

            editor.Traits.addType('cf-counter-field', {
                createInput({ trait }) {
                    const input = document.createElement('input');
                    input.type = 'text';
                    input.placeholder = trait.get('placeholder') || '';
                    input.style.cssText = 'width:100%;padding:6px 8px;border:1px solid #d4d4d8;border-radius:5px;font-size:12px;';

                    return input;
                },
                onEvent({ elInput, component, trait }) {
                    buscarContador(component)?.addAttributes({ [atributoContador(trait)]: elInput.value });
                },
                onUpdate({ elInput, component, trait }) {
                    const attrs = buscarContador(component)?.getAttributes() || {};
                    elInput.value = attrs[atributoContador(trait)] || '';
                },
            });

            const ajustesContador = [
                {
                    type: 'cf-counter-field',
                    name: 'data-counter-prefix',
                    atributo: 'data-counter-prefix',
                    label: 'Prefijo',
                    placeholder: '+',
                    category: categoriaContador,
                },
                {
                    type: 'cf-counter-field',
                    name: 'data-counter-value',
                    atributo: 'data-counter-value',
                    label: 'Número',
                    placeholder: '120',
                    category: categoriaContador,
                },
                {
                    type: 'cf-counter-field',
                    name: 'data-counter-suffix',
                    atributo: 'data-counter-suffix',
                    label: 'Sufijo',
                    placeholder: '%',
                    category: categoriaContador,
                },
                {
                    type: 'cf-counter-field',
                    name: 'data-counter-label',
                    atributo: 'data-counter-label',
                    label: 'Etiqueta',
                    placeholder: 'Jugadores',
                    category: categoriaContador,
                },
            ];

            const ajustesFormulario = [
                {
                    type: 'cf-form-field',
                    name: 'data-form-title',
                    atributo: 'data-form-title',
                    label: 'Título',
                    category: categoriaFormulario,
                },
                {
                    type: 'cf-form-area',
                    name: 'data-form-description',
                    atributo: 'data-form-description',
                    label: 'Descripción',
                    category: categoriaFormulario,
                },
                {
                    type: 'cf-form-field',
                    name: 'data-form-label-nombre',
                    atributo: 'data-form-label-nombre',
                    label: 'Etiqueta: nombre',
                    category: categoriaFormulario,
                },
                {
                    type: 'cf-form-field',
                    name: 'data-form-label-email',
                    atributo: 'data-form-label-email',
                    label: 'Etiqueta: correo',
                    category: categoriaFormulario,
                },
                {
                    type: 'cf-form-field',
                    name: 'data-form-label-telefono',
                    atributo: 'data-form-label-telefono',
                    label: 'Etiqueta: teléfono',
                    category: categoriaFormulario,
                },
                {
                    type: 'cf-form-field',
                    name: 'data-form-label-mensaje',
                    atributo: 'data-form-label-mensaje',
                    label: 'Etiqueta: mensaje',
                    category: categoriaFormulario,
                },
                {
                    type: 'select',
                    name: 'data-form-telefono',
                    label: 'Mostrar teléfono',
                    options: [
                        { id: 'yes', label: 'Sí' },
                        { id: 'no', label: 'No' },
                    ],
                    category: categoriaFormulario,
                },
                {
                    type: 'cf-form-field',
                    name: 'data-form-button',
                    atributo: 'data-form-button',
                    label: 'Texto del botón',
                    category: categoriaFormulario,
                },
                {
                    type: 'cf-form-field',
                    name: 'data-form-success',
                    atributo: 'data-form-success',
                    label: 'Mensaje de éxito',
                    category: categoriaFormulario,
                },
            ];

            editor.Traits.addType('cf-parallax', {
                createInput() {
                    const select = document.createElement('select');
                    select.innerHTML = opcionesParallax
                        .map((opcion) => `<option value="${opcion.id}">${opcion.label}</option>`)
                        .join('');

                    return select;
                },
                onEvent({ elInput, component }) {
                    component.addAttributes({ 'data-cf-parallax': elInput.value });
                },
                onUpdate({ elInput, component }) {
                    const valor = component.getAttributes()['data-cf-parallax'];
                    elInput.value = ['soft', 'strong'].includes(valor) ? valor : 'none';
                },
            });

            const ajustesParallax = [
                {
                    type: 'cf-parallax',
                    name: 'data-cf-parallax',
                    label: 'Fondo parallax',
                    category: categoriaParallax,
                },
            ];

            editor.Traits.addType('cf-directiva-text', {
                createInput({ trait }) {
                    const input = document.createElement('input');
                    input.type = 'text';
                    input.placeholder = trait.get('placeholder') || '';

                    return input;
                },
                onEvent({ elInput, component, trait }) {
                    const tarjeta = buscarTarjetaDirectiva(component);
                    const marca = trait.get('marca');
                    const objetivo = marca ? tarjeta?.find(`[${marca}]`)[0] : null;

                    if (!objetivo) {
                        return;
                    }

                    objetivo.components([{ type: 'textnode', content: elInput.value }]);
                    actualizarAltDirectiva(tarjeta);
                },
                onUpdate({ elInput, component, trait }) {
                    const tarjeta = buscarTarjetaDirectiva(component);
                    const marca = trait.get('marca');
                    elInput.value = marca ? leerTextoDirectiva(tarjeta, marca) : '';
                },
            });

            editor.Traits.addType('cf-directiva-photo', {
                createInput({ trait }) {
                    const input = document.createElement('input');
                    input.type = 'text';
                    input.placeholder = trait.get('placeholder') || '/img/equipo/avatar.svg';

                    return input;
                },
                onEvent({ elInput, component }) {
                    const tarjeta = buscarTarjetaDirectiva(component);
                    const imagen = tarjeta?.find('[data-directiva-avatar] img')[0];

                    if (!imagen) {
                        return;
                    }

                    imagen.addAttributes({ src: elInput.value.trim() || '/img/equipo/avatar.svg' });
                },
                onUpdate({ elInput, component }) {
                    const tarjeta = buscarTarjetaDirectiva(component);
                    const imagen = tarjeta?.find('[data-directiva-avatar] img')[0];
                    elInput.value = imagen?.getAttributes().src || '';
                },
            });

            editor.Traits.addType('cf-directiva-url', {
                createInput({ trait }) {
                    const input = document.createElement('input');
                    input.type = 'text';
                    input.placeholder = trait.get('placeholder') || 'https://...';

                    return input;
                },
                onEvent({ elInput, component, trait }) {
                    const tarjeta = buscarTarjetaDirectiva(component);
                    const atributo = trait.get('atributo');
                    const enlace = atributo
                        ? tarjeta?.find(`[data-directiva-link="${atributo}"]`)[0]
                        : null;

                    if (!enlace) {
                        return;
                    }

                    const url = normalizarEnlaceDirectiva(elInput.value);

                    if (url === '') {
                        enlace.addAttributes({ href: '', 'data-directiva-link-off': 'yes' });
                    } else {
                        enlace.addAttributes({ href: url });
                        enlace.removeAttributes('data-directiva-link-off');
                    }
                },
                onUpdate({ elInput, component, trait }) {
                    const tarjeta = buscarTarjetaDirectiva(component);
                    const atributo = trait.get('atributo');
                    const enlace = atributo
                        ? tarjeta?.find(`[data-directiva-link="${atributo}"]`)[0]
                        : null;
                    elInput.value = enlace?.getAttributes().href || '';
                },
            });

            const ajustesDirectiva = [
                {
                    type: 'cf-directiva-text',
                    name: 'cf-directiva-name',
                    changeProp: true,
                    marca: 'data-directiva-name',
                    label: 'Nombre',
                    placeholder: 'Nombre y apellido',
                    category: categoriaEquipo,
                },
                {
                    type: 'cf-directiva-text',
                    name: 'cf-directiva-role',
                    changeProp: true,
                    marca: 'data-directiva-role',
                    label: 'Puesto',
                    placeholder: 'Entrenador, directora, etc.',
                    category: categoriaEquipo,
                },
                {
                    type: 'cf-directiva-photo',
                    name: 'cf-directiva-photo',
                    changeProp: true,
                    label: 'Fotografía (URL)',
                    placeholder: '/img/equipo/avatar.svg',
                    category: categoriaEquipo,
                },
                {
                    type: 'cf-directiva-url',
                    name: 'cf-directiva-instagram',
                    changeProp: true,
                    atributo: 'instagram',
                    label: 'Instagram',
                    placeholder: 'https://instagram.com/...',
                    category: categoriaEquipo,
                },
                {
                    type: 'cf-directiva-url',
                    name: 'cf-directiva-facebook',
                    changeProp: true,
                    atributo: 'facebook',
                    label: 'Facebook',
                    placeholder: 'https://facebook.com/...',
                    category: categoriaEquipo,
                },
                {
                    type: 'cf-directiva-url',
                    name: 'cf-directiva-linkedin',
                    changeProp: true,
                    atributo: 'linkedin',
                    label: 'LinkedIn',
                    placeholder: 'https://linkedin.com/in/...',
                    category: categoriaEquipo,
                },
                {
                    type: 'cf-directiva-url',
                    name: 'cf-directiva-x',
                    changeProp: true,
                    atributo: 'x',
                    label: 'X',
                    placeholder: 'https://x.com/...',
                    category: categoriaEquipo,
                },
                {
                    type: 'cf-directiva-url',
                    name: 'cf-directiva-web',
                    changeProp: true,
                    atributo: 'web',
                    label: 'Web o portafolio',
                    placeholder: 'https://...',
                    category: categoriaEquipo,
                },
            ];

            const ajustesAnimacion = [
                {
                    type: 'select',
                    name: 'data-reveal',
                    label: 'Animación de entrada',
                    options: @json(\App\Support\AnimationLibrary::entranceOptions()),
                    category: categoriaAnimacion,
                },
                {
                    type: 'select',
                    name: 'data-reveal-trigger',
                    label: 'Cuándo animar',
                    options: @json(\App\Support\AnimationLibrary::triggerOptions()),
                    category: categoriaAnimacion,
                },
                {
                    type: 'number',
                    name: 'data-reveal-delay',
                    label: 'Retardo (ms)',
                    min: 0,
                    max: 3000,
                    step: 50,
                    placeholder: '0',
                    category: categoriaAnimacion,
                },
                {
                    type: 'select',
                    name: 'data-cf-loop',
                    label: 'Animación continua',
                    options: @json(\App\Support\AnimationLibrary::loopOptions()),
                    category: categoriaAnimacion,
                },
            ];

            const categoriaSeccion = { id: 'seccion-dinamica', label: 'Sección dinámica', open: true };

            const ajustesSeccionFaq = [
                {
                    type: 'select',
                    name: 'data-cf-option-columns',
                    label: 'Columnas',
                    options: [
                        { id: '1', label: 'Una columna' },
                        { id: '2', label: 'Dos columnas' },
                    ],
                    category: categoriaSeccion,
                },
                {
                    type: 'select',
                    name: 'data-cf-option-open-first',
                    label: 'Primera pregunta abierta',
                    options: [
                        { id: 'yes', label: 'Sí' },
                        { id: 'no', label: 'No' },
                    ],
                    category: categoriaSeccion,
                },
            ];

            const ajustesSeccionMarcas = [
                {
                    type: 'number',
                    name: 'data-cf-option-speed',
                    label: 'Velocidad (segundos)',
                    min: 10,
                    max: 120,
                    step: 5,
                    placeholder: '40',
                    category: categoriaSeccion,
                },
                {
                    type: 'select',
                    name: 'data-cf-option-grayscale',
                    label: 'Logos en blanco y negro',
                    options: [
                        { id: 'yes', label: 'Sí' },
                        { id: 'no', label: 'No' },
                    ],
                    category: categoriaSeccion,
                },
                {
                    type: 'select',
                    name: 'data-cf-option-pause',
                    label: 'Pausar al pasar el mouse',
                    options: [
                        { id: 'yes', label: 'Sí' },
                        { id: 'no', label: 'No' },
                    ],
                    category: categoriaSeccion,
                },
                {
                    type: 'select',
                    name: 'data-cf-option-invert',
                    label: 'Invertir logos en modo oscuro',
                    options: [
                        { id: 'yes', label: 'Sí' },
                        { id: 'no', label: 'No' },
                    ],
                    category: categoriaSeccion,
                },
            ];

            const ajustesSeccionTestimonios = [
                {
                    type: 'select',
                    name: 'data-cf-option-layout',
                    label: 'Diseño',
                    options: [
                        { id: 'slider', label: 'Carrusel' },
                        { id: 'grid', label: 'Cuadrícula' },
                    ],
                    category: categoriaSeccion,
                },
                {
                    type: 'select',
                    name: 'data-cf-option-autoplay',
                    label: 'Reproducción automática',
                    options: [
                        { id: 'yes', label: 'Sí' },
                        { id: 'no', label: 'No' },
                    ],
                    category: categoriaSeccion,
                },
                {
                    type: 'number',
                    name: 'data-cf-option-interval',
                    label: 'Intervalo (ms)',
                    min: 3000,
                    max: 20000,
                    step: 500,
                    placeholder: '6000',
                    category: categoriaSeccion,
                },
                {
                    type: 'select',
                    name: 'data-cf-option-arrows',
                    label: 'Flechas',
                    options: [
                        { id: 'yes', label: 'Sí' },
                        { id: 'no', label: 'No' },
                    ],
                    category: categoriaSeccion,
                },
                {
                    type: 'select',
                    name: 'data-cf-option-dots',
                    label: 'Puntos',
                    options: [
                        { id: 'yes', label: 'Sí' },
                        { id: 'no', label: 'No' },
                    ],
                    category: categoriaSeccion,
                },
            ];

            const categoriaVideo = { id: 'video', label: 'Video', open: true };

            const ajustesVideo = [
                {
                    type: 'text',
                    name: 'data-video-url',
                    label: 'URL del video',
                    placeholder: 'https://www.youtube.com/watch?v=...',
                    category: categoriaVideo,
                },
                {
                    type: 'select',
                    name: 'data-video-provider',
                    label: 'Proveedor',
                    options: [
                        { id: 'auto', label: 'Detectar automáticamente' },
                        { id: 'youtube', label: 'YouTube' },
                        { id: 'vimeo', label: 'Vimeo' },
                        { id: 'bunny', label: 'Bunny.net' },
                    ],
                    category: categoriaVideo,
                },
                {
                    type: 'select',
                    name: 'data-video-ratio',
                    label: 'Formato',
                    options: [
                        { id: '16/9', label: 'Horizontal 16:9' },
                        { id: '4/3', label: 'Clásico 4:3' },
                        { id: '1/1', label: 'Cuadrado 1:1' },
                        { id: '9/16', label: 'Vertical 9:16' },
                    ],
                    category: categoriaVideo,
                },
                {
                    type: 'text',
                    name: 'data-video-title',
                    label: 'Título accesible',
                    placeholder: 'Video de presentación',
                    category: categoriaVideo,
                },
                {
                    type: 'text',
                    name: 'data-video-poster-url',
                    label: 'Portada personalizada (URL)',
                    placeholder: 'Vacío = miniatura de YouTube',
                    category: categoriaVideo,
                },
            ];

            const actualizarVideoVisual = (componente) => {
                const seccion = buscarVideo(componente);

                if (!seccion) {
                    return;
                }

                const attrs = seccion.getAttributes() || {};
                const titulo = attrs['data-video-title'] || 'Video';
                const caption = seccion.find('[data-video-caption]')[0];
                const boton = seccion.find('[data-video-play]')[0];
                const portada = seccion.find('[data-video-poster]')[0];

                if (caption) {
                    caption.components([{ type: 'textnode', content: titulo }]);
                }

                if (boton) {
                    boton.addAttributes({ 'aria-label': `Reproducir video: ${titulo}` });
                }

                if (!portada) {
                    return;
                }

                const youtube = String(attrs['data-video-url'] || '').match(/(?:youtube\.com\/(?:watch\?(?:.*&)?v=|embed\/|shorts\/|live\/)|youtu\.be\/)([\w-]{6,})/i);
                const personalizada = String(attrs['data-video-poster-url'] || '').trim();
                const imagen = personalizada !== '' ? personalizada : (youtube ? `https://img.youtube.com/vi/${youtube[1]}/hqdefault.jpg` : '');

                portada.setStyle(imagen === ''
                    ? {}
                    : { 'background-image': `linear-gradient(135deg, rgba(15, 23, 42, .55), rgba(15, 23, 42, .15)), url("${imagen}")` });
            };

            const agregarAjustes = (componente) => {
                if (!componente || ['wrapper', 'textnode', 'comment'].includes(componente.get('type'))) {
                    return;
                }

                try {

                const traits = componente.get('traits') || [];
                const existentes = new Set(traits.map((trait) => trait.get('name')));
                const attributes = componente.getAttributes() || {};
                const id = String(attributes.id || '');
                const esBotonArriba = attributes['data-top-button'] !== undefined || id.startsWith('top-button');
                const esContenedorSocial = attributes['data-cf-social'] !== undefined
                    || (componente.components?.().models || []).some(
                        (hijo) => (hijo.getAttributes?.() || {})['data-cf-social-network'] !== undefined,
                    );
                const whatsapp = buscarWhatsapp(componente);
                const producto = buscarProducto(componente);
                const esCarrusel = attributes['data-cf-carousel'] !== undefined;
                const esVideo = attributes['data-cf-video'] !== undefined;
                const esDiapositiva = attributes['data-carousel-slide'] !== undefined;
                const esFormulario = attributes['data-cf-form'] !== undefined;
                const esSeccionParallax = String(componente.get('tagName') || '').toLowerCase() === 'section'
                    || attributes['data-cf-parallax'] !== undefined;
                const contador = buscarContador(componente);
                const tarjetaDirectiva = buscarTarjetaDirectiva(componente);
                const seccionDinamica = String(attributes['data-cf-section'] || '');
                const ajustesSeccion = seccionDinamica === 'faq'
                    ? ajustesSeccionFaq
                    : seccionDinamica === 'marcas'
                        ? ajustesSeccionMarcas
                        : seccionDinamica === 'testimonios'
                            ? ajustesSeccionTestimonios
                            : [];

                if (contador && !contador.get('name')) {
                    contador.set('name', 'Contador');
                }

                if (tarjetaDirectiva && !tarjetaDirectiva.get('name')) {
                    tarjetaDirectiva.set('name', 'Miembro del equipo');
                }

                if (whatsapp) {
                    const attrsWhatsapp = whatsapp.getAttributes() || {};

                    normalizarMarcasWhatsapp(whatsapp);

                    if (!whatsapp.get('name')) {
                        whatsapp.set('name', 'Widget de WhatsApp');
                    }

                    if (attrsWhatsapp['data-cf-whatsapp'] === null || attrsWhatsapp['data-cf-whatsapp'] === '') {
                        whatsapp.addAttributes({ 'data-cf-whatsapp': 'yes' });
                    }

                    if (attrsWhatsapp['data-wa-widget'] === null || attrsWhatsapp['data-wa-widget'] === '') {
                        whatsapp.addAttributes({ 'data-wa-widget': 'yes' });
                    }

                    if (!Object.prototype.hasOwnProperty.call(attrsWhatsapp, 'data-reveal')) {
                        whatsapp.addAttributes({ 'data-reveal': 'up', 'data-reveal-trigger': 'load', 'data-reveal-delay': '150' });
                    }
                }

                if (producto) {
                    const attrsProducto = producto.getAttributes() || {};

                    if (!producto.get('name')) {
                        producto.set('name', 'Producto en venta');
                    }

                    if (attrsProducto['data-cf-product'] === null || attrsProducto['data-cf-product'] === '') {
                        producto.addAttributes({ 'data-cf-product': 'yes' });
                    }

                    if (attrsProducto['data-product-badge-enabled'] === undefined || attrsProducto['data-product-badge-enabled'] === null || attrsProducto['data-product-badge-enabled'] === '') {
                        producto.addAttributes({ 'data-product-badge-enabled': 'yes' });
                    }
                }

                if (esCarrusel) {
                    if (!componente.get('name')) {
                        componente.set('name', 'Carrusel de imágenes');
                    }

                    if (attributes['data-cf-carousel'] === null || attributes['data-cf-carousel'] === '') {
                        componente.addAttributes({ 'data-cf-carousel': 'yes' });
                    }

                    [['data-carousel-autoplay', 'yes'], ['data-carousel-arrows', 'yes'], ['data-carousel-dots', 'yes']].forEach(([marcador, valor]) => {
                        if (attributes[marcador] === undefined || attributes[marcador] === null || attributes[marcador] === '') {
                            componente.addAttributes({ [marcador]: valor });
                        }
                    });
                }

                if (esDiapositiva) {
                    if (!componente.get('name')) {
                        componente.set('name', 'Diapositiva');
                    }

                    if (attributes['data-carousel-slide'] === null || attributes['data-carousel-slide'] === '') {
                        componente.addAttributes({ 'data-carousel-slide': 'yes' });
                    }
                }

                if (esVideo) {
                    if (!componente.get('name')) {
                        componente.set('name', 'Video');
                    }

                    [['data-video-provider', 'auto'], ['data-video-ratio', '16/9'], ['data-video-title', 'Video de presentación']].forEach(([marcador, valor]) => {
                        if (attributes[marcador] === undefined || attributes[marcador] === null || attributes[marcador] === '') {
                            componente.addAttributes({ [marcador]: valor });
                        }
                    });

                    actualizarVideoVisual(componente);
                }

                if (esFormulario) {
                    if (!componente.get('name')) {
                        componente.set('name', 'Formulario de contacto');
                    }

                    if (attributes['data-cf-form'] === null || attributes['data-cf-form'] === '') {
                        componente.addAttributes({ 'data-cf-form': 'yes' });
                    }
                }

                if (esContenedorSocial) {
                    if (attributes['data-cf-social'] === undefined || attributes['data-cf-social'] === null || attributes['data-cf-social'] === '') {
                        componente.addAttributes({ 'data-cf-social': 'yes' });
                    }

                    if (attributes['data-cf-social-tone'] === undefined || attributes['data-cf-social-tone'] === null || attributes['data-cf-social-tone'] === '') {
                        componente.addAttributes({ 'data-cf-social-tone': 'light' });
                    }
                }

                /*
                 * GrapesJS omite los atributos con valor vacío al exportar el HTML.
                 * Los convertimos en marcadores con valor para que las animaciones,
                 * el pie propio y el botón volver arriba sobrevivan al guardado.
                 */
                [['data-reveal', 'up'], ['data-cf-footer', 'yes']].forEach(([marcador, valor]) => {
                    if (!Object.prototype.hasOwnProperty.call(attributes, marcador)) {
                        return;
                    }

                    if (attributes[marcador] === null || attributes[marcador] === '') {
                        componente.addAttributes({ [marcador]: valor });
                    }
                });

                if (esBotonArriba) {
                    const marcador = attributes['data-top-button'];

                    if (marcador === undefined || marcador === null || marcador === '') {
                        componente.addAttributes({ 'data-top-button': 'yes' });
                    }

                    if (attributes['data-top-enabled'] === undefined) {
                        componente.addAttributes({ 'data-top-enabled': 'yes' });
                    }
                }

                const estaDentroBotonArriba = !esBotonArriba && buscarBotonArriba(componente) !== null;
                const ajustes = [
                    ...(tarjetaDirectiva ? ajustesDirectiva : []),
                    ...(esSeccionParallax ? ajustesParallax : []),
                    ...(esContenedorSocial ? ajustesRedes : []),
                    ...(whatsapp ? ajustesWhatsapp : []),
                    ...(producto ? ajustesProducto : []),
                    ...(esCarrusel ? ajustesCarrusel : []),
                    ...(esVideo ? ajustesVideo : []),
                    ...(esDiapositiva ? ajustesDiapositiva : []),
                    ...(esFormulario ? ajustesFormulario : []),
                    ...(contador ? ajustesContador : []),
                    ...(esBotonArriba ? ajustesBotonArriba : []),
                    ...(estaDentroBotonArriba ? ajustesBotonArribaImagen : []),
                    ...ajustesSeccion,
                    ...ajustesAnimacion,
                ];

                ajustes.forEach((ajuste) => {
                    if (!existentes.has(ajuste.name)) {
                        componente.addTrait({ ...ajuste });
                    }
                });
                } catch (error) {
                    console.error('agregarAjustes', error);
                }
            };

            editor.on('component:create', agregarAjustes);
            editor.on('component:update:attributes', (componente) => {
                if (componente.getAttributes?.()['data-cf-video'] !== undefined || buscarVideo(componente)) {
                    actualizarVideoVisual(componente);
                }
            });
            editor.getWrapper()?.find('*').forEach(agregarAjustes);

            /*
             * El widget es un conjunto: al pulsar el botón verde, una imagen o el
             * panel se selecciona el contenedor completo. Los ajustes de WhatsApp
             * y de animación se muestran al abrir Settings (el engranaje).
             */
            editor.on('component:selected', (componente) => {
                if (!componente) {
                    return;
                }

                agregarAjustes(componente);

                const esTexto = ['text', 'textnode'].includes(componente.get('type'));
                const whatsapp = buscarWhatsapp(componente);
                const producto = buscarProducto(componente);
                const carrusel = buscarCarrusel(componente);
                const diapositiva = buscarDiapositiva(componente);
                const formulario = buscarFormulario(componente);
                const contador = buscarContador(componente);

                if (whatsapp && whatsapp !== componente) {
                    agregarAjustes(whatsapp);

                    if (!esTexto) {
                        editor.select(whatsapp);
                    }

                    return;
                }

                if (producto && producto !== componente) {
                    agregarAjustes(producto);

                    if (!esTexto) {
                        editor.select(producto);
                    }

                    return;
                }

                if (diapositiva && diapositiva !== componente) {
                    agregarAjustes(diapositiva);

                    if (!esTexto) {
                        editor.select(diapositiva);
                    }

                    return;
                }

                if (formulario && formulario !== componente) {
                    agregarAjustes(formulario);

                    if (!esTexto) {
                        editor.select(formulario);
                    }

                    return;
                }

                if (contador && contador !== componente) {
                    agregarAjustes(contador);

                    if (!esTexto) {
                        editor.select(contador);
                    }

                    return;
                }

                if (carrusel && !diapositiva && carrusel !== componente) {
                    agregarAjustes(carrusel);

                    if (!esTexto) {
                        editor.select(carrusel);
                    }

                    return;
                }

                if (whatsapp) {
                    agregarAjustes(whatsapp);
                }

                if (producto) {
                    agregarAjustes(producto);
                }

                if (carrusel && !diapositiva) {
                    agregarAjustes(carrusel);
                }

                if (diapositiva) {
                    agregarAjustes(diapositiva);
                }

                if (formulario) {
                    agregarAjustes(formulario);
                }

                if (contador) {
                    agregarAjustes(contador);
                }
            });

            /*
             * Los proyectos guardados antes del rediseño conservan el formulario
             * antiguo (sin cuadrícula ni tarjeta). Lo sustituimos por el bloque
             * actual al cargar el editor, manteniendo textos, ancla y animación.
             */
            const actualizarFormulariosAntiguos = () => {
                const contenido = editor.BlockManager.get('cf-formulario')?.get('content');

                if (!contenido) {
                    return;
                }

                const atributosConservados = [
                    'data-form-title',
                    'data-form-description',
                    'data-form-label-nombre',
                    'data-form-label-email',
                    'data-form-label-telefono',
                    'data-form-label-mensaje',
                    'data-form-telefono',
                    'data-form-button',
                    'data-form-success',
                    'data-reveal',
                    'data-reveal-trigger',
                    'data-reveal-delay',
                    'data-cf-loop',
                ];

                const formularios = editor.getWrapper()?.find('[data-cf-form]') || [];

                formularios.forEach((formulario) => {
                    if (formulario.find('.cf-form-grid').length > 0) {
                        return;
                    }

                    const atributos = formulario.getAttributes?.() || {};
                    const contactoOcupado = formularios.some(
                        (otro) => otro !== formulario && (otro.getAttributes?.() || {}).id === 'contacto',
                    );
                    const id = contactoOcupado && atributos.id ? atributos.id : 'contacto';
                    const padre = formulario.parent?.();
                    const indice = formulario.index?.() ?? 0;

                    formulario.replaceWith(contenido);

                    const reemplazo = padre?.components?.().at(indice);

                    if (!reemplazo?.addAttributes) {
                        return;
                    }

                    const restantes = { id, 'data-form-upgraded': 'yes' };

                    atributosConservados.forEach((nombre) => {
                        if (
                            atributos[nombre] !== undefined
                            && atributos[nombre] !== null
                            && atributos[nombre] !== ''
                        ) {
                            restantes[nombre] = atributos[nombre];
                        }
                    });

                    reemplazo.addAttributes(restantes);
                });
            };

            /*
             * Refuerzo: cuando el editor termina de cargar, volvemos a añadir los
             * ajustes y, si ya había un widget seleccionado, forzamos la re-selección
             * para que el panel de Settings se vuelva a pintar.
             */
            const finalizarCarga = () => {
                actualizarFormulariosAntiguos();
                editor.getWrapper()?.find('*').forEach(agregarAjustes);
                editor.getWrapper()?.find('[data-cf-product]').forEach(actualizarProducto);
                editor.getWrapper()?.find('[data-carousel-slide]').forEach(actualizarDiapositiva);
                editor.getWrapper()?.find('[data-cf-form]').forEach(actualizarFormulario);
                editor.getWrapper()?.find('[data-counter]').forEach(actualizarContador);

                const seleccion = editor.getSelected();

                if (seleccion) {
                    agregarAjustes(seleccion);
                }
            };

            editor.on('load', finalizarCarga);
            window.setTimeout(finalizarCarga, 0);
            window.setTimeout(finalizarCarga, 1000);

            const botonAjustesDom = [...document.querySelectorAll('.gjs-pn-views .gjs-pn-btn')]
                .find((elemento) => elemento.getAttribute('title') === 'Settings');

            botonAjustesDom?.addEventListener('click', (event) => {
                const seleccion = editor.getSelected();

                if (seleccion && buscarWhatsapp(seleccion)) {
                    agregarAjustes(seleccion);
                }

                /*
                 * El engranaje solo debe abrir Settings, nunca cerrarlo: si ya está
                 * activo, evitamos que GrapesJS lo vuelva a conmutar y deje el panel
                 * en blanco.
                 */
                if (botonAjustesDom.classList.contains('gjs-pn-active')) {
                    event.preventDefault();
                    event.stopImmediatePropagation();
                }
            }, true);

            const actualizarWhatsapp = (componente) => {
                const attributes = componente?.getAttributes?.() || {};

                if (attributes['data-cf-whatsapp'] === undefined) {
                    return;
                }

                const titulo = String(attributes['data-wa-title'] || '').trim() || 'WhatsApp';
                const mensaje = String(attributes['data-wa-message'] || '').trim();

                const buscarHijo = (atributo) => componente.find('*').find(
                    (hijo) => Object.prototype.hasOwnProperty.call(hijo.getAttributes?.() || {}, atributo),
                );

                const escribir = (atributo, texto) => {
                    const objetivo = buscarHijo(atributo);

                    if (objetivo) {
                        objetivo.components([{ type: 'textnode', content: texto }]);
                    }
                };

                escribir('data-wa-title-text', titulo);
                escribir('data-wa-message-text', mensaje);

                const panel = buscarHijo('data-wa-panel');

                if (panel) {
                    panel.addAttributes({ 'aria-label': `Chat de WhatsApp con ${titulo}` });
                }
            };

            editor.on('component:update:attributes', actualizarWhatsapp);

            const actualizarProducto = (componente) => {
                const raiz = buscarProducto(componente);

                if (!raiz) {
                    return;
                }

                const imagen = raiz.find('[data-product-image]')[0];
                const attrsImagen = imagen?.getAttributes?.() || {};

                // Si la imagen se cambió con el gestor de activos, lo reflejamos
                // en el atributo editable para no sobrescribir la elección.
                if (imagen && componente === imagen) {
                    const srcActual = String(attrsImagen.src || '');
                    const urlAttr = String(raiz.getAttributes?.()['data-product-image-url'] || '');

                    if (srcActual && srcActual !== urlAttr) {
                        raiz.addAttributes({ 'data-product-image-url': srcActual });
                    }
                }

                // Se relee después de la sincronización anterior para no revertir
                // la imagen recién elegida con el valor antiguo del atributo.
                const attributes = raiz.getAttributes?.() || {};

                const leer = (clave, defecto = '') => String(attributes[clave] ?? '').trim() || defecto;
                const nombre = leer('data-product-name', 'Producto');
                const descripcion = leer('data-product-description');
                const precio = leer('data-product-price');
                const moneda = leer('data-product-currency');
                const nota = leer('data-product-price-note');
                const anterior = leer('data-product-old-price');
                const etiqueta = leer('data-product-badge-text');
                const etiquetaVisible = leer('data-product-badge-enabled', 'yes') !== 'no' && etiqueta !== '';
                const boton = leer('data-product-cta-text', 'Comprar ahora');
                const enlace = leer('data-product-url');
                const urlImagen = leer('data-product-image-url', 'https://picsum.photos/seed/cielo-prod/800/600');
                const alt = leer('data-product-image-alt', nombre);

                const buscarHijo = (marca) => raiz.find('*').find(
                    (hijo) => Object.prototype.hasOwnProperty.call(hijo.getAttributes?.() || {}, marca),
                );

                const escribirTexto = (marca, texto) => {
                    const objetivo = buscarHijo(marca);

                    if (!objetivo) {
                        return;
                    }

                    const actual = (objetivo.components?.().models || [])
                        .map((modelo) => modelo.get?.('content') ?? '')
                        .join('');

                    if (actual !== texto) {
                        objetivo.components([{ type: 'textnode', content: texto }]);
                    }
                };

                escribirTexto('data-product-name-text', nombre);
                escribirTexto('data-product-description-text', descripcion);
                escribirTexto('data-product-price-text', precio);
                escribirTexto('data-product-currency-text', moneda);
                escribirTexto('data-product-price-note-text', nota);
                escribirTexto('data-product-old-price-text', anterior);
                escribirTexto('data-product-badge-label', etiqueta);
                escribirTexto('data-product-cta-text', boton);

                const conmutarVisibilidad = (marcaBase, marcaOff, ocultar) => {
                    const objetivo = buscarHijo(marcaBase);

                    if (!objetivo) {
                        return;
                    }

                    const attrs = objetivo.getAttributes?.() || {};
                    const tiene = attrs[marcaOff] !== undefined;

                    if (ocultar && !tiene) {
                        objetivo.addAttributes({ [marcaOff]: 'yes' });
                    } else if (!ocultar && tiene) {
                        objetivo.removeAttributes(marcaOff);
                    }
                };

                conmutarVisibilidad('data-product-badge', 'data-product-badge-off', !etiquetaVisible);
                conmutarVisibilidad('data-product-currency', 'data-product-currency-off', moneda === '');
                conmutarVisibilidad('data-product-price-note', 'data-product-price-note-off', nota === '');
                conmutarVisibilidad('data-product-old-price', 'data-product-old-price-off', anterior === '');

                const cta = buscarHijo('data-product-cta');

                if (cta) {
                    const attrsCta = cta.getAttributes?.() || {};
                    const enlaceValido = /^https?:\/\//i.test(enlace);

                    if (enlaceValido) {
                        if (attrsCta.href !== enlace) {
                            cta.addAttributes({ href: enlace });
                        }

                        if (attrsCta.target !== '_blank') {
                            cta.addAttributes({ target: '_blank' });
                        }

                        if (attrsCta.rel !== 'noopener noreferrer') {
                            cta.addAttributes({ rel: 'noopener noreferrer' });
                        }

                        if (attrsCta['data-product-cta-empty'] !== undefined) {
                            cta.removeAttributes('data-product-cta-empty');
                        }

                        if (attrsCta['aria-disabled'] !== undefined) {
                            cta.removeAttributes('aria-disabled');
                        }
                    } else {
                        if (attrsCta.href !== undefined) {
                            cta.removeAttributes('href');
                        }

                        if (attrsCta['data-product-cta-empty'] === undefined) {
                            cta.addAttributes({ 'data-product-cta-empty': 'yes', 'aria-disabled': 'true' });
                        }
                    }
                }

                if (imagen) {
                    if (attrsImagen.src !== urlImagen) {
                        imagen.addAttributes({ src: urlImagen });
                    }

                    if (attrsImagen.alt !== alt) {
                        imagen.addAttributes({ alt });
                    }
                }
            };

            editor.on('component:update:attributes', actualizarProducto);

            const actualizarDiapositiva = (componente) => {
                const diapositiva = buscarDiapositiva(componente);

                if (!diapositiva) {
                    return;
                }

                const imagen = diapositiva.find('[data-carousel-image]')[0];
                const attrsImagen = imagen?.getAttributes?.() || {};

                // Si la imagen se cambió con el gestor de activos, lo reflejamos
                // en el atributo editable para no sobrescribir la elección.
                if (imagen && componente === imagen) {
                    const srcActual = String(attrsImagen.src || '');
                    const urlAttr = String(diapositiva.getAttributes?.()['data-slide-image'] || '');

                    if (srcActual && srcActual !== urlAttr) {
                        diapositiva.addAttributes({ 'data-slide-image': srcActual });
                    }
                }

                const attributes = diapositiva.getAttributes?.() || {};

                const leer = (clave, defecto = '') => String(attributes[clave] ?? '').trim() || defecto;
                const titulo = leer('data-slide-title');
                const texto = leer('data-slide-text');
                const boton = leer('data-slide-cta-text');
                const enlace = leer('data-slide-cta-url');
                const urlImagen = leer('data-slide-image', 'https://picsum.photos/seed/cielo-slide/1600/900');

                const buscarHijo = (marca) => diapositiva.find('*').find(
                    (hijo) => Object.prototype.hasOwnProperty.call(hijo.getAttributes?.() || {}, marca),
                );

                const escribirTexto = (marca, valor) => {
                    const objetivo = buscarHijo(marca);

                    if (!objetivo) {
                        return;
                    }

                    const actual = (objetivo.components?.().models || [])
                        .map((modelo) => modelo.get?.('content') ?? '')
                        .join('');

                    if (actual !== valor) {
                        objetivo.components([{ type: 'textnode', content: valor }]);
                    }
                };

                escribirTexto('data-carousel-title-text', titulo);
                escribirTexto('data-carousel-text-text', texto);
                escribirTexto('data-carousel-cta-text', boton);

                const parrafo = buscarHijo('data-carousel-text');

                if (parrafo) {
                    const attrs = parrafo.getAttributes?.() || {};
                    const vacio = texto === '';

                    if (vacio && attrs['data-carousel-text-off'] === undefined) {
                        parrafo.addAttributes({ 'data-carousel-text-off': 'yes' });
                    } else if (!vacio && attrs['data-carousel-text-off'] !== undefined) {
                        parrafo.removeAttributes('data-carousel-text-off');
                    }
                }

                const cta = buscarHijo('data-carousel-cta');

                if (cta) {
                    const attrs = cta.getAttributes?.() || {};
                    const enlaceValido = enlace !== '' && (enlace.startsWith('#') || /^https?:\/\//i.test(enlace));

                    if (enlaceValido) {
                        if (attrs.href !== enlace) {
                            cta.addAttributes({ href: enlace });
                        }

                        if (attrs['data-carousel-cta-off'] !== undefined) {
                            cta.removeAttributes('data-carousel-cta-off');
                        }
                    } else if (attrs['data-carousel-cta-off'] === undefined) {
                        cta.addAttributes({ 'data-carousel-cta-off': 'yes' });
                    }
                }

                if (imagen && attrsImagen.src !== urlImagen) {
                    imagen.addAttributes({ src: urlImagen });
                }
            };

            editor.on('component:update:attributes', actualizarDiapositiva);

            const actualizarFormulario = (componente) => {
                const formulario = buscarFormulario(componente);

                if (!formulario) {
                    return;
                }

                const attributes = formulario.getAttributes?.() || {};

                const buscarHijo = (marca) => formulario.find('*').find(
                    (hijo) => Object.prototype.hasOwnProperty.call(hijo.getAttributes?.() || {}, marca),
                );

                const escribirTexto = (marca, valor) => {
                    const objetivo = buscarHijo(marca);

                    if (!objetivo) {
                        return;
                    }

                    const actual = (objetivo.components?.().models || [])
                        .map((modelo) => modelo.get?.('content') ?? '')
                        .join('');

                    if (actual !== valor) {
                        objetivo.components([{ type: 'textnode', content: valor }]);
                    }
                };

                escribirTexto('data-form-title-text', String(attributes['data-form-title'] ?? ''));
                escribirTexto('data-form-description-text', String(attributes['data-form-description'] ?? ''));
                escribirTexto('data-form-label-nombre-text', String(attributes['data-form-label-nombre'] ?? ''));
                escribirTexto('data-form-label-email-text', String(attributes['data-form-label-email'] ?? ''));
                escribirTexto('data-form-label-telefono-text', String(attributes['data-form-label-telefono'] ?? ''));
                escribirTexto('data-form-label-mensaje-text', String(attributes['data-form-label-mensaje'] ?? ''));
                escribirTexto('data-form-button-text', String(attributes['data-form-button'] ?? ''));
            };

            editor.on('component:update:attributes', actualizarFormulario);

            const actualizarContador = (componente) => {
                const contador = buscarContador(componente);

                if (!contador) {
                    return;
                }

                const attributes = contador.getAttributes?.() || {};

                const escribir = (selector, valor) => {
                    const objetivo = contador.find(selector)[0];

                    if (!objetivo) {
                        return;
                    }

                    const actual = (objetivo.components?.().models || [])
                        .map((modelo) => modelo.get?.('content') ?? '')
                        .join('');

                    if (actual !== valor) {
                        objetivo.components([{ type: 'textnode', content: valor }]);
                    }
                };

                escribir('.cf-counter-prefix', String(attributes['data-counter-prefix'] ?? ''));
                escribir('.cf-counter-number', String(attributes['data-counter-value'] ?? '0'));
                escribir('.cf-counter-suffix', String(attributes['data-counter-suffix'] ?? ''));
                escribir('.cf-counter-label', String(attributes['data-counter-label'] ?? ''));
            };

            editor.on('component:update:attributes', actualizarContador);

            /*
             * Mantiene las miniaturas de la lista al día si la imagen de una
             * diapositiva se cambia desde el lienzo con el carrusel seleccionado.
             */
            editor.on('component:update:attributes', (componente) => {
                const attributes = componente?.getAttributes?.() || {};

                if (attributes['data-carousel-slide'] === undefined) {
                    return;
                }

                const lista = document.querySelector('[data-carousel-slides-list]');
                const raiz = buscarCarrusel(componente);

                if (lista && raiz && editor.getSelected() === raiz) {
                    actualizarListaDiapositivas(lista, raiz);
                }
            });

            /*
             * Tope de diapositivas también al duplicar o pegar: si un carrusel
             * supera el máximo, se retira la diapositiva recién añadida. Solo
             * se aplica después de cargar el proyecto para no borrar contenido
             * antiguo que ya tuviera más diapositivas guardadas.
             */
            let carruselListo = false;

            editor.on('component:add', (componente) => {
                if (!carruselListo) {
                    return;
                }

                const attributes = componente?.getAttributes?.() || {};

                if (attributes['data-carousel-slide'] === undefined) {
                    return;
                }

                window.setTimeout(() => {
                    const raiz = componente.parent?.();

                    if (!raiz || (raiz.getAttributes?.() || {})['data-cf-carousel'] === undefined) {
                        return;
                    }

                    if (raiz.find('[data-carousel-slide]').length > MAX_DIAPOSITIVAS) {
                        componente.remove();
                        avisarEditor(`Máximo ${MAX_DIAPOSITIVAS} imágenes por carrusel.`);
                    }
                }, 50);
            });

            editor.on('load', () => {
                window.setTimeout(() => {
                    carruselListo = true;
                }, 500);
            });

            const inyectarEstilos = () => {
                inyectarFuentes();

                const doc = editor.Canvas.getDocument();

                if (!doc || doc.getElementById('cf-animation-styles')) {
                    return;
                }

                const style = doc.createElement('style');
                style.id = 'cf-animation-styles';
                style.textContent = canvasCss;
                doc.head.appendChild(style);
            };

            /*
             * El lienzo no ejecuta app.js, así que la capa que la página pública
             * recibe del servidor se clona aquí solo para verla mientras se
             * edita. No forma parte del modelo de GrapesJS y por eso no se
             * guarda: la capa real siempre la genera ParallaxWidget al render.
             */
            const inyectarCapasParallax = () => {
                const doc = editor.Canvas.getDocument();

                if (!doc) {
                    return;
                }

                doc.querySelectorAll('[data-cf-parallax]').forEach((seccion) => {
                    const intensidad = seccion.getAttribute('data-cf-parallax');
                    const capa = seccion.querySelector(':scope > [data-cf-parallax-layer]');

                    if (intensidad !== 'soft' && intensidad !== 'strong') {
                        capa?.remove();

                        return;
                    }

                    if (capa && capa.dataset.cfParallaxIntensity === intensidad) {
                        return;
                    }

                    capa?.remove();

                    const contenedor = doc.createElement('div');
                    contenedor.innerHTML = capasParallax[intensidad] || '';
                    const nueva = contenedor.firstElementChild;

                    if (!nueva) {
                        return;
                    }

                    nueva.dataset.cfParallaxIntensity = intensidad;
                    nueva.setAttribute('data-gjs-selectable', 'false');
                    seccion.prepend(nueva);
                });
            };

            let frameParallax = null;
            const programarCapasParallax = () => {
                if (frameParallax !== null) {
                    return;
                }

                frameParallax = window.requestAnimationFrame(() => {
                    frameParallax = null;
                    inyectarCapasParallax();
                });
            };

            let observadorPrevia = null;

            const detenerPrevia = () => {
                const doc = editor.Canvas.getDocument();

                if (!doc) {
                    return;
                }

                observadorPrevia?.disconnect();
                observadorPrevia = null;
                doc.documentElement.classList.remove('js');
                doc.querySelectorAll('[data-reveal]').forEach((el) => {
                    el.classList.remove('reveal-in');
                    el.style.transitionDelay = '';
                });
            };

            const iniciarPrevia = () => {
                const doc = editor.Canvas.getDocument();

                if (!doc) {
                    return;
                }

                inyectarEstilos();
                doc.documentElement.classList.add('js');
                doc.querySelectorAll('[data-reveal]').forEach((el) => {
                    el.classList.remove('reveal-in');
                    el.style.transitionDelay = '';
                });

                const win = doc.defaultView;
                const sinMovimiento = win?.matchMedia('(prefers-reduced-motion: reduce)').matches;

                if (sinMovimiento) {
                    doc.querySelectorAll('[data-reveal]').forEach((el) => el.classList.add('reveal-in'));

                    return;
                }

                observadorPrevia?.disconnect();
                observadorPrevia = new win.IntersectionObserver((entradas) => {
                    entradas.forEach((entrada) => {
                        if (!entrada.isIntersecting) {
                            return;
                        }

                        const retardo = entrada.target.dataset.revealDelay;

                        if (retardo) {
                            entrada.target.style.transitionDelay = `${retardo}ms`;
                        }

                        entrada.target.classList.add('reveal-in');
                        observadorPrevia.unobserve(entrada.target);
                    });
                }, { threshold: 0.15, rootMargin: '0px 0px -60px 0px' });

                doc.querySelectorAll('[data-reveal]').forEach((el) => {
                    if (el.dataset.revealTrigger === 'load') {
                        const retardo = Number(el.dataset.revealDelay || 0);

                        if (retardo) {
                            el.style.transitionDelay = `${retardo}ms`;
                        }

                        win.setTimeout(() => el.classList.add('reveal-in'), 60);

                        return;
                    }

                    observadorPrevia.observe(el);
                });
            };

            const estaEnPrevia = () => editor.Commands.isActive('preview') || editor.Commands.isActive('core:preview');

            editor.on('canvas:frame:load', () => {
                inyectarEstilos();
                inyectarCapasParallax();

                if (estaEnPrevia()) {
                    iniciarPrevia();
                }
            });

            /*
             * Refuerzo para los bloques que se agregan después de cargar el
             * lienzo: si el iframe se recrea o el estilo aún no está, lo
             * reinyectamos para que el bloque nazca con su diseño.
             */
            editor.on('component:add', () => window.setTimeout(() => {
                inyectarEstilos();
                inyectarCapasParallax();
            }, 0));

            editor.on('component:update:attributes', programarCapasParallax);
            editor.on('component:update', programarCapasParallax);

            /*
             * En la primera carga el iframe puede montarse antes de que este
             * script registre sus listeners; al reemplazarse el documento, el
             * <style> inyectado se pierde y el formulario queda sin diseño.
             * Reintentamos durante los primeros segundos. Es idempotente: si
             * el estilo ya está en el documento actual, no hace nada.
             */
            const asegurarLienzo = () => {
                inyectarEstilos();
                inyectarCapasParallax();

                [0, 250, 750, 1500, 3000].forEach((ms) => {
                    window.setTimeout(() => {
                        inyectarEstilos();
                        inyectarCapasParallax();
                    }, ms);
                });
            };

            editor.on('load', asegurarLienzo);

            let intentosLienzo = 0;
            const vigilanteLienzo = window.setInterval(() => {
                inyectarEstilos();
                inyectarCapasParallax();
                intentosLienzo++;

                if (intentosLienzo >= 20) {
                    window.clearInterval(vigilanteLienzo);
                }
            }, 250);

            ['preview', 'core:preview'].forEach((comando) => {
                editor.on(`command:run:${comando}`, iniciarPrevia);
                editor.on(`command:stop:${comando}`, detenerPrevia);
                editor.on(`run:${comando}`, iniciarPrevia);
                editor.on(`stop:${comando}`, detenerPrevia);
            });

            asegurarLienzo();
        })();
    </script>
    {{--
        Fase 1 del editor: protección del trabajo, tema del lienzo, formularios
        múltiples, buscador de bloques, paleta de marca e interfaz en español.
        Vive aparte del bundle del paquete para no modificarlo.
    --}}
    <script>
        (() => {
            const editor = window.__cielotronadorGjs;

            if (!editor) {
                return;
            }

            const configuracion = (() => {
                try {
                    return JSON.parse(document.getElementById('gjs-editor')?.dataset.gjsConfig || '{}');
                } catch {
                    return {};
                }
            })();

            const plantillaId = String(configuracion?.urls?.bewaren || window.location.pathname)
                .split('/')
                .filter(Boolean)
                .pop() || '0';

            const CLAVE_BORRADOR = `cf-borrador-${plantillaId}`;
            const CLAVE_TEMA = 'cf-editor-tema';
            const estado = document.querySelector('[data-gjs-status]');
            const banner = document.getElementById('cf-borrador-editor');
            const fechaBorrador = banner?.querySelector('[data-cf-borrador-fecha]');
            const botonTema = document.querySelector('[data-gjs-tema]');

            /*
             * Los comandos del preset llaman a setDevice con el nombre visible
             * ("Tablet"), pero GrapesJS busca los dispositivos por id ("tablet").
             * Sin esto, los botones cambian de estado pero el lienzo no se
             * redimensiona. Los redefinimos con los ids reales.
             */
            const COMANDOS_DISPOSITIVO = {
                'set-device-desktop': 'desktop',
                'set-device-tablet': 'tablet',
                'set-device-mobile': 'mobilePortrait',
            };

            Object.entries(COMANDOS_DISPOSITIVO).forEach(([comando, dispositivo]) => {
                editor.Commands.add(comando, {
                    run: (instancia) => instancia.setDevice(dispositivo),
                    stop: () => {},
                });
            });

            const anunciar = (texto) => {
                if (estado) {
                    estado.textContent = texto;
                }
            };

            /* ---------- Tema claro / oscuro del lienzo ---------- */
            let temaOscuro = localStorage.getItem(CLAVE_TEMA) === 'dark';

            const aplicarTema = () => {
                if (botonTema) {
                    botonTema.textContent = temaOscuro ? '☀️ Claro' : '🌙 Oscuro';
                    botonTema.setAttribute('aria-pressed', temaOscuro ? 'true' : 'false');
                    botonTema.setAttribute(
                        'title',
                        temaOscuro
                            ? 'Ver el lienzo en modo claro (solo vista previa)'
                            : 'Ver el lienzo en modo oscuro (solo vista previa)',
                    );
                }

                const doc = editor.Canvas.getDocument();

                if (!doc) {
                    return;
                }

                doc.documentElement.classList.toggle('dark', temaOscuro);
            };

            const avisar = (texto) => {
                document.getElementById('cf-tema-aviso')?.remove();

                const aviso = document.createElement('div');
                aviso.id = 'cf-tema-aviso';
                aviso.setAttribute('role', 'status');
                aviso.textContent = texto;
                aviso.style.cssText = 'position:fixed;top:4.25rem;left:50%;transform:translateX(-50%);z-index:9600;padding:8px 14px;border-radius:999px;background:#18181b;color:#fafafa;font:600 12px/1.4 ui-sans-serif,system-ui,sans-serif;box-shadow:0 10px 30px rgba(0,0,0,.35);border:1px solid rgba(255,255,255,.12);';
                document.body.appendChild(aviso);
                window.setTimeout(() => aviso.remove(), 2200);
            };

            botonTema?.addEventListener('click', () => {
                temaOscuro = !temaOscuro;
                localStorage.setItem(CLAVE_TEMA, temaOscuro ? 'dark' : 'light');
                aplicarTema();
                avisar(temaOscuro ? 'Vista previa en modo oscuro' : 'Vista previa en modo claro');
            });

            editor.on('canvas:frame:load', () => window.setTimeout(aplicarTema, 0));
            editor.on('load', () => window.setTimeout(aplicarTema, 0));
            aplicarTema();

            /*
             * Mismo refuerzo que la inyección de CSS: si el lienzo tarda en
             * montarse o se reemplaza, el tema debe volver a aplicarse.
             */
            [0, 250, 750, 1500, 3000].forEach((ms) => {
                window.setTimeout(aplicarTema, ms);
            });

            let intentosTema = 0;
            const vigilanteTema = window.setInterval(() => {
                aplicarTema();
                intentosTema++;

                if (intentosTema >= 20) {
                    window.clearInterval(vigilanteTema);
                }
            }, 250);

            /* ---------- Cambios sin guardar y borrador local ---------- */
            let listo = false;
            let hayCambios = false;
            let saliendo = false;
            let temporizadorBorrador = null;
            let inicioListo = false;

            const alIniciar = (fn) => {
                if (inicioListo) {
                    fn();
                    return;
                }

                editor.once('load', () => {
                    if (!inicioListo) {
                        inicioListo = true;
                        fn();
                    }
                });

                window.setTimeout(() => {
                    if (!inicioListo) {
                        inicioListo = true;
                        fn();
                    }
                }, 2500);
            };

            const leerBorrador = () => {
                try {
                    return JSON.parse(localStorage.getItem(CLAVE_BORRADOR) || 'null');
                } catch {
                    return null;
                }
            };

            const guardarBorrador = () => {
                if (!hayCambios || !listo) {
                    return;
                }

                try {
                    localStorage.setItem(CLAVE_BORRADOR, JSON.stringify({
                        project: editor.getProjectData(),
                        html: editor.getHtml(),
                        css: editor.getCss(),
                        savedAt: Date.now(),
                    }));
                } catch {
                    // Si el navegador llena la cuota, el editor sigue funcionando.
                }
            };

            const marcarCambios = () => {
                if (!listo) {
                    return;
                }

                hayCambios = true;
                window.clearTimeout(temporizadorBorrador);
                temporizadorBorrador = window.setTimeout(guardarBorrador, 2500);
            };

            ['update', 'component:add', 'component:remove', 'component:update'].forEach((evento) => {
                editor.on(evento, marcarCambios);
            });

            window.setInterval(() => {
                if (hayCambios) {
                    guardarBorrador();
                }
            }, 20000);

            window.addEventListener('beforeunload', (evento) => {
                if (!hayCambios || saliendo) {
                    return;
                }

                evento.preventDefault();
                evento.returnValue = '';
            });

            const enlaceCerrar = [...document.querySelectorAll('.gjs-kop a')]
                .find((enlace) => enlace.textContent.trim() === 'Cerrar');

            enlaceCerrar?.addEventListener('click', (evento) => {
                if (!hayCambios) {
                    return;
                }

                if (!window.confirm('Tienes cambios sin guardar. ¿Quieres salir igualmente?')) {
                    evento.preventDefault();
                    return;
                }

                saliendo = true;
            });

            const mostrarBorrador = () => {
                const borrador = leerBorrador();

                if (!borrador || !banner || !(borrador.project || borrador.html)) {
                    return;
                }

                if (Date.now() - Number(borrador.savedAt || 0) > 7 * 24 * 60 * 60 * 1000) {
                    localStorage.removeItem(CLAVE_BORRADOR);
                    return;
                }

                if (fechaBorrador) {
                    const fecha = new Date(Number(borrador.savedAt));
                    fechaBorrador.textContent = `Guardado el ${fecha.toLocaleString('es-CL', { dateStyle: 'short', timeStyle: 'short' })}`;
                }

                banner.hidden = false;

                banner.querySelector('[data-cf-borrador-restaurar]')?.addEventListener('click', () => {
                    try {
                        if (borrador.project) {
                            editor.loadProjectData(borrador.project);
                        } else {
                            editor.setComponents(borrador.html || '');
                            editor.setStyle(borrador.css || '');
                        }

                        hayCambios = true;
                        banner.hidden = true;
                        anunciar('Borrador restaurado · sin guardar');
                        aplicarTema();
                        window.setTimeout(normalizarFormularios, 500);
                    } catch (error) {
                        console.error('No se pudo restaurar el borrador', error);
                        anunciar('No se pudo restaurar el borrador');
                    }
                }, { once: true });

                banner.querySelector('[data-cf-borrador-descartar]')?.addEventListener('click', () => {
                    localStorage.removeItem(CLAVE_BORRADOR);
                    banner.hidden = true;
                    anunciar('');
                }, { once: true });
            };

            // El paquete deja "Guardado" o "Guardado, pero..." al terminar bien.
            if (estado) {
                new MutationObserver(() => {
                    if (!estado.textContent.trim().startsWith('Guardado')) {
                        return;
                    }

                    hayCambios = false;
                    saliendo = false;
                    localStorage.removeItem(CLAVE_BORRADOR);

                    if (banner) {
                        banner.hidden = true;
                    }
                }).observe(estado, { childList: true, characterData: true, subtree: true });
            }

            /* ---------- Guardar borrador y publicar en la web ---------- */
            const botonPublicar = document.querySelector('[data-gjs-publicar]');

            const guardarEnServidor = async () => {
                const respuesta = await fetch(configuracion.urls.bewaren, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': configuracion.token,
                        Accept: 'application/json',
                    },
                    body: JSON.stringify({
                        project: editor.getProjectData(),
                        html: editor.getHtml(),
                        css: editor.getCss(),
                    }),
                });

                if (!respuesta.ok) {
                    throw new Error(`No se pudo guardar (HTTP ${respuesta.status})`);
                }
            };

            botonPublicar?.addEventListener('click', async () => {
                if (!configuracion?.urls?.bewaren || !botonPublicar.dataset.url) {
                    return;
                }

                botonPublicar.disabled = true;
                anunciar('Guardando...');

                try {
                    await guardarEnServidor();

                    const respuesta = await fetch(botonPublicar.dataset.url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': configuracion.token,
                            Accept: 'application/json',
                        },
                    });

                    if (!respuesta.ok) {
                        throw new Error(`No se pudo publicar (HTTP ${respuesta.status})`);
                    }

                    hayCambios = false;
                    localStorage.removeItem(CLAVE_BORRADOR);

                    if (banner) {
                        banner.hidden = true;
                    }

                    anunciar('Publicado');
                    avisar('Cambios publicados en la web');
                } catch (error) {
                    anunciar('No publicado: ' + error.message);
                } finally {
                    botonPublicar.disabled = false;
                }
            });

            /* ---------- Varios formularios en la misma página ---------- */
            const mapaIdsFormulario = (sufijo) => ({
                'contacto-form': `contacto-form${sufijo}`,
                nombre: `nombre${sufijo}`,
                email: `email${sufijo}`,
                telefono: `telefono${sufijo}`,
                mensaje: `mensaje${sufijo}`,
                website: `website${sufijo}`,
                'mensaje-contador': `mensaje-contador${sufijo}`,
                'contacto-submit': `contacto-submit${sufijo}`,
            });

            const sufijarFormulario = (formulario, sufijo) => {
                const mapa = mapaIdsFormulario(sufijo);

                formulario.find('*').forEach((hijo) => {
                    const atributos = hijo.getAttributes?.() || {};
                    const cambios = {};

                    if (atributos.id && mapa[atributos.id]) {
                        cambios.id = mapa[atributos.id];
                    }

                    if (atributos.for && mapa[atributos.for]) {
                        cambios.for = mapa[atributos.for];
                    }

                    if (Object.keys(cambios).length > 0) {
                        hijo.addAttributes(cambios);
                    }
                });
            };

            /*
             * GrapesJS sufija los ids al duplicar, pero no actualiza los `for` de
             * las etiquetas. Los alineamos con el id real de cada campo para que
             * el clic en la etiqueta siga enfocando su input.
             */
            const alinearEtiquetas = (formulario) => {
                const campos = {};

                formulario.find('input, textarea, select').forEach((campo) => {
                    const atributos = campo.getAttributes?.() || {};

                    if (atributos.name && atributos.id) {
                        campos[atributos.name] = atributos.id;
                    }
                });

                formulario.find('label').forEach((etiqueta) => {
                    const atributos = etiqueta.getAttributes?.() || {};
                    const destino = String(atributos.for || '').replace(/-\d+$/, '');

                    if (campos[destino] && campos[destino] !== atributos.for) {
                        etiqueta.addAttributes({ for: campos[destino] });
                    }
                });
            };

            const normalizarFormularios = () => {
                const formularios = editor.getWrapper()?.find('[data-cf-form]') || [];

                if (formularios.length === 0) {
                    return;
                }

                const idsUsados = new Set();

                formularios.forEach((formulario, indice) => {
                    const atributos = formulario.getAttributes?.() || {};
                    const elementoForm = formulario.find('[data-form-form]')[0];
                    const atributosForm = elementoForm?.getAttributes?.() || {};
                    let idSeccion = String(atributos.id || '');

                    if (indice === 0) {
                        if (idSeccion === '') {
                            idSeccion = 'contacto';
                            formulario.addAttributes({ id: idSeccion });
                        }
                    } else if (idSeccion === '' || idSeccion === 'contacto' || idsUsados.has(idSeccion)) {
                        let numero = 2;

                        while (idsUsados.has(`contacto-${numero}`)) {
                            numero++;
                        }

                        idSeccion = `contacto-${numero}`;
                        formulario.addAttributes({ id: idSeccion });
                    }

                    idsUsados.add(idSeccion);

                    const idFormulario = String(atributosForm.id || '');

                    if ((idFormulario === '' || idFormulario === 'contacto-form') && idSeccion !== 'contacto') {
                        sufijarFormulario(formulario, `-${idSeccion.replace(/^contacto-?/, '')}`);
                    }

                    alinearEtiquetas(formulario);
                });
            };

            editor.on('component:add', (componente) => {
                if (!listo) {
                    return;
                }

                const atributos = componente?.getAttributes?.() || {};

                if (atributos['data-cf-form'] !== undefined || atributos['data-form-form'] !== undefined) {
                    window.setTimeout(normalizarFormularios, 80);
                }
            });

            alIniciar(() => {
                window.setTimeout(() => {
                    listo = true;
                    hayCambios = false;
                    anunciar('');
                    normalizarFormularios();
                    mostrarBorrador();
                }, 1200);
            });

            /* ---------- Buscador de bloques ---------- */
            const normalizarTexto = (texto) => String(texto || '')
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .toLowerCase();

            const SINONIMOS_BLOQUES = [
                [/formulario|contacto/, 'correo email mensaje enviar'],
                [/producto|venta|precio|comprar/, 'precio pago tienda mercado'],
                [/carrusel|galeria|imagen/, 'fotos slider imagenes album'],
                [/noticia/, 'blog articulo novedad'],
                [/whatsapp/, 'chat telefono mensaje'],
                [/redes|social/, 'instagram facebook linkedin youtube'],
                [/pie|footer/, 'footer legal privacidad'],
                [/boton|enlace/, 'cta link'],
                [/animacion/, 'efecto movimiento scroll'],
                [/columna|cuadricula|grid/, 'layout estructura'],
                [/tabla/, 'datos comparativa'],
                [/hero|bienvenida/, 'portada inicio cabecera'],
            ];

            const instalarBuscador = () => {
                const contenedor = document.querySelector('.gjs-block-categories');

                if (!contenedor || contenedor.querySelector('[data-cf-buscador]')) {
                    return;
                }

                const caja = document.createElement('div');
                caja.className = 'cf-bloques-buscar';
                caja.setAttribute('data-cf-buscador', 'yes');
                caja.innerHTML = '<input type="search" placeholder="Buscar bloques…" aria-label="Buscar bloques"><small></small>';
                contenedor.insertBefore(caja, contenedor.firstChild);

                const input = caja.querySelector('input');
                const cuenta = caja.querySelector('small');
                const bloques = [...contenedor.querySelectorAll('.gjs-block')];

                bloques.forEach((bloque) => {
                    const texto = normalizarTexto(`${bloque.title} ${bloque.textContent}`);
                    const extra = SINONIMOS_BLOQUES
                        .filter(([patron]) => patron.test(texto))
                        .map(([, palabras]) => palabras)
                        .join(' ');

                    bloque.dataset.cfBuscar = normalizarTexto(`${texto} ${extra}`);
                });

                const aplicar = () => {
                    const consulta = normalizarTexto(input.value.trim());
                    let visibles = 0;

                    bloques.forEach((bloque) => {
                        const coincide = consulta === '' || (bloque.dataset.cfBuscar || '').includes(consulta);
                        bloque.style.display = coincide ? '' : 'none';

                        if (coincide) {
                            visibles++;
                        }
                    });

                    contenedor.querySelectorAll('.gjs-block-category').forEach((categoria) => {
                        const hay = [...categoria.querySelectorAll('.gjs-block')]
                            .some((bloque) => bloque.style.display !== 'none');

                        categoria.style.display = hay ? '' : 'none';

                        if (consulta !== '' && hay) {
                            categoria.classList.add('gjs-open');
                        }
                    });

                    cuenta.textContent = consulta === ''
                        ? `${bloques.length} bloques`
                        : `${visibles} de ${bloques.length} bloques`;
                };

                input.addEventListener('input', aplicar);
                aplicar();
            };

            instalarBuscador();
            window.setTimeout(instalarBuscador, 600);
            window.setTimeout(instalarBuscador, 1800);

            /*
             * Si GrapesJS vuelve a pintar el panel de bloques, el buscador se
             * pierde; lo reinstalamos sin duplicarlo.
             */
            const panelBloques = document.querySelector('.gjs-pn-views-container');

            if (panelBloques) {
                new MutationObserver(() => {
                    if (!document.querySelector('[data-cf-buscador]')) {
                        instalarBuscador();
                    }
                }).observe(panelBloques, { childList: true, subtree: true });
            }

            /* ---------- Paleta de marca en el selector de color ---------- */
            const configuracionEditor = editor.getConfig();

            if (configuracionEditor) {
                configuracionEditor.colorPicker = {
                    ...(configuracionEditor.colorPicker || {}),
                    showPalette: true,
                    palette: @json(\App\Support\BlockTheme::palette()),
                };

                /*
                 * Sin esto, cambiar de dispositivo solo cambia el estado del
                 * botón: el lienzo mantiene el ancho de escritorio. Con el modo
                 * de vista previa activo, el lienzo se ajusta a tableta/móvil.
                 */
                configuracionEditor.devicePreviewMode = true;
            }

            /* ---------- Interfaz en español ---------- */
            const IDIOMA = @json(\App\Support\EditorLocale::spanish());

            if (editor.I18n && IDIOMA) {
                editor.I18n.addMessages({ es: IDIOMA });

                try {
                    editor.I18n.setLocale('es');
                } catch {
                    // El locale ya venía seleccionado; los mensajes hacen el trabajo.
                }
            }

            /*
             * El Style Manager se pinta al inicializar el editor, antes de que
             * existan los mensajes y la paleta. Lo volvemos a pintar para que
             * las propiedades, los sectores y los colores nazcan ya en español.
             */
            try {
                editor.StyleManager.render();
            } catch {
                // Si el módulo no expone render, la traducción por DOM lo cubre.
            }

            const TITULOS_PANEL = {
                preview: 'Vista previa',
                fullscreen: 'Pantalla completa',
                'sw-visibility': 'Ver componentes',
                'export-template': 'Ver código',
                'open-sm': 'Estilos',
                'open-tm': 'Ajustes',
                'open-layers': 'Capas',
                'open-blocks': 'Bloques',
                undo: 'Deshacer',
                redo: 'Rehacer',
                'gjs-open-import-webpage': 'Importar HTML',
                'canvas-clear': 'Vaciar lienzo',
            };

            const NOMBRES_DISPOSITIVOS = {
                desktop: 'Escritorio',
                tablet: 'Tableta',
                mobileLandscape: 'Móvil horizontal',
                mobilePortrait: 'Móvil vertical',
            };

            const NOMBRES_SECTORES = {
                general: 'General',
                layout: 'Diseño',
                typography: 'Tipografía',
                decorations: 'Decoraciones',
                extra: 'Extras',
                flex: 'Flex',
                dimension: 'Dimensión',
            };

            const ETIQUETAS_TRAITS = {
                Id: 'ID',
                Title: 'Título',
                Class: 'Clase',
                Classes: 'Clases',
                Href: 'Enlace',
                Target: 'Abrir en',
                Alt: 'Texto alternativo',
                Source: 'Origen',
                Src: 'Origen',
            };

            const TRADUCCION_SECTORES = {
                General: 'General',
                Flex: 'Flex',
                Dimension: 'Dimensión',
                Typography: 'Tipografía',
                Decorations: 'Decoraciones',
                Extra: 'Extras',
                Layout: 'Diseño',
            };

            const TRADUCCION_ESTILOS = {
                Display: 'Mostrar',
                Float: 'Flotar',
                Position: 'Posición',
                Top: 'Arriba',
                Right: 'Derecha',
                Left: 'Izquierda',
                Bottom: 'Abajo',
                Overflow: 'Desbordamiento',
                'Flex direction': 'Dirección flex',
                'Flex wrap': 'Salto flex',
                'Justify content': 'Justificar contenido',
                'Align items': 'Alinear ítems',
                'Align content': 'Alinear contenido',
                Order: 'Orden',
                'Flex basis': 'Base flex',
                'Flex grow': 'Crecer',
                'Flex shrink': 'Encoger',
                'Align self': 'Alinear a sí mismo',
                Width: 'Ancho',
                Height: 'Alto',
                'Max width': 'Ancho máximo',
                'Min width': 'Ancho mínimo',
                'Max height': 'Alto máximo',
                'Min height': 'Alto mínimo',
                Margin: 'Margen',
                'Margin top': 'Margen arriba',
                'Margin right': 'Margen derecha',
                'Margin bottom': 'Margen abajo',
                'Margin left': 'Margen izquierda',
                Padding: 'Relleno',
                'Padding top': 'Relleno arriba',
                'Padding right': 'Relleno derecha',
                'Padding bottom': 'Relleno abajo',
                'Padding left': 'Relleno izquierda',
                'Font family': 'Tipografía',
                'Font size': 'Tamaño de fuente',
                'Font weight': 'Grosor de fuente',
                'Font style': 'Estilo de fuente',
                'Letter spacing': 'Espaciado',
                'Line height': 'Altura de línea',
                'Text align': 'Alineación',
                'Text decoration': 'Decoración',
                'Text transform': 'Mayúsculas',
                Color: 'Color',
                'Background color': 'Color de fondo',
                'Background image': 'Imagen de fondo',
                Border: 'Borde',
                'Border width': 'Ancho de borde',
                'Border style': 'Estilo de borde',
                'Border color': 'Color de borde',
                'Border radius': 'Radio de borde',
                'Top Left': 'Sup. izq.',
                'Top Right': 'Sup. der.',
                'Bottom Left': 'Inf. izq.',
                'Bottom Right': 'Inf. der.',
                Style: 'Estilo',
                'Box shadow': 'Sombra',
                'Text shadow': 'Sombra de texto',
                Opacity: 'Opacidad',
                Transition: 'Transición',
                Transform: 'Transformación',
                Cursor: 'Cursor',
            };

            const CORRECCIONES_TITULOS = {
                'Abrir Administrador de estilos': 'Estilos',
                'Abrir Aministrador de capas': 'Capas',
                'Administrador de estilos': 'Estilos',
                'Administrador de capas': 'Capas',
                'Abrir Bloques': 'Bloques',
            };

            const actualizarEtiquetasTraits = () => {
                document.querySelectorAll('.gjs-label').forEach((etiqueta) => {
                    const texto = etiqueta.textContent.trim();

                    if (ETIQUETAS_TRAITS[texto]) {
                        etiqueta.textContent = ETIQUETAS_TRAITS[texto];
                    }
                });
            };

            const traducirEstiloDom = () => {
                document.querySelectorAll('.gjs-sm-sector-label').forEach((etiqueta) => {
                    const texto = etiqueta.textContent.trim();

                    if (TRADUCCION_SECTORES[texto]) {
                        etiqueta.textContent = TRADUCCION_SECTORES[texto];
                    }
                });

                document.querySelectorAll('.gjs-sm-label .gjs-sm-icon').forEach((icono) => {
                    const texto = icono.textContent.trim();

                    if (TRADUCCION_ESTILOS[texto]) {
                        icono.textContent = TRADUCCION_ESTILOS[texto];
                        icono.setAttribute('title', TRADUCCION_ESTILOS[texto]);
                    }
                });

                const etiquetaClases = document.getElementById('gjs-clm-label');

                if (etiquetaClases && etiquetaClases.textContent.trim() !== 'Clases') {
                    etiquetaClases.textContent = 'Clases';
                }

                document.querySelectorAll('.gjs-clm-label-sel').forEach((etiqueta) => {
                    if (etiqueta.textContent.trim() === 'Selected:') {
                        etiqueta.textContent = 'Seleccionado:';
                    }
                });

                const estados = document.getElementById('gjs-clm-states');

                if (estados) {
                    const traducciones = {
                        '': '— Estado —',
                        hover: 'Hover',
                        active: 'Clic',
                        'nth-of-type(2n)': 'Pares/impares',
                    };

                    [...estados.options].forEach((opcion) => {
                        const texto = traducciones[opcion.value];

                        if (texto && opcion.textContent !== texto) {
                            opcion.textContent = texto;
                        }
                    });
                }

                const agregarClase = document.getElementById('gjs-clm-add-tag');

                if (agregarClase) {
                    agregarClase.setAttribute('title', 'Añadir clase');
                    agregarClase.setAttribute('aria-label', 'Añadir clase');
                }

                const avisoEstilos = document.querySelector('.gjs-sm-header');
                const textoAviso = 'Selecciona un elemento para usar el administrador de estilos';

                if (avisoEstilos && avisoEstilos.textContent.trim() !== textoAviso) {
                    avisoEstilos.textContent = textoAviso;
                }
            };

            const corregirTitulosDom = () => {
                document.querySelectorAll('.gjs-pn-btn[title]').forEach((boton) => {
                    const titulo = CORRECCIONES_TITULOS[boton.title];

                    if (titulo) {
                        boton.setAttribute('title', titulo);
                        boton.setAttribute('aria-label', titulo);
                    }
                });
            };

            const actualizarInterfaz = () => {
                editor.Panels.getPanels().forEach((panel) => {
                    (panel.get('buttons') || []).forEach((boton) => {
                        const titulo = TITULOS_PANEL[boton.id];

                        if (!titulo) {
                            return;
                        }

                        boton.set('attributes', {
                            ...(boton.get('attributes') || {}),
                            title: titulo,
                            'aria-label': titulo,
                        });

                        if (boton.view?.el) {
                            boton.view.el.setAttribute('title', titulo);
                            boton.view.el.setAttribute('aria-label', titulo);
                        }
                    });
                });

                editor.Devices.getAll().forEach((dispositivo) => {
                    const nombre = NOMBRES_DISPOSITIVOS[dispositivo.id];

                    if (nombre) {
                        dispositivo.set('name', nombre);
                    }
                });

                document.querySelectorAll('.gjs-pn-devices-c .gjs-pn-btn').forEach((boton, indice) => {
                    const nombre = ['Escritorio', 'Tableta', 'Móvil'][indice];

                    if (nombre) {
                        boton.setAttribute('title', nombre);
                        boton.setAttribute('aria-label', `Ver en ${nombre.toLowerCase()}`);
                    }
                });

                editor.StyleManager.getSectors().forEach((sector) => {
                    if (NOMBRES_SECTORES[sector.id]) {
                        sector.set('name', NOMBRES_SECTORES[sector.id]);
                    }
                });

                actualizarEtiquetasTraits();
                traducirEstiloDom();
                corregirTitulosDom();
            };

            let traduciendo = false;

            const observarPaneles = () => {
                const contenedor = document.querySelector('.gjs-pn-views-container');

                if (!contenedor || contenedor.dataset.cfObservado === 'yes') {
                    return;
                }

                contenedor.dataset.cfObservado = 'yes';
                new MutationObserver(() => {
                    if (traduciendo) {
                        return;
                    }

                    traduciendo = true;
                    window.requestAnimationFrame(() => {
                        traducirEstiloDom();
                        actualizarEtiquetasTraits();
                        traduciendo = false;
                    });
                }).observe(contenedor, { childList: true, subtree: true, characterData: true });
            };

            const observarTraits = () => {
                const contenedor = document.querySelector('.gjs-traits-c');

                if (!contenedor || contenedor.dataset.cfObservado === 'yes') {
                    return;
                }

                contenedor.dataset.cfObservado = 'yes';
                new MutationObserver(() => actualizarEtiquetasTraits())
                    .observe(contenedor, { childList: true, subtree: true });
            };

            actualizarInterfaz();
            observarPaneles();
            window.setTimeout(actualizarInterfaz, 800);
            window.setTimeout(observarPaneles, 1200);
            window.setTimeout(observarTraits, 1200);
            window.setTimeout(corregirTitulosDom, 1200);
            window.setTimeout(corregirTitulosDom, 2500);
            editor.on('load', () => {
                window.setTimeout(actualizarInterfaz, 0);
                window.setTimeout(observarPaneles, 0);
            });
            editor.on('component:selected', () => {
                window.setTimeout(() => {
                    observarTraits();
                    actualizarEtiquetasTraits();
                }, 0);
            });
            editor.on('component:update', () => window.setTimeout(actualizarEtiquetasTraits, 0));

            /*
             * Al cambiar de panel, GrapesJS vuelve a pintar los botones con el
             * título del locale. Reaplicamos los títulos cortos en español.
             */
            ['open-sm', 'open-tm', 'open-layers', 'open-blocks', 'open-assets'].forEach((comando) => {
                editor.on(`run:${comando}`, () => window.setTimeout(corregirTitulosDom, 0));
            });
        })();
    </script>
    <script>
        (() => {
            const modal = document.getElementById('gjs-help');
            const trigger = document.querySelector('[data-gjs-help]');

            if (!modal || !trigger) {
                return;
            }

            const closers = modal.querySelectorAll('[data-gjs-help-close]');
            const closeButton = modal.querySelector('.gjs-help__header button');
            let lastFocused = null;

            const open = () => {
                lastFocused = document.activeElement;
                modal.hidden = false;
                trigger.setAttribute('aria-expanded', 'true');
                document.body.classList.add('gjs-help-open');
                closeButton?.focus();
            };

            const close = () => {
                modal.hidden = true;
                trigger.setAttribute('aria-expanded', 'false');
                document.body.classList.remove('gjs-help-open');
                lastFocused?.focus?.();
            };

            trigger.addEventListener('click', open);
            closers.forEach((element) => element.addEventListener('click', close));

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && !modal.hidden) {
                    close();
                }
            });
        })();
    </script>
</body>
</html>
