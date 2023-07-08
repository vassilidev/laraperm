<?php

namespace Vassilidev\Laraperm;

use Illuminate\Auth\Access\Gate;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Application;
use Illuminate\Routing\Route;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use Vassilidev\Laraperm\Commands\InstallLarapermCommand;
use Vassilidev\Laraperm\Commands\UninstallLarapermCommand;

class LarapermServiceProvider extends ServiceProvider
{
    /**
     * @throws BindingResolutionException
     */
    public function boot(): void
    {
        $this->offerPublishing();

        $this->registerCommands();

        $this->registerMacro();

        $this->callAfterResolving(Gate::class, static function (Gate $gate, Application $app) {
            $gate->before(fn(Authorizable $authorizable, string $ability) => Laraperm::authorize($authorizable, $ability));
        });
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/laraperm.php',
            'laraperm'
        );

        $this->callAfterResolving('blade.compiler', fn(BladeCompiler $bladeCompiler) => $this->registerBladeExtensions($bladeCompiler));
    }

    /**
     * @throws BindingResolutionException
     */
    protected function offerPublishing(): void
    {
        if (!$this->app->runningInConsole()) {
            return;
        }

        if (!function_exists('config_path')) {
            // function not available and 'publish' not relevant in Lumen
            return;
        }

        $this->publishes([
            __DIR__ . '/../config/laraperm.php' => config_path('laraperm.php'),
        ], 'laraperm-config');

        $this->publishes([
            __DIR__ . '/../database/migrations/create_laraperm_table.php' => $this->getMigrationFileName('create_laraperm_tables.php'),
        ], 'laraperm-migrations');
    }

    protected function registerCommands(): void
    {
        $this->commands([
            InstallLarapermCommand::class,
            UninstallLarapermCommand::class,
        ]);
    }

    /**
     * @param BladeCompiler $bladeCompiler
     * @return void
     */
    protected function registerBladeExtensions(BladeCompiler $bladeCompiler): void
    {
        $bladeCompiler->directive('permission', fn($args) => "<?php if(auth()->check() && auth()->user()->can($args)): ?>");
        $bladeCompiler->directive('endpermission', fn() => "<?php endif; ?>");
    }

    protected function registerMacro(): void
    {
        if (!method_exists(Route::class, 'macro')) { // Lumen
            return;
        }
    }

    /**
     * @throws BindingResolutionException
     */
    protected function getMigrationFileName(string $migrationFileName): string
    {
        $timestamp = date('Y_m_d_His');

        $filesystem = $this->app->make(Filesystem::class);

        return Collection::make([
            $this->app->databasePath() . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR,
        ])->flatMap(fn($path) => $filesystem->glob($path . '*_' . $migrationFileName))
            ->push($this->app->databasePath() . "/migrations/{$timestamp}_{$migrationFileName}")
            ->first();
    }
}
