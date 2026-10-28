<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BackupDownloadController extends Controller
{
    public function __invoke(Request $request, string $file): BinaryFileResponse
    {
        abort_unless($request->user()?->hasRole('super_admin'), 403);
        abort_unless($request->hasValidSignature(), 403);
        abort_unless(preg_match('/^backup-\d{4}-\d{2}-\d{2}_\d{6}\.zip$/', $file) === 1, 404);

        $path = storage_path('app/backups/'.$file);

        abort_unless(is_file($path), 404);

        return response()->download($path, $file, [
            'Content-Type' => 'application/zip',
        ]);
    }
}
