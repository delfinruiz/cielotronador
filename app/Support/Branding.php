<?php

namespace App\Support;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Storage;

/**
 * Identidad visual de la instalación: nombre, lema, ciudad, datos de contacto
 * y archivos de marca. Se guarda en `site_settings`, así que cada instalación
 * (cada empresa) tiene su propia identidad sin depender de variables de entorno.
 */
final class Branding
{
    public const KEY = 'branding';

    public const DISK = 'branding';

    /**
     * Longitud máxima de cada campo de texto.
     *
     * @var array<string, int>
     */
    private const LIMITS = [
        'company_name' => 60,
        'tagline' => 120,
        'city' => 80,
        'contact_email' => 120,
        'instagram_url' => 255,
    ];

    /**
     * Campos que contienen un archivo en el disco de marca.
     *
     * @var list<string>
     */
    private const IMAGE_FIELDS = ['logo_light', 'logo_dark', 'favicon'];

    /**
     * @return array<string, string|null>
     */
    public static function defaults(): array
    {
        return [
            'company_name' => 'CieloTronador',
            'tagline' => '«Más que un club, una familia»',
            'city' => 'Talcahuano, Chile',
            'contact_email' => (string) config('services.contact.email', 'contacto@cielotronador.cl'),
            'instagram_url' => 'https://www.instagram.com/cielotronadorthno/',
            'logo_light' => null,
            'logo_dark' => null,
            'favicon' => null,
        ];
    }

    /**
     * @return array<string, string|null>
     */
    public static function current(): array
    {
        $stored = SiteSetting::get(self::KEY, []);

        return self::normalize(is_array($stored) ? $stored : []);
    }

    /**
     * Normaliza y limita los valores antes de guardarlos. Un campo de texto
     * vacío vuelve al valor por defecto para que la identidad nunca quede en
     * blanco; un archivo que ya no existe se descarta.
     *
     * @param  array<string, mixed>  $values
     * @return array<string, string|null>
     */
    public static function normalize(array $values): array
    {
        $defaults = self::defaults();
        $normalized = [];

        foreach ($defaults as $field => $default) {
            if (in_array($field, self::IMAGE_FIELDS, true)) {
                $normalized[$field] = self::normalizeImage($values[$field] ?? null);

                continue;
            }

            $value = $values[$field] ?? null;
            $value = is_string($value)
                ? trim(preg_replace('/\s+/u', ' ', strip_tags($value)) ?? '')
                : '';

            if ($field === 'contact_email' && $value !== '' && ! filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $value = '';
            }

            if ($field === 'instagram_url' && $value !== '' && ! filter_var($value, FILTER_VALIDATE_URL)) {
                $value = '';
            }

            $normalized[$field] = $value === ''
                ? $default
                : mb_substr($value, 0, self::LIMITS[$field]);
        }

        return $normalized;
    }

    /**
     * Guarda la identidad y elimina los archivos que hayan sido reemplazados.
     *
     * @param  array<string, mixed>  $values
     */
    public static function save(array $values): void
    {
        $previous = self::current();
        $normalized = self::normalize(array_merge($previous, $values));

        foreach (self::IMAGE_FIELDS as $field) {
            $old = $previous[$field];

            if ($old !== null && $old !== $normalized[$field]) {
                Storage::disk(self::DISK)->delete($old);
            }
        }

        SiteSetting::put(self::KEY, $normalized);
    }

    /**
     * Vuelve a la identidad original y borra los archivos personalizados.
     */
    public static function restoreDefaults(): void
    {
        $previous = self::current();

        foreach (self::IMAGE_FIELDS as $field) {
            if ($previous[$field] !== null) {
                Storage::disk(self::DISK)->delete($previous[$field]);
            }
        }

        SiteSetting::put(self::KEY, self::defaults());
    }

    public static function name(): string
    {
        return (string) self::current()['company_name'];
    }

    public static function tagline(): string
    {
        return (string) self::current()['tagline'];
    }

    public static function city(): string
    {
        return (string) self::current()['city'];
    }

    public static function contactEmail(): string
    {
        return (string) self::current()['contact_email'];
    }

    public static function instagramUrl(): string
    {
        return (string) self::current()['instagram_url'];
    }

    public static function logoLightUrl(): string
    {
        return self::assetUrl('logo-light', versioned: true);
    }

    public static function logoDarkUrl(): string
    {
        return self::assetUrl('logo-dark', versioned: true);
    }

    public static function faviconUrl(): string
    {
        return self::assetUrl('favicon', versioned: true);
    }

