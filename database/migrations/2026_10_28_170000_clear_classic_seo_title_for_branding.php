<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * La portada clásica guardaba un título SEO fijo con el nombre anterior.
     * Al retirarlo, el título vuelve a construirse con la identidad configurada
     * en el módulo de sistema.
     */
    public function up(): void
    {
        $template = DB::table('page_templates')->where('slug', 'clasica')->first();

        if ($template === null) {
            return;
        }

        $settings = json_decode((string) $template->settings, true);

        if (! is_array($settings) || ($settings['seo_title'] ?? null) !== 'Club CieloTronador') {
            return;
        }

        unset($settings['seo_title']);

        DB::table('page_templates')->where('id', $template->id)->update([
            'settings' => json_encode($settings, JSON_UNESCAPED_UNICODE),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        $template = DB::table('page_templates')->where('slug', 'clasica')->first();

        if ($template === null) {
            return;
        }

        $settings = json_decode((string) $template->settings, true);

        if (! is_array($settings)) {
            return;
        }

        $settings['seo_title'] = 'Club CieloTronador';

        DB::table('page_templates')->where('id', $template->id)->update([
            'settings' => json_encode($settings, JSON_UNESCAPED_UNICODE),
            'updated_at' => now(),
        ]);
    }
};
