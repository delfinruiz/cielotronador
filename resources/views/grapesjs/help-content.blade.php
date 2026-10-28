<style>
    .cf-help {
        color: #27272a;
        font-family: ui-sans-serif, system-ui, sans-serif;
        font-size: 14px;
        line-height: 1.65;
    }

    .cf-help h2 {
        margin: 28px 0 8px;
        color: #18181b;
        font-size: 18px;
        font-weight: 800;
        line-height: 1.25;
    }

    .cf-help h2:first-child {
        margin-top: 0;
    }

    .cf-help h3 {
        margin: 18px 0 6px;
        color: #18181b;
        font-size: 15px;
        font-weight: 700;
    }

    .cf-help p {
        margin: 0 0 10px;
    }

    .cf-help ul,
    .cf-help ol {
        margin: 0 0 12px;
        padding-left: 20px;
    }

    .cf-help li {
        margin: 4px 0;
    }

    .cf-help table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .cf-help th,
    .cf-help td {
        padding: 8px 10px;
        border-bottom: 1px solid #e4e4e7;
        text-align: left;
        vertical-align: top;
    }

    .cf-help th {
        background: #f4f4f5;
        color: #18181b;
        font-weight: 700;
    }

    .cf-help .cf-help__icon {
        width: 44px;
        font-size: 18px;
        text-align: center;
    }

    .cf-help .cf-help__note {
        margin: 12px 0;
        padding: 10px 12px;
        border: 1px solid #fed7aa;
        border-left: 4px solid #FF4C00;
        border-radius: 8px;
        background: #fff7ed;
    }

    .cf-help .cf-help__grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 10px;
        margin: 10px 0;
    }

    .cf-help .cf-help__card {
        padding: 10px 12px;
        border: 1px solid #e4e4e7;
        border-radius: 10px;
        background: #fafafa;
    }

    .cf-help .cf-help__card strong {
        display: block;
        margin-bottom: 2px;
    }

    .cf-help .cf-help__table-wrap {
        overflow-x: auto;
    }

    .cf-help .cf-help__muted {
        color: #71717a;
        font-size: 12px;
    }

    .cf-help kbd {
        padding: 1px 5px;
        border: 1px solid #d4d4d8;
        border-radius: 4px;
        background: #f4f4f5;
        font-family: ui-monospace, monospace;
        font-size: 12px;
    }

    .dark .cf-help {
        color: #d4d4d8;
    }

    .dark .cf-help h2,
    .dark .cf-help h3 {
        color: #fafafa;
    }

    .dark .cf-help th {
        background: #27272a;
        color: #fafafa;
    }

    .dark .cf-help th,
    .dark .cf-help td {
        border-color: #3f3f46;
    }

    .dark .cf-help .cf-help__card {
        border-color: #3f3f46;
        background: #18181b;
    }

    .dark .cf-help .cf-help__note {
        border-color: #9a3412;
        background: #431407;
    }

    .dark .cf-help kbd {
        border-color: #52525b;
        background: #27272a;
    }
</style>

