<?php

namespace App\Filament\Resources\PageTemplates\Schemas;

use App\Filament\Pages\ConstructorHelp;
use App\Models\MenuItem;
use App\Models\PageTemplate;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\HtmlString;

class PageTemplateForm
{
    private const BOTON_PRIMARIO = 'inline-flex items-center gap-2 rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-primary-500';

    private const BOTON_SECUNDARIO = 'inline-flex items-center gap-2 rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm font-semibold text-zinc-700 shadow-sm transition-colors hover:bg-zinc-50 dark:border-white/15 dark:bg-white/5 dark:text-zinc-200 dark:hover:bg-white/10';

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Página')
                    ->persistTabInQueryString()
                    ->columnSpanFull()
                    ->tabs([
                        Tab::make('Ajustes')
                            ->icon('heroicon-o-cog-6-tooth')
                            ->schema([
                                Section::make('Página')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('Nombre')
                                            ->required()
                                            ->maxLength(255),
                                        TextInput::make('slug')
                                            ->label('Identificador')
                                            ->required()
                                            ->maxLength(255)
                                            ->alphaDash()
                                            ->unique(ignoreRecord: true)
                                            ->rules(['not_in:admin,api,preview,constructor,grapesjs,up,politica-de-privacidad,politica-de-cookies,politica-de-datos'])
                                            ->helperText('Solo letras, números, guiones y guiones bajos. Es la URL pública de la página: /nosotros.'),
                                        Textarea::make('description')
                                            ->label('Descripción')
                                            ->rows(2)
                                            ->maxLength(500)
                                            ->columnSpanFull(),
                                    ]),
                                Section::make('SEO')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('settings.seo_title')
                                            ->label('Título SEO')
                                            ->maxLength(60)
                                            ->live(debounce: 600)
                                            ->helperText(fn (Get $get): string => self::contador($get, 'settings.seo_title', 60).' · Google muestra entre 50 y 60 caracteres.'),
                                        TextInput::make('settings.canonical_url')
                                            ->label('URL canónica')
                                            ->url()
                                            ->maxLength(2048)
                                            ->helperText('Opcional. Solo si esta página tiene otra URL principal.'),
                                        Textarea::make('settings.seo_description')
                                            ->label('Descripción SEO')
                                            ->rows(2)
                                            ->maxLength(160)
                                            ->live(debounce: 600)
                                            ->columnSpanFull()
                                            ->helperText(fn (Get $get): string => self::contador($get, 'settings.seo_description', 160).' · Google muestra hasta 160 caracteres.'),
                                        Select::make('settings.robots')
                                            ->label('Indexación')
                                            ->options([
                                                'index, follow' => 'Indexar (recomendado)',
                                                'noindex, nofollow' => 'No indexar',
                                            ])
                                            ->default('index, follow'),
                                    ]),
                                Section::make('Redes sociales')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('settings.og_title')
                                            ->label('Título para redes')
                                            ->maxLength(70)
                                            ->live(debounce: 600)
                                            ->helperText(fn (Get $get): string => self::contador($get, 'settings.og_title', 70).' · Se usa en WhatsApp, Facebook, X y LinkedIn. Si se deja vacío, se usa el título SEO.'),
                                        TextInput::make('settings.og_image')
                                            ->label('Imagen para redes (URL)')
                                            ->url()
                                            ->maxLength(2048)
                                            ->helperText('Recomendado 1200×630 px. Si se deja vacía, se usa el logo.'),
                                        Textarea::make('settings.og_description')
                                            ->label('Descripción para redes')
                                            ->rows(2)
                                            ->maxLength(200)
                                            ->live(debounce: 600)
                                            ->columnSpanFull()
                                            ->helperText(fn (Get $get): string => self::contador($get, 'settings.og_description', 200).' · Si se deja vacía, se usa la descripción SEO.'),
                                    ]),
                                Section::make('Datos estructurados')
                                    ->columns(2)
                                    ->schema([
                                        Select::make('settings.schema_type')
                                            ->label('Tipo de datos estructurados')
                                            ->options([
                                                'SportsOrganization' => 'Club deportivo',
                                                'LocalBusiness' => 'Negocio local',
                                                'Organization' => 'Organización',
                                                'WebSite' => 'Sitio web',
                                            ])
                                            ->default('SportsOrganization'),
                                        TextInput::make('settings.schema_name')
                                            ->label('Nombre para Google')
                                            ->maxLength(255)
                                            ->helperText('Por defecto, el nombre de la plantilla.'),
                                        TextInput::make('settings.schema_phone')
                                            ->label('Teléfono para Google')
                                            ->tel()
                                            ->maxLength(50),
                                        TextInput::make('settings.schema_address')
                                            ->label('Dirección para Google')
                                            ->maxLength(255),
                                    ]),
                            ]),

                        Tab::make('Menú principal')
                            ->icon('heroicon-o-bars-3')
                            ->schema([
                                self::menuRepeater('mainMenuItems'),
                            ]),

                        Tab::make('Contenido')
                            ->key('contenido')
                            ->icon('heroicon-o-paint-brush')
                            ->schema([
                                Placeholder::make('editor')
                                    ->label('Constructor visual')
                                    ->content(fn (?PageTemplate $record): HtmlString => self::editorHelp($record)),
                                Placeholder::make('publicacion')
                                    ->label('Publicación')
                                    ->content(fn (?PageTemplate $record): HtmlString => self::publicationHelp($record))
                                    ->hidden(fn (?PageTemplate $record): bool => $record === null || ! $record->isGrapesjs()),
                                Placeholder::make('seguridad')
                                    ->label('Nota de seguridad')
                                    ->content('El HTML y el CSS del editor se sanitizan al guardar. Los scripts, iframes y eventos inline se eliminan automáticamente.'),
                            ]),
                    ]),
            ]);
    }

    private static function editorHelp(?PageTemplate $record): HtmlString
    {
        if (! $record) {
            return new HtmlString(
                '<div class="space-y-2"><p class="text-sm text-zinc-600 dark:text-zinc-300">Guarda la página para habilitar el constructor visual. Al crearla se abrirá el constructor visual automáticamente.</p></div>'
            );
        }

        if ($record->isGrapesjs()) {
            $url = $record->grapesjsEditorUrl();
            $helpUrl = ConstructorHelp::getUrl();

            return new HtmlString(
                '<div class="space-y-3">'
                .'<p class="text-sm text-zinc-600 dark:text-zinc-300">El constructor visual se abre en una pestaña nueva. Allí puedes arrastrar secciones, editar textos, imágenes y estilos.</p>'
                .'<div class="flex flex-wrap gap-2">'
                .'<a href="'.e($url).'" class="'.self::BOTON_PRIMARIO.'">Abrir constructor visual</a>'
                .'<a href="'.e($helpUrl).'" target="_blank" rel="noopener" class="'.self::BOTON_SECUNDARIO.'">¿Cómo usar el editor?</a>'
                .'</div>'
                .'</div>'
            );
        }

        return new HtmlString(
            '<div class="space-y-3">'
            .'<p class="text-sm text-zinc-600 dark:text-zinc-300">La página <strong>Clásica</strong> conserva la portada histórica y su contenido vive en las vistas Blade. Para usar el constructor visual, crea una copia editable.</p>'
            .'<button type="button" wire:click="duplicateAsGrapesjs" wire:confirm="¿Crear una copia editable de la portada clásica?" class="'.self::BOTON_PRIMARIO.'">Crear versión editable</button>'
            .'<p class="text-xs text-zinc-500 dark:text-zinc-400">Se copiarán el menú y los ajustes en una nueva página. La portada actual no se modifica hasta que actives la copia.</p>'
            .'</div>'
        );
    }

    private static function publicationHelp(?PageTemplate $record): HtmlString
    {
        if (! $record || ! $record->isGrapesjs()) {
            return new HtmlString('');
        }

        $previewUrl = URL::temporarySignedRoute(
            'page-templates.preview',
            now()->addMinutes(30),
            ['template' => $record->slug],
        );

        $publicada = $record->isPublished();

        $estado = $publicada
            ? 'Publicada'.($record->published_at ? ' el '.$record->published_at->format('d/m/Y H:i') : '')
            : 'Sin publicar';

        $botones = [
            '<a href="'.e($previewUrl).'" target="_blank" rel="noopener" title="Muestra el borrador actual aunque no esté publicado." class="'.self::BOTON_SECUNDARIO.'">Vista previa (borrador)</a>',
        ];

        if ($publicada) {
            $botones[] = '<a href="'.e($record->publicUrl()).'" target="_blank" rel="noopener" title="Abre la versión publicada, tal como la ven los visitantes." class="'.self::BOTON_SECUNDARIO.'">Ver página publicada</a>';
        }

        if ($record->hasDesign()) {
            $botones[] = '<button type="button" wire:click="publish" wire:confirm="¿Publicar el borrador actual? También se guardarán los ajustes del formulario." class="'.self::BOTON_PRIMARIO.'">Publicar cambios</button>';
        }

        if ($publicada && ! $record->is_active) {
            $botones[] = '<button type="button" wire:click="unpublish" wire:confirm="¿Ocultar la página? Dejará de estar disponible en su URL hasta que la vuelvas a publicar." class="'.self::BOTON_SECUNDARIO.'">Ocultar página</button>';
        }

        if (! $record->is_active) {
            $botones[] = '<button type="button" wire:click="activate" wire:confirm="¿Activar esta página como portada? Se publicará su borrador actual." class="'.self::BOTON_SECUNDARIO.'">Activar como portada</button>';
        }

        return new HtmlString(
            '<div class="space-y-3">'
            .'<p class="text-sm text-zinc-600 dark:text-zinc-300">Estado: <strong>'.e($estado).'</strong>.</p>'
            .'<p class="text-xs text-zinc-500 dark:text-zinc-400">Guardar cambios está al final del formulario y guarda todas las pestañas. Publicar cambios guarda estos ajustes y publica el último diseño guardado en el constructor.</p>'
            .'<p class="text-xs text-zinc-500 dark:text-zinc-400">Vista previa muestra el borrador; Ver página publicada muestra la versión que ya ven los visitantes.</p>'
            .'<div class="flex flex-wrap gap-2">'.implode('', $botones).'</div>'
            .'</div>'
        );
    }

    private static function contador(Get $get, string $campo, int $maximo): string
    {
        return mb_strlen((string) $get($campo)).'/'.$maximo.' caracteres';
    }

    private static function menuRepeater(string $relationship): Repeater
    {
        return Repeater::make($relationship)
            ->label('Enlaces del menú principal')
            ->relationship($relationship)
            ->mutateRelationshipDataBeforeCreateUsing(fn (array $data): array => [
                ...$data,
                'location' => 'main',
            ])
            ->mutateRelationshipDataBeforeSaveUsing(fn (array $data): array => [
                ...$data,
                'location' => 'main',
            ])
            ->schema([
                TextInput::make('label')
                    ->label('Etiqueta')
                    ->required()
                    ->maxLength(255),
                Select::make('link_type')
                    ->label('Tipo de enlace')
                    ->options(MenuItem::linkTypeOptions())
                    ->default('anchor')
                    ->required()
                    ->live(),
                TextInput::make('link_value')
                    ->label('Destino')
                    ->required()
                    ->maxLength(2048)
                    ->helperText(fn (Get $get): string => match ($get('link_type')) {
                        'anchor' => 'Ejemplo: #noticias. Debe empezar con #.',
                        'page' => 'Identificador de la página, por ejemplo: nosotros.',
                        'route' => 'Nombre de una ruta registrada, por ejemplo: politica.privacidad.',
                        default => 'Debe empezar por https://, http://, mailto:, tel: o /.',
                    })
                    ->rules(fn (Get $get): array => match ($get('link_type')) {
                        'anchor' => ['regex:/^#[A-Za-z][A-Za-z0-9_-]*$/'],
                        'page' => [
                            function (string $attribute, mixed $value, \Closure $fail): void {
                                if (! is_string($value) || ! PageTemplate::query()->where('slug', $value)->exists()) {
                                    $fail('La página indicada no existe.');
                                }
                            },
                        ],
                        'route' => [
                            function (string $attribute, mixed $value, \Closure $fail): void {
                                if (! is_string($value) || ! Route::has($value)) {
                                    $fail('La ruta indicada no existe en la aplicación.');
                                }
                            },
                        ],
                        default => ['regex:~^(https?://|mailto:|tel:|/)~i'],
                    }),
                Select::make('target')
                    ->label('Abrir en')
                    ->options([
                        '_self' => 'La misma pestaña',
                        '_blank' => 'Una pestaña nueva',
                    ])
                    ->default('_self')
                    ->required(),
                Toggle::make('is_visible')
                    ->label('Visible')
                    ->default(true)
                    ->onColor('success')
                    ->offColor('danger'),
            ])
            ->columns(2)
            ->orderColumn('sort_order')
            ->reorderable()
            ->collapsible()
            ->collapsed()
            ->itemLabel(fn (array $state): ?string => $state['label'] ?? 'Enlace')
            ->addActionLabel('Agregar enlace')
            ->defaultItems(0);
    }
}
