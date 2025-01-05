<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function get_view(Request $request)
    {
        $role = $request->user()->roles[0]->name;
        if($role === 'store_owner')
        {
            $storeOwnerController = new StoreOwnerController();
            return $storeOwnerController->index($request);
        }

        if($role === 'admin')
        {
            //! LATER
        }

        else abort(403, 'Unauthorized');

    }
}
