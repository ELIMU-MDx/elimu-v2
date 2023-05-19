<?php

namespace App\Providers;

use Google\Client;
use Google\Service\Drive;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;
use Masbug\Flysystem\GoogleDriveAdapter;

class GoogleDriveServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Storage::extend('google', function () {
            $client = new Client();
            $client->setClientId(config('filesystems.disks.google.clientId'));
            $client->setClientSecret(config('filesystems.disks.google.secret'));
            $client->refreshToken(config('filesystems.disks.google.refreshToken'));
            $client->setApplicationName(config('filesystems.disks.google.name'));

            $service = new Drive($client);

            $adapter = new GoogleDriveAdapter($service, config('filesystems.disks.google.root'));

            return new FilesystemAdapter(new Filesystem($adapter), $adapter);
        });
    }
}
