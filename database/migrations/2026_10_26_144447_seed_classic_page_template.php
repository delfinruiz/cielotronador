<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::table('page_templates')->where('slug', 'clasica')->exists()) {
            return;
        }

        $now = now();

        $templateId = DB::table('page_templates')->insertGetId([
            'name' => 'Clásica',
            'slug' => 'clasica',
            'description' => 'Portada histórica del club, con secciones dinámicas.',
            'render_mode' => 'blade',
            'settings' => json_encode(['seo_title' => 'Club CieloTronador']),
            'is_active' => true,
            'published_at' => $now,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $items = [
            ['label' => 'Quiénes Somos', 'value' => '#quienes-somos'],
            ['label' => 'Noticias', 'value' => '#noticias'],
            ['label' => 'Actividades', 'value' => '#actividades'],
            ['label' => 'Horarios', 'value' => '#horarios'],
            ['label' => 'Contáctenos', 'value' => '#contacto'],
        ];

        foreach ($items as $index => $item) {
            DB::table('menu_items')->insert([
                'page_template_id' => $templateId,
                'location' => 'main',
                'label' => $item['label'],
                'link_type' => 'anchor',
                'link_value' => $item['value'],
                'target' => '_self',
                'is_visible' => true,
                'sort_order' => $index + 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        DB::table('page_templates')->where('slug', 'clasica')->delete();
    }
};
