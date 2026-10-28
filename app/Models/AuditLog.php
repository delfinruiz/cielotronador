<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

#[Fillable([
    'user_id',
    'event',
    'auditable_type',
    'auditable_id',
    'ip_address',
    'user_agent',
    'old_values',
    'new_values',
])]
class AuditLog extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'old_values' => 'array',
            'new_values' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function auditable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @param  array<string, mixed>  $old
     * @param  array<string, mixed>  $new
     */
    public static function record(string $event, ?Model $auditable = null, array $old = [], array $new = []): static
    {
        return static::query()->create([
            'user_id' => auth()->id(),
            'event' => $event,
            'auditable_type' => $auditable?->getMorphClass(),
            'auditable_id' => $auditable?->getKey(),
            'ip_address' => request()->ip(),
            'user_agent' => str(request()->userAgent())->limit(500)->toString(),
            'old_values' => $old ?: null,
            'new_values' => $new ?: null,
        ]);
    }
}
