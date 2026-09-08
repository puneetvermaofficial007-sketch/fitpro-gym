<?php

namespace App\Services;

use App\Models\GymNotification;
use App\Models\Member;
use App\Models\User;
use Illuminate\Support\Collection;

class BirthdayNotificationService
{
    public function sendForToday(): int
    {
        $members = $this->membersWithBirthdayToday();
        $users = User::all();

        if ($members->isEmpty() || $users->isEmpty()) {
            return 0;
        }

        $created = 0;

        foreach ($members as $member) {
            $link = route('members.show', $member, false);
            $title = '🎂 Birthday Today!';
            $message = "Today is {$member->full_name}'s birthday.";

            foreach ($users as $user) {
                $exists = GymNotification::query()
                    ->where('user_id', $user->id)
                    ->where('type', 'birthday')
                    ->where('link', $link)
                    ->whereDate('created_at', today())
                    ->exists();

                if ($exists) {
                    continue;
                }

                GymNotification::create([
                    'user_id' => $user->id,
                    'type' => 'birthday',
                    'title' => $title,
                    'message' => $message,
                    'link' => $link,
                    'is_read' => false,
                ]);

                $created++;
            }
        }

        return $created;
    }

    public function membersWithBirthdayToday(): Collection
    {
        $today = now();

        return Member::query()
            ->whereNotNull('date_of_birth')
            ->where(function ($query) use ($today) {
                $query->whereMonth('date_of_birth', $today->month)
                    ->whereDay('date_of_birth', $today->day);

                if ($today->month === 2 && $today->day === 28 && ! $today->isLeapYear()) {
                    $query->orWhere(function ($leapDay) {
                        $leapDay->whereMonth('date_of_birth', 2)
                            ->whereDay('date_of_birth', 29);
                    });
                }
            })
            ->get();
    }
}
