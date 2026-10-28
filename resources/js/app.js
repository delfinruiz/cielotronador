// Los logos de las plantillas guardadas usan una URL estable y dos variantes.
// Al cambiar de tema se intercambia la variante clara por la oscura.
const applyBrandLogos = () => {
    const isDark = document.documentElement.classList.contains('dark');

    document.querySelectorAll('img[data-brand-logo-light]').forEach((image) => {
        const target = isDark ? image.dataset.brandLogoDark : image.dataset.brandLogoLight;

        if (target && image.getAttribute('src') !== target) {
            image.setAttribute('src', target);
        }
    });
};

document.addEventListener('DOMContentLoaded', applyBrandLogos);

// Toggle de tema claro/oscuro. La clase inicial la aplica un script inline
// en <head> (evita el flash); aquí solo gestionamos el botón y la persistencia.
document.addEventListener('DOMContentLoaded', () => {
    const root = document.documentElement;
    const button = document.getElementById('theme-toggle');

    if (!button) {
        return;
    }

    const syncIcons = () => {
        const isDark = root.classList.contains('dark');
        button.querySelector('.icon-sun')?.classList.toggle('hidden', !isDark);
        button.querySelector('.icon-moon')?.classList.toggle('hidden', isDark);
        button.setAttribute('aria-label', isDark ? 'Cambiar a modo claro' : 'Cambiar a modo oscuro');
    };

    syncIcons();

    button.addEventListener('click', () => {
        root.classList.toggle('dark');
        localStorage.setItem('theme', root.classList.contains('dark') ? 'dark' : 'light');
        syncIcons();
        applyBrandLogos();
    });
});

// Menú móvil.
document.addEventListener('DOMContentLoaded', () => {
    const button = document.getElementById('mobile-menu-toggle');
    const menu = document.getElementById('mobile-menu');

    if (!button || !menu) {
        return;
    }

    button.addEventListener('click', () => {
        menu.classList.toggle('hidden');
        button.setAttribute('aria-expanded', menu.classList.contains('hidden') ? 'false' : 'true');
    });
});

// Compatibilidad con las tarjetas antiguas de directiva: traían un botón con
// data-cf-lightbox. Se convierten en un contenedor normal para que la foto no
// sea focusable ni abra el visor en las páginas ya guardadas.
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-cf-directiva] [data-directiva-avatar][data-cf-lightbox]').forEach((boton) => {
        const avatar = document.createElement('div');
        avatar.setAttribute('data-directiva-avatar', 'yes');
        avatar.innerHTML = boton.innerHTML;
        boton.replaceWith(avatar);
    });
});