    /**
     * Ruta estable que se guarda dentro del HTML de las plantillas. Al cambiar
     * el archivo en configuración, la plantilla ya guardada muestra el nuevo.
     */
    public static function logoLightPath(): string
    {
        return '/branding/logo-light';
    }

    public static function logoDarkPath(): string
    {
        return '/branding/logo-dark';
    }

    /**
     * Archivo absoluto que corresponde a un recurso de marca. Si no hay uno
     * personalizado, devuelve el archivo estático original.
     */
    public static function assetFilePath(string $asset): ?string
    {
        $field = match ($asset) {
            'logo-light' => 'logo_light',
            'logo-dark' => 'logo_dark',
            'favicon' => 'favicon',
            default => null,
        };

        if ($field === null) {
            return null;
        }

        $file = self::current()[$field];

        if ($file !== null) {
            $path = Storage::disk(self::DISK)->path($file);

            if (is_file($path)) {
                return $path;
            }
        }

        return match ($asset) {
            'logo-light' => public_path('img/logo.png'),
            'logo-dark' => public_path('img/logo-dark.png'),
            'favicon' => public_path('favicon.ico'),
            default => null,
        };
    }

    /**
     * Sustituye los logos antiguos del HTML ya guardado y actualiza el nombre
     * en los pies clásicos. Los logos nuevos usan la ruta estable y conservan
     * la variante clara/oscura para que el conmutador de tema los cambie.
     */
    public static function applyToHtml(string $html): string
    {
        if ($html === '') {
            return $html;
        }

        $name = self::name();
        $light = self::logoLightPath();
        $dark = self::logoDarkPath();

        $html = preg_replace_callback(
            '~<img\b[^>]*>~i',
            function (array $match) use ($light, $dark, $name): string {
                $tag = $match[0];

                if (! preg_match('~\bsrc\s*=\s*(["\'])([^"\']*)\1~i', $tag, $source)) {
                    return $tag;
                }

                $path = parse_url($source[2], PHP_URL_PATH) ?: $source[2];

                if (! in_array($path, ['/img/logo.png', '/img/logo-dark.png'], true)) {
                    return $tag;
                }

                $tag = preg_replace('~\bsrc\s*=\s*(["\'])[^"\']*\1~i', 'src="'.$light.'"', $tag, 1) ?? $tag;

                if (! str_contains($tag, 'data-brand-logo-light=')) {
                    $tag = preg_replace(
                        '~^<img\b~i',
                        '<img data-brand-logo-light="'.$light.'" data-brand-logo-dark="'.$dark.'"',
                        $tag,
                        1,
                    ) ?? $tag;
                }

                if (preg_match('~\balt\s*=\s*(["\'])Logotipo\1~i', $tag) === 1) {
                    $tag = preg_replace(
                        '~\balt\s*=\s*(["\'])Logotipo\1~i',
                        'alt="'.self::escape($name).'"',
                        $tag,
                    ) ?? $tag;
                }

                return $tag;
            },
            $html,
        ) ?? $html;

        $html = preg_replace_callback(
            '~Copyright\s+(\d{4})\s+por\s+CieloTronador~i',
            fn (array $match): string => 'Copyright '.$match[1].' por '.self::escape($name),
            $html,
        ) ?? $html;

        $html = preg_replace_callback(
            '~©\s*(\d{4})\s+Tu marca\s*·\s*Tu ciudad, País~i',
            fn (array $match): string => '© '.$match[1].' '.self::escape($name).' · '.self::escape(self::city()),
            $html,
        ) ?? $html;

        return $html;
    }

    private static function assetUrl(string $asset, bool $versioned = false): string
    {
        $parameters = ['asset' => $asset];

        if ($versioned) {
            $parameters['v'] = self::version($asset);
        }

        return route('branding.asset', $parameters);
    }

    private static function version(string $asset): int
    {
        $path = self::assetFilePath($asset);

        return $path !== null && is_file($path) ? (int) filemtime($path) : 1;
    }

    private static function normalizeImage(mixed $value): ?string
    {
        if (is_array($value)) {
            $value = $value[0] ?? null;
        }

        if (! is_string($value)) {
            return null;
        }

        $value = trim($value);

        if ($value === '') {
            return null;
        }

        $name = basename(str_replace('\\', '/', $value));

        if ($name === '' || $name === '.' || $name === '..') {
            return null;
        }

        return Storage::disk(self::DISK)->exists($name) ? $name : null;
    }

    private static function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