<div class="cf-help">
    <p>
        Esta guía traduce la interfaz del <strong>editor visual</strong>, que está en inglés, y explica qué hace cada botón.
        No necesitas tocar código: todo se hace arrastrando bloques y editando el lienzo.
    </p>

    <div class="cf-help__note">
        <strong>Lo básico:</strong> abre <strong>Bloques</strong>, arrastra un bloque al lienzo, haz doble clic
        para editar el texto y pulsa <strong>Guardar</strong> en la barra negra. Mientras trabajas, el editor
        guarda un borrador local por si cierras la pestaña sin querer.
    </div>

    <h2>1. Mapa del editor</h2>

    <div class="cf-help__grid">
        <div class="cf-help__card">
            <strong>Barra negra superior</strong>
            Guardar, modo oscuro, Ayuda y Cerrar. Es la barra de esta aplicación, no del editor.
        </div>
        <div class="cf-help__card">
            <strong>Barra de iconos</strong>
            Bloques, capas, estilos, ajustes, imágenes, dispositivos y deshacer.
        </div>
        <div class="cf-help__card">
            <strong>Lienzo central</strong>
            La página que estás construyendo. Aquí arrastras y editas. Con <strong>🌙 Oscuro</strong> revisas
            cómo se ve el diseño en modo oscuro.
        </div>
        <div class="cf-help__card">
            <strong>Panel derecho</strong>
            Cambia según el icono que pulses: bloques, capas, estilos o ajustes. Arriba del catálogo hay un
            buscador de bloques.
        </div>
    </div>

    <h2>2. Qué significa cada botón</h2>

    <div class="cf-help__table-wrap">
        <table>
            <thead>
                <tr>
                    <th class="cf-help__icon">Icono</th>
                    <th>Nombre en el editor</th>
                    <th>Para qué sirve</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="cf-help__icon">🧱</td>
                    <td><strong>Blocks</strong> (Bloques)</td>
                    <td>Abre la paleta de bloques. Arrastra uno al lienzo o dentro de una sección.</td>
                </tr>
                <tr>
                    <td class="cf-help__icon">🗂️</td>
                    <td><strong>Layers</strong> (Capas)</td>
                    <td>Muestra el árbol de elementos. Úsalo para seleccionar algo que está dentro de otra sección.</td>
                </tr>
                <tr>
                    <td class="cf-help__icon">🎨</td>
                    <td><strong>Style Manager</strong> (Estilos)</td>
                    <td>Colores, tipografía, tamaño, márgenes, padding, bordes y fondos del elemento seleccionado.</td>
                </tr>
                <tr>
                    <td class="cf-help__icon">⚙️</td>
                    <td><strong>Settings</strong> (Ajustes)</td>
                    <td>ID, título, enlace, destino y otros atributos del elemento. Aquí pones el <code>id</code> para el menú.</td>
                </tr>
                <tr>
                    <td class="cf-help__icon">🖥️</td>
                    <td><strong>Escritorio / Tableta / Móvil</strong></td>
                    <td>Cambia el ancho del lienzo para revisar cómo se ve en computador, tablet y teléfono. El lienzo se ajusta al ancho real del dispositivo.</td>
                </tr>
                <tr>
                    <td class="cf-help__icon">👁️</td>
                    <td><strong>Preview</strong> (Vista previa)</td>
                    <td>Oculta los controles del editor para ver el resultado limpio.</td>
                </tr>
                <tr>
                    <td class="cf-help__icon">⛶</td>
                    <td><strong>Fullscreen</strong> (Pantalla completa)</td>
                    <td>Agranda el editor al tamaño de la pantalla.</td>
                </tr>
                <tr>
                    <td class="cf-help__icon">↩️</td>
                    <td><strong>Undo / Redo</strong> (Deshacer / Rehacer)</td>
                    <td>Vuelve atrás o repite el último cambio.</td>
                </tr>
                <tr>
                    <td class="cf-help__icon">🖼️</td>
                    <td><strong>Assets</strong> (Imágenes)</td>
                    <td>Abre las imágenes subidas. Desde aquí puedes subir o reemplazar una imagen.</td>
                </tr>
                <tr>
                    <td class="cf-help__icon">&lt;/&gt;</td>
                    <td><strong>Export / Import</strong> (Código)</td>
                    <td>Muestra o importa HTML. No es necesario para el uso normal y el HTML se sanitiza al guardar.</td>
                </tr>
                <tr>
                    <td class="cf-help__icon">🗑️</td>
                    <td><strong>Clear canvas</strong> (Limpiar)</td>
                    <td>Borra todo el contenido del lienzo. Úsalo solo si quieres empezar de cero.</td>
                </tr>
                <tr>
                    <td class="cf-help__icon">💾</td>
                    <td><strong>Guardar</strong> (barra negra)</td>
                    <td>Guarda el borrador en la plantilla. El estado <em>Guardado</em> significa guardado. No cambia la web hasta pulsar <strong>Publicar</strong>.</td>
                </tr>
                <tr>
                    <td class="cf-help__icon">🚀</td>
                    <td><strong>Publicar</strong> (barra negra)</td>
                    <td>Copia el borrador actual a la web pública. Es el paso que hace visibles los cambios a los visitantes.</td>
                </tr>
                <tr>
                    <td class="cf-help__icon">🌙</td>
                    <td><strong>Oscuro / Claro</strong> (barra negra)</td>
                    <td>Cambia el lienzo entre modo claro y oscuro para revisar ambos temas. No cambia la web publicada.</td>
                </tr>
                <tr>
                    <td class="cf-help__icon">🔎</td>
                    <td><strong>Buscar bloques</strong></td>
                    <td>Campo sobre el catálogo de bloques. Filtra por nombre o por tema (por ejemplo “precio”, “formulario”, “carrusel”).</td>
                </tr>
                <tr>
                    <td class="cf-help__icon">✖️</td>
                    <td><strong>Cerrar</strong> (barra negra)</td>
                    <td>Vuelve a la edición de la plantilla en el panel.</td>
                </tr>
                <tr>
                    <td class="cf-help__icon">❓</td>
                    <td><strong>Ayuda</strong> (barra negra)</td>
                    <td>Abre esta guía en cualquier momento.</td>
                </tr>
            </tbody>
        </table>
    </div>

    <p class="cf-help__muted">
        Algunos iconos pueden estar ocultos si la pantalla es angosta. Si no encuentras uno, pulsa el icono de bloques
        o abre el editor en una ventana más grande.
    </p>

    <h2>Empezar desde una plantilla</h2>

    <p>
        Si no quieres partir de un lienzo vacío, abre <strong>Contenido → Páginas</strong> y pulsa el botón <strong>Plantillas</strong>.
        Verás una tarjeta por plantilla con su miniatura y dos botones:
    </p>

    <ul>
        <li><strong>Vista previa:</strong> abre la plantilla tal como quedará, en otra pestaña. No crea nada.</li>
        <li><strong>Usar esta plantilla:</strong> crea una página nueva en modo borrador, con los textos, las imágenes y el menú ya cargados.</li>
    </ul>

    <p>
        La página nace <strong>sin publicar</strong>, así que la web actual no cambia. Cuando termines de editarla,
        publícala desde <strong>Guardar y publicar</strong> o desde la lista de <strong>Páginas</strong>.
    </p>

    <div class="cf-help__note">
        El <strong>menú superior siempre es el del sitio</strong> y se administra desde la pestaña de menú de la página.
        La plantilla aporta el cuerpo y el pie. Los colores y las tipografías vienen con la plantilla; puedes cambiar
        textos e imágenes con doble clic sin perder ese estilo.
    </div>

    <p>
        Las plantillas disponibles son <strong>Agencia</strong> (estudio creativo), <strong>Creativo</strong>
        (portafolio con proceso), <strong>Estudio Visual</strong> (fotografía con carrusel),
        <strong>Corporativo</strong> (servicios con marcas, testimonios y FAQ), <strong>Producto</strong>
        (SaaS con video) y <strong>Club</strong> (equipo y tienda). Cada una trae sus colores, tipografías,
        textos e imágenes listos para reemplazar.
    </p>

    <p>
        Todas se adaptan al <strong>modo claro y oscuro</strong> del sitio: el botón de luna/sol del menú
        cambia los fondos, textos y tarjetas sin tocar la plantilla. En el constructor puedes revisar ambos
        modos con el botón <strong>🌙 Oscuro</strong> de la barra del editor.
    </p>

    <h2>El aviso de cookies</h2>

    <p>
        Todas las páginas muestran siempre un aviso de cookies en la parte inferior. No es un bloque del
        constructor: se añade al renderizar la página, así que no se puede borrar, mover ni duplicar desde el lienzo.
    </p>

    <div class="cf-help__note">
        Para cambiar el mensaje o los textos de los botones, abre
        <a href="{{ \App\Filament\Resources\PageTemplates\PageTemplateResource::getUrl('index', ['pestana' => 'aviso']) }}" target="_blank" rel="noopener">
            <strong>Páginas → Aviso</strong>
        </a>.
        El enlace a la Política de Cookies se mantiene siempre y no se puede quitar.
    </div>

    <h2>3. Crear una página en 6 pasos</h2>

    <ol>
        <li><strong>Arrastra una base:</strong> en <strong>Blocks</strong>, elige <strong>Hero</strong> o <strong>Sección</strong>.</li>
        <li><strong>Edita el texto:</strong> haz doble clic sobre un título o párrafo y escribe.</li>
        <li><strong>Ajusta el estilo:</strong> selecciona el bloque y usa <strong>Style Manager</strong> para colores, tamaños y espacios.</li>
        <li><strong>Pon un ID:</strong> selecciona la sección, abre <strong>Settings</strong> y escribe por ejemplo <code>inicio</code>.</li>
        <li><strong>Guarda:</strong> pulsa <strong>Guardar</strong> en la barra negra.</li>
        <li><strong>Revisa y publica:</strong> pulsa <strong>Cerrar</strong>, luego <strong>Vista previa</strong> y finalmente <strong>Activar en el inicio</strong>.</li>
    </ol>

    <h2>4. Bloques del kit</h2>

    <div class="cf-help__table-wrap">
        <table>
            <thead>
                <tr>
                    <th class="cf-help__icon">Icono</th>
                    <th>Bloque</th>
                    <th>Qué hace</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="cf-help__icon">🏠</td>
                    <td><strong>Hero de bienvenida</strong></td>
                    <td>Portada con título, texto, botón e imagen. Ideal para empezar.</td>
                </tr>
                <tr>
                    <td class="cf-help__icon">🏀</td>
                    <td><strong>Hero del club</strong></td>
                    <td>Portada oscura con el escudo, el lema y los botones de la portada clásica.</td>
                </tr>
                <tr>
                    <td class="cf-help__icon">🎬</td>
                    <td><strong>Video</strong></td>
                    <td>Portada con botón de reproducción. Al pulsar carga YouTube, Vimeo o Bunny.net; no guarda iframes.</td>
                </tr>
                <tr>
                    <td class="cf-help__icon">📦</td>
                    <td><strong>Sección</strong></td>
                    <td>Contenedor con espacio interior y fondo para agrupar contenido.</td>
                </tr>
                <tr>
                    <td class="cf-help__icon">🔠</td>
                    <td><strong>Título</strong></td>
                    <td>Encabezado grande para separar temas.</td>
                </tr>
                <tr>
                    <td class="cf-help__icon">📝</td>
                    <td><strong>Párrafo</strong></td>
                    <td>Texto normal para describir una sección.</td>
                </tr>
                <tr>
                    <td class="cf-help__icon">🖼️</td>
                    <td><strong>Imagen</strong></td>
                    <td>Imagen reemplazable. Haz doble clic para abrir el gestor de imágenes.</td>
                </tr>
                <tr>
                    <td class="cf-help__icon">🔘</td>
                    <td><strong>Botón</strong></td>
                    <td>Enlace con estilo. Cambia el texto y el destino en Settings.</td>
                </tr>
                <tr>
                    <td class="cf-help__icon">🧱</td>
                    <td><strong>2 columnas / 3 columnas</strong></td>
                    <td>Columnas que se acomodan en pantallas pequeñas.</td>
                </tr>
                <tr>
                    <td class="cf-help__icon">🗂️</td>
                    <td><strong>Tarjetas</strong></td>
                    <td>Tres tarjetas con icono, título y texto.</td>
                </tr>
                <tr>
                    <td class="cf-help__icon">🏀</td>
                    <td><strong>Quiénes somos</strong></td>
                    <td>Imagen con marco y tres párrafos en dos columnas, como la portada clásica.</td>
                </tr>
                <tr>
                    <td class="cf-help__icon">🖼️</td>
                    <td><strong>Tarjetas ampliables</strong></td>
                    <td>Tres o cuatro tarjetas con foto; al hacer clic se abre en grande con flechas.</td>
                </tr>
                <tr>
                    <td class="cf-help__icon">👥</td>
                    <td><strong>Nuestro equipo</strong></td>
                    <td>Tres tarjetas con foto, nombre, puesto y enlaces opcionales a redes o portafolio.</td>
                </tr>
                <tr>
                    <td class="cf-help__icon">➖</td>
                    <td><strong>Separador / Espaciador</strong></td>
                    <td>Una línea divisoria o un espacio vertical.</td>
                </tr>
                <tr>
                    <td class="cf-help__icon">💬</td>
                    <td><strong>Cita</strong></td>
                    <td>Texto destacado con una línea lateral.</td>
                </tr>
                <tr>
                    <td class="cf-help__icon">🛒</td>
                    <td><strong>Productos en venta</strong></td>
                    <td>Cuadrícula automática con los artículos publicados en <strong>Contenido → Tienda → Productos</strong>.</td>
                </tr>
                <tr>
                    <td class="cf-help__icon">🕒</td>
                    <td><strong>Horarios</strong></td>
                    <td>Sección oscura con los cuatro afiches de entrenamiento y visor al hacer clic.</td>
                </tr>
                <tr>
                    <td class="cf-help__icon">✉️</td>
                    <td><strong>Contacto completo</strong></td>
                    <td>Datos de contacto, Instagram y formulario en dos columnas.</td>
                </tr>
                <tr>
                    <td class="cf-help__icon">💬</td>
                    <td><strong>WhatsApp del club</strong></td>
                    <td>Widget con el chat de la directora y el balón del club.</td>
                </tr>
                <tr>
                    <td class="cf-help__icon">🏁</td>
                    <td><strong>Pie del club</strong></td>
                    <td>Logo, lema, Instagram, correo, enlaces legales y copyright.</td>
                </tr>
                <tr>
                    <td class="cf-help__icon">🏀</td>
                    <td><strong>Botón volver arriba (balón)</strong></td>
                    <td>Botón flotante con el balón que gira al hacer scroll.</td>
                </tr>
            </tbody>
        </table>
    </div>

    <h3>Más bloques</h3>

    <p>
        Además de la tabla anterior, en <strong>Básicos</strong> tienes título H1, subtítulo H3, lista con viñetas,
        botón secundario, enlace de texto, etiqueta y nota. En <strong>Estructura</strong> tienes 4 columnas,
        combinaciones 1/3 + 2/3, cuadrícula 2×2, tarjeta individual, tarjetas ampliables, nuestro equipo,
        quiénes somos, sección gris, sección oscura, tabla y pasos numerados. El resto se agrupa en
        <strong>Secciones dinámicas</strong> (carrusel, galería, noticias, horarios, contacto completo,
        productos en venta, preguntas frecuentes, marcas y testimonios), <strong>Contacto y redes</strong> (formulario, WhatsApp, WhatsApp del club y redes sociales),
        <strong>Navegación</strong> (botón volver arriba, botón con balón y pies de página)
        y <strong>Animaciones</strong>.
    </p>

    <h3>Portada clásica</h3>

    <p>
        Los bloques <strong>Hero del club</strong>, <strong>Quiénes somos</strong>,
        <strong>Nuestro equipo</strong>, <strong>Horarios</strong>, <strong>Contacto completo</strong>,
        <strong>Pie del club</strong>, <strong>Botón volver arriba (balón)</strong> y
        <strong>WhatsApp del club</strong> reproducen la portada histórica con las mismas imágenes y textos.
        Se pueden combinar con <strong>Noticias</strong> y <strong>Galería</strong> para armar la página completa.
    </p>

    <h3>Tarjetas ampliables</h3>

    <p>
        Los bloques <strong>3 tarjetas ampliables</strong> y <strong>4 tarjetas ampliables</strong> comparten el
        visor de imágenes. Al hacer clic en una foto se abre en grande, con flechas para recorrer las demás y la
        tecla <strong>Escape</strong> para cerrar. Para cambiar una foto, haz doble clic en ella y elige una
        imagen del gestor.
    </p>

    <h3>Nuestro equipo</h3>

    <p>
        El bloque <strong>Nuestro equipo</strong> sirve para la directiva, el cuerpo técnico o cualquier equipo.
        Cada tarjeta muestra una fotografía circular, el nombre, el puesto y enlaces opcionales a Instagram,
        Facebook, LinkedIn, X y web o portafolio. La foto <strong>no abre el visor</strong>: es una tarjeta de
        presentación.
    </p>

    <p>
        Selecciona una tarjeta o cualquiera de sus elementos y abre <strong>Settings → Miembro del equipo</strong>.
        Allí puedes escribir el <strong>nombre</strong>, el <strong>puesto</strong>, la <strong>URL de la
        fotografía</strong> y los enlaces. Si dejas una red vacía, su icono se oculta y no deja un hueco. También
        puedes reemplazar la foto con doble clic sobre ella.
    </p>

    <h3>Secciones dinámicas</h3>

    <p>
        Los bloques <strong>Noticias</strong>, <strong>Galería</strong> y <strong>Productos en venta</strong>
        se generan automáticamente con el contenido que administras en el panel (noticias, fotografías y
        productos de la tienda). No se editan dentro del constructor: en el lienzo se ven como un recuadro
        punteado y en la página pública muestran el contenido real.
    </p>

    <h3>Modo claro y oscuro</h3>

    <p>
        Los bloques usan variables de color compartidas, así que se adaptan solos cuando el visitante cambia
        el tema desde el menú. No tienes que duplicar la página ni elegir colores para cada modo.
    </p>

    <ul>
        <li>Los fondos, textos, bordes y botones de los bloques cambian de claro a oscuro.</li>
        <li>Los bloques marcados como <strong>oscuro</strong> (por ejemplo, <strong>Sección oscura</strong>,
            <strong>Pie oscuro</strong> o <strong>Redes sociales (oscuro)</strong>) siguen oscuros en ambos temas
            porque así fueron diseñados.</li>
        <li>El lienzo del editor siempre se muestra en claro para facilitar la edición; el resultado final
            respeta el tema del visitante.</li>
    </ul>

    <p>
        Si cambias manualmente un color a un valor fijo (por ejemplo, blanco), ese elemento dejará de adaptarse.
        Para conservar el cambio automático, usa los colores de la paleta o los estilos predefinidos del bloque.
    </p>

    <h3>Pies de página</h3>

    <p>
        La pestaña <strong>Pie de página</strong> del formulario se retiró: ahora el pie se construye
        dentro del constructor, al final de la página. En la categoría <strong>Navegación</strong>
        tienes cuatro diseños prediseñados:
    </p>

    <ul>
        <li><strong>Pie simple centrado:</strong> logo, lema, enlaces, redes y legales.</li>
        <li><strong>Pie con columnas:</strong> marca, enlaces del club, recursos, contacto y barra inferior.</li>
        <li><strong>Pie oscuro:</strong> la misma estructura sobre fondo oscuro.</li>
        <li><strong>Pie minimalista:</strong> una sola línea con logo, enlaces y copyright.</li>
    </ul>

    <p>
        Arrastra uno al final del lienzo y edita sus textos. Para cambiar el destino de un enlace,
        selecciónalo y edita <code>href</code> en <strong>Settings</strong>. Si no agregas un pie,
        la plantilla muestra el pie clásico por defecto.
    </p>

    <h3>Redes sociales</h3>

    <p>
        En la categoría <strong>Contacto y redes</strong> tienes un bloque claro y uno oscuro. Arrastra uno
        y selecciónalo; en <strong>Settings → Redes sociales</strong> puedes:
    </p>

    <ul>
        <li>Marcar o desmarcar <strong>LinkedIn</strong>, <strong>YouTube</strong>, <strong>X</strong>,
            <strong>Instagram</strong> y <strong>Facebook</strong>.</li>
        <li>Editar el enlace de cada red.</li>
    </ul>

    <p>
        Las redes desmarcadas se ocultan sin borrar el icono, así puedes volver a activarlas.
        Si borras un icono, vuelve a arrastrar el bloque para recuperarlo.
    </p>

    <h3>Botón volver arriba</h3>

    <p>
        En la categoría <strong>Navegación</strong> tienes el bloque
        <strong>Botón volver arriba</strong>. Arrástralo al lienzo para controlarlo:
    </p>

    <ul>
        <li>Selecciona el botón completo o el balón y abre <strong>Settings</strong> (el engranaje ⚙️
            de la barra derecha, no el pincel de Estilos).</li>
        <li>La primera categoría debe ser <strong>Botón volver arriba</strong>. Ahí elige
            <strong>Mostrar botón: Sí / No</strong>. La opción aparece tanto al seleccionar el botón
            como la imagen.</li>
        <li>Selecciona la imagen del balón y haz doble clic para reemplazarla por otro icono o imagen.</li>
        <li>El botón aparece al bajar unos 300 px y vuelve arriba al pulsarlo.</li>
        <li>Usa un solo bloque de este tipo por plantilla. Si tienes dos, elimina uno desde <strong>Layers</strong>.</li>
    </ul>

    <div class="cf-help__note">
        Si solo ves <strong>Id</strong> y <strong>Title</strong>, estás seleccionando el contenedor y no el
        botón. Abre <strong>Layers</strong> y elige el elemento <code>button</code> con id
        <code>top-button</code> (o <code>top-button-2</code> si hay copias). Si el panel sigue sin mostrar
        la categoría, cierra y vuelve a abrir el editor con <strong>Ctrl + F5</strong>.
    </div>

    <div class="cf-help__note">
        Si no agregas el bloque, la plantilla muestra el botón clásico por defecto, que no es editable.
        Para controlarlo, arrastra el bloque <strong>Botón volver arriba</strong> al lienzo. Para ocultarlo,
        deja el bloque en la página y elige <strong>No</strong>; si borras el bloque, vuelve el botón
        por defecto.
    </div>

    <h3>Widget de WhatsApp</h3>

    <p>
        En la categoría <strong>Contacto y redes</strong> tienes el bloque
        <strong>Widget de WhatsApp</strong>. Es el botón flotante verde que abre un chat y envía a
        WhatsApp. Arrástralo al lienzo y selecciónalo para configurarlo:
    </p>

    <ul>
        <li><strong>Número con código de país:</strong> solo dígitos. Por ejemplo, Chile: <code>56998970550</code>.</li>
        <li><strong>Título del chat:</strong> el nombre que aparece en la cabecera del panel.</li>
        <li><strong>Mensaje:</strong> el texto que se muestra en la burbuja y se usa si el visitante no escribe nada.</li>
    </ul>

    <p>
        Los cambios se ven al instante en el lienzo y se guardan con la plantilla. Usa un solo widget por
        plantilla. En las páginas del constructor el widget solo aparece si agregas este bloque; la portada
        clásica y las páginas legales conservan el widget original.
    </p>

    <p>
        Si aplicas una animación continua (<strong>latido</strong>, <strong>pulso</strong> o
        <strong>flotación</strong>) al widget, solo se mueve el botón: el panel queda quieto. Al abrir el
        chat la animación se pausa y, al cerrarlo, se reanuda. No apliques animaciones continuas al pie
        ni a la sección que contiene el widget, porque pueden desplazarlo.
    </p>

    <p>
        Si no encuentras el widget, abre <strong>Layers</strong> y selecciona el elemento llamado
        <strong>Widget de WhatsApp</strong>, no el pie o la sección que lo contiene. Al hacer clic en el
        botón verde, el editor selecciona el widget completo y abre <strong>Settings</strong> automáticamente.
    </p>

    <h3>Ventas y pagos</h3>

    <p>
        Los productos ya no se escriben uno por uno dentro del constructor. Se administran en el panel, en
        <strong>Contenido → Tienda → Productos</strong>, y el bloque <strong>Productos en venta</strong> los muestra
        automáticamente en la página pública.
    </p>

    <ul>
        <li><strong>Nombre</strong>, <strong>descripción</strong> e <strong>imagen</strong> del artículo.</li>
        <li><strong>Categoría</strong>: agrupa los productos y genera los filtros de la sección. Puedes crear una categoría nueva desde el mismo formulario.</li>
        <li><strong>Moneda</strong>: CLP, USD, EUR y otras. El precio se formatea solo (por ejemplo <code>$29.990</code> o <code>US$25,00</code>).</li>
        <li><strong>Precio</strong> y <strong>precio anterior</strong> opcional; el anterior solo se muestra si es mayor y aparece tachado.</li>
        <li><strong>Nota del precio</strong> opcional (<code>pago único</code>, <code>envío incluido</code>, <code>retiro en el club</code>).</li>
        <li><strong>Distintivo</strong> opcional (<code>Oferta</code>, <code>Nuevo</code>, <code>Evento</code>) sobre la foto.</li>
        <li><strong>Texto del botón</strong> y <strong>enlace de pago</strong> (Mercado Pago, Flow, Webpay, PayPal…). Si el enlace queda vacío, el botón se muestra desactivado.</li>
        <li><strong>Publicado</strong> y <strong>orden</strong>: solo los artículos publicados aparecen en la web; puedes reordenarlos arrastrando las filas de la tabla.</li>
    </ul>

    <p>
        El bloque se ve como un recuadro punteado en el lienzo. En la página pública muestra el título
        <strong>Artículos en venta</strong>, los filtros por categoría y la cuadrícula de tarjetas. Si aún no hay
        productos publicados, aparece un aviso de que la tienda se está preparando.
    </p>

    <h3>Carrusel</h3>

    <p>
        En la categoría <strong>Secciones dinámicas</strong> tienes el bloque
        <strong>Carrusel de imágenes</strong>: una secuencia de diapositivas con foto, título, texto y botón.
        Arrástralo al lienzo y configúralo:
    </p>

    <ul>
        <li>Selecciona el <strong>carrusel</strong> (el marco, las flechas o los puntos) y abre
            <strong>Settings → Carrusel</strong>: ahí ves la lista <strong>Imágenes del carrusel</strong>
            (máximo 5), donde puedes añadir una imagen nueva, quitarla o pulsar <strong>Editar</strong>
            para saltar a esa diapositiva. También ajustas reproducción automática, intervalo (ms),
            mostrar flechas y mostrar puntos.</li>
        <li>Selecciona una <strong>diapositiva</strong> (la foto o su texto) y abre <strong>Settings → Diapositiva</strong>:
            imagen, título, texto, texto del botón y enlace del botón.</li>
        <li>También puedes hacer doble clic sobre la foto para cambiar la imagen con el gestor de activos,
            o editar el título y el texto directamente en el lienzo.</li>
        <li>El carrusel admite entre 1 y 5 imágenes. Duplicar o pegar una sexta diapositiva no está permitido:
            el editor la retira y muestra un aviso.</li>
    </ul>

    <p>
        El giro automático se pausa al pasar el cursor o enfocar el carrusel, y respeta la preferencia de
        “reducir movimiento” del visitante. Si el enlace del botón queda vacío, el botón se oculta.
    </p>

    <h3>Formulario</h3>

    <p>
        En la categoría <strong>Contacto y redes</strong> tienes el bloque <strong>Formulario de contacto</strong>.
        Arrástralo al lienzo y selecciónalo para abrir <strong>Settings → Formulario</strong>:
    </p>

    <ul>
        <li><strong>Título</strong> y <strong>Descripción</strong>.</li>
        <li><strong>Etiquetas</strong> de nombre, correo, teléfono y mensaje.</li>
        <li><strong>Mostrar teléfono</strong> (Sí/No).</li>
        <li><strong>Texto del botón</strong> y <strong>mensaje de éxito</strong>.</li>
    </ul>

    <p>
        El formulario envía el mensaje por correo a la dirección configurada del sitio. El nombre, el correo y el
        mensaje son obligatorios; el teléfono es opcional. Usa un solo formulario por plantilla.
    </p>

    <h3>Animaciones</h3>

    <p>
        Cualquier elemento del lienzo puede animarse. Selecciónalo, abre <strong>Settings</strong> y busca la
        categoría <strong>Animación</strong>. Allí encontrarás:
    </p>

    <ul>
        <li><strong>Animación de entrada:</strong> desde abajo, desde arriba, desde la izquierda, desde la derecha,
            zoom, desvanecer, giro 3D o desenfoque.</li>
        <li><strong>Cuándo animar:</strong> al hacer scroll (por defecto) o al cargar la página.</li>
        <li><strong>Retardo (ms):</strong> para que las tarjetas entren en secuencia. Por ejemplo: 0, 150 y 300.</li>
        <li><strong>Animación continua:</strong> flotación, pulso o latido para iconos y etiquetas.</li>
    </ul>

    <p>
        En la categoría <strong>Animaciones</strong> de los bloques tienes secciones ya preparadas:
        <strong>Aparición escalonada</strong>, <strong>Columnas deslizantes</strong>, <strong>Zoom de imagen</strong>,
        <strong>Entrada al cargar</strong> y <strong>Movimiento continuo</strong>. Arrástralas y cambia sus textos.
    </p>

    <p>
        Para probar el resultado, pulsa el botón de <strong>vista previa</strong> (el ojo) en la barra superior.
        En modo edición los elementos permanecen visibles; la animación se reproduce al previsualizar y en la
        página publicada.
    </p>

    <div class="cf-help__note">
        Usa las animaciones con moderación: anima títulos, tarjetas, imágenes y botones. Evita animar párrafos
        completos o muchas secciones a la vez, y no combines una entrada con un movimiento continuo sobre el
        mismo elemento.
    </div>

    <h3>Fondo parallax</h3>

    <p>
        Cualquier sección puede llevar una decoración de cancha que se mueve suavemente al hacer scroll.
        Selecciona la sección, abre <strong>Settings</strong> y busca la categoría
        <strong>Fondo parallax</strong>. Elige <strong>Sin efecto</strong>, <strong>Suave</strong> o
        <strong>Intenso</strong>.
    </p>

    <p>
        La decoración se agrega sola: no ocupa un bloque, no aparece en <strong>Layers</strong> y no se puede
        borrar por accidente. En el lienzo se ve de forma estática para que puedas componer; el movimiento se
        aprecia en la <strong>vista previa</strong> y en la página publicada. Si el visitante tiene activado
        “reducir movimiento”, la decoración queda quieta.
    </p>

    <div class="cf-help__note">
        Úsalo en secciones con fondo propio y contenido claro, no en todas a la vez. Para las secciones
        dinámicas (Noticias, Galería, Horarios) coloca el ajuste en el contenedor que las envuelve.
    </div>

    <h2>5. Menú y enlaces a secciones</h2>

    <ol>
        <li>Selecciona la sección en el lienzo o en <strong>Layers</strong>.</li>
        <li>Abre <strong>Settings</strong> y escribe un ID, por ejemplo <code>inicio</code> o <code>servicios</code>.</li>
        <li>En el panel, entra a la pestaña <strong>Menú principal</strong>.</li>
        <li>Agrega un enlace con tipo <strong>Sección de esta página</strong> y destino <code>#inicio</code>.</li>
        <li>El enlace <strong>Ingresar / Ir al panel</strong> se agrega solo y no se puede borrar.</li>
    </ol>

    <p>
        Para enlazar otra página del sitio, elige el tipo <strong>Página publicada</strong> y escribe su
        identificador (por ejemplo <code>nosotros</code>). La página debe estar publicada para que el enlace
        funcione. Si una página no tiene menú propio, reutiliza el de la portada.
    </p>

    <h2>6. Imágenes</h2>

    <ol>
        <li>Selecciona una imagen del kit o arrastra el bloque <strong>Imagen</strong>.</li>
        <li>Haz doble clic o abre <strong>Assets</strong>.</li>
        <li>Sube un JPG, PNG, WebP o GIF de máximo <strong>2 MB</strong>.</li>
        <li>Vuelve a guardar. La imagen queda en <code>/img/plantillas</code>.</li>
    </ol>

    <h2>7. Guardar y publicar</h2>

    <ol>
        <li><strong>Guardar</strong> en la barra negra. Espera a que aparezca <em>Guardado</em>. Esto guarda el <strong>borrador</strong>.</li>
        <li><strong>Vista previa</strong> para revisar el borrador tal como quedará.</li>
        <li><strong>Publicar</strong> para copiar el borrador a la web pública.</li>
        <li><strong>Activar en el inicio</strong> para elegir la plantilla como portada.</li>
    </ol>

    <p>
        Guardar no cambia la web pública; publicar sí. Si activas una plantilla, se publica su borrador actual.
        Para volver atrás, activa <strong>Clásica</strong>.
    </p>

    <p>
        En el panel de la plantilla, la sección <strong>Versiones publicadas</strong> guarda cada publicación y
        permite ver o restaurar una versión anterior. Al restaurar, esa versión se publica de inmediato.
    </p>

    <p>
        Si cierras la pestaña con cambios sin guardar, el editor avisa y conserva un borrador en este navegador.
        Al volver a abrir la plantilla aparece el aviso <strong>“Tienes un borrador sin guardar”</strong> con las
        opciones <strong>Restaurar</strong> o <strong>Descartar</strong>.
    </p>

    <h2>8. Atajos útiles</h2>

    <ul>
        <li><kbd>Ctrl</kbd> + <kbd>Z</kbd> deshacer y <kbd>Ctrl</kbd> + <kbd>Shift</kbd> + <kbd>Z</kbd> rehacer.</li>
        <li><kbd>Supr</kbd> borra el elemento seleccionado.</li>
        <li><kbd>Esc</kbd> cierra esta ayuda o cancela una acción.</li>
        <li>Doble clic sobre un texto para editarlo directamente.</li>
        <li>Escribe en el buscador de bloques para filtrar por nombre; por ejemplo <em>formulario</em>, <em>precio</em> o <em>carrusel</em>.</li>
    </ul>

    <h2>9. Problemas comunes</h2>

    <ul>
        <li><strong>No veo los bloques nuevos:</strong> desplázate dentro del panel de bloques (hay varias categorías) o cierra y vuelve a abrir el editor.</li>
        <li><strong>No veo el panel de bloques:</strong> pulsa el icono 🧱 en la barra de iconos.</li>
        <li><strong>No puedo editar un texto:</strong> haz doble clic sobre él; si está dentro de otro elemento, selecciónalo primero en Layers.</li>
        <li><strong>La imagen no sube:</strong> revisa que pese menos de 2 MB y sea JPG, PNG, WebP o GIF.</li>
        <li><strong>El menú no salta a la sección:</strong> el ID de la sección y el destino del menú deben coincidir, por ejemplo <code>inicio</code> y <code>#inicio</code>.</li>
        <li><strong>No veo los cambios en la web:</strong> guarda, revisa la vista previa y pulsa <strong>Publicar</strong>. Activar la plantilla publica el borrador actual.</li>
        <li><strong>Guardé pero la web sigue igual:</strong> Guardar solo guarda el borrador; falta pulsar <strong>Publicar</strong>.</li>
        <li><strong>El estado dice <em>Sin guardar</em>:</strong> significa que hay cambios sin guardar.</li>
        <li><strong>Se me cerró el editor:</strong> vuelve a abrir la plantilla y pulsa <strong>Restaurar</strong> en el aviso del borrador.</li>
        <li><strong>Veo dos formularios y uno no responde:</strong> guarda y vuelve a abrir; los formularios reciben IDs únicos automáticamente.</li>
    </ul>
</div>
