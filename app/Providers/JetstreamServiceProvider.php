<?php

namespace App\Providers;

use Domain\Users\Actions\DeleteUser;
use Illuminate\Support\ServiceProvider;
use Laravel\Jetstream\Jetstream;

class JetstreamServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Jetstream::deleteUsersUsing(DeleteUser::class);
    }
}
