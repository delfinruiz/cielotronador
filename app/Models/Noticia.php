<?php

namespace App\Models;

use Database\Factories\NoticiaFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['titulo', 'fecha', 'extracto', 'imagen', 'cuerpo', 'published'])]
class Noticia extends Model
{
    /** @use HasFactory<NoticiaFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'fecha' => 'date',
            'cuerpo' => 'array',
            'published' => 'boolean',
        ];
    }

    /**
     * Solo las noticias publicadas.
     */
    public function scopePublicadas(Builder $query): Builder
    {
        return $query->where('published', true);
    }
}
