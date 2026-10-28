<?php

namespace App\Console\Commands;

use App\Models\PageTemplate;
use App\Support\BlockTheme;
use Illuminate\Console\Command;

class ThemeTokensCommand extends Command
{
    protected $signature = 'landing:theme-tokens
                            {--dry-run : Muestra las plantillas que cambiarían sin guardar nada}';

    protected $description = 'Convierte los colores fijos de las plantillas guardadas en variables compatibles con el modo oscuro';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $changed = 0;

        PageTemplate::withTrashed()->orderBy('id')->each(function (PageTemplate $template) use ($dryRun, &$changed): void {
            $html = BlockTheme::transformHtml((string) $template->html);
            $css = BlockTheme::transformCss((string) $template->css);
            $project = $template->gjs_data;

            $projectChanged = false;

            if (is_array($project) && is_array($project['styles'] ?? null)) {
                $styles = BlockTheme::transformStyles($project['styles']);
                $projectChanged = $styles !== $project['styles'];
                $project['styles'] = $styles;
            }

            $htmlChanged = $html !== (string) $template->html;
            $cssChanged = $css !== (string) $template->css;

            if (! $htmlChanged && ! $cssChanged && ! $projectChanged) {
                return;
            }

            $changed++;

            $this->line(sprintf(
                '· %s (%s): %s',
                $template->name,
                $template->slug,
                collect([
                    $htmlChanged ? 'HTML' : null,
                    $cssChanged ? 'CSS' : null,
                    $projectChanged ? 'proyecto' : null,
                ])->filter()->implode(', '),
            ));

            if ($dryRun) {
                return;
            }

            $template->html = $html;
            $template->css = $css;

            if ($projectChanged) {
                $template->gjs_data = $project;
            }

            $template->save();
        });

        if ($changed === 0) {
            $this->info('No hay colores fijos que convertir.');

            return self::SUCCESS;
        }

        $this->info($dryRun
            ? "Se convertirían {$changed} plantilla(s). Ejecuta sin --dry-run para guardar los cambios."
            : "Se actualizaron {$changed} plantilla(s).");

        return self::SUCCESS;
    }
}
