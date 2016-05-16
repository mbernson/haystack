<?php

namespace App\Providers;

use App\Database\Incident;
use Illuminate\Support\ServiceProvider;

use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local;
use League\Flysystem\FilesystemInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(FilesystemInterface::class, function() {
            $adapter = new Local(storage_path());
            $filesystem = new Filesystem($adapter);
            return $filesystem;
        });
    }
}
