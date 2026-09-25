@extends('layouts.policy')

@section('titulo', 'Política de Privacidad')

@section('contenido')
    <h1 class="font-heading mb-2 text-3xl font-semibold text-zinc-900 dark:text-white">Política de Privacidad</h1>
    <p class="mb-8 text-sm text-zinc-500 dark:text-zinc-400">Última actualización: {{ now()->format('d/m/Y') }}</p>

    <div class="space-y-8 text-sm leading-relaxed text-zinc-600 dark:text-zinc-300">
        <section>
            <h2 class="font-heading mb-2 text-lg font-semibold text-zinc-900 dark:text-white">1. Responsable del tratamiento</h2>
            <p>
                El responsable del tratamiento de los datos personales es el <strong>Club de Básquetbol Cielo Tronador</strong>,
                con domicilio en Talcahuano, Chile. Para cualquier consulta relacionada con esta política puedes escribirnos a
                <a href="mailto:{{ config('services.contact.email') }}" class="font-medium text-brand-600 hover:text-brand-500 dark:text-brand-400 dark:hover:text-brand-300">{{ config('services.contact.email') }}</a>.
            </p>
        </section>

        <section>
            <h2 class="font-heading mb-2 text-lg font-semibold text-zinc-900 dark:text-white">2. Datos que recopilamos</h2>
            <p>Recopilamos únicamente los datos que nos proporcionas de forma voluntaria a través del formulario de contacto del sitio web:</p>
            <ul class="mt-2 list-disc space-y-1 pl-5">
                <li>Nombre</li>
                <li>Correo electrónico</li>
                <li>Mensaje o consulta</li>
            </ul>
            <p class="mt-2">No recopilamos datos sensibles ni utilizamos mecanismos de seguimiento o publicidad de terceros.</p>
        </section>

        <section>
            <h2 class="font-heading mb-2 text-lg font-semibold text-zinc-900 dark:text-white">3. Finalidad del tratamiento</h2>
            <p>Los datos recopilados se utilizan exclusivamente para:</p>
            <ul class="mt-2 list-disc space-y-1 pl-5">
                <li>Responder a tus consultas y solicitudes de información.</li>
                <li>Gestionar tu relación con el club (inscripciones, contacto institucional).</li>
            </ul>
        </section>

        <section>
            <h2 class="font-heading mb-2 text-lg font-semibold text-zinc-900 dark:text-white">4. Base legal</h2>
            <p>
                El tratamiento se fundamenta en tu <strong>consentimiento</strong>, otorgado al enviar el formulario,
                conforme a la Ley N.º 19.628 sobre Protección de la Vida Privada. Puedes retirar tu consentimiento en cualquier momento.
            </p>
        </section>

        <section>
            <h2 class="font-heading mb-2 text-lg font-semibold text-zinc-900 dark:text-white">5. Conservación de los datos</h2>
            <p>
                Conservamos tus datos únicamente durante el tiempo necesario para atender tu consulta y, como máximo,
                por el periodo que exija la normativa vigente. Cumplido ese plazo, los eliminamos de forma segura.
            </p>
        </section>

        <section>
            <h2 class="font-heading mb-2 text-lg font-semibold text-zinc-900 dark:text-white">6. Derechos del titular</h2>
            <p>De acuerdo con la Ley N.º 19.628, tienes derecho a:</p>
            <ul class="mt-2 list-disc space-y-1 pl-5">
                <li><strong>Acceso</strong>: conocer qué datos tuyos tratamos.</li>
                <li><strong>Rectificación</strong>: corregir datos inexactos o incompletos.</li>
                <li><strong>Cancelación</strong>: solicitar la eliminación de tus datos.</li>
                <li><strong>Oposición</strong>: oponerte al tratamiento de tus datos.</li>
            </ul>
            <p class="mt-2">
                Para ejercer estos derechos, escríbenos a
                <a href="mailto:{{ config('services.contact.email') }}" class="font-medium text-brand-600 hover:text-brand-500 dark:text-brand-400 dark:hover:text-brand-300">{{ config('services.contact.email') }}</a>.
            </p>
        </section>

        <section>
            <h2 class="font-heading mb-2 text-lg font-semibold text-zinc-900 dark:text-white">7. Seguridad de los datos</h2>
            <p>
                Adoptamos medidas técnicas y organizativas razonables para proteger tus datos frente a accesos no autorizados,
                pérdida o alteración. Sin embargo, ninguna transmisión por internet es totalmente segura.
            </p>
        </section>

        <section>
            <h2 class="font-heading mb-2 text-lg font-semibold text-zinc-900 dark:text-white">8. Cesión a terceros</h2>
            <p>No vendemos, arrendamos ni cedemos tus datos personales a terceros, salvo obligación legal.</p>
        </section>
    </div>
@endsection
