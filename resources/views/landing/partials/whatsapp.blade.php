{{-- Widget flotante de WhatsApp. Botón fijo abajo a la derecha que abre un
     panel estilo WhatsApp; al enviar, redirige a wa.me con el mensaje escrito. --}}
<div
    id="whatsapp-widget"
    class="fixed right-6 bottom-6 z-[60]"
    data-wa-number="56998970550"
    data-wa-default="Hola, quiero hacer una consulta al Club CieloTronador."
>
    {{-- Panel de chat --}}
    <div
        id="whatsapp-panel"
        class="whatsapp-panel absolute right-0 bottom-full mb-4 w-[calc(100vw-3rem)] max-w-sm overflow-hidden rounded-2xl shadow-2xl"
        role="dialog"
        aria-modal="false"
        aria-label="Chat de WhatsApp con la tía Jacqueline Reyes"
        hidden
    >
        {{-- Cabecera --}}
        <div class="flex items-center gap-3 bg-[#075E54] px-4 py-3 text-white">
            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-white/95 ring-2 ring-white/30">
                @include('landing.partials.baloncesto', ['attributes' => 'class="h-8 w-8"'])
            </span>
            <div class="min-w-0 flex-1">
                <p class="truncate text-sm font-semibold">Tía Jacqueline Reyes</p>
                <p class="truncate text-xs text-white/75">Directora · Club CieloTronador</p>
            </div>
            <button
                type="button"
                id="whatsapp-close"
                aria-label="Cerrar chat"
                class="inline-flex h-8 w-8 shrink-0 cursor-pointer items-center justify-center rounded-full text-white/80 transition-colors hover:bg-white/10 hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-white/60"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Cuerpo --}}
        <div class="bg-[#ECE5DD] px-4 py-5 dark:bg-[#0B141A]">
            <div class="max-w-[85%] rounded-lg rounded-tl-none bg-white px-3 py-2 shadow-sm dark:bg-[#202C33]">
                <p class="text-sm leading-relaxed text-zinc-800 dark:text-zinc-100">
                    Hola, soy la tía Jacqueline Reyes, directora del club CieloTronador.
                </p>
                <span class="mt-1 block text-right text-[11px] text-zinc-400 dark:text-zinc-500">ahora</span>
            </div>
        </div>

        {{-- Pie: entrada + envío --}}
        <div class="bg-[#F0F2F5] dark:bg-[#1F2C34]">
            <form id="whatsapp-form" class="flex items-center gap-2 px-3 pt-3">
                <input
                    type="text"
                    id="whatsapp-input"
                    autocomplete="off"
                    aria-label="Escribe tu mensaje"
                    placeholder="Escribe un mensaje…"
                    class="min-w-0 flex-1 rounded-full border-0 bg-white px-4 py-2.5 text-sm text-zinc-900 shadow-sm placeholder:text-zinc-400 focus:ring-2 focus:ring-[#25D366] focus:outline-none dark:bg-[#2A3942] dark:text-zinc-100 dark:placeholder:text-zinc-500"
                >
                <button
                    type="submit"
                    aria-label="Enviar por WhatsApp"
                    class="inline-flex h-10 w-10 shrink-0 cursor-pointer items-center justify-center rounded-full bg-[#25D366] text-white transition-colors hover:bg-[#1EBE5B] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#25D366] focus-visible:ring-offset-2 dark:focus-visible:ring-offset-[#1F2C34]"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5"/>
                    </svg>
                </button>
            </form>
            <p class="px-4 pt-1 pb-2 text-[11px] text-zinc-500 dark:text-zinc-400">Se abrirá WhatsApp con tu mensaje.</p>
        </div>
    </div>

    {{-- Botón flotante --}}
    <div class="relative h-14 w-14">
        <span class="absolute inset-0 animate-ping rounded-full bg-[#25D366] opacity-60 motion-reduce:animate-none" aria-hidden="true"></span>
        <button
            type="button"
            id="whatsapp-toggle"
            aria-controls="whatsapp-panel"
            aria-expanded="false"
            aria-label="Abrir chat de WhatsApp"
            class="relative inline-flex h-14 w-14 cursor-pointer items-center justify-center rounded-full bg-[#25D366] text-white shadow-xl shadow-black/20 transition-transform duration-200 hover:scale-105 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#25D366] focus-visible:ring-offset-2 dark:focus-visible:ring-offset-zinc-950"
        >
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-7 w-7" aria-hidden="true">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/>
            </svg>
        </button>
    </div>
</div>
