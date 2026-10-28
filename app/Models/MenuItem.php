<?php

namespace App\Models;

use Database\Factories\MenuItemFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Route;

#[Fillable([
    'page_template_id',
    'parent_id',
    'location',
    'label',
    'link_type',
    'link_value',
    'target',
    'is_visible',
    'sort_order',
])]
class MenuItem extends Model
{
    /** @use HasFactory<MenuItemFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_visible' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function pageTemplate(): BelongsTo
    {
        return $this->belongsTo(PageTemplate::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order');
    }

    public function isExternal(): bool
    {
        return $this->link_type === 'url' && ! str_starts_with($this->link_value, '/');
    }

    /**
     * Resuelve el destino del enlace con una lista blanca de tipos.
     */
    public function resolvedUrl(): string
    {
        return match ($this->link_type) {
            'anchor' => '#'.ltrim($this->link_value, '#'),
            'route' => Route::has($this->link_value) ? route($this->link_value) : '#',
            'page' => $this->resolvedPageUrl(),
            'url' => preg_match('~^(https?://|mailto:|tel:|/)~i', $this->link_value) === 1
                ? $this->link_value
                : '#',
            default => '#',
        };
    }

    private function resolvedPageUrl(): string
    {
        $pagina = PageTemplate::query()->where('slug', $this->link_value)->first();

        if (! $pagina || ! $pagina->isGrapesjs() || ! $pagina->isPublished()) {
            return '#';
        }

        return $pagina->is_active
            ? route('landing')
            : route('page-templates.show', ['template' => $pagina->slug]);
    }

    /**
     * @return array<string, string>
     */
    public static function linkTypeOptions(): array
    {
        return [
            'anchor' => 'Sección de esta página',
            'page' => 'Página publicada',
            'route' => 'Ruta interna con nombre',
            'url' => 'URL externa o relativa',
        ];
    }
}
