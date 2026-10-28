<?php

namespace App\Models;

use Database\Factories\TestimonioFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'role', 'quote', 'avatar', 'rating', 'sort_order', 'is_active'])]
class Testimonio extends Model
{
    /** @use HasFactory<TestimonioFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'rating' => 'integer',
        ];
    }

    public function scopeActivos(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function avatarUrl(): ?string
    {
        return $this->avatar ? asset('img/testimonios/'.$this->avatar) : null;
    }

    /**
     * Iniciales para el avatar cuando no hay fotografía.
     */
    public function initials(): string
    {
        $parts = preg_split('/\s+/', trim((string) $this->name)) ?: [];

        $initials = '';

        foreach (array_slice($parts, 0, 2) as $part) {
            $initials .= mb_strtoupper(mb_substr($part, 0, 1));
        }

        return $initials !== '' ? $initials : '?';
    }
}