// Galería: paginación sin recarga (fetch) + modal de ampliación.
// Se usa delegación de eventos sobre <body> para sobrevivir a los
// re-renderizados del partial de la galería.
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('gallery-modal');

    if (!modal) {
        return;
    }

    const imagen = document.getElementById('gallery-modal-img');
    const caption = document.getElementById('gallery-modal-caption');
    const botonCerrar = document.getElementById('gallery-modal-close');
    const botonAnterior = document.getElementById('gallery-modal-prev');
    const botonSiguiente = document.getElementById('gallery-modal-next');
    let indiceActual = -1;

    /*
     * Las tarjetas de "Nuestro equipo" no abren el visor. Las plantillas
     * antiguas todavía pueden traer una tarjeta de directiva con
     * `data-cf-lightbox`; se excluye aquí para que el modal no la muestre ni
     * la incluya en el recorrido de flechas.
     */
    const fotos = () => [...document.querySelectorAll('[data-gallery-full], [data-cf-lightbox]')]
        .filter((elemento) => !elemento.closest('[data-cf-directiva]'));

    /*
     * La galería dinámica guarda la URL grande en `data-gallery-full`. Las
     * tarjetas ampliables usan `data-cf-lightbox` y muestran la misma imagen
     * que el lienzo, así que se lee el <img> al abrir el modal.
     */
    const datosDe = (elemento) => {
        const miniatura = elemento.querySelector('img');

        return {
            src: elemento.dataset.galleryFull || miniatura?.src || '',
            alt: miniatura?.alt || '',
            caption: elemento.dataset.galleryCaption || miniatura?.alt || '',
        };
    };

    // === Modal ===
    const abrir = (indice) => {
        const lista = fotos();

        if (indice < 0 || indice >= lista.length) {
            return;
        }

        const datos = datosDe(lista[indice]);

        indiceActual = indice;
        imagen.src = datos.src;
        imagen.alt = datos.alt;
        caption.textContent = datos.caption || `Foto ${indice + 1} de ${lista.length}`;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
        botonCerrar.focus();
    };

    const cerrar = () => {
        indiceActual = -1;
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    };

    const mover = (delta) => {
        const total = fotos().length;

        if (indiceActual < 0 || total === 0) {
            return;
        }

        abrir((indiceActual + delta + total) % total);
    };

    document.body.addEventListener('click', (event) => {
        const foto = event.target.closest('[data-gallery-full], [data-cf-lightbox]');

        if (foto) {
            const indice = fotos().indexOf(foto);

            if (indice >= 0) {
                abrir(indice);
            }

            return;
        }

        if (event.target.closest('[data-gallery-page]')) {
            paginar(event);

            return;
        }
    });

    botonCerrar.addEventListener('click', cerrar);
    botonAnterior.addEventListener('click', () => mover(-1));
    botonSiguiente.addEventListener('click', () => mover(1));

    modal.addEventListener('click', (event) => {
        if (event.target === modal) {
            cerrar();
        }
    });

    document.addEventListener('keydown', (event) => {
        if (modal.classList.contains('hidden')) {
            return;
        }

        if (event.key === 'Escape') {
            cerrar();
        } else if (event.key === 'ArrowLeft') {
            mover(-1);
        } else if (event.key === 'ArrowRight') {
            mover(1);
        }
    });

    // === Paginación sin recarga ===
    let cargando = false;

    const paginar = async (event) => {
        const enlace = event.target.closest('[data-gallery-page]');

        if (!enlace || cargando) {
            return;
        }

        event.preventDefault();

        const raiz = document.querySelector('[data-gallery-root]');

        if (!raiz) {
            window.location.href = enlace.href;

            return;
        }

        cargando = true;
        raiz.setAttribute('aria-busy', 'true');
        raiz.classList.add('opacity-60', 'transition-opacity');

        try {
            const url = new URL(enlace.href, window.location.origin);
            url.hash = '';

            const respuesta = await fetch(url, { headers: { 'X-Partial': 'galeria' } });

            if (!respuesta.ok) {
                throw new Error(`HTTP ${respuesta.status}`);
            }

            raiz.outerHTML = await respuesta.text();
            history.pushState({ galeria: url.pathname + url.search }, '', enlace.href);

            // Si el grid quedó fuera del viewport, lo traemos a la vista sin
            // recorrer toda la página desde arriba.
            const nuevo = document.querySelector('[data-gallery-root]');

            if (nuevo && nuevo.getBoundingClientRect().top < 0) {
                nuevo.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }

            initReveal(nuevo);
        } catch (error) {
            window.location.href = enlace.href;
        } finally {
            cargando = false;
        }
    };

    window.addEventListener('popstate', (event) => {
        if (event.state?.galeria) {
            fetch(event.state.galeria, { headers: { 'X-Partial': 'galeria' } })
                .then((respuesta) => (respuesta.ok ? respuesta.text() : Promise.reject(new Error('fetch'))))
                .then((html) => {
                    const raiz = document.querySelector('[data-gallery-root]');

                    raiz.outerHTML = html;
                    initReveal(document.querySelector('[data-gallery-root]'));
                })
                .catch(() => window.location.reload());
        }
    });
});

// Modal de horarios: amplía la imagen al hacer clic.
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('horario-modal');

    if (!modal) {
        return;
    }

    const imagen = document.getElementById('horario-modal-img');
    const botonCerrar = document.getElementById('horario-modal-close');
    const botonAnterior = document.getElementById('horario-modal-prev');
    const botonSiguiente = document.getElementById('horario-modal-next');
    let indiceActual = -1;

    const horarios = () => [...document.querySelectorAll('[data-horario-full]')];

    const abrir = (indice) => {
        const lista = horarios();

        if (indice < 0 || indice >= lista.length) {
            return;
        }

        indiceActual = indice;
        imagen.src = lista[indice].dataset.horarioFull;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
        botonCerrar.focus();
    };

    const cerrar = () => {
        indiceActual = -1;
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    };

    const mover = (delta) => {
        const total = horarios().length;

        if (indiceActual < 0 || total === 0) {
            return;
        }

        abrir((indiceActual + delta + total) % total);
    };

    document.body.addEventListener('click', (event) => {
        const horario = event.target.closest('[data-horario-full]');

        if (horario) {
            abrir(horarios().indexOf(horario));
        }
    });

    botonCerrar.addEventListener('click', cerrar);
    botonAnterior.addEventListener('click', () => mover(-1));
    botonSiguiente.addEventListener('click', () => mover(1));

    modal.addEventListener('click', (event) => {
        if (event.target === modal) {
            cerrar();
        }
    });

    document.addEventListener('keydown', (event) => {
        if (modal.classList.contains('hidden')) {
            return;
        }

        if (event.key === 'Escape') {
            cerrar();
        } else if (event.key === 'ArrowLeft') {
            mover(-1);
        } else if (event.key === 'ArrowRight') {
            mover(1);
        }
    });
});

