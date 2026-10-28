<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_template_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_template_id')->constrained()->cascadeOnDelete();
            $table->longText('html')->nullable();
            $table->longText('css')->nullable();
            $table->json('gjs_data')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['page_template_id', 'published_at']);
        });

        /*
         * Cada plantilla que ya tiene una versión pública arranca con una
         * entrada en el historial. Así el primer rollback ya tiene referencia.
         */
        foreach (DB::table('page_templates')->orderBy('id')->get() as $template) {
            if (
                $template->published_html === null
                && $template->published_css === null
                && $template->published_gjs_data === null
            ) {
                continue;
            }

            $fecha = $template->published_at ?? $template->updated_at ?? now();

            DB::table('page_template_versions')->insert([
                'page_template_id' => $template->id,
                'html' => $template->published_html,
                'css' => $template->published_css,
                'gjs_data' => $template->published_gjs_data,
                'published_at' => $fecha,
                'created_by' => $template->updated_by ?? $template->created_by,
                'created_at' => $fecha,
                'updated_at' => $fecha,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('page_template_versions');
    }
};
