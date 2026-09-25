<?php

namespace Database\Seeders;

use App\Models\Galeria;
use Illuminate\Database\Seeder;
use Illuminate\Filesystem\Filesystem;

class GaleriaSeeder extends Seeder
{
    /**
     * Importa a la base de datos las fotografías actuales de public/img/galeria.
     */
    public function run(): void
    {
        $directorio = public_path('img/galeria');

        if (! (new Filesystem)->isDirectory($directorio)) {
            return;
        }

        $archivos = collect((new Filesystem)->files($directorio))
            ->map(fn ($file) => $file->getFilename())
            ->filter(fn (string $archivo): bool => in_array(
                strtolower((string) pathinfo($archivo, PATHINFO_EXTENSION)),
                ['jpg', 'jpeg', 'png', 'webp'],
            ))
            ->sort()
            ->values();

        foreach ($archivos as $indice => $archivo) {
            Galeria::updateOrCreate(
                ['imagen' => $archivo],
                [
                    'titulo' => 'Actividad del Club CieloTronador',
                    'sort_order' => $indice + 1,
                    'published' => true,
                ],
            );
        }
    }
}
