<?php

namespace App\Http\Controllers;

use App\Models\GymNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = GymNotification::where('user_id', auth()->id())
            ->latest()
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    public function markRead(GymNotification $notification)
    {
        abort_unless($notification->user_id === auth()->id(), 403);
        $notification->update(['is_read' => true]);

        return back()->with('success', 'Notification marked as read.');
    }

    public function markAllRead()
    {
        GymNotification::where('user_id', auth()->id())->update(['is_read' => true]);

        return back()->with('success', 'All notifications marked as read.');
    }
}
