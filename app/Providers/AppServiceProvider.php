<?php

namespace App\Providers;

use App\Models\GymNotification;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Fix MySQL "Specified key was too long" error
        Schema::defaultStringLength(191);

        View::composer('components.header', function ($view) {
            if (auth()->check()) {
                $view->with([
                    'headerNotifications' => GymNotification::where('user_id', auth()->id())
                        ->latest()
                        ->take(5)
                        ->get(),

                    'unreadCount' => GymNotification::where('user_id', auth()->id())
                        ->where('is_read', false)
                        ->count(),
                ]);
            }
        });
    }
}