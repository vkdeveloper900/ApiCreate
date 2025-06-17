<?php

namespace App\Http\Controllers\API\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function list(){
        $users = User::all();

        if ($users->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No Users Found',
                'count' => 0,
                'data' => []
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Users Found',
            'count' => $users->count(),
            'data' => $users
        ]);
    }

}
