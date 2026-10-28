<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use App\Support\CookieBanner;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\View\View;

class CookieBannerSettings extends Page
{
    protected string $view = 'filament.pages.cookie-banner-settings';

    /**
     * La página se oculta del menú lateral: el aviso vive en la pestaña
     * "Aviso" de Páginas. El modo incrustado evita repetir el layout.
     */
    protected static bool $shouldRegisterNavigation = false;

    public bool $embedded = false;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static string|\UnitEnum|null $navigationGroup = 'Configuración';

    protected static ?string $navigationLabel = 'Aviso de cookies';

    protected static ?string $title = 'Aviso de cookies';

    protected static ?string $slug = 'aviso-de-cookies';

    protected static ?int $navigationSort = 11;

    /**
     * @var array<string, mixed>
     */
    public ?array $data = [];

    public function mount(bool $embedded = false): void
    {
        $this->embedded = $embedded;

        $this->form->fill(CookieBanner::current());
    }

    public function getView(): string
    {
        return $this->embedded
            ? 'filament.pages.cookie-banner-settings-embedded'
            : parent::getView();
    }

    public function render(): View
    {
        if ($this->embedded) {
            return view($this->getView(), $this->getViewData());
        }

        return parent::render();
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->can('Update:PageTemplate') ?? false;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Texto del aviso')
                    ->description('Este aviso se muestra siempre en todas las plantillas, la portada clásica y las páginas legales. Aquí solo cambias el texto: no se puede ocultar ni quitar desde el constructor.')
                    ->columns(2)
                    ->schema([
                        Textarea::make('message')
                            ->label('Mensaje')
                            ->required()
                            ->rows(3)
                            ->maxLength(CookieBanner::limits()['message'])
                            ->columnSpanFull()
                            ->helperText('Se muestra antes del enlace a la política. No incluyas HTML.'),
                        TextInput::make('policy_prefix')
                            ->label('Texto antes del enlace')
                            ->required()
                            ->maxLength(CookieBanner::limits()['policy_prefix']),
                        TextInput::make('policy_label')
                            ->label('Texto del enlace')
                            ->required()
                            ->maxLength(CookieBanner::limits()['policy_label']),
                        TextInput::make('necessary_label')
                            ->label('Botón «solo necesarias»')
                            ->required()
                            ->maxLength(CookieBanner::limits()['necessary_label']),
                        TextInput::make('accept_label')
                            ->label('Botón «aceptar todas»')
                            ->required()
                            ->maxLength(CookieBanner::limits()['accept_label']),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        SiteSetting::put(CookieBanner::KEY, $data);

        Notification::make()
            ->title('Aviso de cookies guardado')
            ->success()
            ->send();

        $this->form->fill(CookieBanner::current());
    }

    public function restoreDefaults(): void
    {
        SiteSetting::put(CookieBanner::KEY, CookieBanner::defaults());

        $this->form->fill(CookieBanner::current());

        Notification::make()
            ->title('Texto restaurado')
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('restore')
                ->label('Restaurar texto por defecto')
                ->icon(Heroicon::OutlinedArrowPath)
                ->requiresConfirmation()
                ->modalDescription('Se reemplazarán los textos actuales por los textos originales del aviso.')
                ->action(fn () => $this->restoreDefaults()),
        ];
    }
}
