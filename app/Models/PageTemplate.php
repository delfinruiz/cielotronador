<?php

namespace App\Models;

use App\Filament\Resources\PageTemplates\PageTemplateResource;
use App\Support\PageBlockLibrary;
use App\Support\TemplateSanitizer;
use Database\Factories\PageTemplateFactory;
use HansDeBoeck\Grapesjs\Concerns\Ontwerpbaar;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'name',
    'slug',
    'description',
    'render_mode',
    'settings',
    'gjs_data',
    'html',
    'css',
    'published_gjs_data',
    'published_html',
    'published_css',
    'is_active',
    'published_at',
    'created_by',
    'updated_by',
])]
class PageTemplate extends Model
{
    /** @use HasFactory<PageTemplateFactory> */
    use HasFactory, Ontwerpbaar, SoftDeletes;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'settings' => 'array',
            'gjs_data' => 'array',
            'published_gjs_data' => 'array',
            'is_active' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public static function booted(): void
    {
        static::saved(fn (PageTemplate $template) => $template->forgetCache());
        static::deleted(fn (PageTemplate $template) => $template->forgetCache());
        static::restored(fn (PageTemplate $template) => $template->forgetCache());
        static::forceDeleted(fn (PageTemplate $template) => $template->forgetCache());

        static::created(function (PageTemplate $template): void {
            AuditLog::record('page_template.created', $template, [], $template->auditSnapshot());
        });

        static::updated(function (PageTemplate $template): void {
            $changes = array_intersect_key($template->getChanges(), array_flip([
                'name',
                'slug',
                'render_mode',
                'is_active',
                'published_at',
            ]));

            if ($changes !== []) {
                AuditLog::record('page_template.updated', $template, [], $changes);
            }
        });

        static::deleted(function (PageTemplate $template): void {
            AuditLog::record('page_template.deleted', $template, $template->auditSnapshot(), []);
        });
    }