// Modal de noticia: abre el contenido completo sin abandonar la página.
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('noticia-modal');

    if (!modal) {
        return;
    }

    const datos = JSON.parse(document.getElementById('noticias-data').textContent);
    const imagen = document.getElementById('noticia-modal-img');
    const fecha = document.getElementById('noticia-modal-fecha');
    const titulo = document.getElementById('noticia-modal-titulo');
    const cuerpo = document.getElementById('noticia-modal-cuerpo');
    const botonCerrar = document.getElementById('noticia-modal-close');
    let disparador = null;

    const abrir = (indice) => {
        const noticia = datos[indice];

        if (!noticia) {
            return;
        }

        imagen.src = noticia.imagen;
        imagen.alt = noticia.titulo;
        fecha.textContent = `${noticia.fecha} · Noticias`;
        fecha.setAttribute('datetime', noticia.fecha.split('/').reverse().join('-'));
        titulo.textContent = noticia.titulo;
        cuerpo.innerHTML = noticia.cuerpo.map((parrafo) => `<p>${parrafo}</p>`).join('');

        disparador = document.activeElement;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
        botonCerrar.focus();
    };

    const cerrar = () => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');

        if (disparador instanceof HTMLElement) {
            disparador.focus();
        }
    };

    document.body.addEventListener('click', (event) => {
        const boton = event.target.closest('[data-noticia]');

        if (boton) {
            abrir(Number(boton.dataset.noticia));

            return;
        }

        if (event.target === modal) {
            cerrar();
        }
    });

    botonCerrar.addEventListener('click', cerrar);

    document.addEventListener('keydown', (event) => {
        if (!modal.classList.contains('hidden') && event.key === 'Escape') {
            cerrar();
        }
    });
});

// ===== Animaciones de aparición al hacer scroll (reveal) =====
const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

let revealObserver = null;

const revealElement = (el) => {
    const retardo = el.dataset.revealDelay;

    if (retardo) {
        el.style.transitionDelay = `${retardo}ms`;
    }

    el.classList.add('reveal-in');
};

const initReveal = (raiz = document) => {
    const alCargar = raiz.querySelectorAll('[data-reveal][data-reveal-trigger="load"]:not(.reveal-in)');

    alCargar.forEach((el) => {
        if (reduceMotion) {
            el.classList.add('reveal-in');

            return;
        }

        // Deja que el navegador pinte el estado inicial antes de animar.
        window.setTimeout(() => revealElement(el), 60);
    });

    const elementos = raiz.querySelectorAll('[data-reveal]:not([data-reveal-trigger="load"]):not(.reveal-in)');

    if (reduceMotion) {
        elementos.forEach((el) => el.classList.add('reveal-in'));

        return;
    }

    if (!revealObserver) {
        revealObserver = new IntersectionObserver((entradas) => {
            entradas.forEach((entrada) => {
                if (!entrada.isIntersecting) {
                    return;
                }

                revealElement(entrada.target);
                revealObserver.unobserve(entrada.target);
            });
        }, { threshold: 0.15, rootMargin: '0px 0px -60px 0px' });
    }

    elementos.forEach((el) => revealObserver.observe(el));
};

document.addEventListener('DOMContentLoaded', () => {
    initReveal();
});

