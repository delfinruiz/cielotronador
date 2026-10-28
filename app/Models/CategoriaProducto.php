<?php

namespace App\Models;

use Database\Factories\CategoriaProductoFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

#[Fillable(['nombre', 'slug', 'descripcion', 'orden', 'publicada'])]
class CategoriaProducto extends Model
{
    /** @use HasFactory<CategoriaProductoFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'publicada' => 'boolean',
            'orden' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (CategoriaProducto $categoria): void {
            if (blank($categoria->slug)) {
                $categoria->slug = $categoria->slugUnico($categoria->nombre);
            }
        });
    }

    /**
     * Productos que pertenecen a la categoría.
     */
    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class, 'categoria_id');
    }

    /**
     * Solo las categorías visibles en la tienda.
     */
    public function scopePublicadas(Builder $query): Builder
    {
        return $query->where('publicada', true);
    }

    /**
     * Genera un slug legible y único a partir del nombre.
     */
    private function slugUnico(string $nombre): string
    {
        $base = Str::slug($nombre) ?: 'categoria';
        $slug = $base;
        $contador = 2;

        while (static::query()
            ->where('slug', $slug)
            ->when($this->exists, fn (Builder $query): Builder => $query->whereKeyNot($this->getKey()))
            ->exists()) {
            $slug = $base.'-'.$contador;
            $contador++;
        }

        return $slug;
    }
}
