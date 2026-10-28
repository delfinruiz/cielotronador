<?php

namespace App\Support;

/**
 * Estilos de los módulos dinámicos del constructor (FAQ, marcas y testimonios).
 *
 * Todos los colores salen de las variables `--cf-*`, así que las secciones se
 * adaptan a la plantilla y a su variante oscura sin CSS extra.
 */
final class DynamicSectionsWidget
{
    public static function css(): string
    {
        return <<<'CSS'
.cf-dyn {
    padding: clamp(48px, 7vw, 80px) 24px;
    background-color: var(--cf-surface, #ffffff);
    color: var(--cf-text, #52525b);
}
.cf-dyn__inner { max-width: 1100px; margin: 0 auto; }
.cf-dyn__head { max-width: 660px; margin: 0 auto 40px; text-align: center; }
.cf-dyn__kicker {
    margin: 0 0 12px;
    color: var(--cf-brand-ink, #c2410c);
    font-size: 13px;
    font-weight: 700;
    letter-spacing: .22em;
    text-transform: uppercase;
}
.cf-dyn__title {
    margin: 0 0 16px;
    color: var(--cf-ink, #18181b);
    font-family: var(--cf-font-heading, ui-sans-serif, system-ui, sans-serif);
    font-size: clamp(28px, 5.5vw, 40px);
    font-weight: 800;
    line-height: 1.1;
}
.cf-dyn__intro { margin: 0; font-size: 17px; line-height: 1.7; }

/* --- Preguntas frecuentes --- */
.cf-faq { background-color: var(--cf-surface-muted, #f8fafc); }
.cf-faq__grid { display: grid; gap: 12px; }
.cf-faq__grid[data-columns="2"] { grid-template-columns: repeat(2, minmax(0, 1fr)); }
.cf-faq__item {
    border: 1px solid var(--cf-border, #e4e4e7);
    border-radius: 16px;
    background-color: var(--cf-surface, #ffffff);
    overflow: hidden;
    transition: border-color .2s ease, box-shadow .2s ease;
}
.cf-faq__item.is-open {
    border-color: var(--cf-brand-border, #fed7aa);
    box-shadow: 0 12px 30px -22px rgba(15, 23, 42, .45);
}
.cf-faq__toggle {
    display: flex;
    width: 100%;
    align-items: center;
    justify-content: space-between;
    gap: 18px;
    border: 0;
    background: transparent;
    padding: 18px 22px;
    color: var(--cf-ink, #18181b);
    font: inherit;
    font-size: 16px;
    font-weight: 700;
    line-height: 1.4;
    text-align: left;
    cursor: pointer;
}
.cf-faq__icon {
    position: relative;
    flex: 0 0 auto;
    width: 22px;
    height: 22px;
    border-radius: 9999px;
    border: 1px solid var(--cf-brand-border, #fed7aa);
    background-color: var(--cf-brand-soft, #fff7ed);
    transition: transform .25s ease;
}
.cf-faq__icon::before,
.cf-faq__icon::after {
    content: '';
    position: absolute;
    inset: 50% 5px auto;
    height: 2px;
    border-radius: 2px;
    background-color: var(--cf-brand-ink, #c2410c);
    transform: translateY(-50%);
}
.cf-faq__icon::after { transform: translateY(-50%) rotate(90deg); }
.cf-faq__item.is-open .cf-faq__icon { transform: rotate(45deg); }
.cf-faq__panel { max-height: 0; overflow: hidden; transition: max-height .32s ease; }
.cf-faq__panel-inner {
    padding: 0 22px 20px;
    color: var(--cf-text, #52525b);
    font-size: 15px;
    line-height: 1.75;
}
.cf-faq__panel-inner p { margin: 0; }

/* --- Franja de marcas --- */
.cf-marcas {
    padding: clamp(34px, 5vw, 54px) 0;
    border-top: 1px solid var(--cf-border, #e4e4e7);
    border-bottom: 1px solid var(--cf-border, #e4e4e7);
    background-color: var(--cf-surface, #ffffff);
    overflow: hidden;
}
.cf-marcas__viewport {
    overflow: hidden;
    -webkit-mask-image: linear-gradient(to right, transparent, #000 7%, #000 93%, transparent);
    mask-image: linear-gradient(to right, transparent, #000 7%, #000 93%, transparent);
}
.cf-marcas__track {
    display: flex;
    width: max-content;
    align-items: center;
    gap: clamp(36px, 5vw, 72px);
    animation: cf-marquesa var(--cf-marquesa, 40s) linear infinite;
    will-change: transform;
}
.cf-marcas[data-pause="yes"]:hover .cf-marcas__track { animation-play-state: paused; }
.cf-marcas__item {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 132px;
    text-decoration: none;
}
.cf-marcas__item img {
    display: block;
    height: 36px;
    width: auto;
    max-width: 150px;
    object-fit: contain;
}
.cf-marcas.is-grayscale .cf-marcas__item img {
    filter: grayscale(1);
    opacity: .72;
    transition: filter .2s ease, opacity .2s ease;
}
.cf-marcas.is-grayscale .cf-marcas__item:hover img { filter: grayscale(0); opacity: 1; }
.dark .cf-marcas[data-invert="yes"] .cf-marcas__item img { filter: invert(1); opacity: .8; }
.dark .cf-marcas.is-grayscale[data-invert="yes"] .cf-marcas__item img { filter: invert(1) grayscale(1); }
.dark .cf-marcas.is-grayscale[data-invert="yes"] .cf-marcas__item:hover img { filter: invert(1); opacity: 1; }

/* --- Testimonios --- */
.cf-testimonios { background-color: var(--cf-surface-muted, #f8fafc); }
.cf-testimonios__slider { position: relative; }
.cf-testimonios__track {
    display: flex;
    gap: 24px;
    overflow-x: auto;
    scroll-snap-type: x mandatory;
    scroll-behavior: smooth;
    scrollbar-width: none;
}
.cf-testimonios__track::-webkit-scrollbar { display: none; }
.cf-testimonios__slide { flex: 0 0 100%; min-width: 0; scroll-snap-align: center; }
.cf-testimonios__card {
    height: 100%;
    border: 1px solid var(--cf-border, #e4e4e7);
    border-radius: 24px;
    background-color: var(--cf-surface, #ffffff);
    padding: clamp(24px, 4vw, 40px);
    box-shadow: 0 22px 50px -40px rgba(15, 23, 42, .55);
}
.cf-testimonios__stars {
    margin: 0 0 16px;
    color: var(--cf-brand, #ff4c00);
    font-size: 16px;
    letter-spacing: .18em;
}
.cf-testimonios__quote {
    margin: 0 0 26px;
    color: var(--cf-ink, #18181b);
    font-size: clamp(18px, 2.4vw, 22px);
    line-height: 1.6;
}
.cf-testimonios__author { display: flex; align-items: center; gap: 14px; }
.cf-testimonios__avatar {
    display: inline-flex;
    flex: 0 0 auto;
    width: 48px;
    height: 48px;
    align-items: center;
    justify-content: center;
    border-radius: 9999px;
    background-color: var(--cf-brand-soft, #fff7ed);
    color: var(--cf-brand-ink, #c2410c);
    font-size: 15px;
    font-weight: 800;
    overflow: hidden;
}
.cf-testimonios__avatar img { display: block; width: 100%; height: 100%; object-fit: cover; }
.cf-testimonios__name { margin: 0; color: var(--cf-ink, #18181b); font-size: 15px; font-weight: 700; }
.cf-testimonios__role { margin: 2px 0 0; color: var(--cf-muted, #71717a); font-size: 13px; }
.cf-testimonios__arrow {
    position: absolute;
    top: 50%;
    z-index: 2;
    display: inline-flex;
    width: 40px;
    height: 40px;
    align-items: center;
    justify-content: center;
    border: 0;
    border-radius: 9999px;
    background-color: var(--cf-surface, #ffffff);
    color: var(--cf-ink, #18181b);
    box-shadow: 0 10px 24px -12px rgba(15, 23, 42, .45);
    font-size: 22px;
    line-height: 1;
    cursor: pointer;
    transform: translateY(-50%);
}
.cf-testimonios__arrow[data-testimonios-prev] { left: -8px; }
.cf-testimonios__arrow[data-testimonios-next] { right: -8px; }
.cf-testimonios__dots { display: flex; justify-content: center; gap: 8px; padding-top: 20px; }
.cf-testimonios__dot {
    width: 8px;
    height: 8px;
    padding: 0;
    border: 0;
    border-radius: 9999px;
    background-color: var(--cf-border-strong, #d4d4d8);
    cursor: pointer;
    transition: background-color .2s ease, width .2s ease;
}
.cf-testimonios__dot.is-active { width: 26px; background-color: var(--cf-brand, #ff4c00); }
.cf-testimonios__grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; }

@media (max-width: 820px) {
    .cf-faq__grid[data-columns="2"] { grid-template-columns: 1fr; }
}
@media (max-width: 640px) {
    .cf-testimonios__arrow { display: none; }
}
@media (prefers-reduced-motion: reduce) {
    .cf-marcas__track { animation: none; }
    .cf-faq__panel,
    .cf-faq__icon,
    .cf-testimonios__track { transition: none; scroll-behavior: auto; }
}
@keyframes cf-marquesa {
    from { transform: translateX(0); }
    to { transform: translateX(-50%); }
}
CSS;
    }
}
