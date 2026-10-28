<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categoria_id')
                ->nullable()
                ->constrained('categoria_productos')
                ->nullOnDelete();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->string('imagen')->nullable();
            $table->string('moneda', 3)->default('CLP');
            $table->decimal('precio', 12, 2);
            $table->decimal('precio_anterior', 12, 2)->nullable();
            $table->string('nota_precio')->nullable();
            $table->string('etiqueta')->nullable();
            $table->string('boton_texto')->default('Comprar ahora');
            $table->string('enlace_pago')->nullable();
            $table->boolean('publicado')->default(true);
            $table->unsignedInteger('orden')->default(0);
            $table->timestamps();

            $table->index(['publicado', 'orden']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
