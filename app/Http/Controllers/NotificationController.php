<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserNotification;

class NotificationController extends Controller
{
    public function saveNotification(Request $request)
    {
        $notification = new UserNotification();
        $notification->id = $request->input('id');
        $notification->title = $request->input('title');
        $notification->subtitle = $request->input('subtitle');
        $notification->created_at = $request->input('time');
        $notification->isSeen = $request->input('isSeen');
        $notification->img = $request->input('img');
        $notification->user_id = $request->input('user_id');
        $notification->save();

        return response()->json(['status' => 'Notification saved successfully']);
    }

    // New method for public notifications
    public function savePublicNotification(Request $request)
    {
        $notification = new UserNotification();
        $notification->title = $request->input('title');
        $notification->subtitle = $request->input('subtitle');
        $notification->created_at = $request->input('time');
        $notification->isSeen = $request->input('isSeen');
        $notification->img = $request->input('img');
        $notification->save();

        return response()->json(['status' => 'Public notification saved successfully']);
    }

    public function getNotificationsByUserId($userId)
    {
        $notifications = UserNotification::where('user_id', $userId)->get();
        return response()->json($notifications);
    }

    public function getPublicNotifications()
    {
        $notifications = UserNotification::whereNull('user_id')->get();
        return response()->json($notifications);
    }

    public function deleteNotification($id)
    {
        $notification = UserNotification::find($id);
        if ($notification) {
            $notification->delete();
            return response()->json(['status' => 'Notification deleted successfully']);
        } else {
            return response()->json(['status' => 'Notification not found'], 404);
        }
    }

    public function markAsSeen($id)
    {
        $notification = UserNotification::find($id);
        if ($notification) {
            $notification->isSeen = true;
            $notification->save();
            return response()->json(['status' => 'Notification marked as seen']);
        } else {
            return response()->json(['status' => 'Notification not found'], 404);
        }
    }
}
