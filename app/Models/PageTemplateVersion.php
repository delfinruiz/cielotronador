<?php

namespace App\Models;

use Database\Factories\PageTemplateVersionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'page_template_id',
    'html',
    'css',
    'gjs_data',
    'published_at',
    'created_by',
])]
class PageTemplateVersion extends Model
{
    /** @use HasFactory<PageTemplateVersionFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'gjs_data' => 'array',
            'published_at' => 'datetime',
        ];
    }

    public function pageTemplate(): BelongsTo
    {
        return $this->belongsTo(PageTemplate::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
