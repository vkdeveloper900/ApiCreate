<?php

namespace App\Providers;

use App\Events\UserLoggedIn;
use App\Listeners\LogUserLoginActivity;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    protected $listen = [
        UserLoggedIn::class => [
            LogUserLoginActivity::class,
        ],
    ];
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
