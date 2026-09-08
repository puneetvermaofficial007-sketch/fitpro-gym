<?php

namespace App\Providers;

use App\Models\GymNotification;
use App\Services\BirthdayNotificationService;
use Illuminate\Support\Facades\Cache;
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
                if (Cache::add('birthday_notifications_'.now()->toDateString(), true, now()->endOfDay())) {
                    app(BirthdayNotificationService::class)->sendForToday();
                }

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