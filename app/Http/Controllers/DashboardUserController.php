<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DashboardUserController extends Controller
{
    //
    public function allUser(Request $request)
    {
        $user_name = $request->search;
        $query = User::where('userType','USER');

        // Apply filters if user name is provided
        if ($user_name) {
                $query->where('fullName', 'like', '%' . $user_name . '%');
        }
        $user_list = $query->paginate(9);
        return response()->json([
            'message' => 'user list',
            'data' => $user_list,
        ]);
    }

}
