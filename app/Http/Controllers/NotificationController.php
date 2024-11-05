<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class NotificationController extends Controller
{
    public function index()
    {
        return view('/notifications');
    }

    public function fetchNotifications(Request $request)
    {
        if ($request->ajax()) {
            $notifications = Notification::select(['id', 'message', 'created_at']);

            return DataTables::of($notifications)
                ->editColumn('created_at', function ($notification) {
                    return $notification->created_at->setTimezone('Asia/Ho_Chi_Minh')->format('d-m-Y H:i:s');
                })
                ->addColumn('actions', function () {
                    return '<button class="btn btn-warning deleteBtn" " data-bs-toggle="modal" data-bs-target="#deleteNotificationModal"><i class="bi bi-x-circle"></i></button>';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return abort(404);
    }

    public function destroy($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();

        return response()->json([
            'message' => 'Notification updated successfully.',
        ], 200);
    }
}