// ===== Barra de progreso de scroll + scrollspy + encogido del header =====
document.addEventListener('DOMContentLoaded', () => {
    const barraProgreso = document.getElementById('scroll-progress');
    const header = document.getElementById('site-header');
    const botonesArriba = document.querySelectorAll('[data-top-button], [id^="top-button"]');
    const enlacesNav = document.querySelectorAll('[data-nav-link]');
    const secciones = [...enlacesNav]
        .map((enlace) => {
            const href = enlace.getAttribute('href') ?? '';

            return href.startsWith('#') ? document.querySelector(href) : null;
        })
        .filter(Boolean);
    let scrollYAnterior = window.scrollY;
    let rotacion = 0;
    let temporizadorRebote = null;

    const alDesplazarse = () => {
        const alto = document.documentElement.scrollHeight - window.innerHeight;
        const avance = alto > 0 ? Math.min(window.scrollY / alto, 1) : 0;

        if (barraProgreso) {
            barraProgreso.style.transform = `scaleX(${avance})`;
        }

        if (header) {
            header.classList.toggle('is-scrolled', window.scrollY > 24);
        }

        if (botonesArriba.length) {
            const visible = window.scrollY > 300;
            const delta = window.scrollY - scrollYAnterior;

            if (!reduceMotion && delta !== 0) {
                rotacion += delta * 0.15;
            }

            botonesArriba.forEach((botonArriba) => {
                botonArriba.classList.toggle('is-visible', visible);
                botonArriba.toggleAttribute('inert', !visible);
                botonArriba.classList.add('is-scrolling');

                const rotador = botonArriba.querySelector('.top-button-rotator');

                if (rotador && !reduceMotion && delta !== 0) {
                    rotador.style.setProperty('--rotacion', `${rotacion}deg`);
                }
            });

            scrollYAnterior = window.scrollY;

            clearTimeout(temporizadorRebote);
            temporizadorRebote = setTimeout(() => {
                botonesArriba.forEach((botonArriba) => botonArriba.classList.remove('is-scrolling'));
            }, 220);
        }

        if (secciones.length) {
            const umbral = window.scrollY + window.innerHeight * 0.4;
            let activa = null;

            for (const seccion of secciones) {
                if (seccion.offsetTop <= umbral) {
                    activa = seccion;
                }
            }

            enlacesNav.forEach((enlace) => {
                enlace.classList.toggle('is-active-section', activa !== null && enlace.getAttribute('href') === `#${activa.id}`);
            });
        }
    };

    botonesArriba.forEach((botonArriba) => {
        botonArriba.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    });

    alDesplazarse();
    window.addEventListener('scroll', alDesplazarse, { passive: true });
});

// ===== Efecto parallax de las capas decorativas =====
document.addEventListener('DOMContentLoaded', () => {
    const capas = document.querySelectorAll('[data-parallax]');

    if (!capas.length || reduceMotion) {
        return;
    }

    let frame = null;

    const animarParallax = () => {
        frame = null;
        const mitadAlto = window.innerHeight / 2;

        capas.forEach((capa) => {
            const rect = capa.getBoundingClientRect();

            if (rect.bottom < -300 || rect.top > window.innerHeight + 300) {
                return;
            }

            const velocidad = Number(capa.dataset.parallax) || 0.2;
            const desplazamiento = (rect.top + rect.height / 2 - mitadAlto) * velocidad;

            const velocidadX = Number(capa.dataset.parallaxX) || 0;
            const maxX = window.innerWidth * 0.1;
            const desplazamientoX = Math.max(-maxX, Math.min(maxX, window.scrollY * velocidadX));

            capa.style.transform = `translate3d(${desplazamientoX}px, ${desplazamiento}px, 0)`;
        });
    };

    const alSolicitar = () => {
        if (frame === null) {
            frame = requestAnimationFrame(animarParallax);
        }
    };

    animarParallax();
    window.addEventListener('scroll', alSolicitar, { passive: true });
    window.addEventListener('resize', alSolicitar);
});

// Banner de consentimiento de cookies.
document.addEventListener('DOMContentLoaded', () => {
    const banner = document.getElementById('cookie-banner');

    if (!banner) {
        return;
    }

    const CLAVE = 'cielotronador_cookie_consent';

    const mostrar = () => {
        banner.hidden = false;
        requestAnimationFrame(() => {
            requestAnimationFrame(() => banner.classList.add('is-visible'));
        });
    };

    const ocultar = () => {
        banner.classList.remove('is-visible');
        window.setTimeout(() => {
            banner.hidden = true;
        }, 300);
    };

    if (!localStorage.getItem(CLAVE)) {
        mostrar();
    }

    banner.querySelectorAll('[data-cookie-choice]').forEach((boton) => {
        boton.addEventListener('click', () => {
            localStorage.setItem(CLAVE, boton.dataset.cookieChoice);
            ocultar();
        });
    });
});

