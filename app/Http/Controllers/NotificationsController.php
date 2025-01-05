<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationsController extends Controller
{

    public function get_notifications(Request $request)
    {
        $notifications = $request->user()->notifications;
        return response()->json($notifications);
    }
}
