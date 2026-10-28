<?php

namespace App\Models;

use Database\Factories\MarcaFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'logo', 'url', 'sort_order', 'is_active'])]
class Marca extends Model
{
    /** @use HasFactory<MarcaFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function scopeActivas(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function logoUrl(): string
    {
        return asset('img/marcas/'.$this->logo);
    }
}
