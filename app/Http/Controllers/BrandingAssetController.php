<?php

namespace App\Http\Controllers;

use App\Support\Branding;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BrandingAssetController extends Controller
{
    /**
     * Sirve los archivos de marca desde una URL estable. Así las plantillas
     * guardadas muestran el logo actual sin reescribir su HTML.
     */
    public function __invoke(string $asset): BinaryFileResponse
    {
        $path = Branding::assetFilePath($asset);

        abort_if($path === null || ! is_file($path), 404);

        return response()->file($path, [
            'Cache-Control' => 'public, max-age=0, must-revalidate',
        ]);
    }
}
