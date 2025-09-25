<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class NotificationsController extends Controller
{
    protected function currentUserId(): ?int
    {
        // Session-driven auth key used by this app
        $id = session('auth_user_id');
        if ($id) return (int) $id;
        // Fallback to Laravel auth if available
        if (function_exists('auth') && auth()->check()) return (int) auth()->id();
        return null;
    }

    public function index(Request $request)
    {
        $uid = $this->currentUserId();
        if (!$uid) return response()->json(['items' => [], 'unread_count' => 0]);

        $items = Notification::where('user_id', $uid)
            ->latest('created_at')
            ->limit(20)
            ->get()
            ->map(function (Notification $n) {
                return [
                    'id' => $n->id,
                    'title' => $n->title,
                    'body' => $n->body,
                    'url' => $n->url,
                    'read_at' => $n->read_at ? $n->read_at->toIso8601String() : null,
                    'time' => $n->created_at?->diffForHumans(),
                ];
            });
        $unread = Notification::where('user_id', $uid)->whereNull('read_at')->count();
        return response()->json(['items' => $items, 'unread_count' => $unread]);
    }

    public function markAllRead(Request $request)
    {
        $uid = $this->currentUserId();
        if (!$uid) return response()->json(['ok' => true]);
        Notification::where('user_id', $uid)->whereNull('read_at')->update(['read_at' => now()]);
        return response()->json(['ok' => true]);
    }

    public function markRead(Request $request, Notification $notification)
    {
        $uid = $this->currentUserId();
        if (!$uid || $notification->user_id !== $uid) {
            return response()->json(['ok' => false], 403);
        }
        if (!$notification->read_at) {
            $notification->read_at = now();
            $notification->save();
        }
        return response()->json(['ok' => true]);
    }
}
