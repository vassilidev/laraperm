<?php

namespace Vassilidev\Laraperm\Commands;

use Illuminate\Console\Command;
use Vassilidev\Laraperm\LarapermServiceProvider;

class InstallLarapermCommand extends Command
{
    public $signature = 'laraperm:install';

    public $description = "Install the best package in the world !";

    public function handle(): int
    {
        $this->call('vendor:publish', [
            '--provider' => LarapermServiceProvider::class
        ]);

        if ($this->confirm('Should we run the migrations ?', default: true)) {
            $this->call('migrate');
        }

        return self::SUCCESS;
    }
}
