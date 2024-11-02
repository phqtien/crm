<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        return view('/notifications');
    }

    public function fetchNotifications()
    {
        $notifications = Notification::all();

        $notifications->transform(function ($notification) {
            $notification->created_at = $notification->created_at->setTimezone('Asia/Ho_Chi_Minh')->format('d-m-Y H:i:s');
            return $notification;
        });

        return response()->json(['notifications' => $notifications]);
    }

    public function destroy($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();

        return redirect('/notifications');
    }
}
