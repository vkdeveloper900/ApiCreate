<?php

namespace App\Http\Controllers\API\Admin\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\Profile\UpdateUserRequest;

class ProfileController extends Controller
{
    public function profile()
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User Not Found'
            ]);
        }
        return response()->json([
            'success' => true,
            'message' => 'User profile fetched successfully',
            'data' => $user,
        ]);
    }

    public function update(UpdateUserRequest $request)
    {
        $validated = $request->validated();

        $user = auth()->user();

        $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => $user,
        ]);
    }

}
