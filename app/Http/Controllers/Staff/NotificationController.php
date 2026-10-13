<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        return view('staff.notifications', [
            'notifications' => $request->user()->notifications()->paginate(20),
        ]);
    }

    /**
     * Mark one notification read and follow its link.
     */
    public function open(Request $request, string $notification): RedirectResponse
    {
        $notification = $request->user()->notifications()->findOrFail($notification);
        $notification->markAsRead();

        $url = $notification->data['url'] ?? null;

        // Only follow internal paths stored by our own notifications.
        return is_string($url) && str_starts_with($url, '/') && ! str_starts_with($url, '//')
            ? redirect($url)
            : redirect()->route('staff.notifications.index');
    }

    public function markAllRead(Request $request): RedirectResponse
    {
        $request->user()->unreadNotifications->markAsRead();

        return back()->with('status', 'All notifications marked as read.');
    }
}
