<?php

namespace App\Support;

/**
 * Bloque "Widget de WhatsApp" para el constructor.
 *
 * El número, el título del chat y el mensaje se guardan como atributos
 * `data-wa-*`, de modo que el editor pueda cambiarlos con sus ajustes y el
 * JavaScript del sitio los use al abrir wa.me.
 */
final class WhatsAppWidget
{
    public const DEFAULT_NUMBER = '56998970550';

    public const DEFAULT_TITLE = 'Atención al cliente';

    public const DEFAULT_MESSAGE = 'Hola, ¿en qué podemos ayudarte?';

    /**
     * La animación continua se aplica solo al botón, nunca al panel abierto.
     */
    public static function css(): string
    {
        return <<<'CSS'
[data-cf-whatsapp][data-cf-loop] { animation: none !important; }
[data-cf-whatsapp][data-cf-loop="float"] [data-wa-button] { animation: cf-float 6s ease-in-out infinite; }
[data-cf-whatsapp][data-cf-loop="pulse"] [data-wa-button] { animation: cf-pulse 3.2s ease-in-out infinite; }
[data-cf-whatsapp][data-cf-loop="beat"] [data-wa-button] { animation: cf-beat 1.8s ease-in-out infinite; }
[data-cf-whatsapp][data-wa-open="yes"] [data-wa-button] { animation-play-state: paused; }
CSS;
    }

    public static function block(
        string $number = self::DEFAULT_NUMBER,
        string $title = self::DEFAULT_TITLE,
        string $message = self::DEFAULT_MESSAGE,
        string $subtitle = 'Equipo de atención',
        string $avatar = 'https://picsum.photos/seed/cielo-chat/100/100',
    ): string {
        $numero = e($number);
        $titulo = e($title);
        $mensaje = e($message);
        $subtitulo = e($subtitle);
        $avatar = e($avatar);

        return <<<HTML
<div data-cf-whatsapp="yes" data-wa-widget="yes" data-gjs-name="Widget de WhatsApp"
     data-reveal="up" data-reveal-trigger="load" data-reveal-delay="150"
     data-wa-number="{$numero}"
     data-wa-title="{$titulo}"
     data-wa-message="{$mensaje}"
     style="position:fixed;right:1.5rem;bottom:1.5rem;z-index:60;">
  <div data-wa-panel="yes" role="dialog" aria-modal="false" aria-label="Chat de WhatsApp con {$titulo}"
       style="display:none;position:absolute;right:0;bottom:100%;margin-bottom:1rem;width:min(calc(100vw - 3rem),24rem);max-width:24rem;overflow:hidden;border-radius:1rem;background-color:var(--cf-chat-bg, #ece5dd);box-shadow:0 25px 50px -12px rgba(0,0,0,0.45);">
    <div style="display:flex;align-items:center;gap:.75rem;background-color:#075e54;padding:.75rem 1rem;color:#fff;">
      <span style="display:inline-flex;width:2.75rem;height:2.75rem;flex-shrink:0;align-items:center;justify-content:center;border-radius:9999px;background-color:rgba(255,255,255,0.95);">
        <img src="{$avatar}" alt="" width="32" height="32" style="display:block;width:2rem;height:2rem;border-radius:9999px;">
      </span>
      <div style="min-width:0;flex:1;">
        <p data-wa-title-text="yes" style="margin:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:.875rem;font-weight:600;">{$titulo}</p>
        <p style="margin:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:.75rem;color:rgba(255,255,255,0.75);">{$subtitulo}</p>
      </div>
      <button type="button" data-wa-close="yes" aria-label="Cerrar chat"
              style="display:inline-flex;width:2rem;height:2rem;flex-shrink:0;align-items:center;justify-content:center;border:0;border-radius:9999px;background:transparent;color:#fff;cursor:pointer;opacity:.8;">
        <img src="/img/whatsapp-close.svg" alt="" width="20" height="20" style="display:block;width:1.25rem;height:1.25rem;">
      </button>
    </div>
    <div style="background-color:var(--cf-chat-bg, #ece5dd);padding:1.25rem 1rem;">
      <div style="max-width:85%;border-radius:.5rem;border-top-left-radius:0;background-color:var(--cf-chat-bubble, #ffffff);padding:.5rem .75rem;box-shadow:0 1px 2px rgba(0,0,0,0.08);">
        <p data-wa-message-text="yes" style="margin:0;font-size:.875rem;line-height:1.6;color:var(--cf-ink, #18181b);">{$mensaje}</p>
        <span style="display:block;margin-top:.25rem;text-align:right;font-size:.6875rem;color:var(--cf-muted, #71717a);">ahora</span>
      </div>
    </div>
    <div style="background-color:var(--cf-chat-footer, #f0f2f5);">
      <form data-wa-form="yes" style="display:flex;align-items:center;gap:.5rem;padding:.75rem .75rem 0;">
        <input type="text" data-wa-input="yes" autocomplete="off" aria-label="Escribe tu mensaje" placeholder="Escribe un mensaje…"
               style="min-width:0;flex:1;border:0;border-radius:9999px;background-color:var(--cf-chat-input, #ffffff);padding:.625rem 1rem;font-size:.875rem;color:var(--cf-ink, #18181b);box-shadow:0 1px 2px rgba(0,0,0,0.08);outline:none;">
        <button type="submit" aria-label="Enviar por WhatsApp"
                style="display:inline-flex;width:2.5rem;height:2.5rem;flex-shrink:0;align-items:center;justify-content:center;border:0;border-radius:9999px;background-color:#25d366;color:#fff;cursor:pointer;">
          <img src="/img/whatsapp-send.svg" alt="" width="20" height="20" style="display:block;width:1.25rem;height:1.25rem;">
        </button>
      </form>
      <p style="margin:0;padding:.25rem 1rem .5rem;font-size:.6875rem;color:var(--cf-muted, #71717a);">Se abrirá WhatsApp con tu mensaje.</p>
    </div>
  </div>

  <div data-wa-button="yes" style="position:relative;width:3.5rem;height:3.5rem;">
    <span aria-hidden="true" style="position:absolute;inset:0;border-radius:9999px;background-color:#25d366;opacity:.3;"></span>
    <button type="button" data-wa-toggle="yes" aria-label="Abrir chat de WhatsApp" aria-expanded="false"
            style="position:relative;display:inline-flex;width:3.5rem;height:3.5rem;align-items:center;justify-content:center;border:0;border-radius:9999px;background-color:#25d366;color:#fff;box-shadow:0 10px 15px -3px rgba(0,0,0,0.25);cursor:pointer;">
      <img src="/img/whatsapp.svg" alt="" width="28" height="28" style="display:block;width:1.75rem;height:1.75rem;">
    </button>
  </div>
</div>
HTML;
    }

    /**
     * Widget original de la portada: la directora atiende el chat.
     */
    public static function classicBlock(): string
    {
        return self::block(
            number: '56998970550',
            title: 'Tía Jacqueline Reyes',
            message: 'Hola, soy la tía Jacqueline Reyes, directora del club CieloTronador.',
            subtitle: 'Directora · Club CieloTronador',
            avatar: '/img/balon.svg',
        );
    }
}