// Widget de WhatsApp: cada bloque flotante abre su propio panel de chat y, al
// enviar, redirige a wa.me con el número y el mensaje configurados en el editor.
document.addEventListener('DOMContentLoaded', () => {
    const widgets = document.querySelectorAll('[data-wa-widget]');

    if (!widgets.length) {
        return;
    }

    const NUMERO_POR_DEFECTO = '56998970550';

    widgets.forEach((widget) => {
        const boton = widget.querySelector('[data-wa-toggle]');
        const panel = widget.querySelector('[data-wa-panel]');
        const botonCerrar = widget.querySelector('[data-wa-close]');
        const formulario = widget.querySelector('[data-wa-form]');
        const input = widget.querySelector('[data-wa-input]');

        if (!boton || !panel || !formulario || !input) {
            return;
        }

        const numero = (widget.dataset.waNumber || '').replace(/\D/g, '') || NUMERO_POR_DEFECTO;
        const tituloVisible = widget.querySelector('[data-wa-title-text]');
        const mensajeVisible = widget.querySelector('[data-wa-message-text]');
        const titulo = (tituloVisible?.textContent || '').trim()
            || (widget.dataset.waTitle || '').trim()
            || 'WhatsApp';
        const mensaje = (mensajeVisible?.textContent || '').trim()
            || (widget.dataset.waMessage || '').trim()
            || 'Hola, quiero hacer una consulta.';

        panel.setAttribute('aria-label', `Chat de WhatsApp con ${titulo}`);

        if (!panel.style.display) {
            panel.style.display = 'none';
        }

        let temporizadorCierre = null;

        const abrir = () => {
            window.clearTimeout(temporizadorCierre);
            widget.setAttribute('data-wa-open', 'yes');
            panel.style.display = 'block';
            requestAnimationFrame(() => {
                requestAnimationFrame(() => panel.classList.add('is-open'));
            });
            boton.setAttribute('aria-expanded', 'true');
            input.focus();
        };

        const cerrar = () => {
            panel.classList.remove('is-open');
            boton.setAttribute('aria-expanded', 'false');
            window.clearTimeout(temporizadorCierre);
            temporizadorCierre = window.setTimeout(() => {
                panel.style.display = 'none';
                widget.removeAttribute('data-wa-open');
            }, 200);
        };

        boton.addEventListener('click', () => {
            if (panel.style.display === 'none') {
                abrir();
            } else {
                cerrar();
            }
        });

        botonCerrar?.addEventListener('click', cerrar);

        document.addEventListener('click', (event) => {
            if (panel.style.display !== 'none' && !widget.contains(event.target)) {
                cerrar();
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && panel.style.display !== 'none') {
                cerrar();
            }
        });

        formulario.addEventListener('submit', (event) => {
            event.preventDefault();

            const texto = input.value.trim() || mensaje;
            const url = `https://wa.me/${numero}?text=${encodeURIComponent(texto)}`;

            window.open(url, '_blank', 'noopener');
            cerrar();
        });
    });
});

// Carrusel: autoplay, flechas y puntos sobre scroll-snap nativo.
// Sin JavaScript el carrusel sigue siendo desplazable (scroll-snap).
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-cf-carousel]').forEach((carrusel) => {
        const pista = carrusel.querySelector('[data-carousel-track]');
        const diapositivas = [...carrusel.querySelectorAll('[data-carousel-slide]')];
        const anterior = carrusel.querySelector('[data-carousel-prev]');
        const siguiente = carrusel.querySelector('[data-carousel-next]');
        const contenedorPuntos = carrusel.querySelector('[data-carousel-dots]');

        if (!pista || diapositivas.length < 2) {
            return;
        }

        const total = diapositivas.length;
        const autoplay = carrusel.dataset.carouselAutoplay !== 'no';
        const intervalo = Math.max(1500, Number(carrusel.dataset.carouselInterval) || 5000);
        let temporizador = null;

        const indiceActual = () => {
            const ancho = pista.clientWidth;

            return ancho ? Math.round(pista.scrollLeft / ancho) : 0;
        };

        const ir = (indice) => {
            const destino = ((indice % total) + total) % total;

            pista.scrollTo({ left: destino * pista.clientWidth, behavior: reduceMotion ? 'auto' : 'smooth' });
        };

        // Puntos (se regeneran para coincidir con el número real de diapositivas).
        let puntos = [];

        if (contenedorPuntos) {
            contenedorPuntos.innerHTML = '';
            puntos = diapositivas.map((_, indice) => {
                const boton = document.createElement('button');
                boton.type = 'button';
                boton.setAttribute('data-carousel-dot', 'yes');
                boton.setAttribute('aria-label', `Ir a la diapositiva ${indice + 1}`);
                boton.addEventListener('click', () => ir(indice));
                contenedorPuntos.appendChild(boton);

                return boton;
            });
        }

        const actualizarPuntos = () => {
            const activo = indiceActual();

            puntos.forEach((punto, indice) => punto.classList.toggle('active', indice === activo));
        };

        const detener = () => {
            if (temporizador) {
                window.clearInterval(temporizador);
                temporizador = null;
            }
        };

        const iniciar = () => {
            detener();

            if (autoplay && !reduceMotion) {
                temporizador = window.setInterval(() => ir(indiceActual() + 1), intervalo);
            }
        };

        anterior?.addEventListener('click', () => ir(indiceActual() - 1));
        siguiente?.addEventListener('click', () => ir(indiceActual() + 1));
        pista.addEventListener('scroll', actualizarPuntos, { passive: true });

        carrusel.addEventListener('mouseenter', detener);
        carrusel.addEventListener('mouseleave', iniciar);
        carrusel.addEventListener('focusin', detener);
        carrusel.addEventListener('focusout', iniciar);

        actualizarPuntos();
        iniciar();
    });
});

