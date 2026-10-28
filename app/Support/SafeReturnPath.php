<?php

namespace App\Support;

final class SafeReturnPath
{
    /**
     * Devuelve una ruta interna segura o la portada si el valor no es válido.
     */
    public static function from(mixed $value): string
    {
        if (! is_string($value) || $value === '') {
            return route('landing');
        }

        $value = trim($value);

        if (
            ! str_starts_with($value, '/')
            || str_starts_with($value, '//')
            || str_contains($value, '\\')
        ) {
            return route('landing');
        }

        foreach (['/admin', '/constructor', '/grapesjs', '/preview'] as $reservado) {
            if (str_starts_with($value, $reservado)) {
                return route('landing');
            }
        }

        return $value;
    }
}
