<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

final class ElimuServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->configureRoles();
    }

    private function configureRoles()
    {
    }
}