// Formulario configurable: inyecta el token CSRF y muestra el aviso de envío.
document.addEventListener('DOMContentLoaded', () => {
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    document.querySelectorAll('[data-form-token]').forEach((input) => {
        input.value = token;
    });

    const mostrarAviso = (mensaje, tipo) => {
        const toast = document.createElement('div');
        toast.setAttribute('role', tipo === 'error' ? 'alert' : 'status');
        toast.style.cssText = [
            'position:fixed', 'bottom:1.5rem', 'left:50%', 'transform:translateX(-50%)',
            'z-index:10000', 'max-width:90vw', 'padding:14px 20px', 'border-radius:12px',
            'color:#fff', 'font:600 14px/1.5 ui-sans-serif,system-ui,sans-serif',
            'box-shadow:0 15px 35px -12px rgba(0,0,0,.4)',
            'background-color:' + (tipo === 'error' ? '#dc2626' : '#16a34a'),
        ].join(';');
        toast.textContent = mensaje;
        document.body.appendChild(toast);
        window.setTimeout(() => toast.remove(), 6000);
    };

    const formulario = document.querySelector('[data-cf-form]');
    const estado = document.body.dataset.contactoEstado;

    if (estado === 'enviado') {
        mostrarAviso(formulario?.dataset.formSuccess || '¡Gracias! Tu mensaje fue enviado.', 'exito');
    } else if (estado === 'error') {
        mostrarAviso('No pudimos enviar tu mensaje. Inténtalo de nuevo.', 'error');
    }

    const errores = document.body.dataset.contactoErrores || '';

    if (errores) {
        mostrarAviso(errores, 'error');
    }
});

// Formulario de contacto: habilita "Enviar" solo cuando los tres campos son
// válidos y muestra cuántos caracteres faltan para el mínimo del mensaje.
// Se inicializa cada formulario por separado para que varios bloques de
// contacto en la misma página funcionen de forma independiente.
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-form-form]').forEach((contacto) => {
        const nombre = contacto.querySelector('[name="nombre"]');
        const email = contacto.querySelector('[name="email"]');
        const mensaje = contacto.querySelector('[name="mensaje"]');
        const contador = contacto.querySelector('[data-form-counter], .cf-form-hint');
        const boton = contacto.querySelector('[data-form-button-text]');
        const MINIMO_MENSAJE = 10;
        const MAXIMO_MENSAJE = 3000;

        if (!nombre || !email || !mensaje || !boton) {
            return;
        }

        /*
         * El formulario puede vivir en la portada o en una página interna.
         * Enviamos la ruta actual para volver a la página correcta después de
         * enviar o de un error de validación.
         */
        let retorno = contacto.querySelector('[name="_return"]');

        if (!retorno) {
            retorno = document.createElement('input');
            retorno.type = 'hidden';
            retorno.name = '_return';
            contacto.appendChild(retorno);
        }

        retorno.value = window.location.pathname + window.location.search;

        const esValido = () => {
            const nombreOk = nombre.value.trim().length >= 3;
            const emailOk = email.value.trim() !== '' && email.checkValidity();
            const mensajeOk = mensaje.value.trim().length >= MINIMO_MENSAJE;

            return nombreOk && emailOk && mensajeOk;
        };

        const actualizarContador = () => {
            if (!contador) {
                return;
            }

            const longitud = mensaje.value.trim().length;
            const faltan = Math.max(0, MINIMO_MENSAJE - longitud);

            contador.classList.remove('text-zinc-500', 'dark:text-zinc-400', 'text-red-600', 'dark:text-red-400', 'text-green-600', 'dark:text-green-400');

            if (faltan > 0) {
                contador.textContent = faltan === 1
                    ? 'Te falta 1 carácter para el mínimo de 10.'
                    : `Te faltan ${faltan} caracteres para el mínimo de 10.`;
                contador.classList.add('text-red-600', 'dark:text-red-400');
            } else {
                contador.textContent = `${longitud}/${MAXIMO_MENSAJE} caracteres.`;
                contador.classList.add('text-green-600', 'dark:text-green-400');
            }
        };

        const actualizar = () => {
            actualizarContador();
            boton.disabled = !esValido();
        };

        [nombre, email, mensaje].forEach((campo) => {
            campo.addEventListener('input', actualizar);
            campo.addEventListener('blur', actualizar);
        });

        actualizar();
    });
});

