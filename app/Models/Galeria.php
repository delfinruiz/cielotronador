<?php

namespace App\Models;

use Database\Factories\GaleriaFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['imagen', 'titulo', 'sort_order', 'published'])]
class Galeria extends Model
{
    /** @use HasFactory<GaleriaFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'published' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Solo las fotografías publicadas.
     */
    public function scopePublicadas(Builder $query): Builder
    {
        return $query->where('published', true);
    }
}
