<?php

namespace App\Filament\Pages;

use App\Actions\CreatePageFromPreset;
use App\Filament\Resources\PageTemplates\PageTemplateResource;
use App\Models\PageTemplate;
use App\Support\Presets\LandingPreset;
use App\Support\Presets\PresetLibrary;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;

class TemplateGallery extends Page
{
    protected string $view = 'filament.pages.template-gallery';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'Contenido';

    protected static ?string $navigationLabel = 'Plantillas';

    protected static ?string $slug = 'plantillas';

    protected static ?string $title = 'Plantillas para empezar';

    protected static ?int $navigationSort = 4;

    /**
     * La galería se abre desde el botón "Plantillas" de la página Páginas;
     * no necesita una entrada propia en el menú lateral.
     */
    protected static bool $shouldRegisterNavigation = false;

    public static function canAccess(): bool
    {
        return auth()->user()?->can('Create:PageTemplate') ?? false;
    }

    /**
     * @return Collection<int, LandingPreset>
     */
    public function presets(): Collection
    {
        return PresetLibrary::collection();
    }

    public function thumbnailExists(LandingPreset $preset): bool
    {
        return is_file(public_path(ltrim($preset->thumbnail(), '/')));
    }

    public function previewUrl(LandingPreset $preset): string
    {
        return route('presets.preview', ['preset' => $preset->slug()]);
    }

    public function usar(string $slug, CreatePageFromPreset $action): void
    {
        Gate::authorize('create', PageTemplate::class);

        $preset = PresetLibrary::find($slug);

        abort_unless($preset, 404);

        $template = $action->execute($preset, userId: auth()->id());

        Notification::make()
            ->title('Plantilla aplicada')
            ->body('Se creó «'.$template->name.'» como borrador. Ya aparece en Páginas para que la edites y publiques.')
            ->success()
            ->send();

        $this->redirect(PageTemplateResource::getUrl('index'));
    }
}
