<?php

namespace Vassilidev\Laraperm;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Routing\Route;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use Vassilidev\Laraperm\Commands\RemoveLarapermCommand;

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
            RemoveLarapermCommand::class,
        ]);
    }

    /**
     * @param BladeCompiler $bladeCompiler
     * @return void
     */
    protected function registerBladeExtensions(BladeCompiler $bladeCompiler): void
    {
/*        $bladeCompiler->directive('laraperm', fn($args) => "<?php echo 'Hello !'; ?>");*/
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
