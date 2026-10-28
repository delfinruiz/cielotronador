<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Mail\ContactMessage;
use App\Models\AuditLog;
use App\Support\SafeReturnPath;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function __invoke(ContactRequest $request)
    {
        $destino = SafeReturnPath::from($request->input('_return'));

        if (filled($request->input('website'))) {
            AuditLog::record('contact.honeypot');

            return redirect()->to($destino)->withFragment('contacto')->with('contacto-estado', 'enviado');
        }

        $data = $request->validated();

        try {
            Mail::to(config('services.contact.email'))
                ->send(new ContactMessage($data['nombre'], $data['email'], $data['mensaje'], $data['telefono'] ?? null));

            return redirect()->to($destino)->withFragment('contacto')->with('contacto-estado', 'enviado');
        } catch (\Throwable $e) {
            Log::error('Error al enviar el formulario de contacto: '.$e->getMessage());

            return redirect()->to($destino)->withFragment('contacto')->with('contacto-estado', 'error')->withInput();
        }
    }
}
