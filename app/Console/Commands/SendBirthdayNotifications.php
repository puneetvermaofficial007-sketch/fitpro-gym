<?php

namespace App\Console\Commands;

use App\Services\BirthdayNotificationService;
use Illuminate\Console\Command;

class SendBirthdayNotifications extends Command
{
    protected $signature = 'gym:send-birthday-notifications';

    protected $description = 'Create birthday notifications for members whose birthday is today';

    public function handle(BirthdayNotificationService $service): int
    {
        $created = $service->sendForToday();

        $this->info("Created {$created} birthday notification(s).");

        return self::SUCCESS;
    }
}
