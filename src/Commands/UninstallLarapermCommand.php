<?php

namespace Vassilidev\Laraperm\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class UninstallLarapermCommand extends Command
{
    public $signature = 'laraperm:uninstall';

    public $description = "Remove all generated files from Laraperm package";

    public function handle(): int
    {
        $emptyTable = $this->confirm('Did you remove all the tables ?');

        if (!$emptyTable) {
            return self::INVALID;
        }

        $filesystem = app(Filesystem::class);

        $configFile = config_path('laraperm.php');

        if ($filesystem->exists($configFile)) {
            $filesystem->delete($configFile);
            $this->info('Successfully delete config file ! [' . $configFile . ']');
        }

        $migrationFiles = $filesystem->glob(database_path('migrations') . DIRECTORY_SEPARATOR . '*laraperm*.php');

        foreach ($migrationFiles as $migrationFile) {
            if ($filesystem->exists($migrationFile)) {
                $filesystem->delete($migrationFile);
                $this->info('Successfully delete migration file ! [' . $migrationFile . ']');
            }
        }

        $this->newLine();
        $this->info('Successfully removed Laraperm package !');

        return self::SUCCESS;
    }
}
