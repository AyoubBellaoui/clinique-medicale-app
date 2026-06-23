<?php

namespace App\Http\Controllers;

class NotificationController extends Controller
{
    public function unreadCount()
    {
        return response()->json([
            'count' => auth()->user()->unreadNotifications()->count(),
        ]);
    }

    public function index()
    {
        $notifications = auth()->user()
            ->notifications()
            ->latest()
            ->take(30)
            ->get()
            ->map(fn($n) => [
                'id'      => $n->id,
                'data'    => $n->data,
                'read'    => ! is_null($n->read_at),
                'time'    => $n->created_at->diffForHumans(),
            ]);

        return response()->json($notifications);
    }

    public function markRead(string $id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return response()->json(['ok' => true]);
    }

    public function markAllRead()
    {
        auth()->user()->unreadNotifications()->update(['read_at' => now()]);

        return response()->json(['ok' => true]);
    }
}
