<?php

namespace App\Console\Commands;

use App\Models\AuditLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use ZipArchive;

class BackupRun extends Command
{
    protected $signature = 'backup:run {--keep=30 : Cantidad de respaldos a conservar}';

    protected $description = 'Crea un respaldo privado de la base de datos y de las imágenes públicas';

    public function handle(): int
    {
        $backupDirectory = storage_path('app/backups');
        File::ensureDirectoryExists($backupDirectory, 0700);

        $temporaryDirectory = $backupDirectory.'/tmp-'.Str::uuid();
        File::ensureDirectoryExists($temporaryDirectory, 0700);

        try {
            $this->respaldarBaseDeDatos($temporaryDirectory);
            $this->respaldarArchivos($temporaryDirectory);

            $filename = 'backup-'.now()->format('Y-m-d_His').'.zip';
            $zipPath = $backupDirectory.'/'.$filename;

            if (! $this->comprimir($temporaryDirectory, $zipPath)) {
                $this->error('No se pudo crear el archivo ZIP.');

                return self::FAILURE;
            }

            @chmod($zipPath, 0600);

            $this->limpiar($backupDirectory, max(1, (int) $this->option('keep')));

            AuditLog::record('backup.created', null, [], [
                'file' => $filename,
                'bytes' => filesize($zipPath) ?: 0,
            ]);

            $this->info('Respaldo creado: storage/app/backups/'.$filename);

            return self::SUCCESS;
        } finally {
            File::deleteDirectory($temporaryDirectory);
        }
    }

    private function respaldarBaseDeDatos(string $temporaryDirectory): void
    {
        if (config('database.default') !== 'sqlite') {
            $this->warn('La conexión no es SQLite; se omite el respaldo de la base de datos.');

            return;
        }

        $database = database_path('database.sqlite');

        if (! File::exists($database)) {
            $this->warn('No se encontró database/database.sqlite.');

            return;
        }

        $snapshot = $temporaryDirectory.'/database.sqlite';
        $pdo = DB::connection()->getPdo();

        // VACUUM INTO produce una copia consistente aunque haya escrituras en curso.
        $pdo->exec('VACUUM INTO '.$pdo->quote($snapshot));

        $this->line('Base de datos respaldada.');
    }

    private function respaldarArchivos(string $temporaryDirectory): void
    {
        if (File::isDirectory(public_path('img'))) {
            File::copyDirectory(public_path('img'), $temporaryDirectory.'/public/img');
        }

        if (File::isDirectory(storage_path('app/public'))) {
            File::copyDirectory(storage_path('app/public'), $temporaryDirectory.'/storage/app/public');
        }
    }

    private function comprimir(string $temporaryDirectory, string $zipPath): bool
    {
        $zip = new ZipArchive;

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return false;
        }

        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($temporaryDirectory, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::LEAVES_ONLY,
        );

        /** @var SplFileInfo $file */
        foreach ($files as $file) {
            if (! $file->isFile()) {
                continue;
            }

            $path = $file->getRealPath();

            if ($path === false) {
                continue;
            }

            $relative = ltrim(str_replace($temporaryDirectory, '', $path), DIRECTORY_SEPARATOR);
            $zip->addFile($path, $relative);
        }

        return $zip->close();
    }

    private function limpiar(string $backupDirectory, int $keep): void
    {
        $backups = collect(File::glob($backupDirectory.'/backup-*.zip') ?: [])
            ->sortByDesc(fn (string $path): int => filemtime($path) ?: 0)
            ->values();

        $backups->slice($keep)->each(fn (string $path) => File::delete($path));
    }
}
