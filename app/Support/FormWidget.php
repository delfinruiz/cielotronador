<?php

namespace App\Support;

/**
 * Bloque "Formulario" configurable para el constructor.
 *
 * Reproduce el diseño de la sección de contacto clásica: una columna con el
 * texto de presentación y los datos de contacto, y una tarjeta con el
 * formulario. El título, la descripción, las etiquetas de los campos, el
 * teléfono opcional, el texto del botón y el mensaje de éxito se guardan como
 * atributos `data-form-*` y se editan desde Settings. El token CSRF se inyecta
 * por JavaScript en la página pública.
 */
final class FormWidget
{
    public static function css(): string
    {
        return <<<'CSS'
[data-cf-form] {
    padding: 56px 24px;
}

/*
 * Las plantillas guardadas con el bloque antiguo conservan reglas por id
 * (por ejemplo `#contacto-2{max-width:640px}`). El bloque actualizado lleva
 * este marcador para que esas reglas no achiquen el diseño nuevo.
 */
[data-cf-form][data-form-upgraded="yes"] {
    max-width: none !important;
    margin: 0 !important;
    padding: 56px 24px !important;
}

[data-cf-form][data-form-telefono="no"] [data-form-telefono-row] { display: none; }

[data-cf-form] input,
[data-cf-form] textarea {
    width: 100%; box-sizing: border-box;
    border: 1px solid var(--cf-border-strong, #d4d4d8);
    border-radius: 12px; background-color: var(--cf-surface-muted, #f8fafc);
    color: var(--cf-ink, #18181b); padding: 12px 16px; font-size: 14px;
    font-family: inherit; box-shadow: 0 1px 2px rgba(15, 23, 42, .05);
    transition: border-color .18s ease, box-shadow .18s ease;
}
[data-cf-form] input::placeholder,
[data-cf-form] textarea::placeholder {
    color: var(--cf-muted, #71717a);
}
[data-cf-form] input:focus,
[data-cf-form] textarea:focus {
    outline: none; border-color: var(--cf-brand, #ff4c00);
    box-shadow: 0 0 0 3px var(--cf-brand-border, #fed7aa);
}

[data-cf-form] .cf-form-card {
    padding: clamp(22px, 4vw, 32px);
}

[data-cf-form] .cf-form-hint {
    margin: 8px 0 0; font-size: 12px; line-height: 1.5;
    color: var(--cf-muted, #71717a);
}

[data-cf-form] .cf-form-info-value {
    transition: color .18s ease;
}
[data-cf-form] .cf-form-info:hover .cf-form-info-value {
    color: var(--cf-brand, #ff4c00);
}

[data-cf-form] .cf-form-icon {
    display: inline-flex; flex: 0 0 auto; align-items: center; justify-content: center;
    width: 48px; height: 48px; border-radius: 9999px;
}
[data-cf-form] .cf-form-icon-mail {
    background-color: var(--cf-brand, #ff4c00); color: var(--cf-on-dark, #ffffff);
    font-size: 20px; line-height: 1; box-shadow: 0 12px 24px -12px rgba(255, 76, 0, .8);
}
[data-cf-form] .cf-form-icon-mail::before {
    content: "\2709";
}
[data-cf-form] .cf-form-icon-instagram {
    position: relative;
    background-color: var(--cf-ink, #18181b); color: var(--cf-surface, #ffffff);
}
[data-cf-form] .cf-form-icon-instagram::before {
    content: ""; position: absolute; width: 18px; height: 18px; box-sizing: border-box;
    border: 2px solid currentColor; border-radius: 6px;
}
[data-cf-form] .cf-form-icon-instagram::after {
    content: ""; position: absolute; width: 8px; height: 8px; box-sizing: border-box;
    border: 2px solid currentColor; border-radius: 9999px;
}

[data-cf-form] .cf-form-submit:hover {
    filter: brightness(.92);
}
[data-cf-form] .cf-form-submit:disabled {
    cursor: not-allowed; opacity: .6;
}
[data-cf-form] .cf-form-submit:disabled:hover {
    filter: none;
}
[data-cf-form] .cf-form-submit:focus-visible {
    outline: 2px solid var(--cf-brand, #ff4c00);
    outline-offset: 2px;
}

@media (min-width: 1024px) {
    [data-cf-form] {
        padding: 112px 24px;
    }

    [data-cf-form][data-form-upgraded="yes"] {
        padding: 112px 24px !important;
    }

    [data-cf-form] .cf-form-grid {
        grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
        gap: 64px;
    }

    [data-cf-form] .cf-form-card {
        padding: 40px;
    }
}
CSS;
    }

    /**
     * Sustituye el marcado de las plantillas guardadas con una versión anterior
     * del bloque por el diseño actual, conservando textos, ancla y animación.
     *
     * Es idempotente: los formularios que ya llevan la cuadrícula nueva se
     * devuelven sin tocar.
     */
    public static function upgradeLegacyHtml(string $html): string
    {
        if ($html === '' || ! str_contains($html, 'data-cf-form')) {
            return $html;
        }

        $contactoOcupado = (bool) preg_match('~id=(["\'])contacto\1~i', $html);

        $upgraded = preg_replace_callback(
            '~<section\b([^>]*\bdata-cf-form\b[^>]*)>(.*?)</section>~is',
            function (array $match) use ($contactoOcupado): string {
                if (stripos($match[2], 'cf-form-grid') !== false) {
                    return $match[0];
                }

                $attributes = self::parseAttributes($match[1]);
                $id = (string) ($attributes['id'] ?? 'contacto');

                if (! $contactoOcupado || $id === '') {
                    $id = 'contacto';
                }

                $overrides = array_filter([
                    'id' => $id,
                    'actualizado' => true,
                    'titulo' => $attributes['data-form-title'] ?? null,
                    'descripcion' => $attributes['data-form-description'] ?? null,
                    'labelNombre' => $attributes['data-form-label-nombre'] ?? null,
                    'labelEmail' => $attributes['data-form-label-email'] ?? null,
                    'labelTelefono' => $attributes['data-form-label-telefono'] ?? null,
                    'labelMensaje' => $attributes['data-form-label-mensaje'] ?? null,
                    'telefono' => $attributes['data-form-telefono'] ?? null,
                    'boton' => $attributes['data-form-button'] ?? null,
                    'exito' => $attributes['data-form-success'] ?? null,
                    'revelar' => $attributes['data-reveal'] ?? null,
                    'revelarDisparo' => $attributes['data-reveal-trigger'] ?? null,
                    'revelarRetardo' => $attributes['data-reveal-delay'] ?? null,
                    'loop' => $attributes['data-cf-loop'] ?? null,
                ], static fn (string|bool|null $value): bool => $value !== null);

                return self::block($overrides);
            },
            $html,
        );

        return $upgraded ?? $html;
    }

    /**
     * @return array<string, string>
     */
    private static function parseAttributes(string $attributes): array
    {
        preg_match_all(
            '~([a-zA-Z_:][-a-zA-Z0-9_:.]*)(?:\s*=\s*(?:"([^"]*)"|\'([^\']*)\'|([^\s"\'>=`]+)))?~',
            $attributes,
            $matches,
            PREG_SET_ORDER,
        );

        $parsed = [];

        foreach ($matches as $match) {
            $value = $match[2] ?? '';

            if ($value === '') {
                $value = $match[3] ?? '';
            }

            if ($value === '') {
                $value = $match[4] ?? '';
            }

            $parsed[strtolower($match[1])] = html_entity_decode((string) $value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }

        return $parsed;
    }

    /**
     * @param  array<string, string|bool|null>  $overrides
     */
    public static function block(array $overrides = []): string
    {
        $titulo = (string) ($overrides['titulo'] ?? 'Escríbenos');
        $descripcion = (string) ($overrides['descripcion'] ?? 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.');
        $labelNombre = (string) ($overrides['labelNombre'] ?? 'Nombre');
        $labelEmail = (string) ($overrides['labelEmail'] ?? 'Correo electrónico');
        $labelTelefono = (string) ($overrides['labelTelefono'] ?? 'Teléfono');
        $labelMensaje = (string) ($overrides['labelMensaje'] ?? 'Mensaje');
        $boton = (string) ($overrides['boton'] ?? 'Enviar mensaje');
        $exito = (string) ($overrides['exito'] ?? '¡Gracias! Tu mensaje fue enviado. Te responderemos a la brevedad.');
        $telefono = ($overrides['telefono'] ?? 'yes') === 'no' ? 'no' : 'yes';
        $idBase = (string) ($overrides['id'] ?? 'contacto');
        $id = e($idBase);

        /*
         * Los ids internos del formulario se sufijan con el id de la sección para
         * que dos formularios en la misma página no colisionen (labels, inputs,
         * contador y botón). El formulario principal conserva los ids históricos.
         */
        $sufijo = '';

        if ($idBase !== 'contacto') {
            $resto = str_starts_with($idBase, 'contacto') ? substr($idBase, strlen('contacto')) : $idBase;
            $limpio = preg_replace('/[^A-Za-z0-9_-]+/', '-', $resto) ?? '';
            $sufijo = $limpio === '' ? '' : '-'.ltrim($limpio, '-');
        }

        $revelar = e((string) ($overrides['revelar'] ?? 'up'));
        $revelarDisparo = e((string) ($overrides['revelarDisparo'] ?? 'scroll'));
        $revelarRetardo = e((string) ($overrides['revelarRetardo'] ?? '0'));
        $loop = (string) ($overrides['loop'] ?? '');
        $loopAttr = $loop === '' ? '' : ' data-cf-loop="'.e($loop).'"';
        $actualizado = (bool) ($overrides['actualizado'] ?? false) ? ' data-form-upgraded="yes"' : '';
        $email = e((string) ($overrides['email'] ?? config('services.contact.email')));
        $instagram = e((string) ($overrides['instagram'] ?? '@tu-marca'));
        $instagramUrl = e((string) ($overrides['instagramUrl'] ?? 'https://www.instagram.com/'));

        $titulo = e($titulo);
        $descripcion = e($descripcion);
        $labelNombre = e($labelNombre);
        $labelEmail = e($labelEmail);
        $labelTelefono = e($labelTelefono);
        $labelMensaje = e($labelMensaje);
        $boton = e($boton);
        $exito = e($exito);

        return <<<HTML
<section data-cf-form="yes" data-gjs-name="Formulario de contacto" id="{$id}"{$actualizado}
     data-reveal="{$revelar}" data-reveal-trigger="{$revelarDisparo}" data-reveal-delay="{$revelarRetardo}"{$loopAttr}
     data-form-title="{$titulo}"
     data-form-description="{$descripcion}"
     data-form-label-nombre="{$labelNombre}"
     data-form-label-email="{$labelEmail}"
     data-form-label-telefono="{$labelTelefono}"
     data-form-label-mensaje="{$labelMensaje}"
     data-form-telefono="{$telefono}"
     data-form-button="{$boton}"
     data-form-success="{$exito}"
     style="background-color:var(--cf-surface-gray, #f4f4f5);font-family:var(--cf-font-body, ui-sans-serif, system-ui, sans-serif);">
  <div style="max-width:1120px;margin:0 auto;">
    <div class="cf-form-grid" style="display:grid;gap:clamp(28px,4vw,48px);align-items:start;">
      <div>
        <p style="margin:0 0 12px;color:var(--cf-brand, #ff4c00);font-size:13px;font-weight:700;letter-spacing:.25em;text-transform:uppercase;">Contáctenos</p>
        <h2 data-form-title-text="yes" class="cf-form-title" style="margin:0;font-size:clamp(30px,5vw,48px);line-height:1.1;font-weight:700;letter-spacing:.05em;text-transform:uppercase;color:var(--cf-ink, #18181b);">{$titulo}</h2>
        <p data-form-description-text="yes" style="margin:24px 0 0;max-width:28rem;font-size:16px;line-height:1.7;color:var(--cf-text, #52525b);">{$descripcion}</p>

        <div style="margin-top:40px;display:flex;flex-direction:column;gap:20px;">
          <a class="cf-form-info" href="mailto:{$email}" style="display:flex;align-items:center;gap:16px;text-decoration:none;">
            <span class="cf-form-icon cf-form-icon-mail" aria-hidden="true"></span>
            <span>
              <span style="display:block;font-size:11px;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:var(--cf-muted, #71717a);">O escríbenos a</span>
              <span class="cf-form-info-value" style="display:block;font-weight:600;font-size:15px;color:var(--cf-ink, #18181b);">{$email}</span>
            </span>
          </a>

          <a class="cf-form-info" href="{$instagramUrl}" target="_blank" rel="noopener" style="display:flex;align-items:center;gap:16px;text-decoration:none;">
            <span class="cf-form-icon cf-form-icon-instagram" aria-hidden="true"></span>
            <span>
              <span style="display:block;font-size:11px;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:var(--cf-muted, #71717a);">Síguenos en Instagram</span>
              <span class="cf-form-info-value" style="display:block;font-weight:600;font-size:15px;color:var(--cf-ink, #18181b);">{$instagram}</span>
            </span>
          </a>
        </div>
      </div>

      <div class="cf-form-card" style="background-color:var(--cf-surface, #ffffff);border:1px solid var(--cf-border, #e4e4e7);border-radius:24px;box-shadow:0 20px 45px -25px rgba(15, 23, 42, .45);">
        <form method="POST" action="/contacto" id="contacto-form{$sufijo}" data-form-form="yes" style="display:grid;gap:20px;">
          <input type="hidden" name="_token" value="" data-form-token="yes">
          <div style="display:none;" aria-hidden="true">
            <label for="website{$sufijo}">No completar</label>
            <input type="text" name="website" id="website{$sufijo}" value="" tabindex="-1" autocomplete="off">
          </div>

          <div>
            <label for="nombre{$sufijo}" style="display:block;margin:0 0 8px;font-size:14px;font-weight:600;color:var(--cf-ink-soft, #3f3f46);"><span data-form-label-nombre-text="yes">{$labelNombre}</span> <span style="color:#ef4444;" aria-hidden="true">*</span></label>
            <input type="text" name="nombre" id="nombre{$sufijo}" required="required" autocomplete="name" placeholder="Tu nombre">
          </div>

          <div>
            <label for="email{$sufijo}" style="display:block;margin:0 0 8px;font-size:14px;font-weight:600;color:var(--cf-ink-soft, #3f3f46);"><span data-form-label-email-text="yes">{$labelEmail}</span> <span style="color:#ef4444;" aria-hidden="true">*</span></label>
            <input type="email" name="email" id="email{$sufijo}" required="required" autocomplete="email" placeholder="tu@correo.cl">
          </div>

          <div data-form-telefono-row="yes">
            <label for="telefono{$sufijo}" style="display:block;margin:0 0 8px;font-size:14px;font-weight:600;color:var(--cf-ink-soft, #3f3f46);"><span data-form-label-telefono-text="yes">{$labelTelefono}</span></label>
            <input type="tel" name="telefono" id="telefono{$sufijo}" autocomplete="tel" placeholder="+56 9 1234 5678">
          </div>

          <div>
            <label for="mensaje{$sufijo}" style="display:block;margin:0 0 8px;font-size:14px;font-weight:600;color:var(--cf-ink-soft, #3f3f46);"><span data-form-label-mensaje-text="yes">{$labelMensaje}</span> <span style="color:#ef4444;" aria-hidden="true">*</span></label>
            <textarea name="mensaje" id="mensaje{$sufijo}" rows="5" required="required" placeholder="Cuéntanos en qué podemos ayudarte..."></textarea>
            <p id="mensaje-contador{$sufijo}" class="cf-form-hint" aria-live="polite">Escribe al menos 10 caracteres.</p>
          </div>

          <button type="submit" id="contacto-submit{$sufijo}" class="cf-form-submit" data-form-button-text="yes"
                  style="display:flex;width:100%;align-items:center;justify-content:center;border:0;border-radius:9999px;background-color:var(--cf-brand, #ff4c00);color:var(--cf-on-brand, #ffffff);padding:14px 32px;font-weight:600;font-size:14px;font-family:inherit;cursor:pointer;box-shadow:0 12px 24px -12px rgba(255, 76, 0, .8);transition:filter .18s ease;">{$boton}</button>
        </form>
      </div>
    </div>
  </div>
</section>
HTML;
    }
}
