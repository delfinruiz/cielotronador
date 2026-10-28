<?php

namespace App\Support;

use App\Models\PageTemplate;
use Illuminate\Support\Str;

/**
 * Metadatos de la página pública: título, descripción, Open Graph, canonical,
 * robots y datos estructurados. Se comparten entre la portada clásica y las
 * plantillas GrapesJS.
 */
final class SeoData
{
    /**
     * @return array{
     *     title: string,
     *     description: string,
     *     canonical: string,
     *     robots: string,
     *     og_title: string,
     *     og_description: string,
     *     og_image: string,
     *     site_name: string,
     *     json_ld: array<string, mixed>
     * }
     */
    public static function for(PageTemplate $template, bool $preview = false): array
    {
        $settings = is_array($template->settings) ? $template->settings : [];
        $classic = $template->isClassic();

        $brand = Branding::name();
        $isOriginalBrand = $brand === Branding::defaults()['company_name'];

        $title = self::text($settings['seo_title'] ?? null) ?: (
            $classic
                ? ($isOriginalBrand ? 'Club CieloTronador | Cielo Tronador' : $brand.' | Sitio web oficial')
                : Str::limit($template->name ?: 'Sitio web', 57)
        );

        $description = self::text($settings['seo_description'] ?? null) ?: (
            $classic
                ? ($isOriginalBrand
                    ? 'Club de Básquetbol Cielo Tronador de Talcahuano, Chile. Más que un club, una familia. Categorías desde sub 9 hasta adultos.'
                    : 'Sitio web oficial de '.$brand.'.')
                : 'Sitio web creado con el constructor visual.'
        );

        $ogTitle = self::text($settings['og_title'] ?? null) ?: $title;
        $ogDescription = self::text($settings['og_description'] ?? null) ?: $description;
        $ogImage = self::absolute((string) ($settings['og_image'] ?? '')) ?: Branding::logoLightUrl();
        $canonical = self::absolute((string) ($settings['canonical_url'] ?? '')) ?: url()->current();
        $siteName = self::text($settings['schema_name'] ?? null) ?: $brand;

        $robots = $preview
            ? 'noindex, nofollow'
            : (self::text($settings['robots'] ?? null) ?: 'index, follow');

        if (! in_array($robots, ['index, follow', 'noindex, nofollow'], true)) {
            $robots = 'index, follow';
        }

        return [
            'title' => $title,
            'description' => $description,
            'canonical' => $canonical,
            'robots' => $robots,
            'og_title' => $ogTitle,
            'og_description' => $ogDescription,
            'og_image' => $ogImage,
            'site_name' => $siteName,
            'json_ld' => self::jsonLd($settings, $description, $canonical, $ogImage, $siteName),
        ];
    }

    /**
     * @param  array<string, mixed>  $settings
     * @return array<string, mixed>
     */
    private static function jsonLd(
        array $settings,
        string $description,
        string $canonical,
        string $ogImage,
        string $siteName,
    ): array {
        $type = self::text($settings['schema_type'] ?? null) ?: 'SportsOrganization';
        $tipos = ['SportsOrganization', 'LocalBusiness', 'Organization', 'WebSite'];

        if (! in_array($type, $tipos, true)) {
            $type = 'SportsOrganization';
        }

        $datos = [
            '@context' => 'https://schema.org',
            '@type' => $type,
            'name' => self::text($settings['schema_name'] ?? null) ?: $siteName,
            'url' => $canonical,
            'description' => $description,
        ];

        if ($type !== 'WebSite') {
            $datos['logo'] = $ogImage;
        }

        if ($telefono = self::text($settings['schema_phone'] ?? null)) {
            $datos['telephone'] = $telefono;
        }

        if ($direccion = self::text($settings['schema_address'] ?? null)) {
            $datos['address'] = [
                '@type' => 'PostalAddress',
                'streetAddress' => $direccion,
            ];
        }

        return $datos;
    }

    private static function absolute(string $value): string
    {
        $value = trim($value);

        if ($value === '') {
            return '';
        }

        if (preg_match('~^https?://~i', $value) === 1) {
            return $value;
        }

        return url($value);
    }

    private static function text(mixed $value): string
    {
        return is_string($value) ? trim($value) : '';
    }
}
