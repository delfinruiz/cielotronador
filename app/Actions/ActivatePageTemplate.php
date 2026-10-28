<?php

namespace App\Actions;

use App\Models\AuditLog;
use App\Models\PageTemplate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ActivatePageTemplate
{
    public function execute(PageTemplate $template): PageTemplate
    {
        Gate::authorize('activate', $template);

        return DB::transaction(function () use ($template): PageTemplate {
            PageTemplate::query()
                ->whereKeyNot($template->getKey())
                ->where('is_active', true)
                ->update(['is_active' => false]);

            $template->publish();

            $template->forceFill([
                'is_active' => true,
                'updated_by' => auth()->id(),
            ])->save();

            AuditLog::record('page_template.activated', $template, [], [
                'slug' => $template->slug,
            ]);

            return $template->refresh();
        });
    }
}
