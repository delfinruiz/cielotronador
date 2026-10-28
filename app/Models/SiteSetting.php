<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

#[Fillable(['key', 'value'])]
class SiteSetting extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'value' => 'array',
        ];
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::rememberForever(self::cacheKey($key), function () use ($key, $default) {
            $setting = static::query()->where('key', $key)->first();

            return $setting?->value ?? $default;
        });
    }

    public static function put(string $key, mixed $value): void
    {
        static::query()->updateOrCreate(['key' => $key], ['value' => $value]);

        Cache::forget(self::cacheKey($key));
    }

    public static function forget(string $key): void
    {
        Cache::forget(self::cacheKey($key));
    }

    protected static function booted(): void
    {
        static::saved(fn (self $setting) => Cache::forget(self::cacheKey($setting->key)));
        static::deleted(fn (self $setting) => Cache::forget(self::cacheKey($setting->key)));
    }

    private static function cacheKey(string $key): string
    {
        return 'site-setting:'.$key;
    }
}
