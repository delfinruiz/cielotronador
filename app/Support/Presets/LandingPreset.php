<?php

namespace App\Support\Presets;

/**
 * Contrato de una plantilla de inicio del constructor.
 *
 * Las plantillas viven en código, no en la base de datos: la galería las lista
 * desde el registro y solo se crea una página cuando el usuario pulsa "Usar".
 */
interface LandingPreset
{
    public function slug(): string;

    public function name(): string;

    public function tagline(): string;

    /**
     * @return list<string>
     */
    public function tags(): array;

    public function thumbnail(): string;

    /**
     * Alias de Vite para las tipografías de la plantilla.
     *
     * @return list<string>
     */
    public function fonts(): array;

    /**
     * @return array<string, mixed>
     */
    public function settings(): array;

    /**
     * @return list<array{label: string, value: string}>
     */
    public function menu(): array;

    public function html(): string;

    public function css(): string;
}