// Contadores: animación numérica al entrar en pantalla.
document.addEventListener('DOMContentLoaded', () => {
    const contadores = [...document.querySelectorAll('[data-cf-stats] [data-counter]')];

    if (contadores.length === 0) {
        return;
    }

    const reducirMovimiento = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    const escribir = (elemento, valor) => {
        const numero = elemento.querySelector('.cf-counter-number');

        if (numero) {
            numero.textContent = valor;
        }
    };

    const animar = (elemento) => {
        const valor = String(elemento.dataset.counterValue ?? '').trim();

        if (reducirMovimiento || !/^\d+(?:[.,]\d+)?$/.test(valor)) {
            escribir(elemento, valor);

            return;
        }

        const objetivo = Number(valor.replace(',', '.'));
        const duracion = 1200;
        const inicio = performance.now();

        const paso = (ahora) => {
            const avance = Math.min(1, (ahora - inicio) / duracion);
            const suavizado = 1 - Math.pow(1 - avance, 3);

            escribir(elemento, String(Math.round(objetivo * suavizado)));

            if (avance < 1) {
                window.requestAnimationFrame(paso);

                return;
            }

            escribir(elemento, valor);
        };

        window.requestAnimationFrame(paso);
    };

    if ('IntersectionObserver' in window) {
        const observador = new IntersectionObserver((entradas) => {
            entradas.forEach((entrada) => {
                if (! entrada.isIntersecting) {
                    return;
                }

                animar(entrada.target);
                observador.unobserve(entrada.target);
            });
        }, { threshold: 0.3 });

        contadores.forEach((contador) => {
            escribir(contador, '0');
            observador.observe(contador);
        });

        return;
    }

    contadores.forEach(animar);
});

// Tienda: filtra las tarjetas de la sección de ventas por categoría.
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-cf-sales]').forEach((seccion) => {
        const botones = [...seccion.querySelectorAll('[data-sales-filter]')];
        const productos = [...seccion.querySelectorAll('[data-cf-product]')];

        if (botones.length < 2 || productos.length === 0) {
            return;
        }

        const aplicar = (categoria) => {
            botones.forEach((boton) => {
                boton.setAttribute('aria-pressed', String(boton.dataset.salesFilter === categoria));
            });

            productos.forEach((producto) => {
                const visible = categoria === 'todos' || producto.dataset.productCategory === categoria;

                producto.hidden = ! visible;
            });
        };

        botones.forEach((boton) => {
            boton.addEventListener('click', () => aplicar(boton.dataset.salesFilter || 'todos'));
        });
    });
});

// ===== Preguntas frecuentes: apertura animada =====
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-faq-item]').forEach((item) => {
        const boton = item.querySelector('[data-faq-toggle]');
        const panel = item.querySelector('[data-faq-panel]');

        if (!boton || !panel) {
            return;
        }

        const aplicar = (abierto) => {
            item.classList.toggle('is-open', abierto);
            boton.setAttribute('aria-expanded', abierto ? 'true' : 'false');
            panel.style.maxHeight = abierto ? `${panel.scrollHeight}px` : '0px';
        };

        aplicar(boton.getAttribute('aria-expanded') === 'true');

        boton.addEventListener('click', () => {
            aplicar(! item.classList.contains('is-open'));
        });

        window.addEventListener('resize', () => {
            if (item.classList.contains('is-open')) {
                panel.style.maxHeight = `${panel.scrollHeight}px`;
            }
        });
    });
});

// ===== Testimonios: carrusel con flechas, puntos y autoplay =====
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-testimonios]').forEach((raiz) => {
        const pista = raiz.querySelector('[data-testimonios-track]');
        const diapositivas = [...raiz.querySelectorAll('[data-testimonios-slide]')];

        if (!pista || diapositivas.length === 0) {
            return;
        }

        const puntos = [...raiz.querySelectorAll('[data-testimonios-dot]')];
        const anterior = raiz.querySelector('[data-testimonios-prev]');
        const siguiente = raiz.querySelector('[data-testimonios-next]');
        const reducido = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        let indice = 0;
        let temporizador = null;
        let pausado = false;

        const marcar = () => {
            puntos.forEach((punto, i) => {
                const activo = i === indice;
                punto.classList.toggle('is-active', activo);
                punto.setAttribute('aria-selected', activo ? 'true' : 'false');
            });
        };

        const ir = (destino) => {
            indice = (destino + diapositivas.length) % diapositivas.length;

            const objetivo = diapositivas[indice].offsetLeft - pista.offsetLeft;

            pista.scrollTo({ left: objetivo, behavior: reducido ? 'auto' : 'smooth' });
            marcar();
        };

        const detectar = () => {
            const caja = pista.getBoundingClientRect();
            let mejor = 0;
            let distancia = Infinity;

            diapositivas.forEach((diapositiva, i) => {
                const actual = Math.abs(diapositiva.getBoundingClientRect().left - caja.left);

                if (actual < distancia) {
                    distancia = actual;
                    mejor = i;
                }
            });

            if (mejor !== indice) {
                indice = mejor;
                marcar();
            }
        };

        anterior?.addEventListener('click', () => ir(indice - 1));
        siguiente?.addEventListener('click', () => ir(indice + 1));
        puntos.forEach((punto, i) => punto.addEventListener('click', () => ir(i)));

        let espera = null;
        pista.addEventListener('scroll', () => {
            window.clearTimeout(espera);
            espera = window.setTimeout(detectar, 120);
        }, { passive: true });

        marcar();

        const intervalo = Number(raiz.dataset.interval || 6000);
        const autoplay = raiz.dataset.autoplay === 'yes' && ! reducido;

        const iniciar = () => {
            if (! autoplay || temporizador) {
                return;
            }

            temporizador = window.setInterval(() => {
                if (! pausado && ! document.hidden) {
                    ir(indice + 1);
                }
            }, Number.isFinite(intervalo) && intervalo >= 3000 ? intervalo : 6000);
        };

        const detener = () => {
            window.clearInterval(temporizador);
            temporizador = null;
        };

        raiz.addEventListener('pointerenter', () => { pausado = true; });
        raiz.addEventListener('pointerleave', () => { pausado = false; });
        raiz.addEventListener('focusin', () => { pausado = true; });
        raiz.addEventListener('focusout', () => { pausado = false; });

        iniciar();
    });
});

