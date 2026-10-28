<?php

namespace App\Support\Presets;

use App\Support\PageBlockLibrary;

/**
 * Reutiliza los bloques reales del constructor para componer una plantilla.
 *
 * No se duplica el HTML de cada bloque: se toma el contenido del catálogo y se
 * sustituyen los textos de ejemplo. Así la plantilla se comporta exactamente
 * igual que si el usuario hubiera arrastrado esos bloques.
 */
final class PresetBlocks
{
    /**
     * @var array<string, string>
     */
    private array $contents;

    public function __construct()
    {
        $this->contents = collect(PageBlockLibrary::all())
            ->pluck('content', 'id')
            ->all();
    }

    public function get(string $id): string
    {
        return $this->contents[$id] ?? '';
    }

    /**
     * Sustituye textos exactos del bloque.
     *
     * @param  array<string, string>  $replace
     */
    public function fill(string $html, array $replace): string
    {
        return strtr($html, $replace);
    }

    /**
     * Sustituye la primera aparición de un texto por cada valor, en orden.
     *
     * Sirve para los bloques que repiten el mismo párrafo de ejemplo varias
     * veces (por ejemplo, las tarjetas de servicios).
     *
     * @param  list<string>  $values
     */
    public function nth(string $html, string $search, array $values): string
    {
        foreach ($values as $value) {
            $position = strpos($html, $search);

            if ($position === false) {
                break;
            }

            $html = substr_replace($html, $value, $position, strlen($search));
        }

        return $html;
    }

    /**
     * Añade un id a la primera sección que todavía no tenga uno.
     */
    public function withId(string $html, string $id): string
    {
        $result = preg_replace(
            '/<section\b(?![^>]*\bid=)/',
            '<section id="'.e($id).'"',
            $html,
            1,
        );

        return $result ?? $html;
    }
}
