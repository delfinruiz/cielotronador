<?php

namespace App\Support;

/**
 * Animaciones disponibles para las plantillas GrapesJS.
 *
 * Reutiliza el mismo mecanismo `data-reveal` de la portada clásica, de modo que
 * los elementos se animan al entrar en pantalla sin JavaScript adicional por
 * bloque. Las animaciones continuas usan las propiedades individuales
 * `translate` y `scale` para no pisar el `transform` de la entrada.
 */
final class AnimationLibrary
{
    /**
     * Animaciones de entrada que se ofrecen en el panel de ajustes.
     *
     * @return list<array{id: string, label: string}>
     */
    public static function entranceOptions(): array
    {
        return [
            ['id' => 'none', 'label' => 'Sin animación'],
            ['id' => 'up', 'label' => 'Aparecer desde abajo'],
            ['id' => 'down', 'label' => 'Aparecer desde arriba'],
            ['id' => 'left', 'label' => 'Deslizar desde la izquierda'],
            ['id' => 'right', 'label' => 'Deslizar desde la derecha'],
            ['id' => 'zoom', 'label' => 'Zoom suave'],
            ['id' => 'fade', 'label' => 'Solo desvanecer'],
            ['id' => 'flip', 'label' => 'Giro 3D suave'],
            ['id' => 'blur', 'label' => 'Desenfoque a nítido'],
        ];
    }

    /**
     * Momentos en que se dispara la animación de entrada.
     *
     * @return list<array{id: string, label: string}>
     */
    public static function triggerOptions(): array
    {
        return [
            ['id' => 'scroll', 'label' => 'Al hacer scroll'],
            ['id' => 'load', 'label' => 'Al cargar la página'],
        ];
    }

    /**
     * Animaciones continuas para llamar la atención sin mover el diseño.
     *
     * @return list<array{id: string, label: string}>
     */
    public static function loopOptions(): array
    {
        return [
            ['id' => 'none', 'label' => 'Ninguna'],
            ['id' => 'float', 'label' => 'Flotación suave'],
            ['id' => 'pulse', 'label' => 'Pulso suave'],
            ['id' => 'beat', 'label' => 'Latido'],
        ];
    }

    /**
     * CSS que se inyecta en el lienzo de GrapesJS y en las plantillas públicas.
     */
    public static function css(): string
    {
        return <<<'CSS'
/* Animaciones de entrada y continuas para plantillas GrapesJS. */
.js [data-reveal] {
    opacity: 0;
    transform: translateY(1.5rem);
    transition:
        opacity 0.7s cubic-bezier(0.22, 0.61, 0.36, 1),
        transform 0.7s cubic-bezier(0.22, 0.61, 0.36, 1),
        filter 0.7s cubic-bezier(0.22, 0.61, 0.36, 1);
    will-change: opacity, transform;
}

.js [data-reveal='none'] {
    opacity: 1;
    transform: none;
    filter: none;
    transition: none;
}

.js [data-reveal='down'] {
    transform: translateY(-1.5rem);
}

.js [data-reveal='left'] {
    transform: translateX(-2.5rem);
}

.js [data-reveal='right'] {
    transform: translateX(2.5rem);
}

.js [data-reveal='zoom'] {
    transform: scale(0.9);
}

.js [data-reveal='fade'] {
    transform: none;
}

.js [data-reveal='flip'] {
    transform: perspective(900px) rotateX(-14deg);
    transform-origin: center top;
}

.js [data-reveal='blur'] {
    filter: blur(12px);
    transform: translateY(1rem);
}

.js [data-reveal].reveal-in {
    opacity: 1;
    transform: none;
    filter: none;
}

[data-cf-loop='float'] {
    animation: cf-float 6s ease-in-out infinite;
}

[data-cf-loop='pulse'] {
    animation: cf-pulse 3.2s ease-in-out infinite;
}

[data-cf-loop='beat'] {
    animation: cf-beat 1.8s ease-in-out infinite;
}

@keyframes cf-float {
    0%,
    100% {
        translate: 0 0;
    }

    50% {
        translate: 0 -10px;
    }
}

@keyframes cf-pulse {
    0%,
    100% {
        scale: 1;
    }

    50% {
        scale: 1.04;
    }
}

@keyframes cf-beat {
    0%,
    100% {
        scale: 1;
    }

    14% {
        scale: 1.08;
    }

    28% {
        scale: 1;
    }

    42% {
        scale: 1.08;
    }

    70% {
        scale: 1;
    }
}

@media (prefers-reduced-motion: reduce) {
    .js [data-reveal],
    .js [data-reveal].reveal-in {
        opacity: 1;
        transform: none;
        filter: none;
        transition: none;
    }

    [data-cf-loop] {
        animation: none !important;
    }
}
CSS;
    }
}
