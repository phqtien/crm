<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::paginate(10);

        $notifications->getCollection()->transform(function ($notifications) {
            $notifications->created_at = $notifications->created_at->setTimezone('Asia/Ho_Chi_Minh')->format('d-m-Y H:i:s');
            return $notifications;
        });

        return view('/notifications', compact('notifications'));
    }

    public function destroy($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();

        return redirect('/notifications');
    }
}
