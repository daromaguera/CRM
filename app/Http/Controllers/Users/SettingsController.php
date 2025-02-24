<?php

namespace App\Http\Controllers\Users;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function update_timezone(Request $request)
    {
        $data = $request->all();
        $user_id = auth()->id();

        User::where('id', $user_id)->update([
            'timezone'     => $data['timezone'],
        ]);

        return response()->json([
            'success' => true,
            'data'    => User::where('id', $user_id)->get(),
            'message' => 'User Timezone has been updated successfully!',
        ], 201);
    }
}
