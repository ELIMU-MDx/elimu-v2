<?php

namespace App\Providers;

use Alchemy\Zippy\Adapter\AdapterContainer;
use Alchemy\Zippy\FileStrategy\AbstractFileStrategy;
use Alchemy\Zippy\FileStrategy\ZipFileStrategy;
use Alchemy\Zippy\Zippy;
use Domain\Rdml\RdmlFileStrategy;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class ZippyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Zippy::class, function () {
            $adapters = AdapterContainer::load();
            $factory = new Zippy($adapters);

            $factory->addStrategy(new RdmlFileStrategy($adapters));

            return $factory;
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