    /**
     * @return array<string, mixed>
     */
    public function auditSnapshot(): array
    {
        return $this->only(['name', 'slug', 'render_mode', 'is_active', 'published_at']);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeGrapesjs(Builder $query): Builder
    {
        return $query->where('render_mode', 'grapesjs');
    }

    public function menuItems(): HasMany
    {
        return $this->hasMany(MenuItem::class)->orderBy('sort_order')->orderBy('id');
    }

    public function versions(): HasMany
    {
        return $this->hasMany(PageTemplateVersion::class);
    }

    public function mainMenuItems(): HasMany
    {
        return $this->menuItems()->where('location', 'main');
    }

    public function footerMenuItems(): HasMany
    {
        return $this->menuItems()->where('location', 'footer');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function isClassic(): bool
    {
        return $this->render_mode === 'blade';
    }

    public function isGrapesjs(): bool
    {
        return $this->render_mode === 'grapesjs';
    }

    public function hasDesign(): bool
    {
        return filled($this->html) || filled($this->gjs_data);
    }

    public function isPublished(): bool
    {
        return $this->published_at !== null;
    }

    /**
     * URL pública de la página. La portada activa vive en `/`; el resto, en su slug.
     */
    public function publicUrl(): string
    {
        return $this->is_active
            ? route('landing')
            : route('page-templates.show', ['template' => $this->slug]);
    }

    /**
     * Plantilla activa, con respaldo a la portada clásica si todavía no hay ninguna.
     */
    public static function activeOrClassic(): static
    {
        $id = Cache::rememberForever('page_templates.active_id', function (): ?int {
            return static::query()->active()->value('id');
        });

        $template = $id ? static::query()->find($id) : null;

        return $template instanceof static ? $template : static::classic();
    }

    /**
     * Plantilla en memoria que reproduce la portada histórica.
     */
    public static function classic(): static
    {
        return (new static([
            'name' => 'Clásica',
            'slug' => 'clasica',
            'description' => 'Portada histórica del club, con secciones dinámicas.',
            'render_mode' => 'blade',
            'is_active' => false,
        ]))->setRelation('menuItems', collect());
    }

    public function forgetCache(): void
    {
        Cache::forget('page_templates.active_id');
        Cache::forget('page_templates.menu.'.$this->getKey());
    }

    public function grapesjsTitel(): string
    {
        return (string) $this->name;
    }

    public function grapesjsTerugUrl(): ?string
    {
        if (! $this->exists) {
            return null;
        }

        /*
         * Al cerrar el constructor, la persona vuelve directo a la pestaña
         * "Contenido", donde están las acciones para abrirlo y publicarlo.
         */
        return PageTemplateResource::getUrl('edit', [
            'record' => $this,
            'tab' => 'contenido',
        ]);
    }

    /**
     * URL del constructor con versión para evitar que el navegador reutilice
     * una página antigua sin los ajustes más recientes.
     */
    public function grapesjsEditorUrl(): string
    {
        $version = @filemtime(resource_path('views/vendor/grapesjs/editor.blade.php')) ?: '1';

        return route('page-templates.editor', [
            'template' => $this,
        ]).'?v='.$version;
    }

    public function grapesjsModus(): string
    {
        return 'pagina';
    }

    /**
     * Bloques que se ofrecen dentro del editor GrapesJS.
     *
     * @return list<array{id: string, label: string, content: string, category: string}>
     */
    public function grapesjsBlokken(): array
    {
        return PageBlockLibrary::all();
    }

    /**
     * @return array<string, string>
     */
    public function grapesjsPlaatshouders(): array
    {
        return [];
    }

    /**
     * El paquete no persiste el CSS en modo página. Lo guardamos nosotros y
     * sanitizamos el HTML antes de que llegue a la base de datos.
     *
     * @param  array{project?: array<string, mixed>, html?: string, css?: string}  $gegevens
     */
    public function bewaarOntwerp(array $gegevens): static
    {
        $sanitizer = app(TemplateSanitizer::class);

        $this->gjs_data = $gegevens['project'] ?? [];
        $this->html = $sanitizer->html($gegevens['html'] ?? '');
        $this->css = $sanitizer->css($gegevens['css'] ?? '');
        $this->save();

        return $this;
    }

    /**
     * Copia el borrador actual a la versión pública.
     *
     * Guardar en el editor solo toca `html`, `css` y `gjs_data`; la portada
     * pública lee los campos `published_*`. Así se puede preparar un cambio sin
     * que el sitio lo muestre hasta pulsar Publicar.
     */
    public function publish(): static
    {
        $ultima = $this->versions()->latest('id')->first();

        $cambio = ! $ultima
            || $ultima->html !== $this->html
            || $ultima->css !== $this->css
            || $ultima->gjs_data != $this->gjs_data;

        $this->forceFill([
            'published_html' => $this->html,
            'published_css' => $this->css,
            'published_gjs_data' => $this->gjs_data,
            'published_at' => now(),
        ])->save();

        if ($cambio && $this->hasDesign()) {
            $this->versions()->create([
                'html' => $this->published_html,
                'css' => $this->published_css,
                'gjs_data' => $this->published_gjs_data,
                'published_at' => $this->published_at,
                'created_by' => auth()->id(),
            ]);
        }

        AuditLog::record('page_template.published', $this, [], [
            'published_at' => $this->published_at?->toIso8601String(),
        ]);

        return $this;
    }

    /**
     * Oculta la página de su URL pública sin borrar el diseño ni el historial.
     */
    public function unpublish(): static
    {
        $anterior = $this->published_at?->toIso8601String();

        $this->forceFill(['published_at' => null])->save();

        AuditLog::record('page_template.unpublished', $this, [
            'published_at' => $anterior,
        ], []);

        return $this;
    }

    /**
     * Recupera una versión publicada anterior y la publica de inmediato.
     */
    public function restoreVersion(PageTemplateVersion $version): static
    {
        abort_unless((int) $version->page_template_id === (int) $this->getKey(), 404);

        $this->forceFill([
            'html' => $version->html,
            'css' => $version->css,
            'gjs_data' => $version->gjs_data,
        ])->save();

        AuditLog::record('page_template.version_restored', $this, [], [
            'version_id' => $version->getKey(),
            'published_at' => $version->published_at?->toIso8601String(),
        ]);

        return $this->publish();
    }

    /**
     * Indica si la plantilla ya tiene una versión pública guardada.
     */
    public function hasPublishedDesign(): bool
    {
        return filled($this->published_html)
            || filled($this->published_css)
            || filled($this->published_gjs_data);
    }

    /**
     * Ruta pública de una imagen subida desde el editor.
     */
    public static function editorDisk(): string
    {
        return (string) config('grapesjs.uploads.schijf', 'plantillas');
    }

    public static function editorDirectory(): string
    {
        return trim((string) config('grapesjs.uploads.map', ''), '/');
    }

    public static function editorImageUrl(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        return Storage::disk(static::editorDisk())->url($path);
    }
}
