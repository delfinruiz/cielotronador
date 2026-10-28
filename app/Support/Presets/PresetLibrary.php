<?php

namespace App\Support\Presets;

use Illuminate\Support\Collection;

/**
 * Catálogo de plantillas de inicio disponibles en el constructor.
 *
 * El orden del arreglo es el orden de la galería. Para publicar una plantilla
 * nueva basta con crear su clase y registrarla aquí.
 */
final class PresetLibrary
{
    /**
     * @return array<string, LandingPreset>
     */
    public static function all(): array
    {
        return collect([
            new AgencyPreset,
            new CreativePreset,
            new VisualPreset,
            new CorporatePreset,
            new ProductPreset,
            new ClubPreset,
        ])->keyBy(static fn (LandingPreset $preset): string => $preset->slug())->all();
    }

    public static function find(string $slug): ?LandingPreset
    {
        return self::all()[$slug] ?? null;
    }

    /**
     * @return Collection<int, LandingPreset>
     */
    public static function collection(): Collection
    {
        return collect(self::all())->values();
    }
}
