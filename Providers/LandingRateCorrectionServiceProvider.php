<?php

namespace Modules\LandingRateCorrection\Providers;

use App\Services\ModuleService;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class LandingRateCorrectionServiceProvider extends ServiceProvider
{
    protected $moduleSvc;

    public function boot(): void
    {
        $this->moduleSvc = app(ModuleService::class);

        $this->registerTranslations();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path('LandingRateCorrection', 'Database/migrations'));
        $this->registerLinks();
    }

    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
        $this->mergeConfigFrom(
            module_path('LandingRateCorrection', 'Config/config.php'),
            'landingratecorecorrection'
        );
    }

    public function registerLinks(): void
    {
        $this->moduleSvc->addAdminLink('LR Corrections', '/admin/lrc', 'pe-7s-graph1');
    }

    protected function registerViews(): void
    {
        $viewPath   = resource_path('views/modules/landingratecorecorrection');
        $sourcePath = module_path('LandingRateCorrection', 'Resources/views');

        $this->publishes([$sourcePath => $viewPath], 'views');

        $paths   = array_map(fn($p) => $p . '/modules/landingratecorecorrection', \Config::get('view.paths'));
        $paths[] = $sourcePath;
        $this->loadViewsFrom($paths, 'landingratecorecorrection');

        Blade::include('landingratecorecorrection::components.admin-badge', 'lrcAdminBadge');
    }

    protected function registerTranslations(): void
    {
        // Published override path (takes priority if it exists)
        $publishedPath = resource_path('lang/modules/lrc');

        // Module's own lang folder (always available)
        $modulePath = module_path('LandingRateCorrection', 'Resources/lang');

        if (is_dir($publishedPath)) {
            $this->loadTranslationsFrom($publishedPath, 'lrc');
        } else {
            $this->loadTranslationsFrom($modulePath, 'lrc');
        }
    }

    public function provides(): array
    {
        return [];
    }
}
