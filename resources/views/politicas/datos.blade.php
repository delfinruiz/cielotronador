@extends('layouts.policy')

@section('titulo', 'Política de Seguridad y Manejo de Datos')

@section('contenido')
    <h1 class="font-heading mb-2 text-3xl font-semibold text-zinc-900 dark:text-white">Política de Seguridad y Manejo de Datos</h1>
    <p class="mb-8 text-sm text-zinc-500 dark:text-zinc-400">Última actualización: {{ now()->format('d/m/Y') }}</p>

    <div class="space-y-8 text-sm leading-relaxed text-zinc-600 dark:text-zinc-300">
        <section>
            <h2 class="font-heading mb-2 text-lg font-semibold text-zinc-900 dark:text-white">1. Alcance</h2>
            <p>
                Esta política describe cómo el <strong>Club de Básquetbol Cielo Tronador</strong> protege y maneja la
                información personal que recibe a través de su sitio web y del formulario de contacto, en cumplimiento
                de la Ley N.º 19.628 sobre Protección de la Vida Privada.
            </p>
        </section>

        <section>
            <h2 class="font-heading mb-2 text-lg font-semibold text-zinc-900 dark:text-white">2. Principios de manejo de datos</h2>
            <ul class="mt-2 list-disc space-y-1 pl-5">
                <li><strong>Minimización</strong>: solo recopilamos los datos estrictamente necesarios.</li>
                <li><strong>Finalidad</strong>: los datos se usan únicamente para el fin informado.</li>
                <li><strong>Transparencia</strong>: informamos claramente qué datos tratamos y por qué.</li>
                <li><strong>Confidencialidad</strong>: el acceso a los datos está restringido a personal autorizado.</li>
            </ul>
        </section>

        <section>
            <h2 class="font-heading mb-2 text-lg font-semibold text-zinc-900 dark:text-white">3. Medidas de seguridad</h2>
            <p>Para proteger la información aplicamos, entre otras, las siguientes medidas:</p>
            <ul class="mt-2 list-disc space-y-1 pl-5">
                <li>Cifrado de las comunicaciones mediante protocolo seguro (HTTPS).</li>
                <li>Control de acceso a los sistemas que almacenan los datos.</li>
                <li>Validación y saneamiento de los datos ingresados en los formularios.</li>
                <li>Copias de respaldo y revisión periódica de la configuración.</li>
            </ul>
        </section>

        <section>
            <h2 class="font-heading mb-2 text-lg font-semibold text-zinc-900 dark:text-white">4. Acceso a los datos</h2>
            <p>
                El acceso a los datos personales se limita a las personas del club que lo necesitan para cumplir sus
                funciones. No compartimos la información con terceros, salvo obligación legal o requerimiento de la
                autoridad competente.
            </p>
        </section>

        <section>
            <h2 class="font-heading mb-2 text-lg font-semibold text-zinc-900 dark:text-white">5. Conservación y eliminación</h2>
            <p>
                Los datos se conservan solo durante el tiempo necesario para la finalidad para la que fueron recopilados.
                Al cumplirse ese plazo, o cuando el titular lo solicite, se eliminan de forma segura.
            </p>
        </section>

        <section>
            <h2 class="font-heading mb-2 text-lg font-semibold text-zinc-900 dark:text-white">6. Contacto</h2>
            <p>
                Para ejercer tus derechos o reportar cualquier incidente de seguridad, escríbenos a
                <a href="mailto:{{ config('services.contact.email') }}" class="font-medium text-brand-600 hover:text-brand-500 dark:text-brand-400 dark:hover:text-brand-300">{{ config('services.contact.email') }}</a>.
            </p>
        </section>
    </div>
@endsection
