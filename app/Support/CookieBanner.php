<?php

namespace App\Support;

use App\Models\SiteSetting;

/**
 * Textos del aviso de cookies.
 *
 * El aviso no es un bloque del constructor: se renderiza siempre desde el
 * layout, así que no se puede borrar ni duplicar desde el editor. Esta clase
 * solo resuelve los textos editables y garantiza que nunca queden vacíos.
 */
final class CookieBanner
{
    public const KEY = 'cookie_banner';

    /**
     * Límites de longitud por campo.
     *
     * @var array<string, int>
     */
    private const LIMITS = [
        'message' => 300,
        'policy_prefix' => 60,
        'policy_label' => 60,
        'necessary_label' => 60,
        'accept_label' => 60,
    ];

    /**
     * @return array<string, string>
     */
    public static function defaults(): array
    {
        return [
            'message' => 'Usamos cookies necesarias para el funcionamiento del sitio y, con tu consentimiento, para mejorar tu experiencia.',
            'policy_prefix' => 'Consulta nuestra',
            'policy_label' => 'Política de Cookies',
            'necessary_label' => 'Solo necesarias',
            'accept_label' => 'Aceptar todas',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function current(): array
    {
        $stored = SiteSetting::get(self::KEY, []);

        return self::normalize(is_array($stored) ? $stored : []);
    }

    /**
     * @param  array<string, mixed>  $values
     * @return array<string, string>
     */
    public static function normalize(array $values): array
    {
        $defaults = self::defaults();
        $normalized = [];

        foreach ($defaults as $field => $default) {
            $value = $values[$field] ?? null;
            $value = is_string($value)
                ? trim(preg_replace('/\s+/u', ' ', strip_tags(self::withoutScripts($value))) ?? '')
                : '';

            if ($value === '') {
                $value = $default;
            }

            $normalized[$field] = mb_substr($value, 0, self::LIMITS[$field]);
        }

        return $normalized;
    }

    /**
     * @return array<string, int>
     */
    public static function limits(): array
    {
        return self::LIMITS;
    }

    private static function withoutScripts(string $value): string
    {
        return preg_replace('~<(script|style)\b[^>]*>.*?</\1>~is', '', $value) ?? $value;
    }
}
