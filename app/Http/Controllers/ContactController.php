<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Mail\ContactMessage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function __invoke(ContactRequest $request)
    {
        $data = $request->validated();

        try {
            Mail::to(config('services.contact.email'))
                ->send(new ContactMessage($data['nombre'], $data['email'], $data['mensaje']));

            return back()->with('contacto-estado', 'enviado');
        } catch (\Throwable $e) {
            Log::error('Error al enviar el formulario de contacto: '.$e->getMessage());

            return back()->with('contacto-estado', 'error')->withInput();
        }
    }
}
