<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /* The two gates Gadya CMS guards every screen and write with. */
        Gate::define('manage-content', fn (User $user): bool => $user->canManageContent());
        Gate::define('manage-users', fn (User $user): bool => $user->isAdministrator());
    }
}
