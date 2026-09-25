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

    const fotos = () => [...document.querySelectorAll('[data-gallery-full]')];

    // === Modal ===
    const abrir = (indice) => {
        const lista = fotos();

        if (indice < 0 || indice >= lista.length) {
            return;
        }

        indiceActual = indice;
        imagen.src = lista[indice].dataset.galleryFull;
        imagen.alt = lista[indice].querySelector('img')?.alt ?? '';
        caption.textContent = lista[indice].dataset.galleryCaption || `Foto ${indice + 1} de ${lista.length}`;
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
        const foto = event.target.closest('[data-gallery-full]');

        if (foto) {
            abrir(fotos().indexOf(foto));

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

const initReveal = (raiz = document) => {
    const elementos = raiz.querySelectorAll('[data-reveal]:not(.reveal-in)');

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

                const retardo = entrada.target.dataset.revealDelay;

                if (retardo) {
                    entrada.target.style.transitionDelay = `${retardo}ms`;
                }

                entrada.target.classList.add('reveal-in');
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
    const botonArriba = document.getElementById('top-button');
    const enlacesNav = document.querySelectorAll('[data-nav-link]');
    const secciones = [...enlacesNav]
        .map((enlace) => document.querySelector(enlace.getAttribute('href')))
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

        if (botonArriba) {
            const visible = window.scrollY > 300;

            botonArriba.classList.toggle('is-visible', visible);
            botonArriba.toggleAttribute('inert', !visible);

            const rotador = botonArriba.querySelector('.top-button-rotator');
            const delta = window.scrollY - scrollYAnterior;

            if (rotador && !reduceMotion && delta !== 0) {
                rotacion += delta * 0.15;
                rotador.style.setProperty('--rotacion', `${rotacion}deg`);
            }

            scrollYAnterior = window.scrollY;

            botonArriba.classList.add('is-scrolling');
            clearTimeout(temporizadorRebote);
            temporizadorRebote = setTimeout(() => {
                botonArriba.classList.remove('is-scrolling');
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

    botonArriba?.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
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
