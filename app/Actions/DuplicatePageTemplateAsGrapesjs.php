<?php

namespace App\Actions;

use App\Models\AuditLog;
use App\Models\PageTemplate;
use Illuminate\Support\Str;

class DuplicatePageTemplateAsGrapesjs
{
    public function execute(PageTemplate $source): PageTemplate
    {
        $copy = $source->replicate(['is_active', 'published_at']);
        $copy->name = $source->name.' (GrapesJS)';
        $copy->slug = Str::slug($source->slug.'-grapesjs-'.Str::lower(Str::random(4)));
        $copy->render_mode = 'grapesjs';
        $copy->html = null;
        $copy->css = null;
        $copy->gjs_data = null;
        $copy->is_active = false;
        $copy->published_at = null;
        $copy->created_by = auth()->id();
        $copy->updated_by = auth()->id();
        $copy->save();

        foreach ($source->menuItems()->get() as $item) {
            $copy->menuItems()->create($item->only([
                'location',
                'label',
                'link_type',
                'link_value',
                'target',
                'is_visible',
                'sort_order',
            ]));
        }

        AuditLog::record('page_template.duplicated_as_grapesjs', $copy, [], [
            'from' => $source->getKey(),
        ]);

        return $copy;
    }
}