// ===== Video: portada propia y carga diferida del reproductor =====
const cfParseVideo = (rawUrl, proveedor = 'auto') => {
    const url = String(rawUrl || '').trim();

    if (url === '') {
        return null;
    }

    const youtube = url.match(/(?:youtube\.com\/(?:watch\?(?:.*&)?v=|embed\/|shorts\/|live\/)|youtu\.be\/)([\w-]{6,})/i);

    if ((proveedor === 'auto' || proveedor === 'youtube') && youtube) {
        return {
            provider: 'youtube',
            src: `https://www.youtube-nocookie.com/embed/${youtube[1]}?autoplay=1&rel=0`,
            poster: `https://img.youtube.com/vi/${youtube[1]}/hqdefault.jpg`,
        };
    }

    const vimeo = url.match(/vimeo\.com\/(?:video\/)?(\d+)/i);

    if ((proveedor === 'auto' || proveedor === 'vimeo') && vimeo) {
        return {
            provider: 'vimeo',
            src: `https://player.vimeo.com/video/${vimeo[1]}?autoplay=1`,
            poster: '',
        };
    }

    const bunny = url.match(/(?:iframe|player)\.mediadelivery\.net\/(?:embed\/)?(\d+)\/([\w-]+)/i)
        || url.match(/video\.bunnycdn\.com\/play\/(\d+)\/([\w-]+)/i);

    if ((proveedor === 'auto' || proveedor === 'bunny') && bunny) {
        return {
            provider: 'bunny',
            src: `https://iframe.mediadelivery.net/embed/${bunny[1]}/${bunny[2]}?autoplay=true`,
            poster: '',
        };
    }

    return null;
};

window.cfParseVideo = cfParseVideo;

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-cf-video]').forEach((seccion) => {
        const boton = seccion.querySelector('[data-video-play]');
        const portada = seccion.querySelector('[data-video-poster]');
        const titulo = seccion.dataset.videoTitle || 'Video';
        const info = cfParseVideo(seccion.dataset.videoUrl, seccion.dataset.videoProvider || 'auto');

        const pintarPortada = () => {
            if (!portada) {
                return;
            }

            const personalizada = String(seccion.dataset.videoPosterUrl || '').trim();
            const imagen = personalizada !== '' ? personalizada : (info?.poster || '');

            portada.style.backgroundImage = imagen === ''
                ? ''
                : `linear-gradient(135deg, rgba(15, 23, 42, .55), rgba(15, 23, 42, .15)), url("${imagen}")`;
        };

        const pintarTexto = () => {
            if (boton) {
                boton.setAttribute('aria-label', `Reproducir video: ${titulo}`);
            }

            const leyenda = seccion.querySelector('[data-video-caption]');

            if (leyenda) {
                leyenda.textContent = titulo;
            }
        };

        pintarPortada();
        pintarTexto();

        if (!boton || !info) {
            boton?.setAttribute('aria-disabled', 'true');
            return;
        }

        boton.addEventListener('click', () => {
            const iframe = document.createElement('iframe');

            iframe.src = info.src;
            iframe.title = titulo;
            iframe.loading = 'lazy';
            iframe.referrerPolicy = 'strict-origin-when-cross-origin';
            iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share';
            iframe.setAttribute('allowfullscreen', '');

            boton.replaceWith(iframe);
            iframe.focus();
        }, { once: true });
    });
});
