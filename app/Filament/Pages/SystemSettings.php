<?php

namespace App\Filament\Pages;

use App\Support\Branding;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class SystemSettings extends Page
{
    protected string $view = 'filament.pages.system-settings';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string|UnitEnum|null $navigationGroup = 'Configuración';

    protected static ?string $navigationLabel = 'Configuración del sistema';

    protected static ?string $title = 'Configuración del sistema';

    protected static ?string $slug = 'configuracion-del-sistema';

    protected static ?int $navigationSort = 0;

    /**
     * @var array<string, mixed>
     */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(Branding::current());
    }

    /**
     * Solo el propietario de la instalación puede cambiar la identidad del
     * sitio. No se delega por permisos de contenido.
     */
    public static function canAccess(): bool
    {
        return auth()->user()?->hasRole('super_admin') ?? false;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Datos de la empresa')
                    ->description('El nombre, el lema y la ciudad se usan en la portada, el panel, el login y el pie de página.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('company_name')
                            ->label('Nombre de la empresa')
                            ->required()
                            ->maxLength(60)
                            ->placeholder('CieloTronador')
                            ->helperText('Aparece en el panel, el login, el pie y los metadatos del sitio.')
                            ->columnSpanFull(),
                        TextInput::make('tagline')
                            ->label('Lema o frase')
                            ->maxLength(120)
                            ->placeholder('«Más que un club, una familia»')
                            ->helperText('Frase breve que acompaña al logo en el pie de página.')
                            ->columnSpanFull(),
                        TextInput::make('city')
                            ->label('Ciudad y país')
                            ->maxLength(80)
                            ->placeholder('Talcahuano, Chile')
                            ->helperText('Se muestra junto al copyright del pie.'),
                    ]),
                Section::make('Logotipos')
                    ->description('Se usan en la portada, las plantillas, el panel y el login. Si no subes uno, se mantiene el logo actual.')
                    ->columns(2)
                    ->schema([
                        FileUpload::make('logo_light')
                            ->label('Logo para fondo claro')
                            ->image()
                            ->disk(Branding::DISK)
                            ->directory('')
                            ->visibility('public')
                            ->maxSize(3072)
                            ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/webp'])
                            ->imagePreviewHeight('120')
                            ->dehydrateStateUsing(fn ($state): ?string => is_array($state) ? ($state[0] ?? null) : $state)
                            ->helperText('PNG, JPG o WebP. Máximo 3 MB. Se recomienda fondo transparente.')
                            ->columnSpan(1),
                        FileUpload::make('logo_dark')
                            ->label('Logo para fondo oscuro')
                            ->image()
                            ->disk(Branding::DISK)
                            ->directory('')
                            ->visibility('public')
                            ->maxSize(3072)
                            ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/webp'])
                            ->imagePreviewHeight('120')
                            ->dehydrateStateUsing(fn ($state): ?string => is_array($state) ? ($state[0] ?? null) : $state)
                            ->helperText('Se muestra al activar el modo oscuro. PNG, JPG o WebP. Máximo 3 MB.')
                            ->columnSpan(1),
                    ]),
                Section::make('Favicon')
                    ->description('Icono que aparece en la pestaña del navegador.')
                    ->schema([
                        FileUpload::make('favicon')
                            ->label('Favicon')
                            ->disk(Branding::DISK)
                            ->directory('')
                            ->visibility('public')
                            ->maxSize(512)
                            ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/webp', 'image/x-icon', 'image/vnd.microsoft.icon'])
                            ->dehydrateStateUsing(fn ($state): ?string => is_array($state) ? ($state[0] ?? null) : $state)
                            ->helperText('PNG o ICO. Máximo 512 KB. Si no subes uno, se usa el icono actual.'),
                    ]),
                Section::make('Pie de página')
                    ->description('Datos de contacto que se muestran en el pie público.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('contact_email')
                            ->label('Correo de contacto')
                            ->email()
                            ->maxLength(120)
                            ->placeholder('contacto@tuempresa.cl')
                            ->helperText('Enlace de correo del pie.'),
                        TextInput::make('instagram_url')
                            ->label('Instagram')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://www.instagram.com/tucuenta/')
                            ->helperText('Enlace completo al perfil.'),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        Branding::save($this->form->getState());

        Notification::make()
            ->title('Configuración del sistema guardada')
            ->success()
            ->send();

        $this->form->fill(Branding::current());
    }

    public function restoreDefaults(): void
    {
        Branding::restoreDefaults();

        $this->form->fill(Branding::current());

        Notification::make()
            ->title('Identidad original restaurada')
            ->success()
            ->send();
    }
}
