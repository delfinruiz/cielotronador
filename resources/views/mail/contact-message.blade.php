<x-mail::message>
# Nuevo mensaje de contacto

**Nombre:** {{ $nombre }}

**Correo electrónico:** {{ $email }}

**Mensaje:**

{{ $mensaje }}

Gracias,<br>
{{ config('app.name') }}
</x-mail::message>
