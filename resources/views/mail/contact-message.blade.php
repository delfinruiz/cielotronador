<x-mail::message>
# Nuevo mensaje de contacto

**Nombre:** {{ $nombre }}

**Correo electrónico:** {{ $email }}

@if ($telefono)
**Teléfono:** {{ $telefono }}
@endif

**Mensaje:**

{{ $mensaje }}

Gracias,<br>
{{ config('app.name') }}
</x-mail::message>
