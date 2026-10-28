<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('page_templates', function (Blueprint $table) {
            $table->longText('published_html')->nullable()->after('html');
            $table->longText('published_css')->nullable()->after('css');
            $table->json('published_gjs_data')->nullable()->after('gjs_data');
        });

        /*
         * Las plantillas existentes ya están publicadas: su diseño actual pasa a
         * ser la versión pública. A partir de aquí, guardar en el editor solo
         * cambia el borrador y publicar copia el borrador a la web.
         */
        DB::table('page_templates')->update([
            'published_html' => DB::raw('html'),
            'published_css' => DB::raw('css'),
            'published_gjs_data' => DB::raw('gjs_data'),
        ]);
    }

    public function down(): void
    {
        Schema::table('page_templates', function (Blueprint $table) {
            $table->dropColumn(['published_html', 'published_css', 'published_gjs_data']);
        });
    }
};
