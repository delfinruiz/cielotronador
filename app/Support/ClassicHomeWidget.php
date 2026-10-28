<?php

namespace App\Support;

/**
 * Bloques y composición de la portada clásica para el constructor.
 *
 * Replica la portada histórica con módulos editables: hero, quiénes somos,
 * equipo, noticias, galería, horarios, contacto, pie, botón volver arriba y
 * WhatsApp. Las secciones dinámicas se insertan como marcadores que el
 * renderizador sustituye por el contenido real.
 */
final class ClassicHomeWidget
{
    /**
     * Estilos de los bloques que no existen en el kit genérico.
     */
    public static function css(): string
    {
        return <<<'CSS'
.cf-club-hero {
    position: relative;
    display: flex;
    min-height: calc(100vh - 5rem);
    align-items: center;
    overflow: hidden;
    background-color: #09090b;
    color: #ffffff;
}
.cf-club-hero__glow {
    position: absolute;
    border-radius: 9999px;
    filter: blur(64px);
    pointer-events: none;
}
.cf-club-hero__glow--top {
    top: -10rem;
    left: 50%;
    width: 36rem;
    height: 36rem;
    transform: translateX(-50%);
    background-color: rgba(255, 76, 0, .25);
}
.cf-club-hero__glow--bottom {
    bottom: -8rem;
    left: -6rem;
    width: 20rem;
    height: 20rem;
    background-color: rgba(234, 88, 12, .10);
}
.cf-club-hero__grid {
    position: relative;
    display: grid;
    width: 100%;
    max-width: 80rem;
    margin: 0 auto;
    padding: clamp(3rem, 8vw, 7rem) 1.5rem;
    gap: clamp(2rem, 5vw, 3rem);
    align-items: center;
}
.cf-club-hero__content {
    order: 2;
    text-align: center;
}
.cf-club-hero__media {
    position: relative;
    order: 1;
    width: 100%;
    max-width: 36rem;
    margin: 0 auto;
}
.cf-club-hero__media::before {
    content: '';
    position: absolute;
    inset: 0;
    transform: rotate(-3deg);
    border-radius: 1.5rem;
    background-color: rgba(255, 76, 0, .20);
    filter: blur(24px);
}
.cf-club-hero__media img {
    position: relative;
    display: block;
    width: 100%;
    height: auto;
    filter: drop-shadow(0 0 40px rgba(255, 76, 0, .35));
}
.cf-club-hero__kicker {
    margin: 0 0 1rem;
    color: #fb923c;
    font-size: .875rem;
    font-weight: 600;
    letter-spacing: .3em;
    text-transform: uppercase;
}
.cf-club-hero__title {
    margin: 0;
    font-family: 'Oswald', var(--font-heading, ui-sans-serif), system-ui, sans-serif;
    font-size: clamp(3rem, 9vw, 4.5rem);
    font-weight: 600;
    letter-spacing: .04em;
    line-height: 1;
    text-transform: uppercase;
}
.cf-club-hero__title span {
    color: #ff4c00;
}
.cf-club-hero__quote {
    margin: 1.5rem 0 0;
    color: #d4d4d8;
    font-size: clamp(1.125rem, 2.5vw, 1.5rem);
    font-weight: 500;
}
.cf-club-hero__text {
    max-width: 28rem;
    margin: 1rem auto 0;
    color: #a1a1aa;
    font-size: .875rem;
    line-height: 1.8;
}
.cf-club-hero__text strong {
    color: #e4e4e7;
    font-weight: 600;
}
.cf-club-hero__text .is-brand {
    color: #fb923c;
}
.cf-club-hero__actions {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
    margin-top: 2.5rem;
}
.cf-club-hero__button {
    display: inline-flex;
    width: 100%;
    align-items: center;
    justify-content: center;
    border-radius: 9999px;
    background-color: #ff4c00;
    padding: .875rem 2rem;
    color: #ffffff;
    font-size: .875rem;
    font-weight: 600;
    text-decoration: none;
    box-shadow: 0 10px 15px -3px rgba(255, 76, 0, .25);
    transition: background-color .2s ease, border-color .2s ease, color .2s ease;
}
.cf-club-hero__button:hover {
    background-color: #e64500;
}
.cf-club-hero__button--outline {
    border: 1px solid rgba(255, 255, 255, .2);
    background-color: transparent;
    box-shadow: none;
}
.cf-club-hero__button--outline:hover {
    border-color: #fb923c;
    background-color: transparent;
    color: #fb923c;
}
.cf-club-about {
    position: relative;
    overflow: hidden;
    padding: clamp(3rem, 7vw, 5rem) 1.5rem;
    background-color: var(--cf-surface, #ffffff);
}
.cf-club-about__grid {
    display: grid;
    max-width: 80rem;
    margin: 0 auto;
    gap: clamp(2rem, 5vw, 3rem);
    align-items: center;
}
.cf-club-about__media {
    position: relative;
    order: 2;
    width: 100%;
    max-width: 24rem;
    margin: 0 auto;
}
.cf-club-about__frame {
    position: absolute;
    top: 1rem;
    left: 1rem;
    width: 100%;
    height: 100%;
    border-radius: 1rem;
    background-color: var(--cf-brand-soft, #fff7ed);
}
.cf-club-about__media img {
    position: relative;
    display: block;
    width: 100%;
    aspect-ratio: 3 / 4;
    object-fit: cover;
    border-radius: 1rem;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, .10);
}
.cf-club-about__content {
    order: 1;
}
.cf-club-kicker {
    margin: 0 0 .75rem;
    color: var(--cf-brand-ink, #c2410c);
    font-size: .875rem;
    font-weight: 600;
    letter-spacing: .25em;
    text-transform: uppercase;
}
.cf-club-title {
    margin: 0;
    color: var(--cf-ink, #18181b);
    font-family: 'Oswald', var(--font-heading, ui-sans-serif), system-ui, sans-serif;
    font-size: clamp(2rem, 5vw, 3rem);
    font-weight: 600;
    letter-spacing: .03em;
    line-height: 1.1;
    text-transform: uppercase;
}
.cf-club-copy {
    display: grid;
    gap: 1.25rem;
    margin-top: 2rem;
    color: var(--cf-text, #52525b);
    font-size: 1rem;
    line-height: 1.8;
}
.cf-club-copy p {
    margin: 0;
}
.cf-club-copy strong {
    color: var(--cf-ink, #18181b);
    font-weight: 600;
}
.cf-club-copy .is-brand {
    color: var(--cf-brand-ink, #c2410c);
}
.cf-club-footer {
    border-top: 1px solid var(--cf-border, #e4e4e7);
    padding: clamp(2.5rem, 6vw, 3.5rem) 1.5rem;
    background-color: var(--cf-surface, #ffffff);
    color: var(--cf-text, #52525b);
}
.cf-club-footer__inner {
    max-width: 80rem;
    margin: 0 auto;
}
.cf-club-footer__top {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: space-between;
    gap: 2rem;
}
.cf-club-footer__logo img {
    display: block;
    width: auto;
    height: 3.5rem;
}
.cf-club-footer__motto {
    margin: 0;
    color: var(--cf-muted, #71717a);
    font-family: 'Oswald', var(--font-heading, ui-sans-serif), system-ui, sans-serif;
    font-size: .875rem;
    font-weight: 600;
    letter-spacing: .25em;
    text-align: center;
    text-transform: uppercase;
}
.cf-club-footer__social {
    display: flex;
    align-items: center;
    gap: 1rem;
}
.cf-club-footer__social a {
    display: inline-flex;
    width: 2.75rem;
    height: 2.75rem;
    align-items: center;
    justify-content: center;
    border: 1px solid var(--cf-border-strong, #d4d4d8);
    border-radius: 9999px;
    color: var(--cf-text, #52525b);
    transition: border-color .2s ease, color .2s ease;
}
.cf-club-footer__social a:hover {
    border-color: var(--cf-brand, #ff4c00);
    color: var(--cf-brand, #ff4c00);
}
.cf-club-footer__social img {
    display: block;
    width: 1.25rem;
    height: 1.25rem;
}
.dark .cf-club-footer__social img {
    filter: invert(1);
}
.cf-club-footer__bottom {
    margin-top: 2.5rem;
    border-top: 1px solid var(--cf-border, #e4e4e7);
    padding-top: 1.5rem;
    text-align: center;
}
.cf-club-footer__legal {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: .5rem 1.5rem;
    margin: 0 0 .75rem;
}
.cf-club-footer__legal a {
    color: var(--cf-muted, #71717a);
    font-size: .875rem;
    text-decoration: none;
    transition: color .2s ease;
}
.cf-club-footer__legal a:hover {
    color: var(--cf-brand, #ff4c00);
}
.cf-club-footer__copy {
    margin: 0;
    color: var(--cf-muted, #71717a);
    font-size: .875rem;
}
@media (min-width: 640px) {
    .cf-club-hero__actions {
        flex-direction: row;
        justify-content: center;
    }
    .cf-club-hero__button {
        width: auto;
    }
}
@media (min-width: 768px) {
    .cf-club-footer__top {
        flex-direction: row;
    }
}
@media (min-width: 1024px) {
    .cf-club-hero__grid {
        grid-template-columns: 1fr 1fr;
        padding-right: 2rem;
        padding-left: 2rem;
    }
    .cf-club-hero__content {
        order: 1;
        text-align: left;
    }
    .cf-club-hero__media {
        order: 2;
    }
    .cf-club-hero__text {
        margin-right: 0;
        margin-left: 0;
    }
    .cf-club-hero__actions {
        justify-content: flex-start;
    }
    .cf-club-about {
        padding: clamp(4rem, 7vw, 7rem) 2rem;
    }
    .cf-club-about__grid {
        grid-template-columns: 1fr 1fr;
        gap: 4rem;
    }
    .cf-club-about__media {
        order: 1;
        max-width: 28rem;
    }
    .cf-club-about__content {
        order: 2;
    }
}
@media (prefers-reduced-motion: reduce) {
    .cf-club-hero__button,
    .cf-club-footer__social a,
    .cf-club-footer__legal a {
        transition: none;
    }
}
CSS;
    }

    /**
     * Hero de la portada clásica.
     */
    public static function hero(): string
    {
        return <<<'HTML'
<section id="inicio" data-gjs-name="Hero del club" class="cf-club-hero">
  <div class="cf-club-hero__glow cf-club-hero__glow--top" aria-hidden="true"></div>
  <div class="cf-club-hero__glow cf-club-hero__glow--bottom" aria-hidden="true"></div>
  <div class="cf-club-hero__grid">
    <div class="cf-club-hero__content">
      <p data-reveal class="cf-club-hero__kicker">Bienvenidos</p>
      <h1 data-reveal data-reveal-delay="100" class="cf-club-hero__title">Cielo<br><span>Tronador</span></h1>
      <p data-reveal data-reveal-delay="200" class="cf-club-hero__quote">«Más que un club, una familia»</p>
      <p data-reveal data-reveal-delay="300" class="cf-club-hero__text">
        ¡Bienvenidos a nuestro sitio web! Somos el Club de Básquetbol Cielo Tronador de
        <strong>Talcahuano, Chile</strong>: una familia unida por la pasión por el baloncesto.
      </p>
      <p data-reveal data-reveal-delay="350" class="cf-club-hero__text">
        Desde <strong class="is-brand">2006</strong> formamos jugadores y personas en categorías
        sub 9 hasta adultos, con un cuerpo técnico comprometido y valores como el respeto, la
        disciplina y el compañerismo.
      </p>
      <div data-reveal data-reveal-delay="400" class="cf-club-hero__actions">
        <a href="#quienes-somos" class="cf-club-hero__button">Conócenos</a>
        <a href="/admin/login" class="cf-club-hero__button cf-club-hero__button--outline">Ingresar</a>
      </div>
    </div>
    <div data-reveal="zoom" data-reveal-delay="250" class="cf-club-hero__media">
      <img src="/img/hero.png" alt="Escudo del Club CieloTronador">
    </div>
  </div>
</section>
HTML;
    }

    /**
     * Sección "Quiénes somos" con la imagen y los textos originales.
     */
    public static function about(): string
    {
        return <<<'HTML'
<section id="quienes-somos" data-cf-parallax="soft" data-gjs-name="Quiénes somos" class="cf-club-about">
  <div class="cf-club-about__grid">
    <div data-reveal="left" class="cf-club-about__media">
      <div class="cf-club-about__frame" aria-hidden="true"></div>
      <img src="/img/bienvenida.jpg" alt="Jugadores del Club CieloTronador">
    </div>
    <div data-reveal="right" class="cf-club-about__content">
      <p class="cf-club-kicker">Quiénes somos</p>
      <h2 class="cf-club-title">Bienvenidos a<br>nuestro sitio web</h2>
      <div class="cf-club-copy">
        <p>
          Somos una verdadera familia unida por la pasión por el baloncesto. Desde
          <strong class="is-brand">2006</strong>, en <strong>Talcahuano, Chile</strong>,
          hemos visto crecer a generaciones de niños y niñas que llegaron con una pelota bajo el brazo
          y se fueron formando como jugadores y, sobre todo, como personas.
        </p>
        <p>
          Con categorías que van desde <strong>sub 9 hasta adultos</strong>, trabajamos junto a un
          cuerpo técnico comprometido no solo el desarrollo técnico y táctico, sino también los valores
          que nos definen: el respeto, la disciplina, la solidaridad y el compañerismo.
        </p>
        <p>
          Creemos en el deporte como una herramienta de transformación social. Nuestro lema
          «Más que un club, una familia» te invita a vivir el básquetbol con nosotros y a formar parte
          de una historia que se escribe con esfuerzo, garra y mucho corazón.
        </p>
      </div>
    </div>
  </div>
</section>
HTML;
    }

    /**
     * Pie clásico con logo, lema, redes y enlaces legales.
     */
    public static function footer(): string
    {
        $year = now()->year;
        $nombre = e(Branding::name());
        $lema = e(Branding::tagline());
        $ciudad = e(Branding::city());
        $correo = e(Branding::contactEmail());
        $instagram = e(Branding::instagramUrl());

        return <<<HTML
<footer data-cf-footer="yes" data-gjs-name="Pie del club" class="cf-club-footer">
  <div class="cf-club-footer__inner">
    <div class="cf-club-footer__top">
      <a href="/" class="cf-club-footer__logo" aria-label="{$nombre} - Inicio">
        <img src="/branding/logo-light" data-brand-logo-light="/branding/logo-light" data-brand-logo-dark="/branding/logo-dark" alt="{$nombre}">
      </a>
      <p class="cf-club-footer__motto">{$lema}</p>
      <div class="cf-club-footer__social">
        <a href="{$instagram}" target="_blank" rel="noopener" aria-label="Instagram">
          <img src="/img/social/instagram.svg" alt="Instagram" width="20" height="20" loading="lazy">
        </a>
        <a href="mailto:{$correo}" aria-label="Correo de contacto">
          <img src="/img/social/email.svg" alt="Correo" width="20" height="20" loading="lazy">
        </a>
      </div>
    </div>
    <div class="cf-club-footer__bottom">
      <nav class="cf-club-footer__legal" aria-label="Enlaces legales">
        <a href="/politica-de-privacidad">Política de Privacidad</a>
        <a href="/politica-de-cookies">Política de Cookies</a>
        <a href="/politica-de-datos">Seguridad y Manejo de Datos</a>
      </nav>
      <p class="cf-club-footer__copy">Copyright {$year} por {$nombre} · {$ciudad}</p>
    </div>
  </div>
</footer>
HTML;
    }

    /**
     * Botón flotante con el balón del club.
     */
    public static function topButton(): string
    {
        return <<<'HTML'
<button id="top-button" type="button" aria-label="Volver arriba" data-top-button="yes" data-top-enabled="yes" data-gjs-name="Botón volver arriba"
        style="position:fixed;bottom:1.5rem;left:50%;transform:translateX(-50%);z-index:50;background:transparent;border:0;padding:0;cursor:pointer;">
  <span class="top-button-bounce" aria-hidden="true" style="display:block;transform-origin:50% 100%;">
    <span class="top-button-rotator" style="display:block;--rotacion:0deg;">
      <img src="/img/balon.svg" alt="" width="56" height="56" style="display:block;width:3.5rem;height:3.5rem;">
    </span>
  </span>
</button>
HTML;
    }

    /**
     * Página completa en el orden de la portada clásica.
     */
    public static function page(): string
    {
        $bloques = collect(PageBlockLibrary::all())->keyBy('id');

        $partes = [
            $bloques->get('cf-hero-club')['content'] ?? '',
            $bloques->get('cf-quienes-somos')['content'] ?? '',
            DirectivaWidget::block(
                kicker: 'Nuestra directiva',
                title: 'Quienes llevan el club',
                intro: '',
                members: [
                    ['nombre' => 'Jacqueline Reyes', 'puesto' => 'Directora', 'foto' => '/img/balon.svg'],
                    ['nombre' => 'Angélica Fuente', 'puesto' => 'Secretaria', 'foto' => '/img/balon.svg'],
                    ['nombre' => 'Bertita Vergara', 'puesto' => 'Tesorera', 'foto' => '/img/balon.svg'],
                ],
                classic: true,
            ),
            self::withParallax($bloques->get('cf-noticias')['content'] ?? ''),
            self::withParallax($bloques->get('cf-galeria')['content'] ?? ''),
            self::withParallax($bloques->get('cf-horarios')['content'] ?? '', 'light'),
            self::withParallax($bloques->get('cf-contacto')['content'] ?? ''),
            $bloques->get('cf-footer-club')['content'] ?? '',
            $bloques->get('cf-top-button-ball')['content'] ?? '',
            $bloques->get('cf-whatsapp-club')['content'] ?? '',
        ];

        return implode("\n\n", array_filter($partes));
    }

    /**
     * Envuelve una sección dinámica para que reciba la capa de parallax.
     */
    private static function withParallax(string $html, string $tone = ''): string
    {
        if ($html === '') {
            return '';
        }

        $toneAttribute = $tone !== '' ? ' data-cf-parallax-tone="'.e($tone).'"' : '';

        return "<div data-cf-parallax=\"soft\"{$toneAttribute}>\n{$html}\n</div>";
    }
}
